<?php



namespace DownGrade\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Input;

use Illuminate\Validation\Rule;

use DownGrade\Models\Product;

use DownGrade\Models\Members;

use DownGrade\Models\Pages;

use DownGrade\Models\Settings;

use Mail;

use Auth;

use PDF;

use Paystack;

use Razorpay\Api\Api;

use Currency;

use Session;

use charlesassets\LaravelPerfectMoney\PerfectMoney;

use CoinbaseCommerce\ApiClient;
use CoinbaseCommerce\Resources\Checkout;
use CoinbaseCommerce\Resources\Charge;
use CoinbaseCommerce\Webhook;

class GuestController extends Controller
{

	public function view_cart(Request $request)
	{

		$product_id = $request->input('product_id');

		$product_package = $request->product_package;

        if($product_package!=''){
            $product_package_items = explode(',', $product_package);
            $package_id = $product_package_items[0];
           
        }else{
            $package_id = '';
        }

		$guest_cart_item['product_id'] = $product_id;
		$guest_cart_item['package_id'] = $package_id;

		$message = 'Cart product has been removed';

		$guest_cart_arr = Session::get('guest_cart_arr', []);

		foreach ($guest_cart_arr as $key => $value){
			if($value['product_id'] == $product_id) {
				unset($guest_cart_arr[$key]);
				$message = 'Product has been updated to cart';
			}
		}

		Session::put('guest_cart_arr', $guest_cart_arr);

		Session::push('guest_cart_arr', $guest_cart_item);

		return redirect('guest-cart')->with('success',$message); 

	}

    public function show_cart()
	{
		$cart['item'] = Product::getcartGuestData();

		$cart_count = Product::getcartGuestCount();

		$data = array('cart' => $cart, 'cart_count' => $cart_count);
		
		return view('cart')->with($data);

	}

    public function clear_cart()
	{

		Session::forget('guest_cart_arr');
		Session::save();
		
		return redirect()->back()->with('success', 'Your cart has been cleared');
	}

    public function remove_cart_item($productId)
	{

		$product_id = base64_decode($productId);

		$guest_cart_arr = Session::get('guest_cart_arr', []);

		foreach ($guest_cart_arr as $key => $value){
			if($value['product_id'] == $product_id) {
				unset($guest_cart_arr[$key]);
			}
		}

		Session::forget('guest_cart_arr');
		Session::save();

		Session::put('guest_cart_arr', $guest_cart_arr);

		return redirect()->back()->with('success', 'Cart product has been removed');

	}

}

