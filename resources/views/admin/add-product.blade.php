<!doctype html>

<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->

<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->

<!--[if IE 8]>
<html class="no-js lt-ie9" lang=""> <![endif]-->

<!--[if gt IE 8]><!-->

<html class="no-js" lang="en">

<!--<![endif]-->


<head>


    @include('admin.stylesheet')

</head>


<body>


@include('admin.navigation')



<!-- Right Panel -->

@if(in_array('manage-products',$avilable))

    <div id="right-panel" class="right-panel">


        @include('admin.header')


        <div class="breadcrumbs">

            <div class="col-sm-4">

                <div class="page-header float-left">

                    <div class="page-title">

                        <h1>Add Product</h1>

                    </div>

                </div>

            </div>

            <div class="col-sm-8">

                <div class="page-header float-right">


                </div>

            </div>

        </div>


        @if (session('success'))

            <div class="col-sm-12">

                <div class="alert  alert-success alert-dismissible fade show" role="alert">

                    {{ session('success') }}

                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">

                        <span aria-hidden="true">&times;</span>

                    </button>

                </div>

            </div>

        @endif



        @if (session('error'))

            <div class="col-sm-12">

                <div class="alert  alert-danger alert-dismissible fade show" role="alert">

                    {{ session('error') }}

                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">

                        <span aria-hidden="true">&times;</span>

                    </button>

                </div>

            </div>

        @endif





        @if ($errors->any())

            <div class="col-sm-12">

                <div class="alert  alert-danger alert-dismissible fade show" role="alert">

                    @foreach ($errors->all() as $error)



                        {{$error}}



                    @endforeach

                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">

                        <span aria-hidden="true">&times;</span>

                    </button>

                </div>

            </div>

        @endif


        <div class="content mt-3">

            <div class="animated fadeIn">

                <div class="row">


                    <div class="col-md-12">


                        <div class="card">

                            @if($demo_mode == 'on')

                                @include('admin.demo-mode')

                            @else

                                <form action="{{ route('admin.add-product') }}" method="post" id="category_form" enctype="multipart/form-data">

                                    {{ csrf_field() }}

                                    @endif


                                    <div class="col-md-6">


                                        <div class="card-body">

                                            <!-- Credit Card -->

                                            <div id="pay-invoice">

                                                <div class="card-body">


                                                    <div class="form-group">

                                                        <label for="name" class="control-label mb-1">Product Name <span class="require">*</span></label>

                                                        <input id="product_name" name="product_name" type="text" class="form-control" data-bvalidator="required,maxlen[100]">

                                                    </div>


                                                    <div class="form-group">

                                                        <label for="site_keywords" class="control-label mb-1">Short Description <span class="require">*</span></label>


                                                        <textarea name="product_short_desc" id="product_short_desc" rows="4" class="form-control noscroll_textarea"
                                                                  data-bvalidator="required,maxlen[160]"></textarea>

                                                    </div>


                                                    <div class="form-group">

                                                        <label for="site_desc" class="control-label mb-1">Description<span class="require">*</span></label>


                                                        <textarea name="product_desc" id="summary-ckeditor" rows="6" class="form-control" data-bvalidator="required"></textarea>

                                                    </div>


                                                    <div class="form-group">

                                                        <label for="site_title" class="control-label mb-1"> Category <span class="require">*</span></label>

                                                        <select name="product_category" class="form-control" data-bvalidator="required">

                                                            <option value=""></option>

                                                            @foreach($category['view'] as $category)

                                                                <option value="{{$category->cat_id}}">{{ $category->category_name }}</option>

                                                            @endforeach


                                                        </select>


                                                    </div>


                                                    <div class="form-group">

                                                        <label for="site_title" class="control-label mb-1"> Package Includes</label>

                                                        <select name="package_includes[]" class="form-control" multiple>

                                                            @foreach($package['view'] as $package)

                                                                <option value="{{$package->package_id}}">{{ $package->package_name }}</option>

                                                            @endforeach


                                                        </select>


                                                    </div>


                                                    <div class="form-group">

                                                        <label for="site_title" class="control-label mb-1"> Compatible Browsers</label>

                                                        <select name="compatible_browsers[]" class="form-control" multiple>

                                                            @foreach($browser['view'] as $browser)

                                                                <option value="{{$browser->browser_id}}">{{ $browser->browser_name }}</option>

                                                            @endforeach


                                                        </select>


                                                    </div>

                                                    <div class="form-group">
                                                        <label for="regular_price" class="control-label mb-1">Current Price ({{ $allsettings->site_currency_symbol }})</label>
                                                        <input id="regular_price" name="current_price" type="text" class="form-control" data-bvalidator="required,min[1]">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="name" class="control-label mb-1">Packages <span class="require">*</span></label>
                                                        <div id="product_package">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <input name="packages[name][0]" type="text" class="form-control" placeholder="name">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <input name="packages[price][0]" type="text" class="form-control" placeholder="price">
                                                                </div>
                                                                <div class="col-md-2" id="0">
                                                                    <button type="button" class="btn btn-sm btn-primary" id="add_package"><i class="fa fa-plus-circle"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    

                                                    <div class="form-group">
                                                        <label for="name" class="control-label mb-1">Regular License Price (6 Months Support) ({{ $allsettings->site_currency_symbol }})<span
                                                          class="require">*</span></label>
                                                        <input id="regular_price" name="regular_price" type="text" class="form-control" data-bvalidator="required,min[1]">
                                                    </div>
                                                    
                                                   


                                                    {{--                                                    <div class="form-group">--}}

                                                    {{--                                                        <label for="name" class="control-label mb-1">Extended License Price (12 Months Support) ({{ $allsettings->site_currency_symbol }})</label>--}}

                                                    {{--                                                        <input id="extended_price" name="extended_price" type="text" class="form-control" data-bvalidator="min[1]">--}}

                                                    {{--                                                    </div>--}}


                                                </div>

                                            </div>


                                        </div>

                                    </div>


                                    <div class="col-md-6">


                                        <div class="card-body">

                                            <!-- Credit Card -->

                                            <div id="pay-invoice">

                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label for="site_title" class="control-label mb-1"> Feature Update <span class="require">*</span></label>
                                                        <select name="future_update" class="form-control" data-bvalidator="required">
                                                            <option value=""></option>
                                                            <option value="1">Yes</option>
                                                            <option value="0">No</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="replacement_days" class="control-label mb-1">Replacement days</label>
                                                        <input id="replacement_days" name="replacement_days" type="text" class="form-control" data-bvalidator="required,min[1]">
                                                    </div>

{{--                                                    <div class="form-group">--}}
{{--                                                        <label for="site_title" class="control-label mb-1"> Product Support <span class="require">*</span></label>--}}
{{--                                                        <select name="item_support" class="form-control" data-bvalidator="required">--}}
{{--                                                            <option value=""></option>--}}
{{--                                                            <option value="1">Yes</option>--}}
{{--                                                            <option value="0">No</option>--}}
{{--                                                        </select>--}}
{{--                                                    </div>--}}


                                                    <div class="form-group">

                                                        <label for="customer_earnings" class="control-label mb-1">Upload Featured Image <span class="require">*</span></label>

                                                        <input type="file" id="product_image" name="product_image" class="form-control-file" data-bvalidator="required,extension[jpg:png:jpeg]"
                                                               data-bvalidator-msg="Please select file of type .jpg, .png or .jpeg">

                                                    </div>


                                                    <div class="form-group">

                                                        <label for="customer_earnings" class="control-label mb-1">Upload Gallery Images</label>

                                                        <input type="file" id="product_gallery" name="product_gallery[]" class="form-control-file" data-bvalidator="extension[jpg:png:jpeg]"
                                                               data-bvalidator-msg="Please select file of type .jpg, .png or .jpeg" multiple>

                                                    </div>


                                                    <div class="form-group">
                                                        <label for="site_keywords" class="control-label mb-1">Tags</label>
                                                        <textarea name="product_tags" id="product_tags" rows="4" placeholder="separate tag with commas"
                                                                  class="form-control noscroll_textarea"></textarea>
                                                    </div>


                                                    <div class="form-group">

                                                        <label for="site_title" class="control-label mb-1"> Featured <span class="require">*</span></label>

                                                        <select name="product_featured" class="form-control" data-bvalidator="required">

                                                            <option value=""></option>


                                                            <option value="1">Yes</option>

                                                            <option value="0">No</option>

                                                        </select>


                                                    </div>


                                                    <div class="form-group">
                                                        <label for="site_title" class="control-label mb-1"> Flash Sale <span class="require">*</span></label>
                                                        <select name="product_flash_sale" class="form-control" data-bvalidator="required" id="flash_sale">
                                                            <option value=""></option>
                                                            <option value="1">Yes</option>
                                                            <option value="0">No</option>
                                                        </select>
                                                    </div>

                                                    <div id="ifSale">
                                                        <div class="form-group">
                                                            <label for="product_flash_sale_percentage" class="control-label mb-1">Flash Sale Percentage<span class="require">*</span></label>
                                                            <input id="product_flash_sale_percentage" name="product_flash_sale_percentage" type="text" class="form-control" data-bvalidator="min[1]">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">

                                                        <label for="site_title" class="control-label mb-1"> Free Download <span class="require">*</span></label>

                                                        <select name="product_free" class="form-control" data-bvalidator="required">

                                                            <option value=""></option>


                                                            <option value="1">Yes</option>

                                                            <option value="0">No</option>

                                                        </select>


                                                    </div>


                                                    <div class="form-group">

                                                        <label for="customer_earnings" class="control-label mb-1">Upload File (Zip Format Only)<span class="require">*</span></label>

                                                        <input type="file" id="product_file" name="product_file" class="form-control-file" data-bvalidator="required,extension[zip]"
                                                               data-bvalidator-msg="Please select file of type .zip">

                                                    </div>


                                                    <div class="form-group">

                                                        <label for="name" class="control-label mb-1">Demo Url</label>

                                                        <input id="product_demo_url" name="product_demo_url" type="text" class="form-control" data-bvalidator="url">


                                                    </div>


                                                    <div class="form-group">

                                                        <label for="site_title" class="control-label mb-1"> Allow Seo? <span class="require">*</span></label>

                                                        <select name="product_allow_seo" id="product_allow_seo" class="form-control" data-bvalidator="required">

                                                            <option value=""></option>

                                                            <option value="1">Yes</option>

                                                            <option value="0">No</option>

                                                        </select>


                                                    </div>

                                                    <div id="ifseo">

                                                        <div class="form-group">
                                                            <label for="product_seo_title" class="control-label mb-1">SEO Meta Title<span class="require">*</span></label>
                                                            <textarea name="product_seo_title" id="product_seo_title" rows="4" class="form-control noscroll_textarea"
                                                                      data-bvalidator="required,maxlen[160]"></textarea>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="site_keywords" class="control-label mb-1">SEO Meta Keywords (max 160 chars) <span class="require">*</span></label>
                                                            <textarea name="product_seo_keyword" id="product_seo_keyword" rows="4" class="form-control noscroll_textarea"
                                                                      data-bvalidator="required,maxlen[160]"></textarea>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="site_desc" class="control-label mb-1">SEO Meta Description (max 160 chars) <span class="require">*</span></label>
                                                            <textarea name="product_seo_desc" id="product_seo_desc" rows="4" class="form-control noscroll_textarea"
                                                                      data-bvalidator="required,maxlen[160]"></textarea>
                                                        </div>
                                                    </div>


                                                    <div class="form-group">

                                                        <label for="name" class="control-label mb-1">Video Url</label>

                                                        <input id="product_video_url" name="product_video_url" type="text" class="form-control">

                                                        <small>( Example : https://www.youtube.com/watch?v=cXxAVn3rASk )</small>

                                                    </div>


                                                    <input type="hidden" name="image_size" value="{{ $allsettings->site_max_image_size }}">

                                                    <input type="hidden" name="zip_size" value="{{ $allsettings->site_max_zip_size }}">

                                                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

                                                </div>

                                            </div>


                                        </div>


                                    </div>
                                    <!-- faq section start -->
                                    <div class="col-md-12"><hr>
                                        <div class="card-body">
                                        <div class="form-group">
                                            <label for="name" class="control-label mb-1">FAQ <span class="require">*</span></label>
                                            <div id="add_faq_que_ans">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <textarea name="faq[que][0]" rows="2" class="form-control noscroll_textarea"
                                                         placeholder="Question"></textarea>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <textarea name="faq[ans][0]"  rows="2" class="form-control noscroll_textarea"
                                                          placeholder="Answer"></textarea>
                                                    </div>
                                                    <div class="col-md-2" id="0">
                                                        <button type="button" class="btn btn-sm btn-primary" id="add_faq"><i class="fa fa-plus-circle"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                    <!-- faq section end -->
                                  
                                    <div class="col-md-12 no-padding">

                                        <div class="card-footer">

                                            <button type="submit" name="submit" class="btn btn-primary btn-sm"><i class="fa fa-dot-circle-o"></i> Submit</button>

                                            <button type="reset" class="btn btn-danger btn-sm"><i class="fa fa-ban"></i> Reset</button>

                                        </div>


                                    </div>


                                </form>


                        </div>


                    </div>


                </div>

            </div><!-- .animated -->

        </div><!-- .content -->


    </div><!-- /#right-panel -->

@else

    @include('admin.denied')

@endif

<!-- Right Panel -->





@include('admin.javascript')

</body>
<script>
    $(document).ready(function(){

        $('#add_package').click(function(){
            var i  = $('#product_package div:last').attr('id');
            i++;
            var row = '<div class="row" style="margin-top:5px;"><div class="col-md-6"> <input name="packages[name]['+i+']" type="text" class="form-control" placeholder="name"> </div> <div class="col-md-4"> <input name="packages[price]['+i+']" type="text" class="form-control" placeholder="price"> </div> <div class="col-md-2" id="'+i+'"> <button type="button" class="btn btn-sm btn-danger" onclick="removePackage(this);"><i class="fa fa-minus-circle"></i></button></div></div>';
            $('#product_package').append(row);
        });

    });
    function removePackage(e)
    {
        $(e).parent().parent().remove();
    }

     /*add or remove faq*/

    $(document).ready(function(){

        $('#add_faq').click(function(){
            var i  = $('#add_faq_que_ans div:last').attr('id');
            i++;
            
            var row = '<div class="row" style="margin-top:5px;"><div class="col-md-5"> <textarea name="faq[que]['+i+']" rows="2" class="form-control noscroll_textarea" placeholder="Question"></textarea> </div> <div class="col-md-5"> <textarea name="faq[ans]['+i+']" rows="2" class="form-control noscroll_textarea" placeholder="Answer"></textarea> </div> <div class="col-md-2" id="'+i+'"> <button type="button" class="btn btn-sm btn-danger" onclick="removeFaq(this);"><i class="fa fa-minus-circle"></i></button></div></div>';
            $('#add_faq_que_ans').append(row);
        });

    });
    function removeFaq(e)
    {
        $(e).parent().parent().remove();
    }



</script>

</html>

