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
                        <h1>Order Details</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <a href="{{ url('/admin/orders') }}" class="btn btn-success btn-sm"><i class="fa fa-chevron-left"></i> Back</a>
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
                                <strong class="card-title">Order Details</strong>
                            </div>
                            <div class="card-body">
                                <table id="bootstrap-data-table-export" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Sno</th>
                                            <th>Product Name</th>
                                            <th>License</th>
                                            <th>Purchased Date</th>
                                            <th>Expiry Date</th>
                                            <th>Amount</th>
                                            <th>Payment Status</th>
                                            <th>Payment Approval?</th>
                                            <th>More Info</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @php $no = 1; @endphp
                                    @foreach($itemData['item'] as $order)
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td>{{ $order->product_name }} </td>
                                            <td>{{ $order->license }} </td>
                                            <td>{{ date('d F Y', strtotime($order->start_date)) }} </td>
                                            <td>{{ date('d F Y', strtotime($order->end_date)) }} </td>
                                           
                                            
                                            <td>{{ $allsettings->site_currency_symbol }} {{ $order->total_price }}</td>
                                            <td>@if($order->order_status == 'completed') <span class="badge badge-success">Completed</span> @else <span class="badge badge-danger">Pending</span> @endif</td>
                                            <td>
                                            
                                            {{ $order->approval_status }}
                                            
                                            </td>
                                            <td><a href="{{ URL::to('/admin/more-info') }}/{{ $order->purchase_token }}" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i>&nbsp; More Info</a></td>
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
