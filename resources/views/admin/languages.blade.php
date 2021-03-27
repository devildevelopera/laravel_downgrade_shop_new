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
    @if(in_array('languages',$avilable))
    <div id="right-panel" class="right-panel">

        
                       @include('admin.header')
                       

        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Languages</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <a href="{{ url('/admin/add-language') }}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Add Language</a>
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
        <div class="content mt-3">
            <div class="animated fadeIn">
                <div class="row">

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <strong class="card-title">Languages</strong>
                            </div>
                            <div class="card-body">
                                <table id="bootstrap-data-table-export" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Sno</th>
                                            <th>Language Name</th>
                                            <th>Language Code</th>
                                            <th>Display Order</th>
                                            <th>Default Language?</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @php $no = 1; @endphp
                                    @foreach($language['view'] as $language)
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td>{{ $language->language_name }} </td>
                                            <td>{{ $language->language_code }}</td>
                                            <td>{{ $language->language_order }}</td>
                                            <td>@if($language->language_default == 1) <span class="badge badge-success">Yes</span> @else <span class="badge badge-danger">No</span> @endif</td>
                                            <td>@if($language->language_status == 1) <span class="badge badge-success">Active</span> @else <span class="badge badge-danger">InActive</span> @endif</td>
                                            <td>
                                            <?php /*?><a href="add-keywords" class="btn btn-success btn-sm"><i class="fa fa-language"></i>&nbsp; Add Keywords</a><?php */?>
                                            <a href="edit-keywords/{{ $language->language_code }}" class="btn btn-success btn-sm"><i class="fa fa-language"></i>&nbsp; Edit Keywords</a>
                                            <a href="edit-language/{{ $language->language_token }}" class="btn btn-success btn-sm"><i class="fa fa-edit"></i>&nbsp; Edit</a> 
                                            @if($language->language_code != 'en')
                                            @if($demo_mode == 'on') 
                                            <a href="demo-mode" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>&nbsp;Delete</a>
                                            @else 
                                            <a href="languages/{{ $language->language_token }}/{{ $language->language_code }}" class="btn btn-danger btn-sm" onClick="return confirm('Are you sure you want to delete?');"><i class="fa fa-trash"></i>&nbsp;Delete</a>@endif @endif</td>
                                        </tr>
                                        @php $no++; @endphp
                                   @endforeach     
                                        
                                    </tbody>
                                </table>
                            </div>
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
