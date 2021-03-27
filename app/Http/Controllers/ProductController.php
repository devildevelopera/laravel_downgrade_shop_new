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

use charlesassets\LaravelPerfectMoney\PerfectMoney;

use CoinbaseCommerce\ApiClient;
use CoinbaseCommerce\Resources\Checkout;
use CoinbaseCommerce\Resources\Charge;
use CoinbaseCommerce\Webhook;



class ProductController extends Controller
{
    /**

     * Create a new controller instance.

     *

     * @return void

     */    

    public function __construct()
    {
        $this->middleware('auth');
    }


	public function favourites_item()
	{

	   $user_id = Auth::user()->id;

	   $fav['product'] = Product::with('ratings')->join('product_favorite','product_favorite.product_id','product.product_id')->join('category','category.cat_id','product.product_category')->where('product.product_status','=',1)->where('product_favorite.user_id','=',$user_id)->where('product.product_drop_status','=','no')->orderBy('product.product_views', 'desc')->get();

	   $data = array('fav' => $fav);

	   return view('my-favourite')->with($data);

	}


	public function view_cart(Request $request)
	{

		$item_price = $request->input('item_price');

		$user_id = $request->input('user_id');

		$product_id = $request->input('product_id');

		$product_name = $request->input('product_name');

		$product_user_id = $request->input('product_user_id');

		$product_token = $request->input('product_token');

		$split = explode("_", $item_price);

		$price = base64_decode($split[0]);

		$license = ''; // $split[1];
		
		$start_date = date('Y-m-d');
		$end_date = date('Y-m-d');

		if($license == 'regular')
		{

			$end_date = date('Y-m-d', strtotime('+6 month'));

		} else if($license == 'extended') {


			$end_date = date('Y-m-d', strtotime('+12 month'));

	   	}

		$order_status = 'pending';

		$sid = 1;

		$setting['setting'] = Settings::editGeneral($sid);

		$extra_fee = $setting['setting']->site_extra_fee;

		$admin_amount = $price;

		$getcount  = Product::getorderCount($product_id, $user_id, $order_status);

		$product_package = $request->product_package;
        if($product_package!=''){
            $product_package_items = explode(',', $product_package);
            $package_id = $product_package_items[0];
           
        }else{
            $package_id = '';
        }
		
		$savedata = array('user_id' => $user_id, 'product_id' => $product_id, 'product_name' => $product_name, 'product_user_id' => $product_user_id, 'product_token' => $product_token, 'license' => $license, 'start_date' => $start_date, 'end_date' => $end_date, 'product_price' => $price, 'admin_amount' => $admin_amount, 'total_price' => $price, 'order_status' => $order_status,'package_id'=>$package_id);


		$updatedata = array('license' => $license, 'start_date' => $start_date, 'end_date' => $end_date, 'product_price' => $price, 'total_price' => $price);
	   

		if($getcount == 0)
		{

			Product::savecartData($savedata);	

			return redirect('cart')->with('success','Product has been added to cart'); 

		} else {

			Product::updatecartData($product_id, $user_id, $order_status, $updatedata);

			return redirect('cart')->with('success','Product has been updated to cart'); 

		}

	}

	
	public function show_cart()
	{

		$cart['item'] = Product::getcartData();

		$cart_count = Product::getcartCount();

		$data = array('cart' => $cart, 'cart_count' => $cart_count);

		return view('cart')->with($data);

	}

	
	public function remove_cart_item($ordid)
	{


		$ord_id = base64_decode($ordid); 

		Product::deletecartdata($ord_id);

		return redirect()->back()->with('success', 'Cart product has been removed');

	}

	public function clear_cart()
	{

		$log_user = Auth::user()->id;

		Product::clearcartdata($log_user);
		
		return redirect()->back()->with('success', 'Your cart has been cleared');
	}


	public function remove_favourites_item($favid,$itemid)
	{

	    $fav_id = base64_decode($favid);

		$item_id = base64_decode($itemid);

		Product::dropFavitem($fav_id);

		$get['item'] = Product::selecteditemData($item_id);

		$liked = $get['item']->product_liked - 1;

		$record = array('product_liked' => $liked);

		Product::updatefavouriteData($item_id,$record);

	    return redirect()->back()->with('success', 'Product removed to favorite');

	}

    
	public function add_post_comment(Request $request)
	{

	    $comm_text = $request->input('comm_text');

		$comm_user_id = $request->input('comm_user_id');

		$comm_product_user_id = $request->input('comm_product_user_id');

		$comm_product_id = $request->input('comm_product_id');

		$product_url = $request->input('comm_product_url');

		

		$comm_date = date('Y-m-d H:i:s');

		$comment_data = array('comm_user_id' => $comm_user_id, 'comm_product_user_id' => $comm_product_user_id, 'comm_product_id' => $comm_product_id, 'comm_text' => $comm_text, 'comm_date' => $comm_date);

		Product::savecommentData($comment_data);

		$product_user_id = $comm_product_user_id;

		$user_id = $comm_user_id;

		$getvendor['user'] = Members::singlevendorData($product_user_id);

		$getbuyer['user'] = Members::singlebuyerData($user_id);

			

		$from_name = $getbuyer['user']->name;

		$from_email = $getbuyer['user']->email;

		

		$to_name = $getvendor['user']->name;

		$to_email = $getvendor['user']->email;

		

		$record = array('product_url' => $product_url, 'from_name' => $from_name, 'from_email' => $from_email, 'comm_text' => $comm_text);

		Mail::send('comment_mail', $record, function($message) use ($from_email, $from_name, $to_name, $to_email) {

				$message->to($to_email, $to_name)

						->subject('New Comment Received');

				$message->from($from_email,$from_name);

			});

			

		return redirect()->back()->with('success', 'Your comment has been sent successfully');

		

		

	}

	
	public function view_favorite_item($itemid,$favorite,$liked)
	{  

	   $product_id = base64_decode($itemid);

	   $like = base64_decode($liked) + 1;

	   $log_user = Auth::user()->id;

	   $getcount  = Product::getfavouriteCount($product_id,$log_user);

	   if($getcount == 0)

	   {

	      $data = array ('product_id' => $product_id, 'user_id' => $log_user);

		  Product::savefavouriteData($data);

		  $record = array('product_liked' => $like);

		  Product::updatefavouriteData($product_id,$record);

		  return redirect()->back()->with('success', 'Product added to favorite');

		  

	   }

	   else

	   {

	     return redirect()->back()->with('error', 'Sorry Product already added to favorite');

	   }

	  

	

	}

	

	public function reply_post_comment(Request $request)
	{

	    $comm_text = $request->input('comm_text');

		$comm_user_id = $request->input('comm_user_id');

		$comm_product_user_id = $request->input('comm_product_user_id');

		$comm_product_id = $request->input('comm_product_id');

		$comm_id = $request->input('comm_id');

		$product_url = $request->input('comm_product_url');

		$comm_date = date('Y-m-d H:i:s');

		$comment_data = array('comm_user_id' => $comm_user_id, 'comm_product_user_id' => $comm_product_user_id, 'comm_product_id' => $comm_product_id, 'comm_id' => $comm_id, 'comm_text' => $comm_text, 'comm_date' => $comm_date);

		Product::replycommentData($comment_data);

		

		$product_user_id = $comm_product_user_id;

		$user_id = $comm_user_id;

		$getvendor['user'] = Members::singlevendorData($product_user_id);

		$getbuyer['user'] = Members::singlebuyerData($user_id);

		

		$to_name = $getbuyer['user']->name;

		$to_email = $getbuyer['user']->email;

		

		$from_name = $getvendor['user']->name;

		$from_email = $getvendor['user']->email;

		

		$record = array('product_url' => $product_url, 'from_name' => $from_name, 'from_email' => $from_email, 'comm_text' => $comm_text);

		Mail::send('comment_mail', $record, function($message) use ($from_email, $from_name, $to_name, $to_email) {

				$message->to($to_email, $to_name)

						->subject('New Comment Received');

				$message->from($from_email,$from_name);

			});

		return redirect()->back()->with('success', 'Your comment has been sent successfully');
	}

	

	public function contact_support(Request $request)
	{

	   $support_subject = $request->input('support_subject');

	   $support_msg = $request->input('support_msg');

	   $to_email = $request->input('to_address');

	   $from_email = $request->input('from_address');

	   $to_name = $request->input('to_name');

	   $from_name = $request->input('from_name');

	   $product_url = $request->input('product_url');

	   

	    $sid = 1;

		$setting['setting'] = Settings::editGeneral($sid);

		

		$admin_name = $setting['setting']->sender_name;

        $admin_email = $setting['setting']->sender_email;

		

		$record = array('to_name' => $to_name, 'from_name' => $from_name, 'from_email' => $from_email, 'product_url' => $product_url, 'support_msg' => $support_msg, 'support_subject' => $support_subject);

		Mail::send('support_mail', $record, function($message) use ($admin_name, $admin_email, $to_email, $from_email, $to_name, $from_name) {

			$message->to($admin_email, $admin_name)

					->subject('Contact Support');

			$message->from($from_email,$from_name);

		});

		

		

		Mail::send('support_mail', $record, function($message) use ($admin_name, $admin_email, $to_email, $from_email, $to_name, $from_name) {

			$message->to($to_email, $to_name)

					->subject('Contact Support');

			$message->from($from_email,$from_name);

		});

	   

	  return redirect()->back()->with('success', 'Thank You! Your message sent successfully'); 

	  

	

	}

	
	/* checkout */


	public function show_checkout()
	{

		$cart['item'] = Product::getcartData();

		$cart_mobile['item'] = Product::getcartData();

		$cart_count = Product::getcartCount();

		$sid = 1;

		$setting['setting'] = Settings::editGeneral($sid);

		$get_payment = explode(',', $setting['setting']->payment_option);

		$data = array('cart' => $cart, 'cart_count' => $cart_count, 'get_payment' => $get_payment, 'cart_mobile' => $cart_mobile);

		return view('checkout')->with($data);

	}


	public function redirectToGateway()
    {

        return Paystack::getAuthorizationUrl()->redirectNow();

    }

	public function razorpay_payment(Request $request)
    {

	    $sid = 1;

	    $setting['setting'] = Settings::editGeneral($sid);

        $input = $request->all();


        $api = new Api($setting['setting']->razorpay_key, $setting['setting']->razorpay_secret);

        $payment = $api->payment->fetch($input['razorpay_payment_id']); 

        $user_id = Auth::user()->id;


        //dd($paymentDetails);

         //print_r($paymentDetails);

		if(count($input)  && !empty($input['razorpay_payment_id'])) 
		{

			$payment_token = $input['razorpay_payment_id'];

			$purchased_token = $payment->description;

			/*$final_amount = $payment->amount / 100;*/

			$payment_status = 'completed';

			$orderdata = array('payment_token' => $payment_token, 'order_status' => $payment_status);

			$checkoutdata = array('payment_token' => $payment_token, 'payment_status' => $payment_status);

			Product::singleordupdateData($purchased_token,$orderdata);

			Product::singlecheckoutData($purchased_token,$checkoutdata);

			$token = $purchased_token;

			$check['display'] = Product::getcheckoutData($token);

			$order_id = $check['display']->order_ids;

			$order_loop = explode(',',$order_id);

			foreach($order_loop as $order)
			{

				$getitem['item'] = Product::getorderData($order);

				$token = $getitem['item']->product_token;

				$item['display'] = Product::solditemData($token);

				$product_sold = $item['display']->product_sold + 1;

				$item_token = $token; 

				$data = array('product_sold' => $product_sold);

				Product::updateitemData($item_token,$data);

				$orderdata = array('approval_status' => 'payment released to admin');

				Product::singleorderupData($order,$orderdata);

			}	

			$final_amount = $check['display']->total;

			$admin['info'] = Members::adminData();

			$admin_token = $admin['info']->user_token;

			$admin_earning = $admin['info']->earnings + $final_amount;

			$admin_record = array('earnings' => $admin_earning);

			Members::updateadminData($admin_token, $admin_record);

			$sid = 1;

			$setting['setting'] = Settings::editGeneral($sid);

			$to_name = $setting['setting']->sender_name;

			$to_email = $setting['setting']->sender_email;

			$currency = $setting['setting']->site_currency_code;

			$from['info'] = Members::singlevendorData($user_id);

			$from_name = $from['info']->name;

			$from_email = $from['info']->email;

			$data = array('to_name' => $to_name, 'to_email' => $to_email, 'final_amount' => $final_amount, 'currency' => $currency);

			Mail::send('admin_payment_mail', $data , function($message) use ($from_name, $from_email, $to_name, $to_email) {

				$message->to($to_email, $to_name)

						->subject('New Payment Received');

				$message->from($from_email,$from_name);

			});

			$data_record = array('payment_token' => $payment_token);

			return view('success')->with($data_record);

	    } else {

		  return redirect('/cancel');

		}

		/* try 

		{

			$response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount'=>$payment['amount'])); 

		} catch (\Exception $e) {

			return  $e->getMessage();

			\Session::put('error',$e->getMessage());

			return redirect()->back();

		}*/

    }


	public function view_checkout(Request $request)
	{

		$sid = 1;

		$setting['setting'] = Settings::editGeneral($sid);

		$cart['item'] = Product::getcartData();

		$cart_mobile['item'] = Product::getcartData();

		$cart_count = Product::getcartCount();

		$get_payment = explode(',', $setting['setting']->payment_option);

		$totaldata = array('cart' => $cart, 'cart_count' => $cart_count, 'get_payment' => $get_payment, 'cart_mobile' => $cart_mobile);

		$order_firstname = $request->input('order_firstname');

		$order_lastname = $request->input('order_lastname');

		$order_company = $request->input('order_company');

		$order_email = $request->input('order_email');

		$order_country = $request->input('order_country');

		$order_address = $request->input('order_address');

		$order_city = $request->input('order_city');

		$token = $request->input('token');

		$order_zipcode = $request->input('order_zipcode');

		$order_notes = $request->input('order_notes');

		$purchase_token = rand(111111,999999);

		$order_id = $request->input('order_id');

		$product_prices = base64_decode($request->input('product_prices'));

		$product_user_id = $request->input('product_user_id');

		$user_id = Auth::user()->id;

		$amount = base64_decode($request->input('amount'));

		$processing_fee = base64_decode($request->input('processing_fee'));

		$final_amount = $amount + $processing_fee;

		$payment_method = $request->input('payment_method');

		$website_url = $request->input('website_url');

		$payment_date = date('Y-m-d');

		$payment_status = 'pending';

		$getcount  = Product::getcheckoutCount($purchase_token, $user_id, $payment_status);

	  	$savedata = array('purchase_token' => $purchase_token, 'order_ids' => $order_id, 'product_prices' => $product_prices, 'product_user_id' => $product_user_id, 'user_id' => $user_id, 'total' => $final_amount, 'subtotal' => $amount, 'processing_fee' => $processing_fee, 'payment_type' => $payment_method, 'payment_date' => $payment_date, 'order_firstname' => $order_firstname, 'order_lastname' => $order_lastname, 'order_company' => $order_company, 'order_email' => $order_email, 'order_country' => $order_country, 'order_address' => $order_address, 'order_city' => $order_city, 'order_zipcode' => $order_zipcode, 'order_notes' => $order_notes, 'payment_status' => $payment_status);

		$updatedata = array('order_ids' => $order_id, 'product_prices' => $product_prices, 'product_user_id' => $product_user_id, 'total' => $final_amount, 'subtotal' => $amount, 'processing_fee' => $processing_fee, 'payment_type' => $payment_method, 'payment_date' => $payment_date, 'order_firstname' => $order_firstname, 'order_lastname' => $order_lastname, 'order_company' => $order_company, 'order_email' => $order_email, 'order_country' => $order_country, 'order_address' => $order_address, 'order_city' => $order_city, 'order_zipcode' => $order_zipcode, 'order_notes' => $order_notes);

		/* settings */

		$paypal_email = $setting['setting']->paypal_email;

		$paypal_mode = $setting['setting']->paypal_mode;

		$site_currency = $setting['setting']->site_currency_code;

		if($paypal_mode == 1)
		{

			$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
		} else {

	    	$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
	   	}

		$success_url = $website_url.'/success/'.$purchase_token;

		$cancel_url = $website_url.'/cancel';

	   	$stripe_mode = $setting['setting']->stripe_mode;

		if($stripe_mode == 0)
		{
			$stripe_publish_key = $setting['setting']->test_publish_key;

			$stripe_secret_key = $setting['setting']->test_secret_key;

		} else {

			$stripe_publish_key = $setting['setting']->live_publish_key;

			$stripe_secret_key = $setting['setting']->live_secret_key;

	   	}

	   	/* settings */

		if($getcount == 0)
		{

	    	Product::savecheckoutData($savedata);		  

			$order_loop = explode(',', $order_id);

			$item_names = "";

			foreach($order_loop as $order)
			{

				$orderdata = array('purchase_token' => $purchase_token, 'payment_type' => $payment_method);

				Product::singleorderupData($order,$orderdata);

				$item['name'] = Product::singleorderData($order);

				$item_names .= $item['name']->product_name;
			}

		  	$item_names_data = rtrim($item_names,',');

			if($payment_method == 'paypal') {
				
				$paypal = '<form method="post" id="paypal_form" action="'.$paypal_url.'">

					<input type="hidden" value="_xclick" name="cmd">

					<input type="hidden" value="'.$paypal_email.'" name="business">

					<input type="hidden" value="'.$item_names_data.'" name="item_name">

					<input type="hidden" value="'.$purchase_token.'" name="item_number">

					<input type="hidden" value="'.$final_amount.'" name="amount">

					<input type="hidden" value="'.$site_currency.'" name="currency_code">

					<input type="hidden" value="'.$success_url.'" name="return">

					<input type="hidden" value="'.$cancel_url.'" name="cancel_return">

					</form>';

				$paypal .= '<script>window.paypal_form.submit();</script>';

				echo $paypal;

		  	} else if($payment_method == 'paystack') {

		    	$convert = Currency::convert($site_currency,'NGN',$final_amount);

                if($convert->error==true) {

                    $convertedAmount = $final_amount;

                } else { 

                	$convertedAmount = $convert['convertedAmount'];

                }

				$callback = $website_url.'/paystack';

				$csf_token = csrf_token();

				$reference = Paystack::genTranxRef();

				$price_amount = $convertedAmount * 100;

			   	$paystack = '<form method="post" id="stack_form" action="'.route('paystack').'">

					  <input type="hidden" name="_token" value="'.$csf_token.'">

					  <input type="hidden" name="email" value="'.$order_email.'" >

					  <input type="hidden" name="purchase_token" value="'.$purchase_token.'">

					  <input type="hidden" name="amount" value="'.$price_amount.'">

					  <input type="hidden" name="site_currency" value="NGN">

					  <input type="hidden" name="reference" value="'.$reference.'">

					  <input type="hidden" name="callback_url" value="'.$callback.'">

					  <input type="hidden" name="metadata" value="'.$purchase_token.'">

					  <input type="hidden" name="key" value="'.$setting['setting']->paystack_secret_key.'">

					</form>';

				$paystack .= '<script>window.stack_form.submit();</script>';

				echo $paystack;

		  	} else if($payment_method == 'razorpay') {

				$convert = Currency::convert($site_currency,'USD',$final_amount);

				if($convert->error==true) {

                    $convertedAmount = $final_amount;

                } else { 

                	$convertedAmount = $convert['convertedAmount'];

                }

				$csf_token = csrf_token();

				$price_amount = $convertedAmount * 100;

				$logo_url = $website_url.'/public/storage/settings/'.$setting['setting']->site_logo;

				$script_url = $website_url.'/resources/views/theme/js/jquery.min.js';

				$callback = $website_url.'/razorpay';

				$razorpay = '

					<script type="text/javascript" src="'.$script_url.'"></script>
					<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
					<script>

						var options = {

							"key": "'.$setting['setting']->razorpay_key.'",
							"amount": "'.$price_amount.'", 
							"currency": "INR",
							"name": "'.$item_names_data.'",
							"description": "'.$purchase_token.'",
							"image": "'.$logo_url.'",
							"callback_url": "'.$callback.'",
							"prefill": {

								"name": "'.$order_firstname.'",
								"email": "'.$order_email.'"
							},
							"notes": {
								"address": "'.$order_address.'"
							},
							"theme": {

								"color": "'.$setting['setting']->site_theme_color.'"

							}
						};

						var rzp1 = new Razorpay(options);

						rzp1.on("payment.failed", function (response){

								alert(response.error.code);

								alert(response.error.description);

								alert(response.error.source);

								alert(response.error.step);

								alert(response.error.reason);

								alert(response.error.metadata);
						});


						$(window).on("load", function() {

							rzp1.open();
							e.preventDefault();
						});

					</script>';

				echo $razorpay;

		  	} else if($payment_method == 'perfectmoney') {

			  	/* PerfectMoney code */

                $convert = Currency::convert($site_currency, 'USD' ,$final_amount);

                if($convert->error==true) {

                    $convertedAmount = $final_amount;

                } else { 

                	$convertedAmount = $convert['convertedAmount'];

                }


                $perfectmpney = PerfectMoney::render();

                $perfectmpney->PAYMENT_AMOUNT = $convertedAmount;

                $perfectmpney->PAYEE_ACCOUNT = $setting['setting']->perfectmoney_key;

                $perfectmpney .= '<script>window.perfectmoney_form.submit();</script>';

              	echo $perfectmpney;

          	} else if($payment_method == 'stripe') {
				
				/* stripe code */ 

				$stripe = array(

					"secret_key"      => $stripe_secret_key,
					"publishable_key" => $stripe_publish_key
				);
			 
				\Stripe\Stripe::setApiKey($stripe['secret_key']);

				$customer = \Stripe\Customer::create(array(

					'email' => $order_email,
					'source'  => $token
				));

				$item_name = $item_names_data;

				$item_price = $final_amount * 100;

				$currency = $site_currency;

				$order_id = $purchase_token;

				$charge = \Stripe\Charge::create(array(

					'customer' => $customer->id,

					'amount'   => $item_price,

					'currency' => $currency,

					'description' => $item_name,

					'metadata' => array(

						'order_id' => $order_id
					)
				));

				$chargeResponse = $charge->jsonSerialize();

				if($chargeResponse['paid'] == 1 && $chargeResponse['captured'] == 1) 
				{
					$payment_token = $chargeResponse['balance_transaction'];

					$payment_status = 'completed';

					$purchased_token = $order_id;

					$orderdata = array('payment_token' => $payment_token, 'order_status' => $payment_status);

					$checkoutdata = array('payment_token' => $payment_token, 'payment_status' => $payment_status);

					Product::singleordupdateData($purchased_token, $orderdata);

					Product::singlecheckoutData($purchased_token, $checkoutdata);


					$token = $purchased_token;

					$check['display'] = Product::getcheckoutData($token);

					$order_id = $check['display']->order_ids;

					$order_loop = explode(',',$order_id);

					foreach($order_loop as $order)
					{

						

						$getitem['item'] = Product::getorderData($order);

						$token = $getitem['item']->product_token;

						$item['display'] = Product::solditemData($token);

						$product_sold = $item['display']->product_sold + 1;

						$item_token = $token; 

						$data = array('product_sold' => $product_sold);

					    Product::updateitemData($item_token,$data);


						$orderdata = array('approval_status' => 'payment released to admin');

		                Product::singleorderupData($order,$orderdata);


					}

					$admin['info'] = Members::adminData();

					$admin_token = $admin['info']->user_token;

					$admin_earning = $admin['info']->earnings + $final_amount;

					$admin_record = array('earnings' => $admin_earning);

					Members::updateadminData($admin_token, $admin_record);

					$sid = 1;

					$setting['setting'] = Settings::editGeneral($sid);

					$to_name = $setting['setting']->sender_name;

					$to_email = $setting['setting']->sender_email;

					$currency = $setting['setting']->site_currency_code;

					$from['info'] = Members::singlevendorData($user_id);

					$from_name = $from['info']->name;

					$from_email = $from['info']->email;

					$data = array('to_name' => $to_name, 'to_email' => $to_email, 'final_amount' => $final_amount, 'currency' => $currency);

					Mail::send('admin_payment_mail', $data , function($message) use ($from_name, $from_email, $to_name, $to_email) {

						$message->to($to_email, $to_name)

								->subject('New Payment Received');

						$message->from($from_email,$from_name);
					});

					$data_record = array('payment_token' => $payment_token);

					return view('success')->with($data_record);

				}

		  	} else if($payment_method == 'coinbase') {
				
				$coinbase_api_key = $setting['setting']->coinbase_key;

				ApiClient::init($coinbase_api_key);

				$chargeObj = new Charge(
					[
						"description" => $setting['setting']->site_title . " Coinbase Commerce",
						"metadata" => [
							"customer_id" => $user_id,
							"customer_name" => $order_firstname . ' ' . $order_lastname
						],
						"name" => $order_firstname . ' ' . $order_lastname . '(' . $order_email . ')',
						'local_price' => [
							'amount' => $final_amount,
							'currency' => $site_currency
						],
						"pricing_type" => "fixed_price"
					]
				);
				
				try {
					$chargeObj->save();

					$checkoutdata = array('payment_token' => $chargeObj->code);

					Product::singlecheckoutData($purchase_token, $checkoutdata);
					
					$coinbase = '<script type="text/javascript">
						window.open("'. $chargeObj->hosted_url .'", "_self");
					</script>';

					echo $coinbase;

				} catch (\Exception $exception) {
					echo sprintf("Enable to create charge. Error: %s \n", $exception->getMessage());
				}
			}

		} else {

			Product::updatecheckoutData($purchase_token, $user_id, $payment_status, $updatedata);

			$order_loop = explode(',',$order_id);

			$item_names = "";

			foreach($order_loop as $order)
			{

				$orderdata = array('purchase_token' => $purchase_token, 'payment_type' => $payment_method);

				Product::singleorderupData($order,$orderdata);

				$item['name'] = Product::singleorderData($order);

				$item_names .= $item['name']->product_name;
			}

		  	$item_names_data = rtrim($item_names,',');


			if($payment_method == 'paypal')
			{

				$paypal = '<form method="post" id="paypal_form" action="'.$paypal_url.'">

					<input type="hidden" value="_xclick" name="cmd">

					<input type="hidden" value="'.$paypal_email.'" name="business">

					<input type="hidden" value="'.$item_names_data.'" name="item_name">

					<input type="hidden" value="'.$purchase_token.'" name="item_number">

					<input type="hidden" value="'.$final_amount.'" name="amount">

					<input type="hidden" value="USD" name="'.$site_currency.'">

					<input type="hidden" value="'.$success_url.'" name="return">

					<input type="hidden" value="'.$cancel_url.'" name="cancel_return">

				</form>';

				$paypal .= '<script>window.paypal_form.submit();</script>';

				echo $paypal;



			} else if($payment_method == 'paystack') {

				$callback = $website_url.'/paystack';

				$csf_token = csrf_token();

				$reference = Paystack::genTranxRef();

				$price_amount = $final_amount * 100;

				$paystack = '<form method="post" id="stack_form" action="'.route('paystack').'">

						<input type="hidden" name="_token" value="'.$csf_token.'">

						<input type="hidden" name="email" value="'.$order_email.'" >

						<input type="hidden" name="purchase_token" value="'.$purchase_token.'">

						<input type="hidden" name="amount" value="'.$price_amount.'">

						<input type="hidden" name="site_currency" value="'.$site_currency.'">

						<input type="hidden" name="reference" value="'.$reference.'">

						<input type="hidden" name="callback_url" value="'.$callback.'">

						<input type="hidden" name="metadata" value="'.$purchase_token.'">

						<input type="hidden" name="key" value="'.$setting['setting']->paystack_secret_key.'">

					</form>';

				$paystack .= '<script>window.stack_form.submit();</script>';

				echo $paystack;
			}
			/* stripe code */
			else if($payment_method == 'stripe') {

				$stripe = array(

					"secret_key"      => $stripe_secret_key,

					"publishable_key" => $stripe_publish_key
				);

				\Stripe\Stripe::setApiKey($stripe['secret_key']);

				$customer = \Stripe\Customer::create(array(

					'email' => $order_email,

					'source'  => $token

				));

				$item_name = $item_names_data;

				$item_price = $final_amount * 100;

				$currency = $site_currency;

				$order_id = $purchase_token;


				$charge = \Stripe\Charge::create(array(

					'customer' => $customer->id,

					'amount'   => $item_price,

					'currency' => $currency,

					'description' => $item_name,

					'metadata' => array(

						'order_id' => $order_id

					)

				));

				$chargeResponse = $charge->jsonSerialize();			

				if($chargeResponse['paid'] == 1 && $chargeResponse['captured'] == 1) 

				{
										

					$payment_token = $chargeResponse['balance_transaction'];

					$payment_status = 'completed';

					$purchased_token = $order_id;

					$orderdata = array('payment_token' => $payment_token, 'order_status' => $payment_status);

					$checkoutdata = array('payment_token' => $payment_token, 'payment_status' => $payment_status);

					Product::singleordupdateData($purchased_token,$orderdata);

					Product::singlecheckoutData($purchased_token,$checkoutdata);

					

					$token = $purchased_token;

					$check['display'] = Product::getcheckoutData($token);

					$order_id = $check['display']->order_ids;

					$order_loop = explode(',',$order_id);

					foreach($order_loop as $order)
					{

						$getitem['item'] = Product::getorderData($order);

						$token = $getitem['item']->product_token;

						$item['display'] = Product::solditemData($token);

						$product_sold = $item['display']->product_sold + 1;

						$item_token = $token; 

						$data = array('product_sold' => $product_sold);

					    Product::updateitemData($item_token,$data);

						$orderdata = array('approval_status' => 'payment released to admin');

						Product::singleorderupData($order,$orderdata);

					}


					$admin['info'] = Members::adminData();

					$admin_token = $admin['info']->user_token;

					$admin_earning = $admin['info']->earnings + $final_amount;

					$admin_record = array('earnings' => $admin_earning);

					Members::updateadminData($admin_token, $admin_record);

					$sid = 1;

					$setting['setting'] = Settings::editGeneral($sid);

					$to_name = $setting['setting']->sender_name;

					$to_email = $setting['setting']->sender_email;

					$currency = $setting['setting']->site_currency_code;

					$from['info'] = Members::singlevendorData($user_id);

					$from_name = $from['info']->name;

					$from_email = $from['info']->email;

					$data = array('to_name' => $to_name, 'to_email' => $to_email, 'final_amount' => $final_amount, 'currency' => $currency);

					Mail::send('admin_payment_mail', $data , function($message) use ($from_name, $from_email, $to_name, $to_email) {

						$message->to($to_email, $to_name)

								->subject('New Payment Received');

						$message->from($from_email,$from_name);

					});


					$data_record = array('payment_token' => $payment_token);

					return view('success')->with($data_record);

				}

		  	}

		  	/* stripe code */
		}

	   	return view('checkout')->with($totaldata);
	}

	public function handleGatewayCallback()
    {

        $paymentDetails = Paystack::getPaymentData();

		$user_id = Auth::user()->id;

        // dd($paymentDetails);

        // print_r($paymentDetails);

		if (array_key_exists('data', $paymentDetails) && array_key_exists('status', $paymentDetails['data']) && ($paymentDetails['data']['status'] === 'success')) 
		{

			$payment_token = $paymentDetails['data']['reference'];

			$purchased_token = $paymentDetails['data']['metadata'];

		 	/*$final_amount = $paymentDetails['data']['amount'] / 100;*/

			$payment_status = 'completed';

			$orderdata = array('payment_token' => $payment_token, 'order_status' => $payment_status);

			$checkoutdata = array('payment_token' => $payment_token, 'payment_status' => $payment_status);

			Product::singleordupdateData($purchased_token,$orderdata);

			Product::singlecheckoutData($purchased_token,$checkoutdata);

			$token = $purchased_token;

			$check['display'] = Product::getcheckoutData($token);

			$order_id = $check['display']->order_ids;

			$order_loop = explode(',',$order_id);
			

			foreach($order_loop as $order)
			{

				$getitem['item'] = Product::getorderData($order);

				$token = $getitem['item']->product_token;

				$item['display'] = Product::solditemData($token);

				$product_sold = $item['display']->product_sold + 1;

				$item_token = $token; 

				$data = array('product_sold' => $product_sold);

				Product::updateitemData($item_token,$data);

				$orderdata = array('approval_status' => 'payment released to admin');

				Product::singleorderupData($order,$orderdata);

			}

			

			$final_amount = $check['display']->total;

			$admin['info'] = Members::adminData();

			$admin_token = $admin['info']->user_token;

			$admin_earning = $admin['info']->earnings + $final_amount;

			$admin_record = array('earnings' => $admin_earning);

			Members::updateadminData($admin_token, $admin_record);

			

			$sid = 1;

			$setting['setting'] = Settings::editGeneral($sid);

			$to_name = $setting['setting']->sender_name;

			$to_email = $setting['setting']->sender_email;

			$currency = $setting['setting']->site_currency_code;

			$from['info'] = Members::singlevendorData($user_id);

			$from_name = $from['info']->name;

			$from_email = $from['info']->email;

			$data = array('to_name' => $to_name, 'to_email' => $to_email, 'final_amount' => $final_amount, 'currency' => $currency);

			Mail::send('admin_payment_mail', $data , function($message) use ($from_name, $from_email, $to_name, $to_email) {

				$message->to($to_email, $to_name)

						->subject('New Payment Received');

				$message->from($from_email,$from_name);

			});

					

				

			$data_record = array('payment_token' => $payment_token);

			return view('success')->with($data_record);

	    } else {

		  return redirect('/cancel');

		}

    }

	public function paypal_success($ord_token, Request $request)
	{

		$payment_token = $request->input('tx');

		$payment_status = 'completed';

		$purchased_token = $ord_token;

		$orderdata = array('payment_token' => $payment_token, 'order_status' => $payment_status);

		$checkoutdata = array('payment_token' => $payment_token, 'payment_status' => $payment_status);

		Product::singleordupdateData($purchased_token, $orderdata);

		Product::singlecheckoutData($purchased_token, $checkoutdata);

		$token = $purchased_token;

		$check['display'] = Product::getcheckoutData($token);

		$order_id = $check['display']->order_ids;

		$order_loop = explode(',',$order_id);

		foreach($order_loop as $order)
		{			

			$getitem['item'] = Product::getorderData($order);

			$token = $getitem['item']->product_token;

			$item['display'] = Product::solditemData($token);

			$product_sold = $item['display']->product_sold + 1;

			$item_token = $token; 

			$data = array('product_sold' => $product_sold);

			Product::updateitemData($item_token,$data);

			

			$orderdata = array('approval_status' => 'payment released to admin');

			Product::singleorderupData($order,$orderdata);

			

		}

		$checkout['details'] = Product::getcheckoutData($purchased_token);

		$final_amount = $checkout['details']->total;

		$user_id = $checkout['details']->user_id;

		$admin['info'] = Members::adminData();

		$admin_token = $admin['info']->user_token;

		$admin_earning = $admin['info']->earnings + $final_amount;

		$admin_record = array('earnings' => $admin_earning);

		Members::updateadminData($admin_token, $admin_record);

		$sid = 1;

		$setting['setting'] = Settings::editGeneral($sid);

		$to_name = $setting['setting']->sender_name;

		$to_email = $setting['setting']->sender_email;

		$currency = $setting['setting']->site_currency_code;

		$from['info'] = Members::singlevendorData($user_id);

		$from_name = $from['info']->name;

		$from_email = $from['info']->email;

		$data = array('to_name' => $to_name, 'to_email' => $to_email, 'final_amount' => $final_amount, 'currency' => $currency);

		Mail::send('admin_payment_mail', $data , function($message) use ($from_name, $from_email, $to_name, $to_email) {

			$message->to($to_email, $to_name)

					->subject('New Payment Received');

			$message->from($from_email,$from_name);

		});

		

		$result_data = array('payment_token' => $payment_token);

		return view('success')->with($result_data);
	}

	/* checkout */

	/* purchases */
	public function view_purchases()
	{

	  $orderData['item'] = Product::getuserOrders();

	  return view('my-purchases',[ 'orderData' => $orderData]); 

	 

	}

	
	public function invoice_download($product_token,$order_id)
	{

	    $logged = Auth::user()->id;

		$check_purchased = Product::checkPurchased($logged,$product_token);

		if($check_purchased != 0)
		{

			$item['data'] = Product::solditemData($product_token);

			$order_details = Product::singleorderData($order_id);

			$pdf_filename = $order_details->ord_id.'-'.$order_details->purchase_token.'-'.$item['data']->product_slug.'.pdf';

			$product_slug = $item['data']->product_slug;

			$user_id = $order_details->user_id;

			$user_details = Members::singlebuyerData($user_id);

			$data = ['order_id' => $order_details->ord_id, 'purchase_id' => $order_details->purchase_token, 'purchase_date' => $order_details->start_date, 'expiry_date' => $order_details->end_date, 'license' => $order_details->license, 'product_name' => $order_details->product_name, 'product_slug' => $product_slug, 'payment_token' => $order_details->payment_token, 'payment_type' => $order_details->payment_type, 'product_price' => $order_details->product_price, 'username' => $user_details->username ];

			$pdf = PDF::loadView('pdf_view', $data);  

			return $pdf->download($pdf_filename);

		}

		else

		{

		  return redirect('404');

		}

	}

	public function purchases_download($token)
	{

	    $logged = Auth::user()->id;

		$check_purchased = Product::checkPurchased($logged,$token);

		if($check_purchased != 0)

		{

		$item['data'] = Product::solditemData($token);

	    $filename = public_path().'/storage/product/'.$item['data']->product_file;

		$headers = ['Content-Type: application/octet-stream'];

		$new_name = uniqid().time().'.zip';

		return response()->download($filename,$new_name,$headers);

		}

		else

		{

		  return redirect('404');

		}

	}

	public function rating_purchases(Request $request)
	{

		$product_id = $request->input('product_id');

		$product_token = $request->input('product_token');

		$user_id = $request->input('user_id');

		$product_user_id = $request->input('product_user_id');

		$rating = $request->input('rating');

		$ord_id = $request->input('ord_id');

		$rating_reason = $request->input('rating_reason');

		$product_url = $request->input('product_url');

		$rating_date = date('Y-m-d H:i:s');

		$rating_comment = $request->input('rating_comment');

		$rating_count = Product::checkRating($product_token,$user_id);


		$savedata = array('or_product_id' => $product_id, 'order_id' => $ord_id, 'or_product_token' => $product_token, 'or_user_id' => $user_id, 'or_product_user_id' => $product_user_id, 'rating' => $rating, 'rating_reason' => $rating_reason, 'rating_comment' => $rating_comment, 'rating_date' => $rating_date); 


		$updata = array('rating' => $rating, 'rating_reason' => $rating_reason, 'rating_comment' => $rating_comment, 'rating_date' => $rating_date); 


		if($rating_count == 0)
		{

		Product::saveRating($savedata);

		$userto['data'] = Members::singlevendorData($product_user_id);

		$userfrom['data'] = Members::singlebuyerData($user_id);

		$to_email = $userto['data']->email;

		$to_name  = $userto['data']->name;

		$from_email = $userfrom['data']->email;

		$from_name = $userfrom['data']->name;

		$sid = 1;

		$setting['setting'] = Settings::editGeneral($sid);

		$admin_name = $setting['setting']->sender_name;

		$admin_email = $setting['setting']->sender_email;

		$record = array('to_name' => $to_name, 'from_name' => $from_name, 'from_email' => $from_email, 'product_url' => $product_url, 'rating' => $rating, 'rating_reason' => $rating_reason, 'rating_comment' => $rating_comment);

		Mail::send('rating_mail', $record, function($message) use ($admin_name, $admin_email, $to_email, $from_email, $to_name, $from_name) {

			$message->to($to_email, $to_name)

					->subject('Product Item Rating Received');

			$message->from($from_email,$from_name);

		});

		} else {

			Product::updateRating($product_token,$user_id,$updata);

		}

	  

	  return redirect('my-purchases')->with('success','Rating has been updated');

	

	}
	/* purchases */

	/* refund */
	public function refund_request(Request $request)
	{

		$product_id = $request->input('product_id');

		$product_token = $request->input('product_token');

		$purchased_token = $request->input('purchased_token');

		$user_id = $request->input('user_id');

		$product_user_id = $request->input('product_user_id');

		$ord_id = $request->input('ord_id');

		$ref_refund_reason = $request->input('refund_reason');

		$ref_refund_comment = $request->input('refund_comment');

		$product_url = $request->input('product_url');

		$refund_count = Product::checkRefund($product_token,$user_id);

		$savedata = array('ref_product_id' => $product_id, 'ref_order_id' => $ord_id, 'ref_product_token' => $product_token, 'ref_purchased_token' => $purchased_token,  'ref_user_id' => $user_id, 'ref_product_user_id' => $product_user_id, 'ref_refund_reason' => $ref_refund_reason, 'ref_refund_comment' => $ref_refund_comment); 


		if($refund_count == 0)
		{

			Product::saveRefund($savedata);

			$userfrom['data'] = Members::singlebuyerData($user_id);

			$from_email = $userfrom['data']->email;

			$from_name = $userfrom['data']->name;

			$sid = 1;

			$setting['setting'] = Settings::editGeneral($sid);

			$admin_name = $setting['setting']->sender_name;

			$admin_email = $setting['setting']->sender_email;

			$record = array('from_name' => $from_name, 'from_email' => $from_email, 'product_url' => $product_url, 'ref_refund_reason' => $ref_refund_reason, 'ref_refund_comment' => $ref_refund_comment);

			Mail::send('refund_mail', $record, function($message) use ($admin_name, $admin_email, $from_email, $from_name) {

				$message->to($admin_email, $admin_name)

						->subject('Refund Request Received');

				$message->from($from_email,$from_name);

			});

			return redirect('my-purchases')->with('success','Your refund request has been sent successfully');

		} else {
			
			return redirect('my-purchases')->with('error','Sorry! Your refund request already sent');

		}

	}
	/* refund */

	public function view_withdrawal()
	{

	  $sid = 1;

	  $setting['setting'] = Settings::editGeneral($sid); 

	  $withdraw_option = explode(',', $setting['setting']->withdraw_option);

	  $itemData['item'] = Product::getdrawalData();

	  $data = array('withdraw_option' => $withdraw_option, 'itemData' => $itemData);

	  

	  return view('withdrawal')->with($data);

	}

	public function withdrawal_request(Request $request)
	{

		$withdrawal = $request->input('withdrawal');

		$paypal_email = $request->input('paypal_email');

		$stripe_email = $request->input('stripe_email');

		$paystack_email = $request->input('paystack_email');

		$available_balance = base64_decode($request->input('available_balance'));

		$get_amount = $request->input('get_amount');

		$user_id = $request->input('user_id');

		$token = $request->input('user_token');

		$wd_data = date('Y-m-d');

		$wd_status = "pending";
	   

		$drawal_data = array('wd_user_id' => $user_id, 'withdraw_type' => $withdrawal, 'paypal_email' => $paypal_email, 'stripe_email' => $stripe_email, 'paystack_email' => $paystack_email, 'wd_amount' => $get_amount, 'wd_status' => $wd_status, 'wd_date' => $wd_data);

		if($available_balance > $get_amount)
		{

			Product::savedrawalData($drawal_data);

			$less_amount = $available_balance - $get_amount;

			$data = array('earnings' => $less_amount);

			Members::updateData($token,$data);

			return redirect()->back()->with('success', 'Your withdrawal request has been sent');
		
		} else {

	    	return redirect()->back()->with('error', 'Sorry Please check your available balance');

		}
	}

}

