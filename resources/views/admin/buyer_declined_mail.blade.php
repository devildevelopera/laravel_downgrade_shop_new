<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ __('Payment Refund Declined') }}</title>
    
</head>

<body class="preload dashboard-upload">

    
    
    
        <div class="dashboard_contents">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="dashboard_title_area">
                            <div class="pull-left">
                                <div class="dashboard__title">
                                    <h2>{{ __('Payment Refund Declined') }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end /.col-md-12 -->
                </div>
                <!-- end /.row -->

                <div class="row">
                    <div class="col-lg-8 col-md-7">
                        <p><strong>Your payment refund is declined. Please contact your administrator</strong></p>   
                        
                        <p><strong> your refund request amount is : </strong> {{ $currency }}{{ $price }} </p>   
                    </div>
                    
                </div>
                
            </div>
            
        </div>
        
    </section>
    
    
   

    
</body>

</html>