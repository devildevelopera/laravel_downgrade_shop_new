<?php



namespace DownGrade\Http\Controllers\Admin;



use Illuminate\Http\Request;

use DownGrade\Http\Controllers\Controller;

use Session;

use DownGrade\Models\Settings;

use DownGrade\Models\Pages;

use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Input;

use Image;



class SettingsController extends Controller

{

    public function __construct()

    {

        $this->middleware('auth');

    }

	

	    

	

	/* settings */

	

	

	public function general_settings()

    {

        

		$sid = 1;

		$setting['setting'] = Settings::editGeneral($sid);

		$page['view'] = Pages::pagelinkData();

		return view('admin.general-settings', [ 'setting' => $setting, 'sid' => $sid, 'page' => $page]);

		

    }

	

	public function demo_mode()

	{

	   return redirect()->back()->with('error', 'This is Demo version. You can not delete');

	}

	

	

	

	public function update_demo_mode(Request $request)

	{

	   return redirect()->back()->with('error', 'This is Demo version. You can not add or change any thing');

	}

	

	

	public function color_settings()

	{

	

	    $sid = 1;

		$setting['setting'] = Settings::editGeneral($sid);

		

		return view('admin.color-settings', [ 'setting' => $setting, 'sid' => $sid]);

	

	}

	

	

	public function update_color_settings(Request $request)

	{

	  	$site_theme_color = $request->input('site_theme_color');

		$site_button_color = $request->input('site_button_color');

		$site_header_color = $request->input('site_header_color');

		$site_footer_color = $request->input('site_footer_color');

		$site_button_hover = $request->input('site_button_hover');

	  	$sid = $request->input('sid');

	     

         

		 $request->validate([

		 

					'site_theme_color' => 'required',

					'site_button_color' => 'required',

					'site_header_color' => 'required',

					'site_footer_color' => 'required',			

							

							

							

         ]);

		 

		  

		 

         

		 

		 $rules = array(

				

				

				

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

		

			  

		 

		 

		$data = array('site_theme_color' => $site_theme_color, 'site_button_color' => $site_button_color, 'site_header_color' => $site_header_color,  'site_footer_color' => $site_footer_color, 'site_button_hover' => $site_button_hover);

 

			Settings::updatemailData($sid, $data);

            return redirect()->back()->with('success', 'Update successfully.');

            

 

       } 

     

	

	

	

	}

	

	

	public function counter_section()

	{

	  

	    $sid = 1;

		$setting['setting'] = Settings::editGeneral($sid);

		

		return view('admin.counter-section', [ 'setting' => $setting, 'sid' => $sid]);

	

	}

	

	

	public function update_counter_section(Request $request)

	{

	   

	   

	   $site_counter_icon1 = $request->input('site_counter_icon1');

	   $site_counter_icon2 = $request->input('site_counter_icon2');

	   $site_counter_icon3 = $request->input('site_counter_icon3');

	   $site_counter_icon4 = $request->input('site_counter_icon4');

	   

	   $site_counter_count1 = $request->input('site_counter_count1');

	   $site_counter_count2 = $request->input('site_counter_count2');

	   $site_counter_count3 = $request->input('site_counter_count3');

	   $site_counter_count4 = $request->input('site_counter_count4');

	   

	   $site_counter_title1 = $request->input('site_counter_title1');

	   $site_counter_title2 = $request->input('site_counter_title2');

	   $site_counter_title3 = $request->input('site_counter_title3');

	   $site_counter_title4 = $request->input('site_counter_title4');

	   

	   $site_counter_display = $request->input('site_counter_display');

	   

	   

	   $sid = $request->input('sid');

	     

         

		 $request->validate([

		 

							

							

							

							

         ]);

		 

		  

		 

         

		 

		 $rules = array(

				

				

				

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

		

			  

		 

		 

		$data = array('site_counter_icon1' => $site_counter_icon1, 'site_counter_icon2' => $site_counter_icon2, 'site_counter_icon3' => $site_counter_icon3, 'site_counter_icon4' => $site_counter_icon4, 'site_counter_count1' => $site_counter_count1, 'site_counter_count2' => $site_counter_count2, 'site_counter_count3' => $site_counter_count3, 'site_counter_count4' => $site_counter_count4, 'site_counter_title1' => $site_counter_title1, 'site_counter_title2' => $site_counter_title2, 'site_counter_title3' => $site_counter_title3, 'site_counter_title4' => $site_counter_title4, 'site_counter_display' => $site_counter_display);

 

			Settings::updatemailData($sid, $data);

            return redirect()->back()->with('success', 'Update successfully.');

            

 

       } 

     

	

	

	}

	

	

	

	

	 public function update_general_settings(Request $request)

	{

	

	     $site_title = $request->input('site_title');

	     $site_desc = $request->input('site_desc');

         $site_keywords = $request->input('site_keywords');

		 $sid = $request->input('sid');

		 $office_email = $request->input('office_email');

		 $office_phone = $request->input('office_phone');

		 $office_address = $request->input('office_address');

		 $image_size = $request->input('image_size');

		 $site_copyright = $request->input('site_copyright');

		 $save_footer_logo = $request->input('save_footer_logo');

		 $site_loader_display = $request->input('site_loader_display');

		 $save_loader_image = $request->input('save_loader_image');

		 $site_banner_heading = $request->input('site_banner_heading');

		 $site_banner_sub_heading = $request->input('site_banner_sub_heading');

         $save_footer_payment = $request->input('save_footer_payment');

		 $site_flash_end_date = $request->input('site_flash_end_date');

		 $product_support_link = $request->input('product_support_link');

		 $cookie_popup = $request->input('cookie_popup');

		 $cookie_popup_text = $request->input('cookie_popup_text');

		 $cookie_popup_button = $request->input('cookie_popup_button');

		 $site_blog_display = $request->input('site_blog_display');

		 $site_google_translate = $request->input('site_google_translate');

		 $site_header_top_bar = $request->input('site_header_top_bar');

		 $request->validate([

							'site_title' => 'required',

							'site_favicon' => 'mimes:jpeg,jpg,png|max:'.$image_size,

							'site_logo' => 'mimes:jpeg,jpg,png|max:'.$image_size,

							'site_footer_payment' => 'mimes:jpeg,jpg,png|max:'.$image_size,

							

							

         ]);

		 $rules = array(

				

				

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

		

		if ($request->hasFile('site_favicon')) {

		     

			Settings::dropFavicon($sid); 

		   

			$image = $request->file('site_favicon');

			$img_name = time() . '.'.$image->getClientOriginalExtension();

			$destinationPath = public_path('/storage/settings');

			$imagePath = $destinationPath. "/".  $img_name;

			$image->move($destinationPath, $img_name);

			$fav_image = $img_name;

		  }

		  else

		  {

		     $fav_image = $request->input('save_favicon');

		  }

		  

		  

		  

		  if ($request->hasFile('site_logo')) {

		     

			Settings::dropLogo($sid); 

		   

			$image = $request->file('site_logo');

			$img_name = time() . '11.'.$image->getClientOriginalExtension();

			$destinationPath = public_path('/storage/settings');

			$imagePath = $destinationPath. "/".  $img_name;

			$image->move($destinationPath, $img_name);

			$logo_image = $img_name;

		  }

		  else

		  {

		     $logo_image = $request->input('save_logo');

		  }

		  

		  

		  if ($request->hasFile('site_loader_image')) {

		     

			Settings::dropLoader($sid); 

		   

			$image = $request->file('site_loader_image');

			$img_name = time() . '6713.'.$image->getClientOriginalExtension();

			$destinationPath = public_path('/storage/settings');

			$imagePath = $destinationPath. "/".  $img_name;

			$image->move($destinationPath, $img_name);

			$site_loader_image = $img_name;

		  }

		  else

		  {

		     $site_loader_image = $save_loader_image;

		  }

		   

		   

		  if ($request->hasFile('site_banner')) {

		     

			Settings::dropBanner($sid); 

		   

			$image = $request->file('site_banner');

			$img_name = time() . '2321.'.$image->getClientOriginalExtension();

			$destinationPath = public_path('/storage/settings');

			$imagePath = $destinationPath. "/".  $img_name;

			$image->move($destinationPath, $img_name);

			$site_banner = $img_name;

		  }

		  else

		  {

		     $site_banner = $request->input('save_banner');

		  } 

		  

		  

		  if ($request->hasFile('site_footer_payment')) {

		     

			Settings::dropPaymentbanner($sid); 

		   

			$image = $request->file('site_footer_payment');

			$img_name = time().'133.'.$image->getClientOriginalExtension();

			$destinationPath = public_path('/storage/settings');

			$imagePath = $destinationPath. "/".  $img_name;

			$image->move($destinationPath, $img_name);

			$site_footer_payment = $img_name;

		  }

		  else

		  {

		     $site_footer_payment = $save_footer_payment;

		  }

		 

		 

		$data = array('site_title' => $site_title, 'site_desc' => $site_desc, 'site_keywords' => $site_keywords,  'site_favicon' => $fav_image, 'site_logo' => $logo_image, 'office_address' => $office_address, 'office_email' => $office_email, 'office_phone' => $office_phone, 'site_copyright' => $site_copyright, 'site_loader_image' => $site_loader_image, 'site_loader_display' => $site_loader_display, 'site_banner' => $site_banner, 'site_banner_heading' => $site_banner_heading, 'site_banner_sub_heading' => $site_banner_sub_heading, 'site_footer_payment' => $site_footer_payment, 'site_flash_end_date' => $site_flash_end_date, 'product_support_link' => $product_support_link, 'cookie_popup' => $cookie_popup, 'cookie_popup_text' => $cookie_popup_text, 'cookie_popup_button' => $cookie_popup_button, 'site_blog_display' => $site_blog_display, 'site_google_translate' => $site_google_translate, 'site_header_top_bar' => $site_header_top_bar);

 

            

			Settings::updategeneralData($sid, $data);

            return redirect()->back()->with('success', 'Update successfully.');

            

 

       } 

     

       

	

	

	} 

	

	

	

	

	public function limitation_settings()

	{

	

	    $sid = 1;

		$setting['setting'] = Settings::editGeneral($sid);

		

		return view('admin.limitation-settings', [ 'setting' => $setting, 'sid' => $sid]);

	

	}

	

	public function update_limitation_settings(Request $request)

	{

	   	   

	      

	   $product_per_page = $request->input('product_per_page');

	   $post_per_page = $request->input('post_per_page');

	   $home_featured_items = $request->input('home_featured_items');

	   $home_flash_items = $request->input('home_flash_items');

	   $home_popular_items = $request->input('home_popular_items');

	   $home_new_items = $request->input('home_new_items');

	   $home_blog_post = $request->input('home_blog_post');

	   $comment_per_page = $request->input('comment_per_page');

	   $review_per_page = $request->input('review_per_page');

	   $site_range_min_price = $request->input('site_range_min_price');

	   $site_range_max_price = $request->input('site_range_max_price');

	   $menu_display_categories = $request->input('menu_display_categories');

	   $menu_categories_order = $request->input('menu_categories_order');

	   $home_free_items = $request->input('home_free_items');

	   

	   $footer_menu_display_categories = $request->input('footer_menu_display_categories');

	   $footer_menu_categories_order = $request->input('footer_menu_categories_order');

	   

	   $sid = $request->input('sid');

	         

         

		 $request->validate([

		 

							

							

							

							

         ]);

		 

		  

		 

         

		 

		 $rules = array(

				

				

				

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

		

		 

		   $data = array('product_per_page' => $product_per_page, 'post_per_page' => $post_per_page, 'home_featured_items' => $home_featured_items, 'home_flash_items' => $home_flash_items, 'home_popular_items' => $home_popular_items, 'home_new_items' => $home_new_items, 'home_blog_post' => $home_blog_post, 'comment_per_page' => $comment_per_page, 'review_per_page' => $review_per_page, 'site_range_min_price' => $site_range_min_price, 'site_range_max_price' => $site_range_max_price, 'menu_display_categories' => $menu_display_categories, 'menu_categories_order' => $menu_categories_order, 'home_free_items' => $home_free_items, 'footer_menu_display_categories' => $footer_menu_display_categories, 'footer_menu_categories_order' => $footer_menu_categories_order);

 

			Settings::updategeneralData($sid, $data);

            return redirect()->back()->with('success', 'Update successfully.');

            

 

       } 

     

	

	

	}

	

	

	

	

	

	

	public function custom_section()

	{

	  

	    $sid = 1;

		$setting['setting'] = Settings::editGeneral($sid);

		

		return view('admin.custom-section', [ 'setting' => $setting, 'sid' => $sid]);

	

	}

	

	

	public function update_custom_section(Request $request)

	{

	   	   

	      

	   $site_custom_display = $request->input('site_custom_display');

	   $site_custom_title = $request->input('site_custom_title');

	   $site_custom_content = $request->input('site_custom_content');

	   $sid = $request->input('sid');

	         

         

		 $request->validate([

		 

							

							

							

							

         ]);

		 

		  

		 

         

		 

		 $rules = array(

				

				

				

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

		

		 

		   $data = array('site_custom_display' => $site_custom_display, 'site_custom_title' => $site_custom_title, 'site_custom_content' => $site_custom_content);

 

			Settings::updatemailData($sid, $data);

            return redirect()->back()->with('success', 'Update successfully.');

            

 

       } 

     

	

	

	}

	

	

	

	

	public function about_section()

	{

	

	    $sid = 1;

		$setting['setting'] = Settings::editGeneral($sid);

		

		return view('admin.about-section', [ 'setting' => $setting, 'sid' => $sid]);

	

	}

	

	

	public function update_about_section(Request $request)

	{

	   if(!empty($request->input('site_about_heading')))

	   {

	   $site_about_heading = $request->input('site_about_heading');

	   }

	   else

	   {

	   $site_about_heading = "";

	   }

	   if(!empty($request->input('site_about_desc')))

	   {

	   $site_about_desc = $request->input('site_about_desc');

	   }

	   else 

	   {

	   $site_about_desc = "";

	   }

	   

	   if(!empty($request->input('site_about_btntext')))

	   {

	   $site_about_btntext = $request->input('site_about_btntext');

	   }

	   else 

	   {

	   $site_about_btntext = "";

	   }

	   if(!empty($request->input('site_about_btnlink')))

	   {

	   $site_about_btnlink = $request->input('site_about_btnlink');

	   }

	   else 

	   {

	   $site_about_btnlink = "";

	   }

	   

	   

	   

	   if(!empty($request->input('site_about_videolink')))

	   {

	   $site_about_videolink = $request->input('site_about_videolink');

	   }

	   else 

	   {

	   $site_about_videolink = "";

	   }

	   

	   $save_about_image = $request->input('save_about_image');

	   $sid = $request->input('sid');

	   $image_size = $request->input('image_size');

	   $site_about_display = $request->input('site_about_display');

	            

         

		 $request->validate([

		 

							'site_about_image' => 'mimes:jpeg,jpg,png|max:'.$image_size,

							

							

							

         ]);

		 

		  

		 

         

		 

		 $rules = array(

				

				

				

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

		

			  

		 if ($request->hasFile('site_about_image')) {

		     

			Settings::dropAboutbanner($sid); 

		   

			$image = $request->file('site_about_image');

			$img_name = time().'.'.$image->getClientOriginalExtension();

			$destinationPath = public_path('/storage/settings');

			$imagePath = $destinationPath. "/".  $img_name;

			$image->move($destinationPath, $img_name);

			$site_about_image = $img_name;

		  }

		  else

		  {

		     $site_about_image = $request->input('save_about_image');

		  }

		 

		$data = array('site_about_heading' => $site_about_heading, 'site_about_desc' => $site_about_desc, 'site_about_btntext' => $site_about_btntext, 'site_about_btnlink' => $site_about_btnlink, 'site_about_image' => $site_about_image, 'site_about_videolink' => $site_about_videolink, 'site_about_display' => $site_about_display);

 

            

            

			Settings::updatemailData($sid, $data);

            return redirect()->back()->with('success', 'Update successfully.');

            

 

       } 

     

	

	

	}

	

	

	

	

	public function media_settings()

	{

	

	    $sid = 1;

		$setting['setting'] = Settings::editGeneral($sid);

		

		return view('admin.media-settings', [ 'setting' => $setting, 'sid' => $sid]);

	

	}

	

	

	public function update_media_settings(Request $request)

	{

	

	   $site_max_image_size = $request->input('site_max_image_size');

	   $site_max_zip_size = $request->input('site_max_zip_size');

		$watermark_option = $request->input('watermark_option');         

         

		 $request->validate([

							'site_max_image_size' => 'required',

							'site_max_zip_size' => 'required',

							'site_watermark' => 'mimes:jpeg,jpg,png|max:2000',

							

							

         ]);

		 

		  $sid = $request->input('sid');

		 

         

		 

		 $rules = array(

				

				

				

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

		

			  

		 if ($request->hasFile('site_watermark')) {

		     

			Settings::dropWatermark($sid); 

		   

			$image = $request->file('site_watermark');

			$img_name = time() . '141.'.$image->getClientOriginalExtension();

			$destinationPath = public_path('/storage/settings');

			$imagePath = $destinationPath. "/".  $img_name;

			$image->move($destinationPath, $img_name);

			$site_watermark = $img_name;

		  }

		  else

		  {

		     $site_watermark = $request->input('save_watermark');

		  }

		 

		$data = array('site_max_image_size' => $site_max_image_size, 'site_max_zip_size' => $site_max_zip_size, 'site_watermark' => $site_watermark, 'watermark_option' => $watermark_option);

 

            

            

			Settings::updatemailData($sid, $data);

            return redirect()->back()->with('success', 'Update successfully.');

            

 

       } 

     

	

	

	}

	

	

	public function email_settings()

    {

        

		$sid = 1;

		$setting['setting'] = Settings::editGeneral($sid);

		

		return view('admin.email-settings', [ 'setting' => $setting, 'sid' => $sid]);

		

    }

	

	

	

	public function update_email_settings(Request $request)

	{

	

	   $sender_name = $request->input('sender_name');

	   $sender_email = $request->input('sender_email');

	   $mail_driver = $request->input('mail_driver');

	   $mail_port = $request->input('mail_port');

	   $mail_password = $request->input('mail_password');

	   $mail_host = $request->input('mail_host');

	   $mail_username = $request->input('mail_username');

	   $mail_encryption = $request->input('mail_encryption');

		         

         

		 $request->validate([

							'sender_name' => 'required',

							'sender_email' => 'required',

							'mail_driver' => 'required',

							'mail_port' => 'required',

							'mail_host' => 'required',

							

							

							

							

         ]);

		 

		  $sid = $request->input('sid');

		 

         

		 

		 $rules = array(

				

				

				

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

		

			  

		 

		 

		$data = array('sender_name' => $sender_name, 'sender_email' => $sender_email, 'mail_driver' => $mail_driver, 'mail_host' => $mail_host, 'mail_port' => $mail_port, 'mail_username' => $mail_username, 'mail_password' => $mail_password, 'mail_encryption' => $mail_encryption);

 

            

            

			Settings::updatemailData($sid, $data);

            return redirect()->back()->with('success', 'Update successfully.');

            

 

       } 

     

      

	

	

	}

	

	

	

	public function social_settings()

    {

        

		$sid = 1;

		$setting['setting'] = Settings::editGeneral($sid);

		

		return view('admin.social-settings', [ 'setting' => $setting, 'sid' => $sid]);

		

    }

	

	

	public function update_social_settings(Request $request)

	{

	    if(!empty($request->input('facebook_url')))

		{

	    $facebook = $request->input('facebook_url');

		}

		else

		{

		 $facebook = ""; 

		}

		

		if(!empty($request->input('twitter_url')))

		{

	    $twitter = $request->input('twitter_url');

		}

		else

		{

		$twitter = "";

		}

		

		if(!empty($request->input('gplus_url')))

		{

		$gplus = $request->input('gplus_url');

		}

		else

		{

		$gplus = "";

		}

		

		if(!empty($request->input('pinterest_url')))

		{

		$pinterest = $request->input('pinterest_url');

		}

		else

		{

		$pinterest = "";

		}

		

		if(!empty($request->input('instagram_url')))

		{

		$instagram = $request->input('instagram_url');

		}

		else

		{

		$instagram = "";

		}

		

		$facebook_client_id = $request->input('facebook_client_id');

		$facebook_client_secret = $request->input('facebook_client_secret');

		$facebook_callback_url = $request->input('facebook_callback_url');

		$google_client_id = $request->input('google_client_id');

		$google_client_secret = $request->input('google_client_secret');

		$google_callback_url = $request->input('google_callback_url');

		$display_social_login = $request->input('display_social_login');

		 

		$sid = $request->input('sid');

			 

		$data = array('facebook_url' => $facebook, 'twitter_url' => $twitter, 'gplus_url' => $gplus, 'pinterest_url' => $pinterest, 'instagram_url' => $instagram, 'facebook_client_id' => $facebook_client_id, 'facebook_client_secret' => $facebook_client_secret, 'facebook_callback_url' => $facebook_callback_url, 'google_client_id' => $google_client_id, 'google_client_secret' => $google_client_secret, 'google_callback_url' => $google_callback_url, 'display_social_login' => $display_social_login);

  		Settings::updatemailData($sid, $data);

        return redirect()->back()->with('success', 'Update successfully.');

       

	

		

	

	}

	

	

	

	public function preferred_settings()

    {

        

		$sid = 1;

		$setting['setting'] = Settings::editGeneral($sid);

		

		return view('admin.preferred-settings', [ 'setting' => $setting, 'sid' => $sid]);

		

    }

	

	

	public function update_preferred_settings(Request $request)

	{

	

	     

		 $sid = $request->input('sid');

		 

		 

		 $site_blog_display = $request->input('site_blog_display');

		 $site_development_display = $request->input('site_development_display');

		

		 

         

		 $request->validate([

							

							

							

							'site_blog_display' => 'required',

							'site_development_display' => 'required', 

							

         ]);

		 $rules = array(

				

				

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

		

		

		 

		$data = array('site_blog_display' => $site_blog_display, 'site_development_display' => $site_development_display);

        Settings::updategeneralData($sid, $data);

        return redirect()->back()->with('success', 'Update successfully.');

            

 

       } 

     

       

	

	

	} 

	

	

	

	

	

	

	public function currency_settings()

    {

        

		$sid = 1;

		$setting['setting'] = Settings::editGeneral($sid);

		

		return view('admin.currency-settings', [ 'setting' => $setting, 'sid' => $sid]);

		

    }

	

	

	

	public function update_currency_settings(Request $request)

	{

	

	     

		 $sid = $request->input('sid');

		 

		 $site_currency_code = $request->input('site_currency_code');

		 $site_currency_symbol = $request->input('site_currency_symbol');

		

		

		 

         

		 $request->validate([

							

							'site_currency_code' => 'required',

							'site_currency_symbol' => 'required',

							

         ]);

		 $rules = array(

				

				

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

		

		

		 

		$data = array('site_currency_code' => $site_currency_code, 'site_currency_symbol' => $site_currency_symbol);

        Settings::updategeneralData($sid, $data);

        return redirect()->back()->with('success', 'Update successfully.');

            

 

       } 

     

       

	

	

	} 

	

	

	

	

	public function payment_settings()

    {

		$sid = 1;

		$setting['setting'] = Settings::editGeneral($sid);

		$payment_option = array('paypal','stripe','paystack','razorpay', 'perfectmoney', 'coinbase', 'payeer');

		$withdraw_option = array('paypal','stripe','paystack', 'perfectmoney');

		$get_payment = explode(',', $setting['setting']->payment_option);

		$get_withdraw = explode(',', $setting['setting']->withdraw_option);

		return view('admin.payment-settings', [ 'setting' => $setting, 'sid' => $sid, 'payment_option' => $payment_option, 'withdraw_option' => $withdraw_option, 'get_payment' => $get_payment, 'get_withdraw' => $get_withdraw]);

		

    }

	

	

	public function update_payment_settings(Request $request)
	{

		$site_extra_fee = $request->input('site_extra_fee');   

		if(!empty($request->input('payment_option'))) {

			$payment = "";

			foreach($request->input('payment_option') as $payment_option)
			{

				$payment .= $payment_option.',';

			}

			$payment_method = rtrim($payment,',');

		} else {

	   		$payment_method = "";

	   	}

		if(!empty($request->input('withdraw_option'))) {

			$withdraw = "";

			foreach($request->input('withdraw_option') as $withdraw_option)
			{

				$withdraw .= $withdraw_option.',';

			}

			$withdraw_method = rtrim($withdraw,',');

		} else {

			$withdraw_method = "";

		}

		$paypal_email = $request->input('paypal_email');

		$paypal_mode = $request->input('paypal_mode');

		$stripe_mode = $request->input('stripe_mode');

		$test_publish_key = $request->input('test_publish_key');

		$live_publish_key = $request->input('live_publish_key');

		$test_secret_key = $request->input('test_secret_key');

		$live_secret_key = $request->input('live_secret_key');

		$site_minimum_withdrawal = $request->input('site_minimum_withdrawal');

		$paystack_public_key = $request->input('paystack_public_key');

		$paystack_secret_key = $request->input('paystack_secret_key');

		$paystack_merchant_email = $request->input('paystack_merchant_email');

		$razorpay_key = $request->input('razorpay_key');

		$razorpay_secret = $request->input('razorpay_secret');

		$perfectmoney_key = $request->input('perfectmoney_key');

		$perfectmoney_secret = $request->input('perfectmoney_secret');

		$coinbase_key = $request->input('coinbase_key');

		$coinbase_secret = $request->input('coinbase_secret');



	   	$request->validate([

			'site_extra_fee' => 'required',

        ]);

		$sid = $request->input('sid');

		$rules = array();
		$messsages = array();		 

		$validator = Validator::make($request->all(), $rules,$messsages);

		if ($validator->fails()) {

			$failedRules = $validator->failed();

			return back()->withErrors($validator);

		} else {

			$data = array(
				'site_extra_fee' => $site_extra_fee, 
				'payment_option' => $payment_method, 
				'withdraw_option' => $withdraw_method, 
				'paypal_email' => $paypal_email, 
				'paypal_mode' => $paypal_mode, 
				'stripe_mode' => $stripe_mode, 
				'test_publish_key' => $test_publish_key, 
				'test_secret_key' => $test_secret_key, 
				'live_publish_key' => $live_publish_key, 
				'live_secret_key' => $live_secret_key, 
				'site_minimum_withdrawal' => $site_minimum_withdrawal, 
				'paystack_public_key' => $paystack_public_key, 
				'paystack_secret_key' => $paystack_secret_key, 
				'paystack_merchant_email' => $paystack_merchant_email, 
				'razorpay_key' => $razorpay_key, 
				'razorpay_secret' => $razorpay_secret, 
				'perfectmoney_key' => $perfectmoney_key, 
				'perfectmoney_secret' => $perfectmoney_secret, 
				'coinbase_key' => $coinbase_key, 
				'coinbase_secret' => $coinbase_secret
			);

			Settings::updatemailData($sid, $data);

            return redirect()->back()->with('success', 'Update successfully.');

       	}

	}

	

	/* settings */

	

	/* country settings */

  	public function country_settings()
    {

		$country['data'] = Settings::getcountryData();

		return view('admin.country-settings',[ 'country' => $country]);

    }

	public function add_country()
	{

	   return view('admin.add-country');

	}

	

	public function save_country(Request $request)

	{

 

    

         $country_name = $request->input('country_name');

		         

         

		 $request->validate([

							'country_name' => 'required',

							

							

         ]);

		 $rules = array(

				

				'country_name' => ['required', 'max:255', Rule::unique('country') -> where(function($sql){ $sql->where('country_name','!=','');})],

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

		

				 

		$data = array('country_name' => $country_name);

 

            

            Settings::savecountryData($data);

            return redirect('/admin/country-settings')->with('success', 'Insert successfully.');

            

 

       } 

     

    

  }

  

  

  

  public function delete_country($cid){



      

	  

      Settings::deleteCountrydata($cid);

	  

	  return redirect()->back()->with('success', 'Delete successfully.');



    

  }

  

  

  public function edit_country($cid)

	{

	   

	   $edit['country'] = Settings::editCountry($cid);

	   return view('admin.edit-country', [ 'edit' => $edit, 'cid' => $cid]);

	}

	

	

	

	public function update_country(Request $request)

	{

	

	   $country_name = $request->input('country_name');

		         

         

		 $request->validate([

							'country_name' => 'required',

							

							

         ]);

		 

		  $cid = $request->input('cid');

		 

         

		 

		 $rules = array(

				'country_name' => ['required', 'max:255', Rule::unique('country') ->ignore($cid, 'country_id') -> where(function($sql){ $sql->where('country_name','!=','');})],

				

				

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

		

			  

		 

		 

		$data = array('country_name' => $country_name);

 

            

            

			Settings::updatecountryData($cid, $data);

            return redirect('/admin/country-settings')->with('success', 'Update successfully.');

            

 

       } 

     

      

	

	

	}

	

	

	

	

  /* country settings */	

  

  

	

	

	

}

