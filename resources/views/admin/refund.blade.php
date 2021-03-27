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
    @if(in_array('refund-request',$avilable))
    <div id="right-panel" class="right-panel">

        
                       @include('admin.header')
                       

        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Refund Request</h1>
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

        <div class="content mt-3">
            <div class="animated fadeIn">
                <div class="row">

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <strong class="card-title">Refund Request</strong>
                            </div>
                            <div class="card-body">
                                <table id="bootstrap-data-table-export" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Sno</th>
                                            <th>Order ID</th>
                                            <th>Product Name</th>
                                            <th>Buyer</th>
                                            <th>Refund Reason</th>
                                            <th>Refund Comment</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @php $no = 1; @endphp
                                    @foreach($itemData['item'] as $refund)
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td>{{ $refund->ref_purchased_token }} </td>
                                            <td>{{ $refund->product_name }} </td>
                                            <td>{{ $refund->username }}</td>
                                            <td>{{ $refund->ref_refund_reason }} </td>
                                            <td>{{ $refund->ref_refund_comment }}</td>
                                            <td>
                                            @if($refund->ref_refund_approval == "") 
                                            <a href="{{ URL::to('/admin/refund') }}/{{ $refund->ref_order_id }}/{{ $refund->refund_id }}/customer" class="btn btn-success btn-sm" title="payment released to vendor"><i class="fa fa-money"></i>&nbsp; Refund Accept</a> 
                                            <a href="{{ URL::to('/admin/refund') }}/{{ $refund->ref_order_id }}/{{ $refund->refund_id }}/admin" class="btn btn-danger btn-sm" title="payment released to buyer"><i class="fa fa-close"></i>&nbsp; Refund Declined</a>
                                            @else
                                            @if($refund->ref_refund_approval == 'accepted') <span class="badge badge-success">Accepted</span> @else <span class="badge badge-danger">Declined</span> @endif
                                            
                                            @endif
                                            </td>
                                        </tr>
                                        
                                        @php $no++; @endphp
                                   @endforeach     
                                        
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
