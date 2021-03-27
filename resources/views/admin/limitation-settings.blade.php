<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->

<head>
    
    @include('admin.stylesheet')
</head>

<body>
    
    @include('admin.navigation')

    <!-- Right Panel -->
    @if(in_array('settings',$avilable))
    <div id="right-panel" class="right-panel">

       
                       @include('admin.header')
                       

        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Limitation Settings</h1>
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
                           <form action="{{ route('admin.limitation-settings') }}" method="post" id="setting_form" enctype="multipart/form-data">
                           {{ csrf_field() }}
                           @endif
                          
                           <div class="col-md-6">
                           
                            <div class="card-body">
                                <!-- Credit Card -->
                                <div id="pay-invoice">
                                    <div class="card-body">
                                           
                                              
                                            
                                            <div class="form-group">
                                                <label for="product_per_page" class="control-label mb-1">Product per page<span class="require">*</span></label>
                                                <input id="product_per_page" name="product_per_page" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->product_per_page }}" data-bvalidator="required,min[1]">
                                            </div> 
                                            
                                            <div class="form-group">
                                                <label for="comment_per_page" class="control-label mb-1">Comment per page<span class="require">*</span></label>
                                                <input id="comment_per_page" name="comment_per_page" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->comment_per_page }}" data-bvalidator="required,min[1]">
                                            </div> 
                                             
                                            
                                        
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
                                                <label for="post_per_page" class="control-label mb-1">Post per page<span class="require">*</span></label>
                                                <input id="post_per_page" name="post_per_page" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->post_per_page }}" data-bvalidator="required,min[1]">
                                            </div> 
                                            
                                             <div class="form-group">
                                                <label for="review_per_page" class="control-label mb-1">Review per page<span class="require">*</span></label>
                                                <input id="review_per_page" name="review_per_page" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->review_per_page }}" data-bvalidator="required,min[1]">
                                            </div> 
                                            
                                           <input type="hidden" name="sid" value="1">
                             
                             
                             </div>
                                </div>

                            </div>
                             
                             
                             
                             </div>
                             
                             <div class="col-md-12"><div class="card-body"><h4>Main Menu Category Limitation</h4></div></div>
                             
                             <div class="col-md-6">
                           
                            <div class="card-body">
                                <!-- Credit Card -->
                                <div id="pay-invoice">
                                    <div class="card-body">
                                       
                                        
                                            
                                            <div class="form-group">
                                                <label for="site_title" class="control-label mb-1">How many categories display on main menu? <span class="require">*</span></label><br/>
                                               <input id="menu_display_categories" name="menu_display_categories" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->menu_display_categories }}" data-bvalidator="required">
                                                
                                                
                                             </div>
                                             
                                             
                                             
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
                                                <label for="site_loader_display" class="control-label mb-1">Category display on order?<span class="require">*</span></label><br/>
                                               
                                                <select name="menu_categories_order" class="form-control" data-bvalidator="required">
                                                <option value=""></option>
                                                <option value="asc" @if($setting['setting']->menu_categories_order == 'asc') selected @endif>ASC</option>
                                                <option value="desc" @if($setting['setting']->menu_categories_order == 'desc') selected @endif>DESC</option>
                                                </select>
                                                
                                             </div>
                                             <small>ASC - ascending order | DESC - descending order</small>
                                             
                                             
                                             
                                    </div>
                                </div>

                            </div>
                            </div>
                            
                            
                            <div class="col-md-12"><div class="card-body"><h4>Footer Menu Category Limitation</h4></div></div>
                            
                            <div class="col-md-6">
                           
                            <div class="card-body">
                                <!-- Credit Card -->
                                <div id="pay-invoice">
                                    <div class="card-body">
                                       
                                        
                                            
                                            <div class="form-group">
                                                <label for="site_title" class="control-label mb-1">How many categories display on footer menu? <span class="require">*</span></label><br/>
                                               <input id="footer_menu_display_categories" name="footer_menu_display_categories" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->footer_menu_display_categories }}" data-bvalidator="required">
                                                
                                                
                                             </div>
                                             
                                             
                                             
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
                                                <label for="site_loader_display" class="control-label mb-1">Category display on order?<span class="require">*</span></label><br/>
                                               
                                                <select name="footer_menu_categories_order" class="form-control" data-bvalidator="required">
                                                <option value=""></option>
                                                <option value="asc" @if($setting['setting']->footer_menu_categories_order == 'asc') selected @endif>ASC</option>
                                                <option value="desc" @if($setting['setting']->footer_menu_categories_order == 'desc') selected @endif>DESC</option>
                                                </select>
                                                
                                             </div>
                                             <small>ASC - ascending order | DESC - descending order</small>
                                             
                                             
                                             
                                    </div>
                                </div>

                            </div>
                            </div>
                             
                             
                             <div class="col-md-12"><div class="card-body"><h4>Home Page Item Limitation</h4></div></div>
                             
                             
                             <div class="col-md-6">
                           
                            <div class="card-body">
                                <!-- Credit Card -->
                                <div id="pay-invoice">
                                    <div class="card-body">
                                       
                                        
                                            
                                            <div class="form-group">
                                                <label for="site_title" class="control-label mb-1">How many featured items display <span class="require">*</span></label><br/>
                                               <input id="home_featured_items" name="home_featured_items" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->home_featured_items }}" data-bvalidator="required">
                                                
                                                
                                             </div>
                                             
                                             
                                             <div class="form-group">
                                                <label for="site_title" class="control-label mb-1">How many flash items display <span class="require">*</span></label><br/>
                                               <input id="home_flash_items" name="home_flash_items" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->home_flash_items }}" data-bvalidator="required">
                                                
                                                
                                             </div>
                                            
                                      
                                            <div class="form-group">
                                                <label for="site_title" class="control-label mb-1">How many blog post display <span class="require">*</span></label><br/>
                                               <input id="home_blog_post" name="home_blog_post" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->home_blog_post }}" data-bvalidator="required">
                                                
                                                
                                             </div>
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
                                                <label for="site_title" class="control-label mb-1">How many popular items display <span class="require">*</span></label><br/>
                                               <input id="home_popular_items" name="home_popular_items" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->home_popular_items }}" data-bvalidator="required">
                                                
                                                
                                             </div>
                                            
                                          <div class="form-group">
                                                <label for="site_title" class="control-label mb-1">How many new items display <span class="require">*</span></label><br/>
                                               <input id="home_new_items" name="home_new_items" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->home_new_items }}" data-bvalidator="required">
                                                
                                                
                                             </div>
                                             
                                             
                                             
                                              <div class="form-group">
                                                <label for="site_title" class="control-label mb-1">How many free items display <span class="require">*</span></label><br/>
                                               <input id="home_free_items" name="home_free_items" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->home_free_items }}" data-bvalidator="required">
                                                
                                                
                                             </div>
                                                
                                        
                                    </div>
                                </div>

                            </div>
                            </div>
                             
                              <div class="col-md-12"><div class="card-body"><h4>Shop Page Price Limitation</h4></div></div>
                              
                              
                              <div class="col-md-6">
                           
                            <div class="card-body">
                                <!-- Credit Card -->
                                <div id="pay-invoice">
                                    <div class="card-body">
                                       
                                        
                                            
                                             <div class="form-group">
                                                <label for="site_title" class="control-label mb-1">Price range min price <span class="require">*</span></label><br/>
                                               <input id="site_range_min_price" name="site_range_min_price" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->site_range_min_price }}" data-bvalidator="required">
                                                
                                                
                                             </div>
                                            
                                          
                                                
                                        
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
                                                <label for="site_title" class="control-label mb-1">Price range max price <span class="require">*</span></label><br/>
                                               <input id="site_range_max_price" name="site_range_max_price" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->site_range_max_price }}" data-bvalidator="required">
                                                
                                                
                                             </div>
                                            
                                          
                                                
                                        
                                    </div>
                                </div>

                            </div>
                            </div>
                              
                              
                             
                             <div class="col-md-12 no-padding">
                             <div class="card-footer">
                                 <button type="submit" name="submit" class="btn btn-primary btn-sm"><i class="fa fa-dot-circle-o"></i> Submit</button>
                                 <button type="reset" class="btn btn-danger btn-sm"><i class="fa fa-ban"></i> Reset </button>
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

</html>
