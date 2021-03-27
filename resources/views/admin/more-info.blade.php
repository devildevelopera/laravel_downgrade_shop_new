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
    @if(in_array('orders',$avilable))
    <div id="right-panel" class="right-panel">

        
                       @include('admin.header')
                       

        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>More Info</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <a href="{{ url('/admin/order-details') }}/{{ $itemData['item']->purchase_token }}" class="btn btn-success btn-sm"><i class="fa fa-chevron-left"></i> Back</a>
                        </ol>
                    </div>
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

        <div class="content mt-3">
            <div class="animated fadeIn">
                <div class="row">

                    <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title" v-if="headerText">Buyer More Information</strong>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-hover table-striped table-align-middle mb-0">
                                
                                <tbody>
                                    <tr>
                                        <td>
                                            First Name
                                        </td>
                                        
                                        <td>
                                            {{ $itemData['item']->order_firstname }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Last Name
                                        </td>
                                        
                                        <td>
                                            {{ $itemData['item']->order_lastname }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Company
                                        </td>
                                        
                                        <td>
                                            {{ $itemData['item']->order_company }}
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td>
                                            Email
                                        </td>
                                        
                                        <td>
                                            {{ $itemData['item']->order_email }}
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td>
                                            Country
                                        </td>
                                        
                                        <td>
                                            {{ $itemData['item']->order_country }}
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td>
                                            Address
                                        </td>
                                        
                                        <td>
                                            {{ $itemData['item']->order_address }}
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td>
                                            City
                                        </td>
                                        
                                        <td>
                                            {{ $itemData['item']->order_city }}
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td>
                                            Zip Code
                                        </td>
                                        
                                        <td>
                                            {{ $itemData['item']->order_zipcode }}
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td>
                                            Other Notes
                                        </td>
                                        
                                        <td>
                                            {{ $itemData['item']->order_notes }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

 
                </div>
            </div>
        </div>


    </div>
    @else
    @include('admin.denied')
    @endif
    


   @include('admin.javascript')


</body>

</html>
