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
                        <h1>Email Settings</h1>
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
                           <form action="{{ route('admin.email-settings') }}" method="post" id="setting_form" enctype="multipart/form-data">
                           {{ csrf_field() }}
                           @endif
                          
                           <div class="col-md-6">
                           
                            <div class="card-body">
                                <!-- Credit Card -->
                                <div id="pay-invoice">
                                    <div class="card-body">
                                       
                                        
                                            
                                            <div class="form-group">
                                                <label for="site_title" class="control-label mb-1">Sender Name <span class="require">*</span></label>
                                                <input id="sender_name" name="sender_name" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->sender_name }}" data-bvalidator="required">
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
                                                <label for="site_title" class="control-label mb-1">Sender Email <span class="require">*</span></label>
                                                <input id="sender_email" name="sender_email" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->sender_email }}" data-bvalidator="required,email">
                                            </div>
                                                
                                                
                                                <input type="hidden" name="sid" value="1">
                             
                             
                             </div>
                                </div>

                            </div>
                             
                             
                             
                             </div>
                             
                             
                             <div class="col-md-12"><div class="card-body"><h4>Mail Configuration</h4></div></div>
                             
                             
                             <div class="col-md-6">
                             
                             
                             <div class="card-body">
                                <!-- Credit Card -->
                                <div id="pay-invoice">
                                    <div class="card-body">
                             
                             
                              <div class="form-group">
                                                <label for="site_title" class="control-label mb-1">Mail Driver <span class="require">*</span></label>
                                                <input id="mail_driver" name="mail_driver" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->mail_driver }}" data-bvalidator="required"> <small>Example : mail</small>
                                            </div>
                                                
                                                
                                  <div class="form-group">
                                                <label for="site_title" class="control-label mb-1">Mail Port <span class="require">*</span></label>
                                                <input id="mail_port" name="mail_port" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->mail_port }}" data-bvalidator="required"> <small>Example : 25</small>
                                            </div>   
                                            
                                            <div class="form-group">
                                                <label for="site_title" class="control-label mb-1">Mail Password</label>
                                                <input id="mail_password" name="mail_password" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->mail_password }}"> <small>Example : test123</small>
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
                                                <label for="site_title" class="control-label mb-1">Mail Host <span class="require">*</span></label>
                                                <input id="mail_host" name="mail_host" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->mail_host }}" data-bvalidator="required"> <small>Example : mail.mailtrap.io</small>
                                            </div>
                                                
                                    <div class="form-group">
                                                <label for="site_title" class="control-label mb-1">Mail Username </label>
                                                <input id="mail_username" name="mail_username" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->mail_username }}"> <small>Example : sample@sample.com</small>
                                            </div>  
                                            
                                            
                                            
                                      <div class="form-group">
                                                <label for="site_title" class="control-label mb-1">Mail Encryption </label>
                                                <input id="mail_encryption" name="mail_encryption" type="text" class="form-control noscroll_textarea" value="{{ $setting['setting']->mail_encryption }}"> <small>Example : tls or ssl</small>
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
