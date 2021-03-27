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
    @if(in_array('withdrawal',$avilable))
    <div id="right-panel" class="right-panel">

        
                       @include('admin.header')
                       

        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Withdrawal Request</h1>
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
                                <strong class="card-title">Withdrawal Request</strong>
                            </div>
                            <div class="card-body">
                                <table id="bootstrap-data-table-export" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Sno</th>
                                            <th>User Name</th>
                                            <th>Date</th>
                                            
                                            <th>Withdrawal Type</th>
                                            <th>Email</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @php $no = 1; @endphp
                                    @foreach($itemData['item'] as $withdraw)
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td>{{ $withdraw->username }}</td>
                                            <td>{{ $withdraw->wd_date }} </td>
                                            <td>{{ $withdraw->withdraw_type }} </td>
                                            <td>
                                            @if($withdraw->withdraw_type == 'paypal'){{ $withdraw->paypal_email }}@endif
                                            @if($withdraw->withdraw_type == 'stripe'){{ $withdraw->stripe_email }}@endif
                                            @if($withdraw->withdraw_type == 'paystack'){{ $withdraw->paystack_email }}@endif
                                            </td>
                                            <td>{{ $allsettings->site_currency_symbol }}{{ $withdraw->wd_amount }} </td>
                                            <td>
                                            @if($withdraw->wd_status == 'pending')
                                            <a href="{{ URL::to('/admin/withdrawal') }}/{{ $withdraw->wd_id }}/{{ $withdraw->wd_user_id }}" class="btn btn-success btn-sm" onClick="return confirm('Are you sure you want to complete withdrawal request?');"><i class="fa fa-money"></i>&nbsp; Click to Complete Withdrawal Request</a>
                                            
                                            @else
                                            <span class="badge badge-success">{{ $withdraw->wd_status }}</span>
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
