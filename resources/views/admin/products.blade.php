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

                        <h1>Products</h1>

                    </div>

                </div>

            </div>

            <div class="col-sm-8">

                <div class="page-header float-right">

                    <div class="page-title">

                        <ol class="breadcrumb text-right">

                            <a href="{{ url('/admin/add-product') }}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Add Product</a>&nbsp;

                            <a href="{{ url('/admin/products-import-export') }}" class="btn btn-primary btn-sm"><i class="fa fa-file-excel-o"></i> Product Import / Export</a>

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

                                <strong class="card-title">Products</strong>

                            </div>

                            <div class="card-body">

                                <table id="bootstrap-data-table-export" class="table table-striped table-bordered">

                                    <thead>

                                    <tr>

                                        <th>Sno</th>

                                        <th>Image</th>

                                        <th>Product Name</th>

                                        <th>Category</th>

                                        <th>Price</th>

                                        <th>Featured</th>

                                        <th>Free Download</th>

                                        <th>Flash Sale</th>

                                        <th>Status</th>

                                        <th>Action</th>

                                    </tr>

                                    </thead>

                                    <tbody>

                                    @php $no = 1; @endphp

                                    @foreach($product['view'] as $product)

                                        <tr>

                                            <td>{{ $no }}</td>

                                            <td>@if($product->product_image != '') <img src="{{ url('/') }}/public/storage/product/{{ $product->product_image }}" alt="{{ $product->product_name }}"
                                                                                        class="image-size"/>@else <img src="{{ url('/') }}/public/img/no-image.jpg" alt="{{ $product->product_name }}"
                                                                                                                       class="image-size"/>  @endif</td>

                                            <td>{{ substr($product->product_name,0,20).'...' }} </td>

                                            <td>{{ $product->category_name }}</td>

                                            <td>{{ $allsettings->site_currency_symbol }} {{ $product->regular_price }}</td>

                                            <td>@if($product->product_featured == 1) <span class="badge badge-success">Yes</span> @else <span class="badge badge-danger">No</span> @endif</td>

                                            <td>@if($product->product_free == 1) <span class="badge badge-success">Yes</span> @else <span class="badge badge-danger">No</span> @endif</td>

                                            <td>@if($product->product_flash_sale == 1) <span class="badge badge-success">Yes</span> @else <span class="badge badge-danger">No</span> @endif</td>


                                            <td>@if($product->product_status == 1) <span class="badge badge-success">Active</span> @else <span class="badge badge-danger">InActive</span> @endif</td>

                                            <td><a href="edit-product/{{ $product->product_token }}" class="btn btn-success btn-sm"><i class="fa fa-edit"></i>&nbsp; Edit</a>

                                                @if($demo_mode == 'on')

                                                    <a href="demo-mode" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>&nbsp;Delete</a>

                                                @else

                                                    <a href="products/{{ $product->product_token }}" class="btn btn-danger btn-sm" onClick="return confirm('Are you sure you want to delete?');"><i
                                                            class="fa fa-trash"></i>&nbsp;Delete</a>@endif</td>

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

