<?php

namespace DownGrade\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rule;
use DownGrade\Models\Members;
use DownGrade\Models\Settings;
use DownGrade\Models\Subscription;
use DownGrade\Models\Category;
use DownGrade\Models\Causes;
use Auth;
use Mail;

class ProfileController extends Controller
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
    
    	
	public function view_myprofile()
	{
	
	  
	  return view('my-profile');
	  
	
	}
	
	
	
	
	
	public function view_withdrawal_request()
	{
	  $withdraw_option = array('paypal','stripe');
	  $user_id = Auth::user()->id;
	  $withdrawData['view'] = Causes::getdrawalData($user_id);
	  $data = array('withdraw_option' => $withdraw_option, 'withdrawData' => $withdrawData);
	  return view('withdrawal-request')->with($data);
	}
	
	
	
	public function withdrawal_request(Request $request)
	{
	   $withdrawal = $request->input('withdrawal');
	   $paypal_email = $request->input('paypal_email');
	   $stripe_email = $request->input('stripe_email');
	   $available_balance = base64_decode($request->input('available_balance'));
	   $get_amount = $request->input('get_amount');
	   $user_id = $request->input('user_id');
	   $token = $request->input('user_token');
	   $wd_data = date('Y-m-d');
	   $wd_status = "pending";
	   
	   $drawal_data = array('wd_user_id' => $user_id, 'withdraw_type' => $withdrawal, 'paypal_email' => $paypal_email, 'stripe_email' => $stripe_email, 'wd_amount' => $get_amount, 'wd_status' => $wd_status, 'wd_date' => $wd_data);
	   if($available_balance > $get_amount)
	   {
	     Causes::savedrawalData($drawal_data);
		 $less_amount = $available_balance - $get_amount;
		 $data = array('earnings' => $less_amount);
		 Members::updateData($token,$data);
		 $check_email_status = Members::getuserSubscription($user_id);
		 if($check_email_status == 1)
		 {
			 $sid = 1;
			 $setting['setting'] = Settings::editGeneral($sid);
			 $admin_name = $setting['setting']->sender_name;
			 $admin_email = $setting['setting']->sender_email;
			 $currency = $setting['setting']->site_currency_symbol;
			 $user['details'] = Members::singlebuyerData($user_id);
			 $from_name = $user['details']->name;
			 $from_email = $user['details']->email;
			 $record = array('from_name' => $from_name, 'from_email' => $from_email, 'withdrawal' => $withdrawal, 'paypal_email' => $paypal_email, 'stripe_email' => $stripe_email, 'get_amount' => $get_amount, 'currency' => $currency);
			 Mail::send('withdrawal_mail', $record, function($message) use ($admin_name, $admin_email, $from_name, $from_email) {
					$message->to($admin_email, $admin_name)
							->subject('Withdrawal Request');
					$message->from($from_email,$from_name);
				});
		 }	 
		 
		 return redirect()->back()->with('success', 'Your withdrawal request has been sent');
	   }
	   else
	   {
	     return redirect()->back()->with('error', 'Sorry Please check your available balance');
	   }
	   
	   
	   
	}
	
	
	
	public function my_raised_funds_delete($id)
	{
	   $donor_id = base64_decode($id);
	   Causes::deleteDonor($donor_id);
	   return redirect()->back()->with('success','Delete successfully.');
	}
	
	
	public function view_donate_details($id)
	{
	  $donor_id = base64_decode($id);
	  $single['view'] = Causes::singleDonor($donor_id);
	  return view('fund-details', ['single' => $single]);
	}
	
	
	
	public function view_addcauses()
	{
	  $category['view'] = Category::quickbookData();
	  return view('add-causes',['category' => $category]);
	  
	}
	
	public function delete_mycauses($id)
	{
	  $data = array('cause_drop_status' => 'yes');
	  $user_id = Auth::user()->id;
	  Causes::dropCausesphoto($id,$user_id,$data);
	  return redirect()->back()->with('success', 'Delete successfully.'); 
	
	}
	
	
	
	public function view_editcauses($id)
	{
	  $user_id = Auth::user()->id; 
	  $category['view'] = Category::quickbookData();
	  $edit['view'] = Causes::singleCauses($user_id,$id);
	  return view('edit-causes',['category' => $category, 'edit' => $edit]);
	}
	
	
	public function update_edit_causes(Request $request)
	{
	
	   $cause_title = $request->input('cause_title');
	   $cause_slug = $this->cause_slug($cause_title);
	   $cause_short_desc = $request->input('cause_short_desc');
	   $cause_desc = $request->input('cause_desc');
	   $cause_goal = $request->input('cause_goal');
	   $cat_id = $request->input('cat_id');
	   $image_size = $request->input('image_size');
	   $user_id = $request->input('user_id');
	   $cause_token = $this->generateRandomString();
	   $allsettings = Settings::allSettings();
	   $causes_approval = $allsettings->causes_approval;
	   $cause_raised = 0;
	   $save_cause_image = $request->input('save_cause_image');
	   $cause_token = $request->input('cause_token'); 
	   
	   if($causes_approval == 1)
	   {
	      $cause_status = 1;
		  $cause_approve_status = "Thanks for your submission. Your cause updated successfully.";
	   }
	   else
	   {
	      $cause_status = 0;
		  $cause_approve_status = "Thanks for your submission. Once admin will approved your cause. will publish on our website.";
	   }
	   
	   
	   $request->validate([
							'cause_title' => 'required',
							'cause_short_desc' => 'required',
							'cause_desc' => 'required',
							'cause_goal' => 'required',
							'cause_image' => 'mimes:jpeg,jpg,png|max:'.$image_size,
							
							
         ]);
		 $rules = array(
				
				'cause_title' => ['required',  Rule::unique('causes') ->ignore($cause_token, 'cause_token') -> where(function($sql){ $sql->where('cause_drop_status','=','no');})],
				
				
				
	     );
		 
		 $messsages = array(
		      
	    );
		 
		$validator = Validator::make($request->all(), $rules,$messsages);
		
		if ($validator->fails()) 
		{
		 $failedRules = $validator->failed();
		 return back()->withErrors($validator);
		} 
		else
		{
	        
		  
			   if ($request->hasFile('cause_image')) 
				  {
				    Causes::dropCauseimage($cause_token);
					$image = $request->file('cause_image');
					$img_name = time() . '.'.$image->getClientOriginalExtension();
					$destinationPath = public_path('/storage/causes');
					$imagePath = $destinationPath. "/".  $img_name;
					$image->move($destinationPath, $img_name);
					$cause_image = $img_name;
				  }
				  else
				  {
					 $cause_image = $save_cause_image;
				  }
			   
			   $data = array('cat_id' => $cat_id, 'cause_token' => $cause_token, 'cause_title' => $cause_title, 'cause_slug' => $cause_slug, 'cause_short_desc' => $cause_short_desc,'cause_desc' => $cause_desc, 'cause_goal' => $cause_goal, 'cause_image' => $cause_image, 'cause_status' => $cause_status, 'cause_raised' => $cause_raised);
			   
			   Causes::updatecausesData($cause_token,$user_id,$data);
			   
			   return redirect('/my-causes')->with('success', $cause_approve_status);
			 
			   
			   
		}
		
		
		
		
			   
	
	}
	
	
	
	public function upgrade_subscription($id)
	{
	   $subscr_id = base64_decode($id);
	   $subscr['view'] = Members::getSubscription($subscr_id);
	   $sid = 1;
	  $setting['setting'] = Settings::editGeneral($sid);
	  $get_payment = explode(',', $setting['setting']->payment_option);
	   return view('confirm-subscription', ['subscr' => $subscr, 'get_payment' => $get_payment]);
	}
	
	
	
	public function cause_slug($string){
		   $slug=preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
		   return $slug;
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
	
	public function save_add_causes(Request $request)
	{
	
	   $cause_title = $request->input('cause_title');
	   $cause_slug = $this->cause_slug($cause_title);
	   $cause_short_desc = $request->input('cause_short_desc');
	   $cause_desc = $request->input('cause_desc');
	   $cause_goal = $request->input('cause_goal');
	   $cat_id = $request->input('cat_id');
	   $image_size = $request->input('image_size');
	   $user_id = $request->input('user_id');
	   $cause_token = $this->generateRandomString();
	   $allsettings = Settings::allSettings();
	   $causes_approval = $allsettings->causes_approval;
	   $cause_raised = 0;
	   $user_subscr_causes = $request->input('user_subscr_causes');
	   $count_causes = Causes::countCauses($user_id);
	   
	   if($causes_approval == 1)
	   {
	      $cause_status = 1;
		  $cause_approve_status = "Thanks for your submission. Your cause updated successfully.";
	   }
	   else
	   {
	      $cause_status = 0;
		  $cause_approve_status = "Thanks for your submission. Once admin will approved your cause. will publish on our website.";
	   }
	   
	   
	   $request->validate([
							'cause_title' => 'required',
							'cause_short_desc' => 'required',
							'cause_desc' => 'required',
							'cause_goal' => 'required',
							'cause_image' => 'mimes:jpeg,jpg,png|max:'.$image_size,
							
							
         ]);
		 $rules = array(
				
				'cause_title' => ['required',  Rule::unique('causes') -> where(function($sql){ $sql->where('cause_drop_status','=','no');})],
				
				
	     );
		 
		 $messsages = array(
		      
	    );
		 
		$validator = Validator::make($request->all(), $rules,$messsages);
		
		if ($validator->fails()) 
		{
		 $failedRules = $validator->failed();
		 return back()->withErrors($validator);
		} 
		else
		{
	        if($user_subscr_causes > $count_causes)
			{
		  
			   if ($request->hasFile('cause_image')) 
				  {
					$image = $request->file('cause_image');
					$img_name = time() . '.'.$image->getClientOriginalExtension();
					$destinationPath = public_path('/storage/causes');
					$imagePath = $destinationPath. "/".  $img_name;
					$image->move($destinationPath, $img_name);
					$cause_image = $img_name;
				  }
				  else
				  {
					 $cause_image = "";
				  }
			   
			   $data = array('cat_id' => $cat_id, 'cause_token' => $cause_token, 'cause_title' => $cause_title, 'cause_slug' => $cause_slug, 'cause_short_desc' => $cause_short_desc,'cause_desc' => $cause_desc, 'cause_goal' => $cause_goal, 'cause_image' => $cause_image, 'cause_status' => $cause_status, 'cause_raised' => $cause_raised, 'cause_user_id' => $user_id);
			   
			   Causes::savecausesData($data);
			   
			   return redirect('/my-causes')->with('success', $cause_approve_status);
			}
			else
			{
			   return redirect('/my-causes')->with('error', 'Sorry!! Your causes limit reached.');
			} 
			   
			   
		}
		
		
		
		
			   
	
	}
	
	
	
	public function update_subscription(Request $request)
	{
	   
	   $token = $request->input('token');
	   $price = base64_decode($request->input('user_subscr_price'));
	   $user_id = Auth::user()->id;
	   $order_email = Auth::user()->email;
	   $purchase_token = rand(111111,999999);
	   $payment_method = $request->input('payment_method');
	   $user_subscr_type = $request->input('user_subscr_type');
	   $user_subscr_date = $request->input('user_subscr_date');
	   $user_subscr_causes = $request->input('user_subscr_causes');
	   $user_subscr_id = $request->input('user_subscr_id');
	   $website_url = $request->input('website_url');
	   $subscr_value = "+".$user_subscr_date;
	   $subscr_date = date('Y-m-d', strtotime($subscr_value));
	   $sid = 1;
	   $setting['setting'] = Settings::editGeneral($sid);
	   $admin_amount = $price;
	   
	   
	   $updatedata = array('user_subscr_type' => $user_subscr_type, 'user_subscr_price' => $price, 'user_subscr_causes' => $user_subscr_causes, 'user_subscr_id' => $user_subscr_id);
	   
	   
	   /* settings */
	   
	   $paypal_email = $setting['setting']->paypal_email;
	   $paypal_mode = $setting['setting']->paypal_mode;
	   $site_currency = $setting['setting']->site_currency_code;
	   if($paypal_mode == 1)
	   {
	     $paypal_url = "https://www.paypal.com/cgi-bin/webscr";
	   }
	   else
	   {
	     $paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
	   }
	   $success_url = $website_url.'/success/'.$purchase_token;
	   $cancel_url = $website_url.'/cancel';
	   
	   $stripe_mode = $setting['setting']->stripe_mode;
	   if($stripe_mode == 0)
	   {
	     $stripe_publish_key = $setting['setting']->test_publish_key;
		 $stripe_secret_key = $setting['setting']->test_secret_key;
	   }
	   else
	   {
	     $stripe_publish_key = $setting['setting']->live_publish_key;
		 $stripe_secret_key = $setting['setting']->live_secret_key;
	   }
	   
	   /* settings */
	   Subscription::upsubscribeData($user_id,$updatedata);
	   if($payment_method == 'paypal')
		  {
		     
			 $paypal = '<form method="post" id="paypal_form" action="'.$paypal_url.'">
			  <input type="hidden" value="_xclick" name="cmd">
			  <input type="hidden" value="'.$paypal_email.'" name="business">
			  <input type="hidden" value="'.$user_subscr_type.'" name="item_name">
			  <input type="hidden" value="'.$purchase_token.'" name="item_number">
			  <input type="hidden" value="'.$price.'" name="amount">
			  <input type="hidden" value="USD" name="'.$site_currency.'">
			  <input type="hidden" value="'.$success_url.'" name="return">
			  <input type="hidden" value="'.$cancel_url.'" name="cancel_return">
			  		  
			</form>';
			$paypal .= '<script>window.paypal_form.submit();</script>';
			echo $paypal;
					 
			 
		  }
		  /* stripe code */
		  else if($payment_method == 'stripe')
		  {
		     
			 			 
				$stripe = array(
					"secret_key"      => $stripe_secret_key,
					"publishable_key" => $stripe_publish_key
				);
			 
				\Stripe\Stripe::setApiKey($stripe['secret_key']);
			 
				
				$customer = \Stripe\Customer::create(array(
					'email' => $order_email,
					'source'  => $token
				));
			 
				
				$subscribe_name = $user_subscr_type;
				$subscribe_price = $price * 100;
				$currency = $site_currency;
				$book_id = $purchase_token;
			 
				
				$charge = \Stripe\Charge::create(array(
					'customer' => $customer->id,
					'amount'   => $subscribe_price,
					'currency' => $currency,
					'description' => $subscribe_name,
					'metadata' => array(
						'order_id' => $book_id
					)
				));
			 
				
				$chargeResponse = $charge->jsonSerialize();
			 
				
				if($chargeResponse['paid'] == 1 && $chargeResponse['captured'] == 1) 
				{
			 
					
										
					$payment_token = $chargeResponse['balance_transaction'];
					$purchased_token = $book_id;
					$checkoutdata = array('user_subscr_date' => $subscr_date);
					Subscription::confirmsubscriData($user_id,$checkoutdata);
					$data_record = array('payment_token' => $payment_token);
					return view('success')->with($data_record);
					
					
				}
		     
		  
		  }
		  /* stripe code */
		  
	
	
	
	}
	
	
	
	public function update_myprofile(Request $request)
	{
	
	   $name = $request->input('name');
	   $username = $request->input('username');
         $email = $request->input('email');
		 
		 
		 if(!empty($request->input('password')))
		 {
		 $password = bcrypt($request->input('password'));
		 $pass = $password;
		 }
		 else
		 {
		 $pass = $request->input('save_password');
		 }
		 
		 		 
		  $token = $request->input('user_token');
		  $image_size = $request->input('image_size');
		 
         
		 $request->validate([
							'name' => 'required',
							'username' => 'required',
							'password' => 'min:6',
							'email' => 'required|email',
							'user_photo' => 'mimes:jpeg,jpg,png,gif|max:'.$image_size,
							
         ]);
		 $rules = array(
				'username' => ['required', 'regex:/^[\w-]*$/', 'max:255', Rule::unique('users') ->ignore($token, 'user_token') -> where(function($sql){ $sql->where('drop_status','=','no');})],
				'email' => ['required', 'email', 'max:255', Rule::unique('users') ->ignore($token, 'user_token') -> where(function($sql){ $sql->where('drop_status','=','no');})],
				
	     );
		 
		 $messsages = array(
		      
	    );
		 
		$validator = Validator::make($request->all(), $rules,$messsages);
		
		if ($validator->fails()) 
		{
		 $failedRules = $validator->failed();
		 return back()->withErrors($validator);
		} 
		else
		{
		
		if ($request->hasFile('user_photo')) {
		     
			Members::droPhoto($token); 
		   
			$image = $request->file('user_photo');
			$img_name = time() . '.'.$image->getClientOriginalExtension();
			$destinationPath = public_path('/storage/users');
			$imagePath = $destinationPath. "/".  $img_name;
			$image->move($destinationPath, $img_name);
			$user_image = $img_name;
		  }
		  else
		  {
		     $user_image = $request->input('save_photo');
		  }
		  
		 
		 
		$data = array('name' => $name, 'username' => $username, 'email' => $email, 'password' => $pass, 'user_photo' => $user_image, 'updated_at' => date('Y-m-d H:i:s'));
        Members::updateData($token, $data);
        return redirect()->back()->with('success', 'Update successfully.');
            
 
       } 
     
       
	
	
	}
	
	
	
	
	public function paypal_success($ord_token, Request $request)
	{
	
	$payment_token = $request->input('tx');
	$purchased_token = $ord_token;
	$subscr_id = Auth::user()->user_subscr_id;
	$subscr['view'] = Subscription::editsubData($subscr_id);
	$subscri_date = $subscr['view']->subscr_duration;
	$subscr_value = "+".$subscri_date;
	$subscr_date = date('Y-m-d', strtotime($subscr_value));
	$user_id = Auth::user()->id;
	$checkoutdata = array('user_subscr_date' => $subscr_date);
	Subscription::confirmsubscriData($user_id,$checkoutdata);
	$result_data = array('payment_token' => $payment_token);
	return view('success')->with($result_data);
	
	}
	
	public function perfect_fail(Request $request)
	{
	    \Log::info($request);
	  return redirect('/')->with('error', 'Payment failed or canceled.');
	}

    public function perfect_success(Request $request)
    {
        \Log::info($request);
        return redirect('/')->with('error', 'Payment failed or canceled.');
    }
	
	
	
	
	
}
