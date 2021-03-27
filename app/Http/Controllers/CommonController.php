<?php



namespace DownGrade\Http\Controllers;


use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use DownGrade\Models\Members;

use DownGrade\Models\Settings;

use DownGrade\Models\Pages;

use DownGrade\Models\Category;

use DownGrade\Models\Blog;

use DownGrade\Models\Product;

use DownGrade\Models\Comment;

use Mail;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Input;

use Auth;

use Illuminate\Support\Facades\Crypt;

use Illuminate\Validation\Rule;

use URL;

use Cookie;

use Redirect;

use charlesassets\LaravelPerfectMoney\PerfectMoney;

use CoinbaseCommerce\Webhook;



class CommonController extends Controller
{

	public function cookie_translate($id)
	{
		Cookie::queue(Cookie::make('translate', $id, 3000));

		return Redirect::route('index')->withCookie('translate');	  
	}

    public function test()

    {

//        $pm = new PerfectMoney;

//        $balance = $pm->getBalance();

        dd(env('PM_PAYMENT_URL'));

//        if($balance['status'] == 'success')

//        {

//            return $balance['USD'];

//        }



    }

	

	public function view_tags($type,$slug)

	{

	$nslug = str_replace("-"," ",$slug); 

	$tagproduct['view'] = Product::with('ratings')->join('category','category.cat_id','product.product_category')->where('product.product_drop_status','=','no')->where('product.product_status','=',1)->where('product.product_tags', 'LIKE', "%$nslug%")->orderBy('product.product_id', 'desc')->get();

	$data = array('tagproduct' => $tagproduct, 'nslug' => $nslug);

	return view('tag')->with($data);

	

	}

	

	public function payment_cancel()

	{

	  return view('cancel');

	}

	

	public function not_found()

	{

	  return view('404');

	}

	

	

	public function autoComplete(Request $request) {

	    

        $query = $request->get('term','');

        

        $products=Product::autoSearch($query);

        

        $data=array();

        foreach ($products as $product) {

                $data[]=array('value'=>$product->product_name,'id'=>$product->product_id);

        }

        if(count($data))

             return $data;

        else

            return ['value'=>'No Result Found','id'=>''];

    }

	

	public function view_free_item($download,$item_token)
	{

	

	  $token = base64_decode($item_token);

	  

	  $item['data'] = Product::editproductData($token);

	  $item_count = $item['data']->download_count + 1;

	  $data = array('download_count' => $item_count);

	  Product::updateproductData($token,$data);

	  

	  $filename = public_path().'/storage/product/'.$item['data']->product_file;

		$headers = ['Content-Type: application/octet-stream'];

		$new_name = uniqid().time().'.zip';

		return response()->download($filename,$new_name,$headers);

	

	}

	public function view_item($slug)
	{

		$item['view'] = Product::singleitemData($slug);

		$view_count = $item['view']->product_views + 1;

		$product_token = $item['view']->product_token;

		$product_id = $item['view']->product_id;

		$count_data = array('product_views' => $view_count);

		Product::updatefavouriteData($product_id,$count_data);

		$getcount = Product::getimagesCount($product_token);

		$getfirst['image'] = Product::getimagesFirst($product_token);

		$getall['image'] = Product::getimagesAll($product_token);

		$sid = 1;

		$setting['setting'] = Settings::editGeneral($sid);

		$page_slug = $setting['setting']->product_support_link;

		$page['view'] = Pages::editpageData($page_slug);

		if (Auth::check()) 
		{

			$checkif_purchased = Product::ifpurchaseCount($product_token);

		} else {

	    	$checkif_purchased = 0;

	  	}

	    $browser['view'] = Product::browserData();

	   	$package['view'] = Product::packData();

		$comment['view'] = Comment::with('ReplyComment')->leftjoin('users', 'users.id', '=', 'product_comments.comm_user_id')->where('product_comments.comm_product_id','=',$product_id)->orderBy('comm_id', 'asc')->get();


		$comment_count = $comment['view']->count();

		$getreviewdata['view']  = Product::getreviewItems($product_id);

		$review_count = Product::getreviewCount($product_id);


		$getreview  = Product::getreviewRecord($product_id);

		if($getreview != 0)
		{

			$review['view'] = Product::getreviewView($product_id);

			$top = 0;

			$bottom = 0;

			foreach($review['view'] as $review)
			{

				if($review->rating == 1) { $value1 = $review->rating*1; } else { $value1 = 0; }

				if($review->rating == 2) { $value2 = $review->rating*2; } else { $value2 = 0; }

				if($review->rating == 3) { $value3 = $review->rating*3; } else { $value3 = 0; }

				if($review->rating == 4) { $value4 = $review->rating*4; } else { $value4 = 0; }

				if($review->rating == 5) { $value5 = $review->rating*5; } else { $value5 = 0; }

				

				$top += $value1 + $value2 + $value3 + $value4 + $value5;

				$bottom += $review->rating;

			}

			if(!empty(round($top/$bottom)))
			{

				$count_rating = round($top/$bottom);

			} else {

				$count_rating = 0;

			}

		} else {

			$count_rating = 0;

		}

		$related['product'] = Product::with('ratings')->join('category','category.cat_id','product.product_category')->where('product.product_status','=',1)->where('product.product_drop_status','=','no')->where('product.product_token','!=',$product_token)->inRandomOrder()->take(4)->get();

		$product_packages = Product::getproductPackages($product_id);
		$product_faqs = Product::getproductFaqs($product_id);

		$data = array('item' => $item, 'getcount' => $getcount, 'getfirst' => $getfirst, 'getall' => $getall, 'page' => $page, 'checkif_purchased' => $checkif_purchased, 'package' => $package, 'browser' => $browser, 'comment_count' => $comment_count, 'comment' => $comment, 'getreviewdata' => $getreviewdata, 'review_count' => $review_count, 'getreview' => $getreview, 'count_rating' => $count_rating, 'slug' => $slug, 'related' => $related,'product_packages'=>$product_packages,'product_faqs'=>$product_faqs);

		return view('item')->with($data);

	}
	

	public function view_free_items()
	{

		$sid = 1;

		$setting['setting'] = Settings::editGeneral($sid);

		$free['product'] = Product::with('ratings')->join('category','category.cat_id','product.product_category')->where('product.product_status','=',1)->where('product.product_drop_status','=','no')->where('product.product_free','=',1)->orderBy('product.product_date', 'desc')->get();

		$data = array('setting' => $setting, 'free' => $free);

		return view('free-items')->with($data);

	}


	public function view_featured_items()

	{

	   $sid = 1;

	   $setting['setting'] = Settings::editGeneral($sid);

	   $featured['product'] = Product::with('ratings')->join('category','category.cat_id','product.product_category')->where('product.product_status','=',1)->where('product.product_drop_status','=','no')->where('product.product_featured','=',1)->orderBy('product.product_date', 'desc')->get();

	   $data = array('setting' => $setting, 'featured' => $featured);

	   return view('featured-items')->with($data);

	}

    

	public function view_sale_items()

	{

	

	   $sid = 1;

	   $setting['setting'] = Settings::editGeneral($sid);

	   $end_sale = $setting['setting']->site_flash_end_date;

	   $today_date = date('Y-m-d');

	   if($end_sale <= $today_date)

	   {

	     $off_flash = array('product_flash_sale' => 0);

		 Product::offFlash($off_flash);

	   }

	   $flash['product'] = Product::with('ratings')->join('category','category.cat_id','product.product_category')->where('product.product_status','=',1)->where('product.product_drop_status','=','no')->where('product.product_flash_sale','=',1)->orderBy('product.product_date', 'desc')->get();

	   $data = array('setting' => $setting, 'flash' => $flash);

	   return view('sale')->with($data);

	

	}

	

	public function view_popular_items()

	{

	   $sid = 1;

	   $setting['setting'] = Settings::editGeneral($sid);

	   $popular['product'] = Product::with('ratings')->join('category','category.cat_id','product.product_category')->where('product.product_status','=',1)->where('product.product_drop_status','=','no')->orderBy('product.product_views', 'desc')->get();

	   $data = array('setting' => $setting, 'popular' => $popular);

	   return view('popular-items')->with($data);

	}

	public function view_new_items()
	{

	   $sid = 1;

	   $setting['setting'] = Settings::editGeneral($sid);

	   $newest['product'] = Product::with('ratings')->join('category','category.cat_id','product.product_category')->where('product.product_status','=',1)->where('product.product_drop_status','=','no')->orderBy('product.product_id', 'desc')->get();

	   $data = array('setting' => $setting, 'newest' => $newest);

	   return view('new-releases')->with($data);

	}

    public function view_index()
	{

	   $sid = 1;

	   $setting['setting'] = Settings::editGeneral($sid);

	   $end_sale = $setting['setting']->site_flash_end_date;

	   $today_date = date('Y-m-d');

	   if($end_sale <= $today_date)

	   {

	     $off_flash = array('product_flash_sale' => 0);

		 Product::offFlash($off_flash);

	   }

	   $take_featured = $setting['setting']->home_featured_items;

	   $take_flash = $setting['setting']->home_flash_items;

	   $take_popular = $setting['setting']->home_popular_items;

	   $take_newest = $setting['setting']->home_new_items;

	   $take_free = $setting['setting']->home_free_items;

	   $finals['well'] = Product::with('ratings')->join('category','category.cat_id','product.product_category')->where('product.product_status','=',1)->where('product.product_drop_status','=','no')->where('product.product_free','=',1)->orderBy('product.product_date', 'desc')->take($take_free)->get();

	   $featured['product'] = Product::with('ratings')->join('category','category.cat_id','product.product_category')->where('product.product_status','=',1)->where('product.product_drop_status','=','no')->where('product.product_featured','=',1)->orderBy('product.product_date', 'desc')->take($take_featured)->get();

	   $flash['product'] = Product::with('ratings')->join('category','category.cat_id','product.product_category')->where('product.product_status','=',1)->where('product.product_drop_status','=','no')->where('product.product_flash_sale','=',1)->orderBy('product.product_date', 'desc')->take($take_flash)->get();

	   $popular['product'] = Product::with('ratings')->join('category','category.cat_id','product.product_category')->where('product.product_status','=',1)->where('product.product_drop_status','=','no')->orderBy('product.product_views', 'desc')->take($take_popular)->get();

	   $newest['product'] = Product::with('ratings')->join('category','category.cat_id','product.product_category')->where('product.product_status','=',1)->where('product.product_drop_status','=','no')->orderBy('product.product_id', 'desc')->take($take_newest)->get();

	   

	   

	   $latestpost['view'] = Blog::homepostData($setting['setting']->home_blog_post);

	   $viewlogo['display'] = Blog::homelogoData();

	   $comments = Blog::getgroupcommentData();

	  return view('index',['finals' => $finals, 'setting' => $setting, 'featured' => $featured, 'flash' => $flash, 'popular' => $popular, 'newest' => $newest, 'latestpost' => $latestpost, 'viewlogo' => $viewlogo, 'comments' => $comments]); 

	}

	public function view_shop_items(Request $request)
	{

	  $product_item = $request->input('product_item');

	  if(!empty($request->input('category_names')))

	   {

	      

		  $category_no = "";

		  foreach($request->input('category_names') as $category_value)

		  {

		     $category_no .= $category_value.',';

		  }

		  $category_names = rtrim($category_no,",");

		  

	   }

	   else

	   {

	     $category_names = "";

	   }

	  if(!empty($request->input('orderby')))

	  { 

	  $orderby = $request->input('orderby');

	  }

	  else

	  {

	  $orderby = "desc";

	  }

	  $min_price = $request->input('min_price');

	  $max_price = $request->input('max_price'); 

	  if($product_item != "" ||  $orderby != "" || $min_price != "" || $max_price != "")

	  {

	  $itemData['item'] = Product::with('ratings')

	                      ->join('category','category.cat_id','product.product_category')

	                      ->where('product.product_status','=',1)

						  ->where('product.product_drop_status','=','no')

						  ->where(function ($query) use ($product_item,$category_names,$orderby,$min_price,$max_price) { 

						  $query->where('product.product_name', 'LIKE', "%$product_item%");

						  if ($min_price != "" || $max_price != "")

						  {

						  $query->where('product.regular_price', '>', $min_price);

						  $query->where('product.regular_price', '<', $max_price);

						  }

						  if ($category_names != "")

						  {

						  $query->whereRaw('FIND_IN_SET(product.product_category,"'.$category_names.'")');

						  }

						  })->orderBy('product.regular_price', $orderby)->get();

						  

						  

	  }

	  else

	  {

	   $itemData['item'] = Product::with('ratings')->join('category','category.cat_id','product.product_category')->join('users', 'users.id', '=', 'product.user_id')->where('product.product_status','=',1)->where('product.product_drop_status','=','no')->orderBy('product.product_id', 'desc')->get(); 

	   

	  }

	 	 

	 $catData['item'] = Product::getitemcatData();

	

		$browser['view'] = Product::browserData();

		$package['view'] = Product::packData();

		$category['view'] = Category::categorydisplayOrder();

		$type = "";

		$meta_keyword = "";

		$meta_desc = "";

		$count_item = Product::getgroupitemData();

		return view('shop',[ 'itemData' => $itemData, 'catData' => $catData, 'browser' => $browser, 'package' => $package, 'category' => $category, 'type' => $type, 'meta_keyword' => $meta_keyword, 'meta_desc' => $meta_desc, 'count_item' => $count_item]);

	}

	
	public function view_all_items()
	{

	  

	  $itemData['item'] = Product::with('ratings')->join('category','category.cat_id','product.product_category')->join('users', 'users.id', '=', 'product.user_id')->where('product.product_status','=',1)->where('product.product_drop_status','=','no')->orderBy('product.product_id', 'asc')->get();

	  $catData['item'] = Product::getitemcatData();

	  

	  $browser['view'] = Product::browserData();

	   $package['view'] = Product::packData();

	  $category['view'] = Category::categorydisplayOrder();

	  $type = "";

	  $meta_keyword = "";

	  $meta_desc = "";

	  $count_item = Product::getgroupitemData();

	  

	  return view('shop',[ 'itemData' => $itemData, 'catData' => $catData, 'browser' => $browser, 'package' => $package, 'category' => $category, 'type' => $type, 'meta_keyword' => $meta_keyword, 'meta_desc' => $meta_desc, 'count_item' => $count_item]);

	  

	}

	public function view_category_items($type,$slug)
	{

	   $category_type['view'] = Category::slugcategoryData($slug);

	   $cat_id = $category_type['view']->cat_id;

	  

	  $itemData['item'] = Product::with('ratings')->join('category','category.cat_id','product.product_category')->join('users', 'users.id', '=', 'product.user_id')->where('product.product_status','=',1)->where('product.product_drop_status','=','no')->where('product.product_category','=',$cat_id)->orderBy('product.product_id', 'asc')->get();

	  

	 $catData['item'] = Product::getitemcatData();

	  

	  $browser['view'] = Product::browserData();

	   $package['view'] = Product::packData();

	  $category['view'] = Category::categorydisplayOrder();

	  $meta_keyword = $category_type['view']->category_meta_keywords;

	  $meta_desc = $category_type['view']->category_meta_desc;

	  $count_item = Product::getgroupitemData();

	  return view('shop',[ 'itemData' => $itemData, 'catData' => $catData, 'browser' => $browser, 'package' => $package, 'category' => $category, 'type' => $type, 'meta_keyword' => $meta_keyword, 'meta_desc' => $meta_desc, 'count_item' => $count_item]);

	

	}

	public function all_categories()
	{

	  $sid = 1;

	  $setting['setting'] = Settings::editGeneral($sid);

	  $category['view'] = Category::quickbookData();

	  $count_cause = Category::getgroupcauseData();

	  $data = array('setting' => $setting, 'category' => $category, 'count_cause' => $count_cause);

	  return view('categories')->with($data); 

	}

	

	

	public function all_gallery()

	{

	  $gallery['view'] = Events::viewallGallery(); 

	  $data = array('gallery' => $gallery);

	  return view('gallery')->with($data);

	

	}

	

	

	

	public function donor_paypal_success($ord_token, Request $request)

	{

	

	$payment_token = $request->input('tx');

	$purchased_token = $ord_token;

	$donor['details'] = Causes::getDonor($purchased_token);

	$user_id = $donor['details']->donor_cause_user_id;

	$checkcount = Causes::checkuserSubscription($user_id);

	$sid = 1;

	$setting['setting'] = Settings::editGeneral($sid);

	$user_data['view'] = Members::singlebuyerData($user_id);

	if($checkcount == 0)

	{

		$commission = ($setting['setting']->site_admin_commission * $donor['details']->donor_amount) / 100;

		$user_amount = $donor['details']->donor_amount - $commission;

		$admin_amount = $commission;

		$user_old_amount = $user_data['view']->earnings + $user_amount;

		$admin_details['view'] = Members::adminData();

		$admin_old_amount = $admin_details['view']->earnings + $admin_amount;

		$user_record = array('earnings' => $user_old_amount);

		Members::updateuserPrice($user_id, $user_record);

		$admin_data = array('earnings' => $admin_old_amount);

		Members::updateuserPrice(1, $admin_data);			   

				  

	}

	$cause_id = $donor['details']->donor_cause_id;

	$cause['details'] = Causes::singleCausesdetails($cause_id);

	$raised_price = $cause['details']->cause_raised + $donor['details']->donor_amount;

	$pricedata = array('cause_raised' => $raised_price);

	Causes::updatecausePrice($cause_id,$pricedata);

	

	$checkoutdata = array('donor_payment_token' => $payment_token, 'donor_payment_status' => 'completed');

	Causes::updatedonorData($purchased_token,$checkoutdata);

	$result_data = array('payment_token' => $payment_token);

	

	$check_email_support = Members::getuserSubscription($user_id);

	if($check_email_support == 1)

	{   

	    $donor_payment_amount = $donor['details']->donor_amount;

		$admin_name = $setting['setting']->sender_name;

		$admin_email = $setting['setting']->sender_email;

		$currency_symbol = $setting['setting']->site_currency_symbol;

		$cause_url = URL::to('/cause/').$cause['details']->cause_slug;

		$record = array('donor_payment_amount' => $donor_payment_amount, 'currency_symbol' => $currency_symbol, 'cause_url' => $cause_url);

		$to_name = $user_data['view']->name;

		$to_email = $user_data['view']->email;

		Mail::send('donation_mail', $record, function($message) use ($admin_name, $admin_email, $to_email, $to_name) {

		$message->to($to_email, $to_name)

			->subject('Donation payment received');

			$message->from($admin_email,$admin_name);

			});

	}

	return view('donor-success')->with($result_data);

	

	}

	

	

	

	public function confirm_donation(Request $request)

	{

	   

	   $token = $request->input('token');

	   $donor_name = $request->input('donor_name');

	   $donor_email = $request->input('donor_email'); 

	   $donor_phone = $request->input('donor_phone');

	   $donor_amount = $request->input('donor_amount');

	   $donor_note = $request->input('donor_note'); 

	   $cause_title = $request->input('cause_title');

	   $cause_slug = $request->input('cause_slug');

	   $image_size = $request->input('image_size');   

	   $purchase_token = rand(111111,999999);

	   $payment_method = $request->input('payment_method');

	   $website_url = $request->input('website_url');

	   $donor_purchase_date = date('Y-m-d');

	   $donor_cause_id = $request->input('donor_cause_id');

	   $cause_raised = base64_decode($request->input('cause_raised'));

	   $donor_cause_token = $request->input('donor_cause_token');

	   $sid = 1;

	   $setting['setting'] = Settings::editGeneral($sid);

	   $user_id = $request->input('cause_user_id');

	   $raised_price = $cause_raised + $donor_amount;

	   $miniumum_amount = $setting['setting']->site_minimum_donate;

	   $request->validate([

							'donor_name' => 'required',

							'donor_email' => 'required',

							'donor_phone' => 'required',

							'donor_amount' => 'required|numeric|min:'.$miniumum_amount,

							'donor_photo' => 'mimes:jpeg,jpg,png|max:'.$image_size,

							

							

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

	        

			

	   

	   

			   if ($request->hasFile('donor_photo')) 

			   {

							

							$image = $request->file('donor_photo');

							$img_name = time() . '.'.$image->getClientOriginalExtension();

							$destinationPath = public_path('/storage/donors');

							$imagePath = $destinationPath. "/".  $img_name;

							$image->move($destinationPath, $img_name);

							$donor_photo = $img_name;

			  }

			  else

			  {

				$donor_photo = "";

			  }

			   

	   

	   

			   $savedata = array('donor_cause_id' => $donor_cause_id, 'donor_cause_user_id' => $user_id, 'donor_cause_token' => $donor_cause_token, 'donor_name' => $donor_name, 'donor_email' => $donor_email, 'donor_phone' => $donor_phone, 'donor_amount' => $donor_amount, 'donor_note' => $donor_note, 'donor_payment_type' => $payment_method, 'donor_purchase_token' => $purchase_token, 'donor_purchase_date' => $donor_purchase_date, 'donor_photo' => $donor_photo, 'donor_payment_status' => 'pending');

			   

			   

			   $checkcount = Causes::checkuserSubscription($user_id);

			   $user_data['view'] = Members::singlebuyerData($user_id);

			   /* settings */

			   $site_currency = $setting['setting']->site_currency_code;

			   $success_url = $website_url.'/donor-success/'.$purchase_token;

			   $cancel_url = $website_url.'/cancel';

			   

			   if($checkcount == 1)

			   {

				   $paypal_email = $user_data['view']->user_paypal_email;

				   $paypal_mode = $user_data['view']->user_paypal_mode;

				   if($paypal_mode == 1)

				   {

					 $paypal_url = "https://www.paypal.com/cgi-bin/webscr";

				   }

				   else

				   {

					 $paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";

				   }

				  

				   $stripe_mode = $user_data['view']->user_stripe_mode;

				   if($stripe_mode == 0)

				   {

					 $stripe_publish_key = $user_data['view']->user_test_publish_key;

					 $stripe_secret_key = $user_data['view']->user_test_secret_key;

				   }

				   else

				   {

					 $stripe_publish_key = $user_data['view']->user_live_publish_key;

					 $stripe_secret_key = $user_data['view']->user_live_secret_key;

				   }

			   

			   }

			   else

			   {

				  

				   $paypal_email = $setting['setting']->paypal_email;

				   $paypal_mode = $setting['setting']->paypal_mode;

				   if($paypal_mode == 1)

				   {

					 $paypal_url = "https://www.paypal.com/cgi-bin/webscr";

				   }

				   else

				   {

					 $paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";

				   }

				  

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

				   

						  

			   }

			   

				   /* settings */

				   Causes::insertdonorData($savedata);

				   

				   if($payment_method == 'paypal')

					  {

						 

						 $paypal = '<form method="post" id="paypal_form" action="'.$paypal_url.'">

						  <input type="hidden" value="_xclick" name="cmd">

						  <input type="hidden" value="'.$paypal_email.'" name="business">

						  <input type="hidden" value="'.$cause_title.'" name="item_name">

						  <input type="hidden" value="'.$purchase_token.'" name="item_number">

						  <input type="hidden" value="'.$donor_amount.'" name="amount">

						  <input type="hidden" value="'.$site_currency.'" name="currency_code">

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

								'email' => $donor_email,

								'source'  => $token

							));

						 

							

							$cause_name = $cause_title;

							$donor_price = $donor_amount * 100;

							$currency = $site_currency;

							$book_id = $purchase_token;

						 

							

							$charge = \Stripe\Charge::create(array(

								'customer' => $customer->id,

								'amount'   => $donor_price,

								'currency' => $currency,

								'description' => $cause_name,

								'metadata' => array(

									'order_id' => $book_id

								)

							));

						 

							

							$chargeResponse = $charge->jsonSerialize();

						 

							

							if($chargeResponse['paid'] == 1 && $chargeResponse['captured'] == 1) 

							{

						 

								if($checkcount == 0)

								{

								   

								   $commission = ($setting['setting']->site_admin_commission * $donor_amount) / 100;

								   $user_amount = $donor_amount - $commission;

								   $admin_amount = $commission;

								   $user_old_amount = $user_data['view']->earnings + $user_amount;

								   $admin_details['view'] = Members::adminData();

								   $admin_old_amount = $admin_details['view']->earnings + $admin_amount;

								   $user_record = array('earnings' => $user_old_amount);

								   Members::updateuserPrice($user_id, $user_record);

								   $admin_data = array('earnings' => $admin_old_amount);

								   Members::updateuserPrice(1, $admin_data);

			

								  

								}

								$pricedata = array('cause_raised' => $raised_price);

								Causes::updatecausePrice($donor_cause_id,$pricedata);

													

								$payment_token = $chargeResponse['balance_transaction'];

								$purchased_token = $book_id;

								$checkoutdata = array('donor_payment_token' => $payment_token, 'donor_payment_status' => 'completed');

								Causes::updatedonorData($purchased_token,$checkoutdata);

								$data_record = array('payment_token' => $payment_token);

								

								

								$check_email_support = Members::getuserSubscription($user_id);

								if($check_email_support == 1)

								{   

									$donor_payment_amount = $donor_amount;

									$admin_name = $setting['setting']->sender_name;

									$admin_email = $setting['setting']->sender_email;

									$currency_symbol = $setting['setting']->site_currency_symbol;

									$cause_url = URL::to('/cause/').$cause_slug;

									$record = array('donor_payment_amount' => $donor_payment_amount, 'currency_symbol' => $currency_symbol, 'cause_url' => $cause_url);

									$to_name = $user_data['view']->name;

									$to_email = $user_data['view']->email;

									Mail::send('donation_mail', $record, function($message) use ($admin_name, $admin_email, $to_email, $to_name) {

									$message->to($to_email, $to_name)

										->subject('Donation payment received');

										$message->from($admin_email,$admin_name);

										});

								}

								return view('success')->with($data_record);

								

								

							}

						 

					  

					  }

					  /* stripe code */

					  

	     }

	

	

	}

	

	

	public function activate_newsletter($token)

	{

	   

	   $check = Members::checkNewsletter($token);

	   if($check == 1)

	   {

	      

		  $data = array('news_status' => 1);

		

		  Members::updateNewsletter($token,$data);

		  

		  return redirect('/newsletter')->with('success', 'Thank You! Your subscription has been confirmed!');

		  

	   }

	   else

	   {

	       return redirect('/newsletter')->with('error', 'This email address already subscribed');

	   }

	

	}

	

	

	public function view_newsletter()

	{

	 

	  return view('newsletter');

	

	}

	

	

	public function update_newsletter(Request $request)

	{

	

	   $news_email = $request->input('news_email');

	   $news_status = 0;

	   $news_token = $this->generateRandomString();

	   

	   $request->validate([

							

							'news_email' => 'required|email',

							

							

							

         ]);

		 $rules = array(

		 

		      'news_email' => ['required',  Rule::unique('newsletter') -> where(function($sql){ $sql->where('news_status','=',0);})],

								

	     );

		 

		 $messsages = array(

		      

	    );

		 

		$validator = Validator::make($request->all(), $rules,$messsages);

		

		if ($validator->fails()) 

		{

		 $failedRules = $validator->failed();

		 /*return back()->withErrors($validator);*/

		 return redirect()->back()->with('news-error', 'This email address already subscribed.');

		} 

		else

		{

		

		

		$data = array('news_email' => $news_email, 'news_token' => $news_token, 'news_status' => $news_status);

		

		Members::savenewsletterData($data);

		

		$sid = 1;

		$setting['setting'] = Settings::editGeneral($sid);

		

		$from_name = $setting['setting']->sender_name;

        $from_email = $setting['setting']->sender_email;

		$activate_url = URL::to('/newsletter/').$news_token;

		

		$record = array('activate_url' => $activate_url);

		Mail::send('newsletter_mail', $record, function($message) use ($from_name, $from_email, $news_email) {

			$message->to($news_email)

					->subject('Newsletter');

			$message->from($from_email,$from_name);

		});

		

			   

		return redirect()->back()->with('news-success', 'Your email address subscribed. You will receive a confirmation email.');

		

		}

	   

	

	}

	

	

	public function view_allcauses()

	{

	   $causes['view'] = Causes::viewallCauses();

	   $slug = '';

	   $data = array('causes' => $causes, 'slug' => $slug); 

	   return view('causes')->with($data);

	

	}

	

	

	public function view_category_causes($slug)

	{

	  $causes['view'] = Causes::viewcategoryCauses($slug);

	   $data = array('causes' => $causes, 'slug' => $slug); 

	   return view('causes')->with($data);

	}

	

	

	public function single_cause($slug)

	{

	  $single['view'] = Causes::singleCause($slug);

	  $user_id = $single['view']->cause_user_id;

	  $checkcount = Causes::checkuserSubscription($user_id);

	  if($checkcount == 0)

	  {

	  $sid = 1;

	  $setting['setting'] = Settings::editGeneral($sid);

	  $get_payment = explode(',', $setting['setting']->payment_option);

	  }

	  else

	  {

	      $user['details'] = Members::singlebuyerData($user_id);

		  $get_payment = explode(',', $user['details']->user_payment_option);

	  }

	  

	    $x = $single['view']->cause_raised;

        $y = $single['view']->cause_goal;

        $percent = $x/$y;

        $percent_value = number_format( $percent * 100);

        if($percent_value >= 100)

        {

          $percent_val = 100;

        }

        else

        {

          $percent_val = $percent_value;

        }

		

		$donor['details'] = Causes::recentDonation($single['view']->cause_id);

        $data = array('single' => $single, 'percent_val' => $percent_val, 'get_payment' => $get_payment, 'donor' => $donor); 

	  

	   return view('cause')->with($data);

	}

	

	

	public function view_became_volunteer()

	{

	   return view('became-volunteer');

	}



    public function user_verify($user_token)

    {

        $data = array('verified'=>'1');

		$user['user'] = Members::verifyuserData($user_token, $data);

		

		return redirect('login')->with('success','Your e-mail is verified. You can now login.');

    }

	

	

	public function single_volunteer($slug)

	{

	   $single['view'] = Volunteers::slugVolunteers($slug);

	   $data = array('single' => $single); 

	   return view('volunteer')->with($data);

	}

	

	

	public function all_volunteer()

	{

	

	  $display['view'] = Volunteers::allVolunteers();

	   $data = array('display' => $display); 

	   return view('volunteers')->with($data);

	

	}

	

	public function all_events()

	{

	

	  $display['view'] = Events::allEvents();

	  $category['view'] = Category::eventCategoryData();

	  $count_category = Category::getgroupeventData();

	  $slug = "";

	   $data = array('display' => $display, 'category' => $category, 'count_category' => $count_category, 'slug' => $slug); 

	   return view('events')->with($data);

	

	}

	

	

	public function single_event($slug)

	{

	   $single['view'] = Events::singleEvent($slug);

	   $category['view'] = Category::eventCategoryData();

	   $count_category = Category::getgroupeventData();

	   $recent['view'] = Events::recentEvent($slug);

	   $event_start_time = date('F d, Y H:i:s', strtotime($single['view']->event_start_date_time));

	   $data = array('single' => $single, 'category' => $category, 'count_category' => $count_category, 'slug' => $slug, 'recent' => $recent, 'event_start_time' => $event_start_time); 

	   return view('event')->with($data);

	

	}

	

	

	public function view_category_events($cat_id,$slug)

	{

	

	$display['view'] = Events::categoryEvents($cat_id);

	  $category['view'] = Category::eventCategoryData();

	  $count_category = Category::getgroupeventData();

	   $data = array('display' => $display, 'category' => $category, 'count_category' => $count_category, 'slug' => $slug); 

	   return view('events')->with($data);

	

	}

	

	

	

	public function view_subscription()

	{

	 $subscription['view'] = Members::viewSubscription();

	 $data = array('subscription' => $subscription);  

	 return view('subscription')->with($data);

	}

	

	public function view_forgot()

	{

	   return view('forgot');

	}

	

	public function view_contact()

	{

	   return view('contact');

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

	

	public function view_reset($token)

	{

	  $data = array('token' => $token);

	  return view('reset')->with($data);

	}

	

	public function volunteer_slug($string){

		   $slug=preg_replace('/[^A-Za-z0-9-]+/', '-', $string);

		   return $slug;

    }

	

	public function submit_volunteer(Request $request)

	{

	

	   $volu_firstname = $request->input('volu_firstname');

	   $volu_lastname = $request->input('volu_lastname');

	   $volu_name = $volu_firstname.'-'.$volu_lastname;

	   $volu_slug = $this->volunteer_slug($volu_name);

	   $volu_email = $request->input('volu_email');

	   $volu_phone = $request->input('volu_phone');

	   $volu_profession = $request->input('volu_profession');

	   $volu_facebook_link = $request->input('volu_facebook_link');

	   $volu_twitter_link = $request->input('volu_twitter_link');

	   $volu_linked_link = $request->input('volu_linked_link');

	   $volu_address = $request->input('volu_address');	

	   $volu_about = $request->input('volu_about');

	   $image_size = $request->input('image_size');	

	   $volu_token = $this->generateRandomString();

	   $allsettings = Settings::allSettings();

	   $volunteers_approval = $allsettings->volunteers_approval;

	   

	   

	   if($volunteers_approval == 1)

	   {

	      $volunteer_status = 1;

		  $volunteer_approve_status = "Thanks for your submission. Your details updated successfully.";

	   }

	   else

	   {

	      $volunteer_status = 0;

		  $volunteer_approve_status = "Thanks for your submission. Once admin will activated your details. will publish on our website.";

	   }

	   

	   

	   $request->validate([

							'volu_email' => 'required|email',

							'volu_firstname' => 'required',

							'volu_lastname' => 'required',

							'volu_phone' => 'required',

							'volu_photo' => 'mimes:jpeg,jpg,png|max:'.$image_size,

							

							

         ]);

		 $rules = array(

				

				'volu_email' => ['required',  Rule::unique('volunteers') -> where(function($sql){ $sql->where('volu_drop_status','=','no');})],

				

				

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

	        

		  

			   if ($request->hasFile('volu_photo')) 

				  {

					$image = $request->file('volu_photo');

					$img_name = time() . '.'.$image->getClientOriginalExtension();

					$destinationPath = public_path('/storage/volunteers');

					$imagePath = $destinationPath. "/".  $img_name;

					$image->move($destinationPath, $img_name);

					$volu_photo = $img_name;

				  }

				  else

				  {

					 $volu_photo = "";

				  }

			   

			   $data = array('volu_firstname' => $volu_firstname, 'volu_lastname' => $volu_lastname, 'volu_email' => $volu_email, 'volu_phone' => $volu_phone, 'volu_photo' => $volu_photo, 'volu_address' => $volu_address, 'volu_profession' => $volu_profession, 'volu_facebook_link' => $volu_facebook_link, 'volu_twitter_link' => $volu_twitter_link, 'volu_linked_link' => $volu_linked_link, 'volu_about' => $volu_about, 'volu_token' => $volu_token, 'volu_status' => $volunteer_status, 'volu_slug' => $volu_slug);

			   

			   Members::savevolunteerData($data);

			   

			   return redirect()->back()->with('success', $volunteer_approve_status);

			

			   

			   

		}

		

				

	

	

	}

	

	

	public function update_reset(Request $request)

	{

	

	   $user_token = $request->input('user_token');

	   $password = bcrypt($request->input('password'));

	   $password_confirmation = $request->input('password_confirmation');

	   $data = array("user_token" => $user_token);

	   $value = Members::verifytokenData($data);

	   $user['user'] = Members::gettokenData($user_token);

	   if($value)

	   {

	   

	      $request->validate([

							'password' => 'required|confirmed|min:6',

							

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

		   

		   $record = array('password' => $password);

           Members::updatepasswordData($user_token, $record);

           return redirect('login')->with('success','Your new password updated successfully. Please login now.');

		

		}

	   

	   

	   }

	   else

	   {

              

			  return redirect()->back()->with('error', 'These credentials do not match our records.');

       }

	   

	   

	

	}

	

	

	

	public function update_forgot(Request $request)

	{

	   $email = $request->input('email');

	   

	   $data = array("email"=>$email);

 

       $value = Members::verifycheckData($data);

	   $user['user'] = Members::getemailData($email);

       

	   if($value == 1)

	   {

			

		$user_token = $user['user']->user_token;

		$name = $user['user']->name;

		$sid = 1;

		$setting['setting'] = Settings::editGeneral($sid);

		

		$from_name = $setting['setting']->sender_name;

        $from_email = $setting['setting']->sender_email;

		

		$record = array('user_token' => $user_token);

		Mail::send('forgot_mail', $record, function($message) use ($from_name, $from_email, $email, $name, $user_token) {

			$message->to($email, $name)

					->subject('Forgot Password');

			$message->from($from_email,$from_name);

		});

 

         return redirect('forgot')->with('success','We have e-mailed your password reset link!');     

			  

       }

	   else {

			return redirect()->back()->with('error', 'These credentials do not match our records.');

       }
	   

	}

	/* contact */

	

	public function update_contact(Request $request)
	{

		$from_name = $request->input('from_name');

		$from_email = $request->input('from_email');

		$message_text = $request->input('message_text');

		$sid = 1;

		$setting['setting'] = Settings::editGeneral($sid);

	  	$admin_name = $setting['setting']->sender_name;

        $admin_email = $setting['setting']->sender_email;

		$request->validate([

							'from_name' => 'required',

							'from_email' => 'required|email',

							'message_text' => 'required',

							'g-recaptcha-response' => 'required|captcha',

							

							

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
		
		} else {

			$record = array('from_name' => $from_name, 'from_email' => $from_email, 'message_text' => $message_text, 'contact_date' => date('Y-m-d'));

			$contact_count = Members::getcontactCount($from_email);

			if($contact_count == 0)

			{

				Members::saveContact($record);

				Mail::send('contact_mail', $record, function($message) use ($admin_name, $admin_email, $from_email, $from_name) {

					$message->to($admin_email, $admin_name)

							->subject('Contact');

					$message->from($from_email,$from_name);

				});

				return redirect('contact')->with('success','Your message has been sent successfully');

			} else {

				return redirect('contact')->with('error','Sorry! Your message already sent');

			}

		}
	}

	/* contact */

	public function coinbase_webhook()
	{
		$sid = 1;

		$setting['setting'] = Settings::editGeneral($sid);

		$coinbase_key = $setting['setting']->coinbase_key;
		$secret = $setting['setting']->coinbase_secret;

		$headerName = 'X-Cc-Webhook-Signature';
		$headers = getallheaders();
		$signraturHeader = isset($headers[$headerName]) ? $headers[$headerName] : null;
		$payload = trim(file_get_contents('php://input'));

		// $payload = json_decode(trim(file_get_contents('php://input')));
		// $event = $payload->event;

		$payment_token = $event->data->code;

		try {
			$event = Webhook::buildEvent($payload, $signraturHeader, $secret);

			if( $event->type == "charge:confirmed" ) {
			
				$checkoutdata = array('payment_token' => $payment_token);
	
				$checkout = DB::table('product_checkout')->where('payment_token', $payment_token)->first();

				$this->payment_success($checkout->purchase_token, $payment_token);
	
			} else {
	
				$checkoutdata = array('payment_token' => $payment_token);
	
				DB::table('product_checkout')->where('payment_token', $payment_token)->delete();
			}

			http_response_code(200);

		} catch (\Exception $exception) {
			http_response_code(400);
			echo 'Error occured. ' . $exception->getMessage();
		}
	}	

	public function payment_success($purchased_token, $payment_token) {

		$payment_status = 'completed';

		$orderdata = array('order_status' => $payment_status);

		$checkoutdata = array('payment_status' => $payment_status);

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

}

