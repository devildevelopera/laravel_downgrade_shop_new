<?php



namespace DownGrade\Providers;



use Illuminate\Support\ServiceProvider;



use Illuminate\Support\Facades\Schema;

use DownGrade\Models\Members;

use DownGrade\Models\Settings;

use DownGrade\Models\Category;

use DownGrade\Models\Pages;

use DownGrade\Models\Comment;

use DownGrade\Models\Product; 

use DownGrade\Models\Attribute;

use DownGrade\Models\Languages;

use Illuminate\Support\Facades\View;

use Auth;

use Illuminate\Support\Facades\Config;

use Route;

use Request;

use Cookie;

use Illuminate\Support\Facades\Crypt;



class AppServiceProvider extends ServiceProvider

{

    /**

     * Register any application services.

     *

     * @return void

     */

    public function register()

    {

        //

    }



    /**

     * Bootstrap any application services.

     *

     * @return void

     */

    public function boot()

    {

        Schema::defaultStringLength(191);

		$admin = Members::adminData();

		View::share('admin', $admin);

		

		$allsettings = Settings::allSettings();

		View::share('allsettings', $allsettings);

		

		$allcountry = Settings::allCountry();

		View::share('allcountry', $allcountry);

		

		$country['country'] = Settings::allCountry();

		View::share('country', $country);

		

		$demo_mode = 'off'; // on

		View::share('demo_mode', $demo_mode);

		

		$main_menu['category'] = Category::mainmenuCategoryData($allsettings->menu_display_categories,$allsettings->menu_categories_order);

		View::share('main_menu', $main_menu);

		

		

		$footer_menu['category'] = Category::mainmenuCategoryData($allsettings->footer_menu_display_categories,$allsettings->footer_menu_categories_order);

		View::share('footer_menu', $footer_menu);

		

		$footerpages['pages'] = Pages::footermenuData();

		View::share('footerpages', $footerpages);

		

		

		$languages['view'] = Languages::allLanguage();

		View::share('languages', $languages);

		

		if(!empty(Cookie::get('translate')))

		{

		$translate = Cookie::get('translate');

		   $lang_title['view'] = Languages::getLanguage($translate);

		   $language_title = $lang_title['view']->language_name;

		}

		else

		{

		  $default_count = Languages::defaultLanguageCount();

		  if($default_count == 0)

		  { 

		  $translate = "en";

		  $lang_title['view'] = Languages::getLanguage($translate);

		   $language_title = $lang_title['view']->language_name;

		  }

		  else

		  {

		  $default['lang'] = Languages::defaultLanguage();

		  $translate =  $default['lang']->language_code;

		  $lang_title['view'] = Languages::getLanguage($translate);

		   $language_title = $lang_title['view']->language_name;

		  }

		 

		}

		View::share('translate', $translate);

		View::share('language_title', $language_title);

		

		$allpages['pages'] = Pages::menupageData();

		View::share('allpages', $allpages);

		

		$total_customer = Members::totaluserCount();

		View::share('total_customer', $total_customer);

		

		$permission = array('dashboard' => 'Dashboard', 'settings' => 'Settings', 'country' => 'Country', 'customers' => 'Customers', 'category' => 'Category', 'manage-products' => 'Manage Products', 'orders' => 'Orders', 'refund-request' => 'Refund Request', 'rating-reviews' => 'Rating & Reviews', 'withdrawal' => 'Withdrawal Request', 'blog' => 'Blog',  'pages' => 'Pages', 'contact' => 'Contact', 'languages' => 'Languages');

		View::share('permission', $permission);

		

		$view['sold'] = Product::SoldProduct();

		$count_sold = 0;

		foreach($view['sold'] as $sold)

		{

		  $count_sold += $sold->product_sold;

		}

		View::share('count_sold', $count_sold);

		

		$mainmenu_count = Pages::mainmenuPageCount();

		View::share('mainmenu_count', $mainmenu_count);

			

		view()->composer('*', function($view){

            $view_name = str_replace('.', '-', $view->getName());

            view()->share('view_name', $view_name);

        });

		

		if($allsettings->stripe_mode == 0) 

		{ 

		$stripe_publish_key = $allsettings->test_publish_key; 

		$stripe_secret_key = $allsettings->test_secret_key;

		

		}

		else

		{ 

		$stripe_publish_key = $allsettings->live_publish_key;

		$stripe_secret_key = $allsettings->live_secret_key;

		}

		View::share('stripe_publish_key', $stripe_publish_key);

		View::share('stripe_secret_key', $stripe_secret_key);

		

		$product_type = array("physical","digital","external");

		View::share('product_type', $product_type);

		

		$minprice['price'] = Product::minpriceData();

		View::share('minprice', $minprice);

		

		$maxprice['price'] = Product::maxpriceData();

		View::share('maxprice', $maxprice);

		

		

		$minprice_count = Product::minpriceCount();

		View::share('minprice_count', $minprice_count);

		

		$maxprice_count = Product::maxpriceCount();

		View::share('maxprice_count', $maxprice_count);

		

		view()->composer('*', function($view)

		{

			if (Auth::check()) {

			    $user['avilable'] = Members::logindataUser(Auth::user()->id);

			   	$avilable = explode(',',$user['avilable']->user_permission);

			    $cartcount = Product::getcartCount();

				$view->with('cartcount', $cartcount);

				

			}else {

				$cartcount = Product::getcartGuestCount();
				
				$view->with('cartcount', $cartcount);

				$avilable = "";

			}

			view()->share('avilable', $avilable);

		});

		view()->composer('*', function($viewcart)

		{

			if (Auth::check()) {

			    $cartitem['item'] = Product::getcartData();

				$viewcart->with('cartitem', $cartitem);

				

			}else {

				$cartitem['item'] = Product::getcartGuestData();

				$viewcart->with('cartitem', $cartitem);

			}

		});

		

		Config::set('mail.driver', $allsettings->mail_driver);

		Config::set('mail.host', $allsettings->mail_host);

		Config::set('mail.port', $allsettings->mail_port);

		Config::set('mail.username', $allsettings->mail_username);

		Config::set('mail.password', $allsettings->mail_password);

		Config::set('mail.encryption', $allsettings->mail_encryption);

		

		

		

		Config::set('services.facebook.client_id', $allsettings->facebook_client_id);

		Config::set('services.facebook.client_secret', $allsettings->facebook_client_secret);

		Config::set('services.facebook.redirect', $allsettings->facebook_callback_url);

		Config::set('services.google.client_id', $allsettings->google_client_id);

		Config::set('services.google.client_secret', $allsettings->google_client_secret);

		Config::set('services.google.redirect', $allsettings->google_callback_url);

		

		Config::set('paystack.publicKey', $allsettings->paystack_public_key);

		Config::set('paystack.secretKey', $allsettings->paystack_secret_key);

		Config::set('paystack.merchantEmail', $allsettings->paystack_merchant_email);

		Config::set('paystack.paymentUrl', 'https://api.paystack.co');

		

		

    }

}

