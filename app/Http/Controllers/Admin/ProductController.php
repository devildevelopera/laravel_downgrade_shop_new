<?php

namespace DownGrade\Http\Controllers\Admin;

use Illuminate\Http\Request;

use DownGrade\Http\Controllers\Controller;

use Session;

use DownGrade\Models\Product;

use DownGrade\Models\Settings;

use DownGrade\Models\Members;

use DownGrade\Models\Category;

use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Input;

use Mail;

use Illuminate\Support\Str;

use URL;

use Image;

class ProductController extends Controller

{

    public function __construct()

    {
        $this->middleware('auth');
    }

    /* compatible browsers */

    public function view_compatible_browsers()

    {
        $browserData['view'] = Product::browserData();

        return view('admin.compatible-browsers', ['browserData' => $browserData]);
    }

    public function add_compatible_browsers()

    {
        return view('admin.add-compatible-browsers');
    }

    public function save_compatible_browsers(Request $request)

    {
        $browser_name = $request->input('browser_name');

        $request->validate([

            'browser_name' => 'required',

        ]);

        $rules = [

            'browser_name' => ['required', Rule::unique('product_compatible_browsers')->where(function ($sql) {
                $sql->where('browser_drop_status', '=', 'no');
            })],

        ];

        $messsages = [

        ];

        $validator = Validator::make($request->all(), $rules, $messsages);

        if ($validator->fails()) {
            $failedRules = $validator->failed();

            return back()->withErrors($validator);
        } else {
            $data = ['browser_name' => $browser_name];

            Product::insertbrowserData($data);

            return redirect('/admin/compatible-browsers')->with('success', 'Insert successfully.');
        }
    }

    public function delete_compatible_browsers($browser_id)
    {
        $data = ['browser_drop_status' => 'yes'];

        Product::deleteBrowserdata($browser_id, $data);

        return redirect()->back()->with('success', 'Delete successfully.');
    }

    public function edit_compatible_browsers($browser_id)

    {
        $edit['browser'] = Product::editbrowserData($browser_id);

        return view('admin.edit-compatible-browsers', ['edit' => $edit, 'browser_id' => $browser_id]);
    }

    public function update_compatible_browsers(Request $request)

    {
        $browser_name = $request->input('browser_name');

        $browser_id = $request->input('browser_id');

        $request->validate([

            'browser_name' => 'required',

        ]);

        $rules = [

            'browser_name' => ['required', Rule::unique('product_compatible_browsers')->ignore($browser_id, 'browser_id')->where(function ($sql) {
                $sql->where('browser_drop_status', '=', 'no');
            })],

        ];

        $messsages = [

        ];

        $validator = Validator::make($request->all(), $rules, $messsages);

        if ($validator->fails()) {
            $failedRules = $validator->failed();

            return back()->withErrors($validator);
        } else {
            $data = ['browser_name' => $browser_name];

            Product::updatebrowserData($browser_id, $data);

            return redirect('/admin/compatible-browsers')->with('success', 'Update successfully.');
        }
    }



    /* compatible browsers */

    /* package includes */

    public function view_package_includes()

    {
        $packData['view'] = Product::packData();

        return view('admin.package-includes', ['packData' => $packData]);
    }

    public function add_package_includes()

    {
        return view('admin.add-package-includes');
    }

    public function save_package_includes(Request $request)

    {
        $package_name = $request->input('package_name');

        $request->validate([

            'package_name' => 'required',

        ]);

        $rules = [

            'package_name' => ['required', Rule::unique('product_package_includes')->where(function ($sql) {
                $sql->where('package_drop_status', '=', 'no');
            })],

        ];

        $messsages = [

        ];

        $validator = Validator::make($request->all(), $rules, $messsages);

        if ($validator->fails()) {
            $failedRules = $validator->failed();

            return back()->withErrors($validator);
        } else {
            $data = ['package_name' => $package_name];

            Product::insertpackData($data);

            return redirect('/admin/package-includes')->with('success', 'Insert successfully.');
        }
    }

    public function delete_package_includes($package_id)
    {
        $data = ['package_drop_status' => 'yes'];

        Product::deletePackdata($package_id, $data);

        return redirect()->back()->with('success', 'Delete successfully.');
    }

    public function edit_package_includes($package_id)

    {
        $edit['pack'] = Product::editpackData($package_id);

        return view('admin.edit-package-includes', ['edit' => $edit, 'package_id' => $package_id]);
    }

    public function update_package_includes(Request $request)

    {
        $package_name = $request->input('package_name');

        $package_id = $request->input('package_id');

        $request->validate([

            'package_name' => 'required',

        ]);

        $rules = [

            'package_name' => ['required', Rule::unique('product_package_includes')->ignore($package_id, 'package_id')->where(function ($sql) {
                $sql->where('package_drop_status', '=', 'no');
            })],

        ];

        $messsages = [

        ];

        $validator = Validator::make($request->all(), $rules, $messsages);

        if ($validator->fails()) {
            $failedRules = $validator->failed();

            return back()->withErrors($validator);
        } else {
            $data = ['package_name' => $package_name];

            Product::updatepackData($package_id, $data);

            return redirect('/admin/package-includes')->with('success', 'Update successfully.');
        }
    }



    /* package includes */

    /* brands */

    public function view_development()

    {
        $brandData['view'] = Product::brandData();

        return view('admin.development', ['brandData' => $brandData]);
    }

    public function add_development()

    {
        return view('admin.add-development');
    }

    public function brand_slug($string)
    {
        $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);

        return $slug;
    }

    public function save_development(Request $request)

    {
        if (! empty($request->input('logo_order'))) {
            $logo_order = $request->input('logo_order');
        } else {
            $logo_order = 0;
        }

        $logo_status = $request->input('logo_status');

        $image_size = $request->input('image_size');

        $request->validate([

            'logo_image' => 'mimes:jpeg,jpg,png|max:' . $image_size,

            'logo_status' => 'required',

        ]);

        $rules = [

        ];

        $messsages = [

        ];

        $validator = Validator::make($request->all(), $rules, $messsages);

        if ($validator->fails()) {
            $failedRules = $validator->failed();

            return back()->withErrors($validator);
        } else {
            if ($request->hasFile('logo_image')) {
                $image = $request->file('logo_image');

                $img_name = time() . '.' . $image->getClientOriginalExtension();

                $destinationPath = public_path('/storage/brands');

                $imagePath = $destinationPath . "/" . $img_name;

                $image->move($destinationPath, $img_name);

                $logo_image = $img_name;
            } else {
                $logo_image = "";
            }

            $data = ['logo_image' => $logo_image, 'logo_order' => $logo_order, 'logo_status' => $logo_status];

            Product::insertbrandData($data);

            return redirect('/admin/development')->with('success', 'Insert successfully.');
        }
    }

    public function delete_development($brand_id)
    {
        Product::deleteBranddata($brand_id);

        return redirect()->back()->with('success', 'Delete successfully.');
    }

    public function edit_development($brand_id)

    {
        $edit['brand'] = Product::editbrandData($brand_id);

        return view('admin.edit-development', ['edit' => $edit, 'brand_id' => $brand_id]);
    }

    public function update_development(Request $request)

    {
        $logo_id = $request->input('logo_id');

        if (! empty($request->input('logo_order'))) {
            $logo_order = $request->input('logo_order');
        } else {
            $logo_order = 0;
        }

        $logo_status = $request->input('logo_status');

        $image_size = $request->input('image_size');

        $save_file = $request->input('save_logo_image');

        $request->validate([

            'logo_image' => 'mimes:jpeg,jpg,png|max:' . $image_size,

            'logo_status' => 'required',

        ]);

        $rules = [

        ];

        $messsages = [

        ];

        $validator = Validator::make($request->all(), $rules, $messsages);

        if ($validator->fails()) {
            $failedRules = $validator->failed();

            return back()->withErrors($validator);
        } else {
            if ($request->hasFile('logo_image')) {
                Product::dropBrand($logo_id);

                $image = $request->file('logo_image');

                $img_name = time() . '.' . $image->getClientOriginalExtension();

                $destinationPath = public_path('/storage/brands');

                $imagePath = $destinationPath . "/" . $img_name;

                $image->move($destinationPath, $img_name);

                $logo_image = $img_name;
            } else {
                $logo_image = $save_file;
            }

            $data = ['logo_image' => $logo_image, 'logo_order' => $logo_order, 'logo_status' => $logo_status];

            Product::updatebrandData($logo_id, $data);

            return redirect('/admin/development')->with('success', 'Update successfully.');
        }
    }





    /* brands */

    /* products */

    public function view_products()

    {
        $product['view'] = Product::productData();

        return view('admin.products', ['product' => $product]);
    }

    public function add_product()
    {
        $category['view'] = Category::quickbookData();

        $browser['view'] = Product::browserData();

        $package['view'] = Product::packData();

        return view('admin.add-product', ['category' => $category, 'browser' => $browser, 'package' => $package]);
    }

    public function generateRandomString($length = 25)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $charactersLength = strlen($characters);

        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public function save_product(Request $request)
    {
        $product_name = $request->input('product_name');

        $product_slug = $this->brand_slug($product_name);

        $image_size = $request->input('image_size');

        $zip_size = $request->input('zip_size');

        $product_short_desc = $request->input('product_short_desc');

        $product_desc = $request->input('product_desc');

        $product_category = $request->input('product_category');

        $regular_price = $request->input('regular_price');

//        $extended_price = $request->input('extended_price');

        $product_tags = $request->input('product_tags');

        $replacement_days = $request->input('replacement_days');
        $product_featured = $request->input('product_featured');

        $product_demo_url = $request->input('product_demo_url');

        $product_allow_seo = $request->input('product_allow_seo');

        $product_seo_title = $request->input('product_seo_title');
        $product_seo_keyword = $request->input('product_seo_keyword');

        $product_seo_desc = $request->input('product_seo_desc');

        $product_video_url = $request->input('product_video_url');

        $product_flash_sale = $request->input('product_flash_sale');
        $product_flash_sale_percentage = $request->input('product_flash_sale_percentage');

        $product_free = $request->input('product_free');

        $user_id = $request->input('user_id');

        $product_token = $this->generateRandomString();

        $product_date = date('Y-m-d H:i:s');

        $product_update = date('Y-m-d H:i:s');

        $product_status = 1;

        if (! empty($request->input('compatible_browsers'))) {
            $browser1 = "";

            foreach ($request->input('compatible_browsers') as $browser) {
                $browser1 .= $browser . ',';
            }

            $compatible_browsers = rtrim($browser1, ",");
        } else {
            $compatible_browsers = "";
        }

        if (! empty($request->input('package_includes'))) {
            $package1 = "";

            foreach ($request->input('package_includes') as $package) {
                $package1 .= $package . ',';
            }

            $package_includes = rtrim($package1, ",");
        } else {
            $package_includes = "";
        }

        $future_update = $request->input('future_update');

        $item_support = $request->input('item_support');

        $allsettings = Settings::allSettings();

        $watermark = $allsettings->site_watermark;

        $url = URL::to("/");

        $request->validate([

            'product_image' => 'mimes:jpeg,jpg,png|max:' . $image_size,

            'product_file' => 'mimes:zip|max:' . $zip_size,

            'product_gallery.*' => 'image|mimes:jpeg,jpg,png|max:' . $image_size,

            'product_name' => 'required',

            'product_desc' => 'required',

        ]);

        $rules = [

            'product_name' => ['required', 'max:100', Rule::unique('product')->where(function ($sql) {
                $sql->where('product_drop_status', '=', 'no');
            })],

        ];

        $messsages = [

        ];

        $validator = Validator::make($request->all(), $rules, $messsages);

        if ($validator->fails()) {
            $failedRules = $validator->failed();

            return back()->withErrors($validator);
        } else {
            if ($request->hasFile('product_image')) {
                if ($allsettings->watermark_option == 1) {
                    $image = $request->file('product_image');

                    $img_name = time() . '.' . $image->getClientOriginalExtension();

                    $destinationPath = public_path('/storage/product');

                    $imagePath = $destinationPath . "/" . $img_name;

                    $image->move($destinationPath, $img_name);

                    /* new code */

                    $watermarkImg = Image::make($url . '/public/storage/settings/' . $watermark);

                    $img = Image::make($url . '/public/storage/product/' . $img_name);

                    $wmarkWidth = $watermarkImg->width();

                    $wmarkHeight = $watermarkImg->height();

                    $imgWidth = $img->width();

                    $imgHeight = $img->height();

                    $x = 0;

                    $y = 0;

                    while ($y <= $imgHeight) {
                        $img->insert($url . '/public/storage/settings/' . $watermark, 'top-left', $x, $y);

                        $x += $wmarkWidth;

                        if ($x >= $imgWidth) {
                            $x = 0;

                            $y += $wmarkHeight;
                        }
                    }

                    $img->save(base_path('public/storage/product/' . $img_name));

                    $product_image = $img_name;
                    /* new code */
                } else {
                    $image = $request->file('product_image');

                    $img_name = time() . '.' . $image->getClientOriginalExtension();

                    $destinationPath = public_path('/storage/product');

                    $imagePath = $destinationPath . "/" . $img_name;

                    $image->move($destinationPath, $img_name);

                    $product_image = $img_name;
                }
            } else {
                $product_image = "";
            }

            if ($request->hasFile('product_file')) {
                $image = $request->file('product_file');

                $img_name = time() . '147.' . $image->getClientOriginalExtension();

                $destinationPath = public_path('/storage/product');

                $imagePath = $destinationPath . "/" . $img_name;

                $image->move($destinationPath, $img_name);

                $product_file = $img_name;
            } else {
                $product_file = "";
            }

            $data = ['user_id' => $user_id, 'product_token' => $product_token, 'product_name' => $product_name, 'product_slug' => $product_slug, 'product_category' => $product_category, 'product_short_desc' => $product_short_desc, 'product_desc' => $product_desc, 'regular_price' => $regular_price, 'product_image' => $product_image, 'product_video_url' => $product_video_url, 'product_demo_url' => $product_demo_url,
                'replacement_days' => $replacement_days,
                'product_allow_seo' => $product_allow_seo,
                'product_seo_keyword' => $product_seo_keyword,
                'product_seo_title' => $product_seo_title,
                'product_seo_desc' => $product_seo_desc, 'product_tags' => $product_tags, 'product_featured' => $product_featured, 'product_file' => $product_file, 'product_date' => $product_date, 'product_update' => $product_update, 'product_status' => $product_status,
                'product_flash_sale' => $product_flash_sale,
                'product_flash_sale_percentage' => $product_flash_sale_percentage,
                'package_includes' => $package_includes, 'compatible_browsers' => $compatible_browsers, 'product_free' => $product_free, 'item_support' => $item_support, 'future_update' => $future_update];
               
            $product_id  = Product::insertproductData($data);
            if($product_id)
            {
                $packages = $request->packages;
                if($packages){
                    $package_names = $packages['name'];
                    $package_prices = $packages['price'];
                    foreach ($package_names as $key => $name) {
                        $name = $package_names[$key];
                        $price = $package_prices[$key];
                        $data = ['product_id'=>$product_id,'package_name'=>$name,'package_price'=>$price];
                        Product::insertproductpackageData($data);
                    }
                }

                $faqs = $request->faq;
                if($faqs){
                    $faq_que = $faqs['que'];
                    $faq_ans = $faqs['ans'];
                    foreach ($faq_que as $key => $que) {
                        $que = $faq_que[$key];
                        $ans = $faq_ans[$key];
                        $data = ['product_id'=>$product_id,'faq_que'=>$que,'faq_ans'=>$ans];
                        Product::insertproductfaqData($data);
                    }
                }
            }

            if ($request->hasFile('product_gallery')) {
                if ($allsettings->watermark_option == 1) {
                    $files = $request->file('product_gallery');

                    foreach ($files as $file) {
                        $extension = $file->getClientOriginalExtension();

                        $fileName = Str::random(5) . "-" . date('his') . "-" . Str::random(3) . "." . $extension;

                        $folderpath = public_path('/storage/product');

                        $file->move($folderpath, $fileName);

                        /* new code */

                        $watermarkImg = Image::make($url . '/public/storage/settings/' . $watermark);

                        $img = Image::make($url . '/public/storage/product/' . $fileName);

                        $wmarkWidth = $watermarkImg->width();

                        $wmarkHeight = $watermarkImg->height();

                        $imgWidth = $img->width();

                        $imgHeight = $img->height();

                        $x = 0;

                        $y = 0;

                        while ($y <= $imgHeight) {
                            $img->insert($url . '/public/storage/settings/' . $watermark, 'top-left', $x, $y);

                            $x += $wmarkWidth;

                            if ($x >= $imgWidth) {
                                $x = 0;

                                $y += $wmarkHeight;
                            }
                        }

                        $img->save(base_path('public/storage/product/' . $fileName));

                        /* new code */

                        $imgdata = ['product_token' => $product_token, 'product_gallery_image' => $fileName];

                        Product::saveproductImages($imgdata);
                    }
                } else {
                    $files = $request->file('product_gallery');

                    foreach ($files as $file) {
                        $extension = $file->getClientOriginalExtension();

                        $fileName = Str::random(5) . "-" . date('his') . "-" . Str::random(3) . "." . $extension;

                        $folderpath = public_path('/storage/product');

                        $file->move($folderpath, $fileName);

                        $imgdata = ['product_token' => $product_token, 'product_gallery_image' => $fileName];

                        Product::saveproductImages($imgdata);
                    }
                }
            }

            return redirect('/admin/products')->with('success', 'Insert successfully.');
        }
    }

    public function delete_product($product_token)
    {
        $data = ['product_drop_status' => 'yes'];

        Product::deleteProductdata($product_token, $data);

        return redirect()->back()->with('success', 'Delete successfully.');
    }

    public function edit_product($product_token)

    {
        $edit['product'] = Product::editproductData($product_token);

        
        $product_image['view'] = Product::getimagesData($product_token);

        $category['view'] = Category::quickbookData();

        $browser['view'] = Product::browserData();

        $package['view'] = Product::packData();
        $product_id = $edit['product']->product_id;
        $product_packages = Product::getproductPackages($product_id);
        $product_faqs = Product::getproductFaqs($product_id);

        return view('admin.edit-product',
            ['edit' => $edit, 'product_token' => $product_token, 'product_image' => $product_image, 'category' => $category, 'browser' => $browser, 'package' => $package,'product_packages'=>$product_packages,'product_packages_id'=>$product_id,'product_faqs'=>$product_faqs]);
    }

    public function drop_image_product($dropimg, $token)

    {
        $token = base64_decode($token);

        Product::deleteimgdata($token);

        return redirect()->back()->with('success', 'Delete successfully.');
    }

    public function update_product(Request $request)
    {
       
        $product_name = $request->input('product_name');

        $product_slug = $this->brand_slug($product_name);

        $image_size = $request->input('image_size');

        $zip_size = $request->input('zip_size');

        $product_short_desc = $request->input('product_short_desc');

        $product_desc = $request->input('product_desc');

        $product_category = $request->input('product_category');

        $regular_price = $request->input('regular_price');

//        $extended_price = $request->input('extended_price');

        $product_tags = $request->input('product_tags');

        $replacement_days = $request->input('replacement_days');

        $product_featured = $request->input('product_featured');

        $product_demo_url = $request->input('product_demo_url');

        $product_allow_seo = $request->input('product_allow_seo');

        $product_seo_title = $request->input('product_seo_title');
        $product_seo_keyword = $request->input('product_seo_keyword');

        $product_seo_desc = $request->input('product_seo_desc');

        $product_video_url = $request->input('product_video_url');

        $user_id = $request->input('user_id');

        $product_token = $request->input('product_token');

        $product_date = date('Y-m-d H:i:s');

        $product_status = $request->input('product_status');

        $save_product_image = $request->input('save_product_image');

        $save_product_file = $request->input('save_product_file');

        $product_flash_sale = $request->input('product_flash_sale');
        $product_flash_sale_percentage = $request->input('product_flash_sale_percentage');

        $product_free = $request->input('product_free');

        $future_update = $request->input('future_update');

        $item_support = $request->input('item_support');

        if (! empty($request->input('compatible_browsers'))) {
            $browser1 = "";

            foreach ($request->input('compatible_browsers') as $browser) {
                $browser1 .= $browser . ',';
            }

            $compatible_browsers = rtrim($browser1, ",");
        } else {
            $compatible_browsers = "";
        }

        if (! empty($request->input('package_includes'))) {
            $package1 = "";

            foreach ($request->input('package_includes') as $package) {
                $package1 .= $package . ',';
            }

            $package_includes = rtrim($package1, ",");
        } else {
            $package_includes = "";
        }

        $allsettings = Settings::allSettings();

        $watermark = $allsettings->site_watermark;

        $url = URL::to("/");

        $request->validate([

            'product_image' => 'mimes:jpeg,jpg,png|max:' . $image_size,

            'product_file' => 'mimes:zip|max:' . $zip_size,

            'product_gallery.*' => 'image|mimes:jpeg,jpg,png|max:' . $image_size,

            'product_name' => 'required',

            'product_desc' => 'required',

        ]);

        $rules = [

            'product_name' => ['required', 'max:100', Rule::unique('product')->ignore($product_token, 'product_token')->where(function ($sql) {
                $sql->where('product_drop_status', '=', 'no');
            })],

        ];

        $messsages = [

        ];

        $validator = Validator::make($request->all(), $rules, $messsages);

        if ($validator->fails()) {
            $failedRules = $validator->failed();

            return back()->withErrors($validator);
        } else {
            if ($request->hasFile('product_image')) {
                if ($allsettings->watermark_option == 1) {
                    $image = $request->file('product_image');

                    $img_name = time() . '.' . $image->getClientOriginalExtension();

                    $destinationPath = public_path('/storage/product');

                    $imagePath = $destinationPath . "/" . $img_name;

                    $image->move($destinationPath, $img_name);

                    /* new code */

                    $watermarkImg = Image::make($url . '/public/storage/settings/' . $watermark);

                    $img = Image::make($url . '/public/storage/product/' . $img_name);

                    $wmarkWidth = $watermarkImg->width();

                    $wmarkHeight = $watermarkImg->height();

                    $imgWidth = $img->width();

                    $imgHeight = $img->height();

                    $x = 0;

                    $y = 0;

                    while ($y <= $imgHeight) {
                        $img->insert($url . '/public/storage/settings/' . $watermark, 'top-left', $x, $y);

                        $x += $wmarkWidth;

                        if ($x >= $imgWidth) {
                            $x = 0;

                            $y += $wmarkHeight;
                        }
                    }

                    $img->save(base_path('public/storage/product/' . $img_name));

                    $product_image = $img_name;
                    /* new code */
                } else {
                    $image = $request->file('product_image');

                    $img_name = time() . '.' . $image->getClientOriginalExtension();

                    $destinationPath = public_path('/storage/product');

                    $imagePath = $destinationPath . "/" . $img_name;

                    $image->move($destinationPath, $img_name);

                    $product_image = $img_name;
                }
            } else {
                $product_image = $save_product_image;
            }

            if ($request->hasFile('product_file')) {
                $image = $request->file('product_file');

                $img_name = time() . '147.' . $image->getClientOriginalExtension();

                $destinationPath = public_path('/storage/product');

                $imagePath = $destinationPath . "/" . $img_name;

                $image->move($destinationPath, $img_name);

                $product_file = $img_name;

                $getbuyer['displays'] = Product::purchasedBuyer($product_token);

                foreach ($getbuyer['displays'] as $buyerdata) {
                    $buyer_details = Members::singlebuyerData($buyerdata->user_id);

                    $product_url = URL::to('/product') . '/' . $product_slug;

                    $sid = 1;

                    $setting['setting'] = Settings::editGeneral($sid);

                    $admin_name = $setting['setting']->sender_name;

                    $admin_email = $setting['setting']->sender_email;

                    $record = ['product_url' => $product_url, 'product_name' => $product_name];

                    $to_name = $buyer_details->name;

                    $to_email = $buyer_details->email;

                    Mail::send('admin.item_update_mail', $record, function ($message) use ($admin_name, $admin_email, $to_email, $to_name) {
                        $message->to($to_email, $to_name)
                            ->subject('Item Update Notifications');

                        $message->from($admin_email, $admin_name);
                    });
                }
            } else {
                $product_file = $save_product_file;
            }

            $data = ['user_id' => $user_id, 'product_name' => $product_name, 'product_slug' => $product_slug,
                'product_category' => $product_category, 'product_short_desc' => $product_short_desc,
                'product_desc' => $product_desc, 'regular_price' => $regular_price,
                'product_image' => $product_image, 'product_video_url' => $product_video_url, 'product_demo_url' => $product_demo_url,
                'replacement_days' => $replacement_days,
                'product_allow_seo' => $product_allow_seo,
                'product_seo_title' => $product_seo_title,
                'product_seo_keyword' => $product_seo_keyword,
                'product_seo_desc' => $product_seo_desc,
                'product_tags' => $product_tags, 'product_featured' => $product_featured, 'product_file' => $product_file, 'product_update' => $product_date, 'product_status' => $product_status,
                'product_flash_sale' => $product_flash_sale,
                'product_flash_sale_percentage' => $product_flash_sale_percentage,
                'package_includes' => $package_includes, 'compatible_browsers' => $compatible_browsers, 'product_free' => $product_free, 'future_update' => $future_update, 'item_support' => $item_support];

            
            Product::updateproductData($product_token, $data);

            $package_product_id = $request->package_product_id;
            if($package_product_id)
            {
                $packages = $request->packages;
                if($packages){
                    Product::deleteproductpackageData($package_product_id);
                    $package_names = $packages['name'];
                    $package_prices = $packages['price'];
                    foreach ($package_names as $key => $name) {
                        $name = $package_names[$key];
                        $price = $package_prices[$key];
                        $data = ['product_id'=>$package_product_id,'package_name'=>$name,'package_price'=>$price];
                        Product::insertproductpackageData($data);
                    }
                }

                $faqs = $request->faq;
                if($faqs){
                    Product::deleteproductFaqs($package_product_id);
                    $faq_que = $faqs['que'];
                    $faq_ans = $faqs['ans'];
                    foreach ($faq_que as $key => $que) {
                        $que = $faq_que[$key];
                        $ans = $faq_ans[$key];
                        $data = ['product_id'=>$package_product_id,'faq_que'=>$que,'faq_ans'=>$ans];
                        Product::insertproductfaqData($data);
                    }
                }
            }

            if ($request->hasFile('product_gallery')) {
                if ($allsettings->watermark_option == 1) {
                    $files = $request->file('product_gallery');

                    foreach ($files as $file) {
                        $extension = $file->getClientOriginalExtension();

                        $fileName = Str::random(5) . "-" . date('his') . "-" . Str::random(3) . "." . $extension;

                        $folderpath = public_path('/storage/product');

                        $file->move($folderpath, $fileName);

                        /* new code */

                        $watermarkImg = Image::make($url . '/public/storage/settings/' . $watermark);

                        $img = Image::make($url . '/public/storage/product/' . $fileName);

                        $wmarkWidth = $watermarkImg->width();

                        $wmarkHeight = $watermarkImg->height();

                        $imgWidth = $img->width();

                        $imgHeight = $img->height();

                        $x = 0;

                        $y = 0;

                        while ($y <= $imgHeight) {
                            $img->insert($url . '/public/storage/settings/' . $watermark, 'top-left', $x, $y);

                            $x += $wmarkWidth;

                            if ($x >= $imgWidth) {
                                $x = 0;

                                $y += $wmarkHeight;
                            }
                        }

                        $img->save(base_path('public/storage/product/' . $fileName));

                        /* new code */

                        $imgdata = ['product_token' => $product_token, 'product_gallery_image' => $fileName];

                        Product::saveproductImages($imgdata);
                    }
                } else {
                    $files = $request->file('product_gallery');

                    foreach ($files as $file) {
                        $extension = $file->getClientOriginalExtension();

                        $fileName = Str::random(5) . "-" . date('his') . "-" . Str::random(3) . "." . $extension;

                        $folderpath = public_path('/storage/product');

                        $file->move($folderpath, $fileName);

                        $imgdata = ['product_token' => $product_token, 'product_gallery_image' => $fileName];

                        Product::saveproductImages($imgdata);
                    }
                }
            }

            return redirect('/admin/products')->with('success', 'Update successfully.');
        }
    }





    /* products */

    /* admin orders */

    public function view_orders()

    {
        $itemData['item'] = Product::getorderItem();

        $data = ['itemData' => $itemData];

        return view('admin.orders')->with($data);
    }

    public function view_order_single($token)

    {
        $itemData['item'] = Product::adminorderItem($token);

        $data = ['itemData' => $itemData];

        return view('admin.order-details')->with($data);
    }

    public function view_more_info($token)

    {
        $itemData['item'] = Product::getsingleOrder($token);

        $data = ['itemData' => $itemData];

        return view('admin.more-info')->with($data);
    }



    /* admin orders */

    /* admin refund */

    public function view_refund()

    {
        $itemData['item'] = Product::getrefundItem();

        $data = ['itemData' => $itemData];

        return view('admin.refund')->with($data);
    }

    public function view_payment_refund($ord_id, $refund_id, $user_type)

    {
        $order = $ord_id;

        $ordered['data'] = Product::singleorderData($order);

        $user_id = $ordered['data']->user_id;

        $item_user_id = $ordered['data']->product_user_id;

        $price = $ordered['data']->total_price;

        $approval_status = $ordered['data']->approval_status;

        if ($user_type == "customer") {
            if ($approval_status == "") {
                $buyer['info'] = Members::singlebuyerData($user_id);

                $user_token = $buyer['info']->user_token;

                $to_name = $buyer['info']->name;

                $to_email = $buyer['info']->email;

                $buyer_earning = $buyer['info']->earnings + $price;

                $record = ['earnings' => $buyer_earning];

                Members::updatepasswordData($user_token, $record);

                $orderdata = ['approval_status' => 'payment released to customer'];

                $refundata = ['ref_refund_approval' => 'accepted'];

                Product::singleorderupData($order, $orderdata);

                Product::refundupData($refund_id, $refundata);

                Product::deleteRating($ord_id);

                $sid = 1;

                $setting['setting'] = Settings::editGeneral($sid);

                $admin_name = $setting['setting']->sender_name;

                $admin_email = $setting['setting']->sender_email;

                $currency = $setting['setting']->site_currency_symbol;

                $data = ['to_name' => $to_name, 'to_email' => $to_email, 'price' => $price, 'currency' => $currency];

                Mail::send('admin.buyer_refund_mail', $data, function ($message) use ($admin_name, $admin_email, $to_name, $to_email) {
                    $message->to($to_email, $to_name)
                        ->subject('Payment Refund Accepted');

                    $message->from($admin_email, $admin_name);
                });

                return redirect()->back()->with('success', 'Payment released to customer');
            } else if ($approval_status == 'payment released to customer') {
                $refundata = ['ref_refund_approval' => 'accepted'];

                Product::refundupData($refund_id, $refundata);

                Product::deleteRating($ord_id);

                return redirect()->back()->with('success', 'Payment released to customer');
            } else if ($approval_status == 'payment released to admin') {
                $buyer['info'] = Members::singlebuyerData($user_id);

                $user_token = $buyer['info']->user_token;

                $to_name = $buyer['info']->name;

                $to_email = $buyer['info']->email;

                $buyer_earning = $buyer['info']->earnings + $price;

                $record = ['earnings' => $buyer_earning];

                Members::updatepasswordData($user_token, $record);

                $orderdata = ['approval_status' => 'payment released to customer'];

                $refundata = ['ref_refund_approval' => 'accepted'];

                Product::singleorderupData($order, $orderdata);

                Product::refundupData($refund_id, $refundata);

                Product::deleteRating($ord_id);

                $vendor['info'] = Members::singlevendorData($item_user_id);

                $vendor_token = $vendor['info']->user_token;

                $to_name = $vendor['info']->name;

                $to_email = $vendor['info']->email;

                $vendor_earning = $vendor['info']->earnings - $price;

                $record_vendor = ['earnings' => $vendor_earning];

                Members::updatevendorRecord($vendor_token, $record_vendor);

                $sid = 1;

                $setting['setting'] = Settings::editGeneral($sid);

                $admin_name = $setting['setting']->sender_name;

                $admin_email = $setting['setting']->sender_email;

                $currency = $setting['setting']->site_currency_symbol;

                $data = ['to_name' => $to_name, 'to_email' => $to_email, 'price' => $price, 'currency' => $currency];

                Mail::send('admin.buyer_refund_mail', $data, function ($message) use ($admin_name, $admin_email, $to_name, $to_email) {
                    $message->to($to_email, $to_name)
                        ->subject('Payment Refund Accepted');

                    $message->from($admin_email, $admin_name);
                });

                return redirect()->back()->with('success', 'Payment released to customer');
            }
        }

        if ($user_type == "admin") {
            $buyer['info'] = Members::singlebuyerData($user_id);

            $user_token = $buyer['info']->user_token;

            $to_name = $buyer['info']->name;

            $to_email = $buyer['info']->email;

            $sid = 1;

            $setting['setting'] = Settings::editGeneral($sid);

            $admin_name = $setting['setting']->sender_name;

            $admin_email = $setting['setting']->sender_email;

            $currency = $setting['setting']->site_currency_symbol;

            $refundata = ['ref_refund_approval' => 'declined'];

            Product::refundupData($refund_id, $refundata);

            $data = ['to_name' => $to_name, 'to_email' => $to_email, 'price' => $price, 'currency' => $currency];

            Mail::send('admin.buyer_declined_mail', $data, function ($message) use ($admin_name, $admin_email, $to_name, $to_email) {
                $message->to($to_email, $to_name)
                    ->subject('Payment Refund Declined');

                $message->from($admin_email, $admin_name);
            });

            return redirect()->back()->with('success', 'Refund request is declined');
        }
    }









    /* admin refund */

    /* admin rating */

    public function view_rating()

    {
        $itemData['item'] = Product::getratingItem();

        $data = ['itemData' => $itemData];

        return view('admin.rating')->with($data);
    }

    public function rating_delete($rating_id)

    {
        Product::dropRating($rating_id);

        return redirect()->back()->with('success', 'Item rating has been removed');
    }



    /* admin rating */

    /* admin withdrawal */

    public function view_withdrawal()

    {
        $itemData['item'] = Product::getwithdrawalData();

        $data = ['itemData' => $itemData];

        return view('admin.withdrawal')->with($data);
    }

    public function view_withdrawal_update($wd_id, $user_id)

    {
        $drawal_data = ['wd_status' => 'paid'];

        Product::updatedrawalData($wd_id, $user_id, $drawal_data);

        $buyer['info'] = Members::singlebuyerData($user_id);

        $user_token = $buyer['info']->user_token;

        $to_name = $buyer['info']->name;

        $to_email = $buyer['info']->email;

        $sid = 1;

        $setting['setting'] = Settings::editGeneral($sid);

        $admin_name = $setting['setting']->sender_name;

        $admin_email = $setting['setting']->sender_email;

        $currency = $setting['setting']->site_currency_symbol;

        $with['data'] = Product::singledrawalData($wd_id);

        $wd_amount = $with['data']->wd_amount;

        $data = ['to_name' => $to_name, 'to_email' => $to_email, 'wd_amount' => $wd_amount, 'currency' => $currency];

        Mail::send('admin.user_withdrawal_mail', $data, function ($message) use ($admin_name, $admin_email, $to_name, $to_email) {
            $message->to($to_email, $to_name)
                ->subject('Payment Withdrawal Request Accepted');

            $message->from($admin_email, $admin_name);
        });

        return redirect()->back()->with('success', 'Payment withdrawal request has been completed');
    }

    /* admin withdrawal */

}

