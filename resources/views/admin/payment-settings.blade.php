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

                        <h1>Payment Settings</h1>

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

                           <form action="{{ route('admin.payment-settings') }}" method="post" id="setting_form" enctype="multipart/form-data">

                           {{ csrf_field() }}

                          @endif

                           <div class="col-md-6">

                           

                            <div class="card-body">

                                <!-- Credit Card -->

                                <div id="pay-invoice">

                                    <div class="card-body">

                                       

                                        

                                            

                                            <div class="form-group">

                                                <label for="site_title" class="control-label mb-1">Processing Fee (extra fee) <span class="require">*</span></label>

                                                <input id="site_extra_fee" name="site_extra_fee" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->site_extra_fee }}" data-bvalidator="required,min[0]"><small>(if you will set <strong>"0"</strong> processing fee is <strong>OFF</strong>)</small>

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

                                                <label for="site_title" class="control-label mb-1">Minimum withdrawal amount ({{ $setting['setting']->site_currency_symbol }})<span class="require">*</span></label>

                                                <input id="site_minimum_withdrawal" name="site_minimum_withdrawal" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->site_minimum_withdrawal }}" data-bvalidator="required,digit,min[1]">

                                            </div>    

                                                

                                                

                                                <input type="hidden" name="sid" value="1">

                             

                             

                             </div>

                                </div>



                            </div>

                             

                             

                             

                             </div>

                             

                             

                             

                             <div style="clear:both;"></div>

                             

                             

                             <div class="col-md-6">

                           

                            <div class="card-body">

                                <!-- Credit Card -->

                                <div id="pay-invoice">

                                    <div class="card-body">

                                       

                                        

                                            

                                            <div class="form-group">

                                                <label for="site_title" class="control-label mb-1">Payment Methods </label><br/>

                                                @foreach($payment_option as $payment)

                                                <input id="payment_option" name="payment_option[]" type="checkbox" @if(in_array($payment,$get_payment)) checked @endif class="noscroll_textarea" value="{{ $payment }}"> {{ str_replace("-"," ",$payment) }}<br/>

                                                @endforeach

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

                                                <label for="site_title" class="control-label mb-1">Withdraw Methods </label><br/>

                                                @foreach($withdraw_option as $withdraw)

                                                <input id="withdraw_option" name="withdraw_option[]" type="checkbox" @if(in_array($withdraw,$get_withdraw)) checked @endif class="noscroll_textarea" value="{{ $withdraw }}"> {{ $withdraw }}<br/>

                                                @endforeach

                                             </div>

                                            

                                          

                                                

                                        

                                    </div>

                                </div>



                            </div>

                            </div>

                             

                             

                             <div class="col-md-12"><div class="card-body"><h4>Paypal Settings</h4></div></div>

                             

                             

                             <div class="col-md-6">

                           

                            <div class="card-body">

                                <!-- Credit Card -->

                                <div id="pay-invoice">

                                    <div class="card-body">

                                       

                                        

                                            

                                            <div class="form-group">

                                                <label for="site_title" class="control-label mb-1">Paypal Email Id <span class="require">*</span></label><br/>

                                               <input id="paypal_email" name="paypal_email" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->paypal_email }}" data-bvalidator="required,email">

                                                

                                                

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

                                                <label for="site_title" class="control-label mb-1">Paypal Mode <span class="require">*</span></label><br/>

                                               

                                                <select name="paypal_mode" class="form-control" data-bvalidator="required">

                                                <option value=""></option>

                                                <option value="1" @if($setting['setting']->paypal_mode == 1) selected @endif>Live</option>

                                                <option value="0" @if($setting['setting']->paypal_mode == 0) selected @endif>Demo</option>

                                                </select>

                                                

                                             </div>

                                            

                                          

                                                

                                        

                                    </div>

                                </div>



                            </div>

                            </div>

                             

                             

                             <div class="col-md-12"><div class="card-body"><h4>Stripe Settings</h4></div></div>

                             

                             

                              <div class="col-md-6">

                           

                            <div class="card-body">

                                <!-- Credit Card -->

                                <div id="pay-invoice">

                                    <div class="card-body">

                                       

                                        

                                            

                                            <div class="form-group">

                                                <label for="site_title" class="control-label mb-1">Stripe Mode <span class="require">*</span></label><br/>

                                               

                                                <select name="stripe_mode" class="form-control" data-bvalidator="required">

                                                <option value=""></option>

                                                <option value="1" @if($setting['setting']->stripe_mode == 1) selected @endif>Live</option>

                                                <option value="0" @if($setting['setting']->stripe_mode == 0) selected @endif>Demo</option>

                                                </select>

                                                

                                             </div>

                                             

                                             

                                             <div class="form-group">

                                                <label for="site_title" class="control-label mb-1">Test Publishable Key <span class="require">*</span></label><br/>

                                               <input id="test_publish_key" name="test_publish_key" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->test_publish_key }}" data-bvalidator="required">

                                                

                                                

                                             </div>

                                             

                                             

                                             <div class="form-group">

                                                <label for="site_title" class="control-label mb-1">Live Publishable Key <span class="require">*</span></label><br/>

                                               <input id="live_publish_key" name="live_publish_key" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->live_publish_key }}" data-bvalidator="required">

                                                

                                                

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

                                              <div style="height:65px;"></div>

                                                

                                             </div>

                                             

                                             

                                             <div class="form-group">

                                                <label for="site_title" class="control-label mb-1">Test Secret Key <span class="require">*</span></label><br/>

                                               <input id="test_secret_key" name="test_secret_key" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->test_secret_key }}" data-bvalidator="required">

                                                

                                                

                                             </div>

                                           

                                           

                                            <div class="form-group">

                                                <label for="site_title" class="control-label mb-1">Live Secret Key <span class="require">*</span></label><br/>

                                               <input id="live_secret_key" name="live_secret_key" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->live_secret_key }}" data-bvalidator="required">

                                                

                                                

                                             </div>

                                         

                                        

                                    </div>

                                </div>



                            </div>

                            </div>

                             <div class="col-md-12"><div class="card-body"><h4>Paystack Settings</h4></div></div>

                            <div class="col-md-6">

                           

                            <div class="card-body">

                                <!-- Credit Card -->

                                <div id="pay-invoice">

                                    <div class="card-body">

                                    

                                    

                                    <div class="form-group">

                                                <label for="site_title" class="control-label mb-1">Paystack Public Key <span class="require">*</span></label><br/>

                                               <input id="paystack_public_key" name="paystack_public_key" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->paystack_public_key }}" data-bvalidator="required">

                                                

                                                

                                             </div>

                                           

                                           

                                            <div class="form-group">

                                                <label for="site_title" class="control-label mb-1">Paystack Secret Key <span class="require">*</span></label><br/>

                                               <input id="paystack_secret_key" name="paystack_secret_key" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->paystack_secret_key }}" data-bvalidator="required">

                                                

                                                

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

                                                <label for="site_title" class="control-label mb-1">Paystack Merchant Email <span class="require">*</span></label><br/>

                                               <input id="paystack_merchant_email" name="paystack_merchant_email" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->paystack_merchant_email }}" data-bvalidator="required">

                                                

                                                

                                             </div>

                                         

                                    </div>

                                </div>



                            </div>

                            </div>

                             

                             <div class="col-md-12"><div class="card-body"><h4>Razorpay Settings</h4></div></div>

                            <div class="col-md-6">

                                <div class="card-body">

                                    <!-- Credit Card -->

                                    <div id="pay-invoice">

                                        <div class="card-body">

                                        <div class="form-group">

                                                    <label for="site_title" class="control-label mb-1">Razorpay Key Id <span class="require">*</span></label><br/>

                                                   <input id="razorpay_key" name="razorpay_key" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->razorpay_key }}" data-bvalidator="required">

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

                                                    <label for="site_title" class="control-label mb-1">Razorpay Secret Key <span class="require">*</span></label><br/>

                                                   <input id="razorpay_secret" name="razorpay_secret" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->razorpay_secret }}" data-bvalidator="required">

                                                 </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="col-md-12"><div class="card-body"><h4>PerfectMoney Settings</h4></div></div>

                            <div class="col-md-6">

                                <div class="card-body">

                                    <!-- Credit Card -->

                                    <div id="pay-invoice">

                                        <div class="card-body">

                                            <div class="form-group">

                                                <label for="perfectmoney_key" class="control-label mb-1">PerfectMoney Key Id <span class="require">*</span></label><br/>

                                                <input id="perfectmoney_key" name="perfectmoney_key" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->perfectmoney_key }}" data-bvalidator="required">

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

                                                <label for="perfectmoney_secret" class="control-label mb-1">PerfectMoney Secret Key <span class="require">*</span></label><br/>

                                                <input id="perfectmoney_secret" name="perfectmoney_secret" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->perfectmoney_secret }}" data-bvalidator="required">

                                            </div>

                                        </div>

                                    </div>

                                </div>
                            </div>

                            <div class="col-md-12"><div class="card-body"><h4>Coinbase Settings</h4></div></div>

                            <div class="col-md-6">

                                <div class="card-body">

                                    <!-- Credit Card -->

                                    <div id="pay-invoice">

                                        <div class="card-body">

                                            <div class="form-group">

                                                <label for="coinbase_key" class="control-label mb-1">Coinbase Key Id <span class="require">*</span></label><br/>

                                                <input id="coinbase_key" name="coinbase_key" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->coinbase_key }}" data-bvalidator="required">

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

                                                <label for="coinbase_secret" class="control-label mb-1">Coinbase Webhook Secret <span class="require">*</span></label><br/>

                                                <input id="coinbase_secret" name="coinbase_secret" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->coinbase_secret }}" data-bvalidator="required">

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="col-md-12 no-padding">

                                <div class="card-footer">

                                    <button type="submit" name="submit" class="btn btn-primary btn-sm">

                                        <i class="fa fa-dot-circle-o"></i> Submit

                                    </button>

                                    <button type="reset" class="btn btn-danger btn-sm">

                                        <i class="fa fa-ban"></i> Reset

                                    </button>

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

