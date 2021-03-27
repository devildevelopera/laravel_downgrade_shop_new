<?php



namespace DownGrade\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Input;

use Illuminate\Validation\Rule;

use DownGrade\Models\Product;

use DownGrade\Models\Members;

use DownGrade\Models\Pages;

use DownGrade\Models\Settings;

use DownGrade\User;

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

		$message = 'Product has been added to cart';

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

    public function guest_view()
	
	{	
		$cart_count = Product::getcartGuestCount();

		if($cart_count > 0) {
			return view('guest');
		}

		return redirect('login');
	}

    public function guest_register(Request $request)
	
	{


        /* start registering guest user */


        $guest_user_email = $request -> input('email');

        $guest_user_name = 'Guest User';

        $exsiting_users_count = Members::checkUserByEmail($guest_user_email);

        if ($exsiting_users_count>0) {
            return redirect()->back()->with('error', 'The email exists in our system. You have the account, at least ever registered as guest mode. Please login or use another email');
        };
        
        $token = $this->generateRandomString();

        $password = rand(111111,999999);

		$guest_user_data = [

            'name' => $guest_user_name,

            'email' => $request -> input('email'),

			'username' => 'guestuser',

            'password' => Hash::make($password),

			'user_token' => $token,

			'earnings' => 0,

			'user_type' => 'customer'

        ];

        $guest_user_id = Members::insertGuestUser($guest_user_data);

        // $sid = 1;

        // $setting['setting'] = Settings::editGeneral($sid);

		// $from_name = $setting['setting']->sender_name;

        // $from_email = $setting['setting']->sender_email;

        // Mail::send('register_mail', $guest_user_data, function($message) use ($from_name, $from_email, $guest_user_email, $guest_user_name, $password) {

		// 	$message->to($guest_user_email, $guest_user_name)

		// 			->subject('Email Confirmation For Registration, You account password is '. $password);

		// 	$message->from($from_email,$from_name);

		// });


        /* end registering guest user */







        /* start inserting order in product_order table for the guest user */

        $cartitem['item'] = Product::getcartGuestData();

        foreach($cartitem['item'] as $eachitem){

            if($eachitem->id && $eachitem->package_name && $eachitem->package_price && $eachitem->product_flash_sale_percentage) {
                if($eachitem->product_flash_sale) {
                  $item_price = round($eachitem->package_price - ($eachitem->package_price * $eachitem->product_flash_sale_percentage / 100));
                } else {
                  $item_price = $eachitem->package_price;
                }
            } else {
                $item_price = $eachitem->regular_price;
            }

            $user_id = $guest_user_id;
    
            $product_id = $eachitem->product_id;

            $product_name = $eachitem->product_name;
    
            $product_user_id = $eachitem->user_id;
    
            $product_token = $eachitem->product_token;
    
            $price = $item_price;
    
            $license = '';
            
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
    
            $package_id = $eachitem->id;
            
            $savedata = array('user_id' => $user_id, 'product_id' => $product_id, 'product_name' => $product_name, 'product_user_id' => $product_user_id, 'product_token' => $product_token, 'license' => $license, 'start_date' => $start_date, 'end_date' => $end_date, 'product_price' => $price, 'admin_amount' => $admin_amount, 'total_price' => $price, 'order_status' => $order_status,'package_id'=>$package_id);
    
    
            $updatedata = array('license' => $license, 'start_date' => $start_date, 'end_date' => $end_date, 'product_price' => $price, 'total_price' => $price);
           
    
            if($getcount == 0)
            {
    
                Product::savecartData($savedata);	
    
            } else {
    
                Product::updatecartData($product_id, $user_id, $order_status, $updatedata);
    
            }

        }



        /* end inserting order in product_order table for the guest user */
        Session::put('guest_user_id', $user_id);

        return redirect('/guest-checkout');

	}

    public function show_checkout() {

        $user_id = Session::get('guest_user_id');

		$cart['item'] = Product::getcartData($user_id);

		$cart_mobile['item'] = Product::getcartData($user_id);

		$cart_count = Product::getcartCount($user_id);

        $sid = 1;

		$setting['setting'] = Settings::editGeneral($sid);

		$get_payment = explode(',', $setting['setting']->payment_option);

		$data = array('cart' => $cart, 'cart_count' => $cart_count, 'get_payment' => $get_payment, 'cart_mobile' => $cart_mobile);


		return view('checkout')->with($data);
    }

    public function generateRandomString($length = 25) {

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    
        $charactersLength = strlen($characters);
    
        $randomString = '';
    
        for ($i = 0; $i < $length; $i++) {
    
            $randomString .= $characters[rand(0, $charactersLength - 1)];
    
        }
    
        return $randomString;
    
    }

    public function view_checkout(Request $request)
	{

		$user_id = Session::get('guest_user_id');

		$sid = 1;

		$setting['setting'] = Settings::editGeneral($sid);

		$cart['item'] = Product::getcartData($user_id);

		$cart_mobile['item'] = Product::getcartData($user_id);

		$cart_count = Product::getcartCount($user_id);

		$get_payment = explode(',', $setting['setting']->payment_option);

		$totaldata = array('cart' => $cart, 'cart_count' => $cart_count, 'get_payment' => $get_payment, 'cart_mobile' => $cart_mobile);

		$order_firstname = $request->input('order_firstname');

		$order_lastname = $request->input('order_lastname');

		$order_company = $request->input('order_company');

        $from['info'] = Members::singlevendorData($user_id);

		$order_email = $from['info']->email;

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

		$success_url = $website_url.'/guest-success/'.$purchase_token;

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

				$callback = $website_url.'/guest-paystack';

				$csf_token = csrf_token();

				$reference = Paystack::genTranxRef();

				$price_amount = $convertedAmount * 100;

			   	$paystack = '<form method="post" id="stack_form" action="'.route('guest-paystack').'">

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

				$callback = $website_url.'/guest-razorpay';

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

                    Session::forget('guest_cart_arr');
                    Session::save();

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

				$callback = $website_url.'/guest-paystack';

				$csf_token = csrf_token();

				$reference = Paystack::genTranxRef();

				$price_amount = $final_amount * 100;

				$paystack = '<form method="post" id="stack_form" action="'.route('guest-paystack').'">

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

                    Session::forget('guest_cart_arr');
                    Session::save();


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

        Session::forget('guest_cart_arr');
        Session::save();

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

    public function redirectToGateway()
    {

        return Paystack::getAuthorizationUrl()->redirectNow();

    }

    public function handleGatewayCallback()
    {

        $paymentDetails = Paystack::getPaymentData();

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

            Session::forget('guest_cart_arr');
		    Session::save();
			

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

            $user_id = $check['display']->user_id;

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

    public function razorpay_payment(Request $request)
    {

	    $sid = 1;

	    $setting['setting'] = Settings::editGeneral($sid);

        $input = $request->all();


        $api = new Api($setting['setting']->razorpay_key, $setting['setting']->razorpay_secret);

        $payment = $api->payment->fetch($input['razorpay_payment_id']); 

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

            Session::forget('guest_cart_arr');
		    Session::save();

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

            $user_id = $check['display']->user_id;

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

}

