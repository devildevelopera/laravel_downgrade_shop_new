<!DOCTYPE HTML>

<html lang="en">

<head>

    <title>{{ $item['view']->product_name }} - {{ $allsettings->site_title }}</title>

    @if($slug != '')

        @if($item['view']->product_allow_seo == 1)

            <meta name="Title" content="{{ $item['view']->product_seo_title }}">
            <meta name="Description" content="{{ $item['view']->product_seo_desc }}">

            <meta name="Keywords" content="{{ $item['view']->product_seo_keyword }}">

        @else

            @include('meta')

        @endif

    @else

        @include('meta')

    @endif

    @include('style')

    <style>
        .hk-row {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            margin-right: -10px;
            margin-left: -10px;
        }

        @media (min-width: 576px){
            .mt-sm-60 {
                margin-top: 60px !important;
            }
        }
        .mt-30 {
            margin-top: 30px !important;
        }

        .list-group-item.active {
            background-color: #00acf0;
            border-color: #00acf0;
        }
        .accordion .card .card-header.activestate {
            border-width: 1px;
        }
        .accordion .card .card-header {
            padding: 0;
            border-width: 0;
        }
        .card.card-lg .card-header, .card.card-lg .card-footer {
            padding: .9rem 1.5rem;
        }
        .accordion>.card .card-header {
            margin-bottom: -1px;
        }
        .card .card-header {
            background: transparent;
            border: none;
        }
        .accordion.accordion-type-2 .card .card-header > a.collapsed {
            color: #324148;
        }
        .accordion .card:first-of-type .card-header:first-child > a {
            border-top-left-radius: calc(.25rem - 1px);
            border-top-right-radius: calc(.25rem - 1px);
        }
        .accordion.accordion-type-2 .card .card-header > a {
            background: transparent;
            color: #00acf0;
            padding-left: 50px;
        }
        .accordion .card .card-header > a.collapsed {
            color: #324148;
            background: transparent;
        }
        .accordion .card .card-header > a {
            background: #00acf0;
            color: #fff;
            font-weight: 500;
            padding: .75rem 1.25rem;
            display: block;
            width: 100%;
            text-align: left;
            position: relative;
            -webkit-transition: all 0.2s ease-in-out;
            -moz-transition: all 0.2s ease-in-out;
            transition: all 0.2s ease-in-out;
        }
        a {
            text-decoration: none;
            color: #00acf0;
            -webkit-transition: color 0.2s ease;
            -moz-transition: color 0.2s ease;
            transition: color 0.2s ease;
        }


        .badge.badge-pill {
            border-radius: 50px;
        }
        .badge.badge-light {
            background: #eaecec;
            color: #324148;
        }
        .badge {
            font-weight: 500;
            border-radius: 4px;
            padding: 5px 7px;
            font-size: 72%;
            letter-spacing: 0.3px;
            vertical-align: middle;
            display: inline-block;
            text-align: center;
            text-transform: capitalize;
        }
        .ml-15 {
            margin-left: 15px !important;
        }

        .accordion.accordion-type-2 .card .card-header > a.collapsed:after {
            content: "\f158";
        }

        .accordion.accordion-type-2 .card .card-header > a::after {
            display: inline-block;
            font: normal normal normal 14px/1 'Ionicons';
            speak: none;
            text-transform: none;
            line-height: 1;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: auto;
            position: absolute;
            content: "\f176";
            font-size: 21px;
            top: 15px;
            left: 20px;
        }

        .mr-15 {
            margin-right: 15px !important;
        }
    </style>
</head>

<body>

@include('header')

<div class="page-title-overlap pt-4" style="background-image: url('{{ url('/') }}/public/storage/settings/{{ $allsettings->site_banner }}');">

    <div class="container d-lg-flex justify-content-between py-2 py-lg-3">

        <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">

            <nav aria-label="breadcrumb">

                <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">

                    <li class="breadcrumb-item"><a class="text-nowrap" href="{{ URL::to('/') }}"><i class="dwg-home"></i>{{ Helper::translation(3849,$translate) }}</a></li>

                    <li class="breadcrumb-item text-nowrap active" aria-current="page">{{ $item['view']->product_name }}</li>

                    </li>

                </ol>

            </nav>

        </div>

        <div class="order-lg-1 pr-lg-4 text-center text-lg-left">

            <h1 class="h3 mb-0 text-white">{{ $item['view']->product_name }}</h1>

        </div>

    </div>

</div>

<section class="container mb-3 pb-3">

    <div class="bg-light box-shadow-lg rounded-lg overflow-hidden">

        <div class="row">

            <!-- Content-->

            <section class="col-lg-8 pt-2 pt-lg-4 pb-4 mb-lg-3">

                <div class="pt-2 px-4 pr-lg-0 pl-xl-5">

                    <!-- Product gallery-->

                    <div class="cz-gallery">

                        @if($item['view']->product_image!='')

                            <a class="gallery-item rounded-lg mb-grid-gutter" href="{{ url('/') }}/public/storage/product/{{ $item['view']->product_image }}"
                               data-sub-html="{{ $item['view']->product_name }}"><img src="{{ url('/') }}/public/storage/product/{{ $item['view']->product_image }}"
                                                                                      alt="{{ $item['view']->product_name }}"/><span
                                    class="gallery-item-caption">{{ $item['view']->product_name }}</span></a>

                        @else

                            <img src="{{ url('/') }}/public/img/no-image.png" alt="{{ $item['view']->product_name }}" class="card-img-top featured-img">

                        @endif

                        @if($getcount != 0)

                            <div class="row">

                                @foreach($getall['image'] as $image)

                                    <div class="col-sm-2"><a class="gallery-item rounded-lg mb-grid-gutter" href="{{ url('/') }}/public/storage/product/{{ $image->product_gallery_image }}"
                                                             data-sub-html="{{ $item['view']->product_name }}"><img src="{{ url('/') }}/public/storage/product/{{ $image->product_gallery_image }}"
                                                                                                                    alt="{{ $image->product_gallery_image }}"/><span
                                                class="gallery-item-caption">{{ $item['view']->product_name }}</span></a></div>

                                @endforeach

                            </div>

                        @endif

                    </div>

                    <!-- Wishlist + Sharing-->

                    <div class="d-flex flex-wrap justify-content-between align-items-center border-top pt-3">

                        <div class="py-2 mr-2">

                            @if($item['view']->product_demo_url != '')

                                <a class="btn btn-outline-accent btn-sm" href="{{ $item['view']->product_demo_url }}" target="_blank"><i
                                        class="dwg-eye font-size-sm mr-2"></i>{{ Helper::translation(4143,$translate) }}</a>

                            @endif

                            @if($item['view']->product_video_url != '')

                                <a class="btn btn-outline-accent btn-sm popupvideo" href="{{ $item['view']->product_video_url }}"><i
                                        class="dwg-video font-size-lg mr-2"></i>{{ Helper::translation(4146,$translate) }}</a>

                            @endif

                            @if(Auth::guest())

                                <a class="btn btn-outline-accent btn-sm" href="{{ URL::to('/login') }}"><i class="dwg-heart font-size-lg mr-2"></i>{{ Helper::translation(4149,$translate) }}</a>

                            @endif

                            @if (Auth::check())

                                @if($item['view']->user_id != Auth::user()->id)

                                    <a class="btn btn-outline-accent btn-sm"
                                       href="{{ url('/item') }}/{{ base64_encode($item['view']->product_id) }}/favorite/{{ base64_encode($item['view']->product_liked) }}"><i
                                            class="dwg-heart font-size-lg mr-2"></i>{{ Helper::translation(4149,$translate) }}</a>

                                @endif

                            @endif

                        </div>

                        <div class="py-2"><i class="dwg-share-alt font-size-lg align-middle text-muted mr-2"></i>

                            <a class="social-btn sb-outline sb-facebook sb-sm ml-2 share-button" data-share-url="{{ URL::to('/product') }}/{{ $item['view']->product_slug }}"
                               data-share-network="facebook" data-share-text="{{ $item['view']->product_short_desc }}" data-share-title="{{ $item['view']->product_name }}"
                               data-share-via="{{ $allsettings->site_title }}" data-share-tags="" data-share-media="{{ url('/') }}/public/storage/product/{{ $item['view']->product_image }}"
                               href="javascript:void(0)"><i class="dwg-facebook"></i></a>

                            <a class="social-btn sb-outline sb-twitter sb-sm ml-2 share-button" data-share-url="{{ URL::to('/product') }}/{{ $item['view']->product_slug }}"
                               data-share-network="twitter" data-share-text="{{ $item['view']->product_short_desc }}" data-share-title="{{ $item['view']->product_name }}"
                               data-share-via="{{ $allsettings->site_title }}" data-share-tags="" data-share-media="{{ url('/') }}/public/storage/product/{{ $item['view']->product_image }}"
                               href="javascript:void(0)"><i class="dwg-twitter"></i></a>

                            <a class="social-btn sb-outline sb-pinterest sb-sm ml-2 share-button" data-share-url="{{ URL::to('/product') }}/{{ $item['view']->product_slug }}"
                               data-share-network="googleplus" data-share-text="{{ $item['view']->product_short_desc }}" data-share-title="{{ $item['view']->product_name }}"
                               data-share-via="{{ $allsettings->site_title }}" data-share-tags="" data-share-media="{{ url('/') }}/public/storage/product/{{ $item['view']->product_image }}"
                               href="javascript:void(0)"><i class="dwg-google"></i></a>

                            <a class="social-btn sb-outline sb-linkedin sb-sm ml-2 share-button" data-share-url="{{ URL::to('/product') }}/{{ $item['view']->product_slug }}"
                               data-share-network="linkedin" data-share-text="{{ $item['view']->product_short_desc }}" data-share-title="{{ $item['view']->product_name }}"
                               data-share-via="{{ $allsettings->site_title }}" data-share-tags="" data-share-media="{{ url('/') }}/public/storage/product/{{ $item['view']->product_image }}"
                               href="javascript:void(0)"><i class="dwg-linkedin"></i></a>

                        </div>

                    </div>

                </div>

            </section>

            <!-- Sidebar-->

            <aside class="col-lg-4">

                <hr class="d-lg-none">

                <form action="{{ Auth::check()? route('cart') : route('guest-cart') }}" class="setting_form" method="post" id="order_form" enctype="multipart/form-data">

                    {{ csrf_field() }}

                    <div class="cz-sidebar-static h-100 ml-auto border-left">

                        @if($item['view']->product_free == 1)

                            <div class="bg-secondary rounded p-3 mb-4">

                                <p>{{ Helper::translation(4152,$translate) }} <strong>{{ Helper::translation(4155,$translate) }}</strong>. {{ Helper::translation(4158,$translate) }}</p>

                                @php if($item['view']->download_count == "") { $dcount = 0; } else { $dcount = $item['view']->download_count; } @endphp

                                <div align="center">

                                    @if (Auth::check())

                                        <a href="{{ URL::to('/product') }}/download/{{ base64_encode($item['view']->product_token) }}" class="btn btn-primary btn-sm" download> <i
                                                class="fa fa-download"></i> {{ Helper::translation(4161,$translate) }} ({{ $dcount }})</a>

                                    @endif

                                    @if(Auth::guest())

                                        <a href="{{ URL::to('/login') }}" class="btn btn-primary btn-sm"> <i class="fa fa-download"></i> {{ Helper::translation(4161,$translate) }} ({{ $dcount }})</a>

                                    @endif

                                </div>

                            </div>

                        @endif

                        <?php if ($item['view']->product_flash_sale == 1) {
                            $item_price = round($item['view']->regular_price - ($item['view']->regular_price * $item['view']->product_flash_sale_percentage / 100));
                        } else {
                            $item_price = $item['view']->regular_price;
                        }
                        ?>

                        <div class="accordion" id="licenses">

                            <div class="card border-top-0 border-left-0 border-right-0">

                                <div class="card-header d-flex justify-content-between align-items-center py-3 border-0">

                                    {{-- <div class="custom-control custom-radio">

                                      <input class="custom-control-input" type="radio" name="item_price" value="{{ base64_encode($item_price) }}_regular" id="license-std" checked>

                                      <label class="custom-control-label font-weight-medium text-dark" for="license-std" data-toggle="collapse" data-target="#standard-license">{{ Helper::translation(4164,$translate) }}</label>

                                    </div> --}}

                                     <input class="custom-control-input main_encoded_price" name="item_price" value="{{ base64_encode($item_price) }}_regular" id="license-std" type="hidden">
                                    
                                    <h5 class="mb-0 text-accent text-center font-weight-normal">Price:- {{ $allsettings->site_currency_symbol }}<sapn id="product_text_price">{{ $item_price }}</sapn></h5>
                                    <br>

                                </div>

                               <div style="margin-bottom: 5px;">
                                    <select name="product_package" class="form-control" id="product_package">
                                        <option value="">Select Package</option>
                                        @foreach($product_packages as $pro_package)
                                        <?php
                                        if ($item['view']->product_flash_sale == 1) {
                                                $package_price = round($pro_package->package_price - ($pro_package->package_price * $item['view']->product_flash_sale_percentage / 100));
                                            } else {
                                                $package_price = $pro_package->package_price;
                                            }
                                        ?>
                                            <option value="{{$pro_package->id}},{{$package_price}},{{base64_encode($package_price)}}">{{$pro_package->package_name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- <div class="collapse show" id="standard-license" data-parent="#licenses">

                                  <div class="card-body py-0 pb-2">

                                    <ul class="list-unstyled font-size-sm">

                                      <li class="d-flex align-items-center"><i class="dwg-check-circle text-success mr-1"></i><span class="font-size-ms">{{ Helper::translation(4167,$translate) }} {{ $allsettings->site_title }}</span></li>

                                      @if($item['view']->future_update == 1)

                                      <li class="d-flex align-items-center"><i class="dwg-check-circle text-success mr-1"></i><span class="font-size-ms">{{ Helper::translation(4170,$translate) }}</span></li>

                                      @else

                                      <li class="d-flex align-items-center"><i class="dwg-close-circle text-danger mr-1"></i><span class="font-size-ms">{{ Helper::translation(4170,$translate) }}</span></li>

                                      @endif

                                      @if($item['view']->item_support == 1)

                                      <li class="d-flex align-items-center"><i class="dwg-check-circle text-success mr-1"></i><span class="font-size-ms">{{ Helper::translation(4173,$translate) }}</span></li>

                                      @else

                                      <li class="d-flex align-items-center"><i class="dwg-close-circle text-danger mr-1"></i><span class="font-size-ms">{{ Helper::translation(4173,$translate) }}</span></li>

                                      @endif

                                    </ul>

                                  </div>

                                </div> --}}

                            </div>

                            {{-- @if($item['view']->extended_price != 0)

                            <div class="card border-bottom-0 border-left-0 border-right-0">

                              <div class="card-header d-flex justify-content-between align-items-center py-3 border-0">

                                <div class="custom-control custom-radio">

                                  <input class="custom-control-input" type="radio" name="item_price" id="license-ext" value="{{ base64_encode($extend_item_price) }}_extended">

                                  <label class="custom-control-label font-weight-medium text-dark" for="license-ext" data-toggle="collapse" data-target="#extended-license">{{ Helper::translation(4176,$translate) }}</label>

                                </div>

                                <h5 class="mb-0 text-accent font-weight-normal">{{ $allsettings->site_currency_symbol }}{{ $extend_item_price }}</h5>

                              </div>

                              <div class="collapse" id="extended-license" data-parent="#licenses">

                                <div class="card-body py-0 pb-2">

                                  <ul class="list-unstyled font-size-sm">

                                    <li class="d-flex align-items-center"><i class="dwg-check-circle text-success mr-1"></i><span class="font-size-ms">{{ Helper::translation(4167,$translate) }} {{ $allsettings->site_title }}</span></li>

                                    @if($item['view']->future_update == 1)

                                    <li class="d-flex align-items-center"><i class="dwg-check-circle text-success mr-1"></i><span class="font-size-ms">{{ Helper::translation(4170,$translate) }}</span></li>

                                    @else

                                    <li class="d-flex align-items-center"><i class="dwg-close-circle text-danger mr-1"></i><span class="font-size-ms">{{ Helper::translation(4170,$translate) }}</span></li>

                                    @endif

                                    @if($item['view']->item_support == 1)

                                    <li class="d-flex align-items-center"><i class="dwg-check-circle text-success mr-1"></i><span class="font-size-ms">{{ Helper::translation(4179,$translate) }}</span></li>

                                    @else

                                    <li class="d-flex align-items-center"><i class="dwg-close-circle text-danger mr-1"></i><span class="font-size-ms">{{ Helper::translation(4179,$translate) }}</span></li>

                                    @endif

                                  </ul>

                                </div>

                              </div>

                            </div>

                            @endif --}}

                        </div>

                        <hr>

                        {{-- @if($allsettings->product_support_link !='')

                          <p class="mt-2 mb-3"><a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" class="font-size-xs">{{ $page['view']->page_title }}</a></p>

                          <div class="modal fade" id="myModal">

                            <div class="modal-dialog modal-xl">

                              <div class="modal-content">

                                  <div class="modal-header">

                                  <h4 class="modal-title">{{ $page['view']->page_title }}</h4>

                                  <button type="button" class="close" data-dismiss="modal">&times;</button>

                                </div>

                                <div class="modal-body">

                                  @php echo html_entity_decode($page['view']->page_desc); @endphp

                                </div>

                                </div>

                            </div>

                          </div>

                        @endif --}}

                        @if(Auth::guest())

                            <input type="hidden" name="product_id" value="{{ $item['view']->product_id }}">

                            <input type="hidden" name="product_name" value="{{ $item['view']->product_name }}">

                            <input type="hidden" name="product_user_id" value="{{ $item['view']->user_id }}">

                            <input type="hidden" name="product_token" value="{{ $item['view']->product_token }}">

                            @if($checkif_purchased == 0)

                                    <button type="submit" class="btn btn-primary btn-shadow btn-block mt-4"><i class="dwg-cart font-size-lg mr-2"></i>{{ Helper::translation(4182,$translate) }}
                                    </button>

                            @endif

                        @endif

                        @if (Auth::check())

                            @if($item['view']->user_id == Auth::user()->id)

                                <a href="{{ URL::to('/admin/edit-product') }}/{{ $item['view']->product_token }}" class="btn btn-primary btn-shadow btn-block mt-4"><i
                                        class="dwg-cart font-size-lg mr-2"></i>{{ Helper::translation(4185,$translate) }}</a>

                            @else

                                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

                                <input type="hidden" name="product_id" value="{{ $item['view']->product_id }}">

                                <input type="hidden" name="product_name" value="{{ $item['view']->product_name }}">

                                <input type="hidden" name="product_user_id" value="{{ $item['view']->user_id }}">

                                <input type="hidden" name="product_token" value="{{ $item['view']->product_token }}">

                                @if($checkif_purchased == 0)

                                    @if(Auth::user()->id != 1)

                                        <button type="submit" class="btn btn-primary btn-shadow btn-block mt-4"><i class="dwg-cart font-size-lg mr-2"></i>{{ Helper::translation(4182,$translate) }}
                                        </button>

                                    @endif

                                @endif

                            @endif

                        @endif

                        @php if($item['view']->product_sold == 0){ $sale_text = "Sale"; } else  { $sale_text = "Sales"; } @endphp

                        <div class="bg-secondary rounded p-3 mt-4 mb-2"><i class="dwg-download h5 text-muted align-middle mb-0 mt-n1 mr-2"></i><span
                                class="d-inline-block h6 mb-0 mr-1">{{ $item['view']->product_sold }}</span><span class="font-size-sm">{{ $sale_text }}</span></div>

                        <div class="bg-secondary rounded p-3 mb-2">

                            <div class="star-rating">

                                @if($getreview == 0)

                                    <i class="sr-star dwg-star"></i>

                                    <i class="sr-star dwg-star"></i>

                                    <i class="sr-star dwg-star"></i>

                                    <i class="sr-star dwg-star"></i>

                                    <i class="sr-star dwg-star"></i>

                                @else

                                    @if($count_rating == 0)

                                        <i class="sr-star dwg-star"></i>

                                        <i class="sr-star dwg-star"></i>

                                        <i class="sr-star dwg-star"></i>

                                        <i class="sr-star dwg-star"></i>

                                        <i class="sr-star dwg-star"></i>

                                    @endif

                                    @if($count_rating == 1)

                                        <i class="sr-star dwg-star-filled active"></i>

                                        <i class="sr-star dwg-star"></i>

                                        <i class="sr-star dwg-star"></i>

                                        <i class="sr-star dwg-star"></i>

                                        <i class="sr-star dwg-star"></i>

                                    @endif

                                    @if($count_rating == 2)

                                        <i class="sr-star dwg-star-filled active"></i>

                                        <i class="sr-star dwg-star-filled active"></i>

                                        <i class="sr-star dwg-star"></i>

                                        <i class="sr-star dwg-star"></i>

                                        <i class="sr-star dwg-star"></i>

                                    @endif

                                    @if($count_rating == 3)

                                        <i class="sr-star dwg-star-filled active"></i>

                                        <i class="sr-star dwg-star-filled active"></i>

                                        <i class="sr-star dwg-star-filled active"></i>

                                        <i class="sr-star dwg-star"></i>

                                        <i class="sr-star dwg-star"></i>

                                    @endif

                                    @if($count_rating == 4)

                                        <i class="sr-star dwg-star-filled active"></i>

                                        <i class="sr-star dwg-star-filled active"></i>

                                        <i class="sr-star dwg-star-filled active"></i>

                                        <i class="sr-star dwg-star-filled active"></i>

                                        <i class="sr-star dwg-star"></i>

                                    @endif

                                    @if($count_rating == 5)

                                        <i class="sr-star dwg-star-filled active"></i>

                                        <i class="sr-star dwg-star-filled active"></i>

                                        <i class="sr-star dwg-star-filled active"></i>

                                        <i class="sr-star dwg-star-filled active"></i>

                                        <i class="sr-star dwg-star-filled active"></i>

                                    @endif

                                @endif

                            </div>

                            <div class="font-size-ms text-muted">{{ $getreview }} {{ Helper::translation(4188,$translate) }}</div>

                        </div>

                        <div class="bg-secondary rounded p-3 mb-4"><i class="dwg-chat h5 text-muted align-middle mb-0 mt-n1 mr-2"></i><span
                                class="d-inline-block h6 mb-0 mr-1">{{ $comment_count }}</span><span class="font-size-sm">{{ Helper::translation(4191,$translate) }}</span></div>

                        <ul class="list-unstyled font-size-sm">

                            <li class="d-flex justify-content-between mb-3 pb-3 border-bottom"><span class="text-dark font-weight-medium">{{ Helper::translation(4194,$translate) }}</span><span
                                    class="text-muted">{{ date('d M Y', strtotime($item['view']->product_update)) }}</span></li>

                            <li class="d-flex justify-content-between mb-3 pb-3 border-bottom"><span class="text-dark font-weight-medium">Replacement</span><span
                                    class="text-muted">{{ $item['view']->replacement_days }} days</span></li>

                            <li class="d-flex justify-content-between mb-3 pb-3 border-bottom"><span class="text-dark font-weight-medium">{{ Helper::translation(4200,$translate) }}</span><a
                                    class="product-meta" href="{{ URL::to('/shop/category') }}/{{ $item['view']->category_slug }}">{{ $item['view']->category_name }}</a></li>

                            <li class="d-flex justify-content-between mb-3 pb-3 border-bottom"><span
                                    class="text-dark font-weight-medium title-width">{{ Helper::translation(4203,$translate) }}</span><span class="text-muted text-right">

                    @php $pack_info = ""; @endphp

                                    @foreach($package['view'] as $package)

                                        @php $checkpackage = explode(',',$item['view']->package_includes); @endphp

                                        @php if(in_array($package->package_id,$checkpackage)){ $pack_info .= $package->package_name.', '; } @endphp

                                    @endforeach

                                    {{ rtrim($pack_info,", ") }}

                    </span></li>

                            <li class="d-flex justify-content-between mb-3 pb-3 border-bottom"><span
                                    class="text-dark font-weight-medium title-width">{{ Helper::translation(4206,$translate) }}</span><span
                                    class="text-muted text-right">       @php $browse_info = ""; @endphp

                                    @foreach($browser['view'] as $package)

                                        @php $checkpackage = explode(',',$item['view']->compatible_browsers); @endphp

                                        @php if(in_array($package->browser_id,$checkpackage)){ $browse_info .= $package->browser_name.', '; } @endphp

                                    @endforeach

                                    {{ rtrim($browse_info,", ") }}

                    </span></li>

                            @if($item['view']->product_tags != '')

                                <li class="justify-content-between pb-3 border-bottom"><span class="text-dark font-weight-medium">{{ Helper::translation(4209,$translate) }}</span><br/>

                                    @php $item_tags = explode(',',$item['view']->product_tags); @endphp

                                    @foreach($item_tags as $tags)

                                        <span class="text-right"><a href="{{ url('/tag') }}/item/{{ strtolower(str_replace(' ','-',$tags)) }}" class="link-color">{{ $tags.',' }}</a></span>

                                    @endforeach

                                </li>

                            @endif

                        </ul>

                    </div>

                </form>

            </aside>

        </div>

    </div>

</section>

<section class="container mb-4 mb-lg-5">

    <!-- Nav tabs-->

    <ul class="nav nav-tabs" role="tablist">

        <li class="nav-item"><a class="nav-link p-4 active" href="#details" data-toggle="tab" role="tab">{{ Helper::translation(4212,$translate) }}</a></li>

        <li class="nav-item"><a class="nav-link p-4" href="#comments" data-toggle="tab" role="tab">{{ Helper::translation(4191,$translate) }}</a></li>

        <li class="nav-item"><a class="nav-link p-4" href="#reviews" data-toggle="tab" role="tab">{{ Helper::translation(4215,$translate) }}</a></li>

        <li class="nav-item"><a class="nav-link p-4" href="#suppport" data-toggle="tab" role="tab">{{ Helper::translation(4101,$translate) }}</a></li>

        <li class="nav-item"><a class="nav-link p-4" href="#faqs" data-toggle="tab" role="tab">FAQ</a></li>

    </ul>

    <div class="tab-content pt-2">
        <div class="tab-pane fade" id="suppport" role="tabpanel">
            <div class="row">
                <div class="col-lg-8">
                    <h4>{{ __('Contact the Author') }}</h4>
                    @if(Auth::guest())
                        <p>{{ Helper::translation(4554,$translate) }} <a href="{{ URL::to('/login') }}" class="link-color">{{ Helper::translation(4221,$translate) }}</a> {{ Helper::translation(4224,$translate) }}</p>
                    @endif

                    @if (Auth::check())

                        <form action="{{ route('support') }}" class="support_form media-body needs-validation" id="support_form" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="subj">{{ Helper::translation(4227,$translate) }}</label>
                                <input type="text" id="support_subject" name="support_subject" class="form-control" placeholder="{{ Helper::translation(4230,$translate) }}" data-bvalidator="required">
                            </div>

                            <div class="form-group">
                                <label for="supmsg">{{ Helper::translation(4005,$translate) }} </label>
                                <textarea class="form-control" id="support_msg" name="support_msg" rows="5" placeholder="{{ Helper::translation(4233,$translate) }}"
                                          data-bvalidator="required"></textarea></div>

                            <input type="hidden" name="to_address" value="{{ $item['view']->email }}">
                            <input type="hidden" name="to_name" value="{{ $item['view']->username }}">
                            <input type="hidden" name="from_address" value="{{ Auth::user()->email }}">
                            <input type="hidden" name="from_name" value="{{ Auth::user()->username }}">
                            <input type="hidden" name="item_url" value="{{ URL::to('/product') }}/{{ $item['view']->product_slug }}">

                            <button type="submit" class="btn btn-primary btn-sm">{{ Helper::translation(4236,$translate) }}</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Product details tab-->

        <div class="tab-pane fade show active" id="details" role="tabpanel">

            <div class="row">

                <div class="col-lg-8">

                    <p class="font-size-md mb-1">@php echo html_entity_decode($item['view']->product_desc); @endphp</p>

                </div>

            </div>

        </div>

        <!-- FAQ -->
        <div class="tab-pane fade hk-row" id="faqs" role="tabpanel">
            <div class="accordion accordion-type-2 accordion-flush" id="accordion_2">
                @foreach($product_faqs as $key => $faq)

                <div class="card">
                    @if($key == 0)
                     <div class="card">
                        <div class="card-header d-flex justify-content-between activestate">
                            <a role="button" data-toggle="collapse" href="#collapse_{{$key+1}}i" aria-expanded="true">{{$faq->faq_que}}</a>
                        </div>
                        <div id="collapse_{{$key+1}}i" class="collapse show" data-parent="#accordion_2" role="tabpane{{$key+1}}">
                            <div class="card-body pa-15">{{$faq->faq_ans}}</div>
                        </div>
                    </div>
                    @else
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <a class="collapsed" role="button" data-toggle="collapse" href="#collapse_{{$key+1}}i" aria-expanded="false">{{$faq->faq_que}}</a>
                        </div>
                        <div id="collapse_{{$key+1}}i" class="collapse" data-parent="#accordion_2">
                            <div class="card-body pa-15">{{$faq->faq_ans}}</div>
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
               
            </div>
        </div>
        <!-- End FAQ -->

        <!-- Reviews tab-->

        <div class="tab-pane fade" id="reviews" role="tabpanel">

            @if($review_count != 0)

                <div class="row pb-4">

                    <!-- Reviews list-->

                    <div class="col-md-7">

                        <!-- Review-->

                        @foreach($getreviewdata['view'] as $rating)

                            <div class="product-review pb-4 mb-4 border-bottom review-item">

                                <div class="d-flex mb-3">

                                    <div class="media media-ie-fix align-items-center mr-4 pr-2">

                                        @if($rating->user_photo!='')

                                            <img class="rounded-circle" width="50" src="{{ url('/') }}/public/storage/users/{{ $rating->user_photo }}" alt="{{ $rating->username }}"/>

                                        @else

                                            <img class="rounded-circle" width="50" src="{{ url('/') }}/public/img/no-user.png" alt="{{ $rating->username }}"/>

                                        @endif

                                        <div class="media-body pl-3">

                                            <h6 class="font-size-sm mb-0">{{ $rating->username }}</h6><span
                                                class="font-size-ms text-muted">{{ date('d F Y H:i:s', strtotime($rating->rating_date)) }}</span></div>

                                    </div>

                                    <div>

                                        <div class="star-rating">

                                            @if($rating->rating == 0)

                                                <i class="sr-star dwg-star"></i>

                                                <i class="sr-star dwg-star"></i>

                                                <i class="sr-star dwg-star"></i>

                                                <i class="sr-star dwg-star"></i>

                                                <i class="sr-star dwg-star"></i>

                                            @endif

                                            @if($rating->rating == 1)

                                                <i class="sr-star dwg-star-filled active"></i>

                                                <i class="sr-star dwg-star"></i>

                                                <i class="sr-star dwg-star"></i>

                                                <i class="sr-star dwg-star"></i>

                                                <i class="sr-star dwg-star"></i>

                                            @endif

                                            @if($rating->rating == 2)

                                                <i class="sr-star dwg-star-filled active"></i>

                                                <i class="sr-star dwg-star-filled active"></i>

                                                <i class="sr-star dwg-star"></i>

                                                <i class="sr-star dwg-star"></i>

                                                <i class="sr-star dwg-star"></i>

                                            @endif

                                            @if($rating->rating == 3)

                                                <i class="sr-star dwg-star-filled active"></i>

                                                <i class="sr-star dwg-star-filled active"></i>

                                                <i class="sr-star dwg-star-filled active"></i>

                                                <i class="sr-star dwg-star"></i>

                                                <i class="sr-star dwg-star"></i>

                                            @endif

                                            @if($rating->rating == 4)

                                                <i class="sr-star dwg-star-filled active"></i>

                                                <i class="sr-star dwg-star-filled active"></i>

                                                <i class="sr-star dwg-star-filled active"></i>

                                                <i class="sr-star dwg-star-filled active"></i>

                                                <i class="sr-star dwg-star"></i>

                                            @endif

                                            @if($rating->rating == 5)

                                                <i class="sr-star dwg-star-filled active"></i>

                                                <i class="sr-star dwg-star-filled active"></i>

                                                <i class="sr-star dwg-star-filled active"></i>

                                                <i class="sr-star dwg-star-filled active"></i>

                                                <i class="sr-star dwg-star-filled active"></i>

                                            @endif

                                        </div>

                                        <div class="review_tag">{{ $rating->rating_reason }}</div>

                                    </div>

                                </div>

                                <p class="font-size-md mb-2">{{ $rating->rating_comment }}</p>

                            </div>

                        @endforeach

                        <div class="float-right">

                            <div class="pagination-area">

                                <div class="turn-page" id="reviewpager"></div>

                            </div>

                        </div>

                    </div>

                    <!-- Leave review form-->

                </div>

            @endif

        </div>

        <!-- Comments tab-->

        <div class="tab-pane fade" id="comments" role="tabpanel">

            <div class="row thread">

                <div class="col-lg-8">

                    <div class="media-list thread-list" id="listShow">

                        @foreach ($comment['view'] as $parent)

                            <div class="single-thread commli-item">

                                <div class="media">

                                    <div class="media-left">

                                        @if($parent->user_photo!='')

                                            <img src="{{ url('/') }}/public/storage/users/{{ $parent->user_photo }}" alt="{{ $parent->username }}" class="rounded-circle"
                                                 width="50">                                                    @else

                                            <img src="{{ url('/') }}/public/img/no-user.png" alt="{{ $parent->username }}" class="rounded-circle" width="50">

                                        @endif

                                    </div>

                                    <div class="media-body">

                                        <div>

                                            <div class="media-heading">

                                                <h6 class="font-size-md mb-0">{{ $parent->username }}</h6>

                                            </div>

                                            @if($parent->id == $item['view']->user_id)

                                                <span class="comment-tag buyer">{{ Helper::translation(4239,$translate) }}</span>

                                            @endif

                                            @if (Auth::check())

                                                @if($item['view']->user_id == Auth::user()->id)

                                                    <a href="javascript:void(0);" class="nav-link-style font-size-sm font-weight-medium reply-link"><i class="dwg-reply mr-2">

                                                        </i>{{ Helper::translation(4242,$translate) }}</a>

                                                @endif

                                            @endif

                                        </div>

                                        <p class="font-size-md mb-1">{{ $parent->comm_text }}</p>

                                        <span class="font-size-ms text-muted"><i class="dwg-time align-middle mr-2"></i>{{ date('d F Y, H:i:s', strtotime($parent->comm_date)) }}</span>

                                    </div>

                                </div>

                                <div class="children">

                                    @foreach ($parent->replycomment as $child)

                                        <div class="single-thread depth-2">

                                            <div class="media">

                                                <div class="media-left">

                                                    @if($child->user_photo!='')

                                                        <img src="{{ url('/') }}/public/storage/users/{{ $child->user_photo }}" alt="{{ $child->username }}" class="rounded-circle"
                                                             width="50">                                         @else

                                                        <img src="{{ url('/') }}/public/img/no-user.png" alt="{{ $child->username }}" class="rounded-circle" width="50">

                                                    @endif

                                                </div>

                                                <div class="media-body">

                                                    <div class="media-heading">

                                                        <h6 class="font-size-md mb-0">{{ $child->username }}</h6>

                                                    </div>

                                                    @if($child->id == $item['view']->user_id)

                                                        <span class="comment-tag buyer">{{ Helper::translation(4239,$translate) }}</span>

                                                    @endif

                                                    <p class="font-size-md mb-1">{{ $child->comm_text }}</p>

                                                    <span class="font-size-ms text-muted"><i class="dwg-time align-middle mr-2"></i>{{ date('d F Y, H:i:s', strtotime($child->comm_date)) }}</span>
                                                </div>

                                            </div>

                                        </div>

                                    @endforeach

                                </div>

                                <!-- comment reply -->

                                @if (Auth::check())

                                    <div class="media depth-2 reply-comment">

                                        <div class="media-left">

                                            @if(Auth::user()->user_photo!='')

                                                <img src="{{ url('/') }}/public/storage/users/{{ Auth::user()->user_photo }}" alt="{{ Auth::user()->username }}" class="rounded-circle"
                                                     width="50">                             @else

                                                <img src="{{ url('/') }}/public/img/no-user.png" alt="{{ Auth::user()->username }}" class="rounded-circle" width="50">

                                            @endif

                                        </div>

                                        <div class="media-body">

                                            <form action="{{ route('reply-post-comment') }}" class="comment-reply-form media-body needs-validation" method="post"
                                                  enctype="multipart/form-data">                              {{ csrf_field() }}

                                                <textarea name="comm_text" class="form-control" placeholder="{{ Helper::translation(4245,$translate) }}" required></textarea>

                                                <input type="hidden" name="comm_user_id" value="{{ Auth::user()->id }}">

                                                <input type="hidden" name="comm_product_user_id" value="{{ $item['view']->user_id }}">

                                                <input type="hidden" name="comm_product_id" value="{{ $item['view']->product_id }}">

                                                <input type="hidden" name="comm_id" value="{{ $parent->comm_id }}">

                                                <input type="hidden" name="comm_product_url" value="{{ URL::to('/product') }}/{{ $item['view']->product_slug }}">

                                                <button class="btn btn-primary btn-sm">{{ Helper::translation(4248,$translate) }}</button>

                                            </form>

                                        </div>

                                    </div>

                            @endif

                            <!-- comment reply -->

                            </div>

                        @endforeach

                    </div>

                    @if($comment_count != 0)

                        <div class="float-right">

                            <div class="pagination-area">

                                <div class="turn-page" id="commpager"></div>

                            </div>

                        </div>

                    @endif

                    <div class="clearfix"></div>

                    @if (Auth::check())
                        @if($item['view']->user_id != Auth::user()->id)
                            <div class="card border-0 box-shadow my-2">
                                <h4 class="mt-4 ml-4">{{ Helper::translation(4251,$translate) }}</h4>
                                <div class="card-body">
                                    <div class="media">

                                        @if(Auth::user()->user_photo != '')
                                            <img class="rounded-circle" width="50" src="{{ url('/') }}/public/storage/users/{{ Auth::user()->user_photo }}" alt="{{ Auth::user()->name }}"/>
                                        @else
                                            <img class="rounded-circle" width="50" src="{{ url('/') }}/public/img/no-user.png" alt="{{ Auth::user()->name }}"/>
                                        @endif
                                        <form action="{{ route('post-comment') }}" class="comment-reply-form media-body needs-validation ml-3" id="item_form" method="post"
                                              enctype="multipart/form-data">
                                            {{ csrf_field() }}

                                            <div class="form-group">
                                                <textarea class="form-control" rows="4" name="comm_text" placeholder="{{ Helper::translation(4254,$translate) }}" data-bvalidator="required"></textarea>
                                                <input type="hidden" name="comm_user_id" value="{{ Auth::user()->id }}">
                                                <input type="hidden" name="comm_product_user_id" value="{{ $item['view']->user_id }}">
                                                <input type="hidden" name="comm_product_id" value="{{ $item['view']->product_id }}">
                                                <input type="hidden" name="comm_product_url" value="{{ URL::to('/product') }}/{{ $item['view']->product_slug }}">
                                                <div class="invalid-feedback">{{ Helper::translation(4257,$translate) }}</div>
                                            </div>
                                            <button class="btn btn-primary btn-sm" type="submit">{{ Helper::translation(4248,$translate) }}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

</section>

<section class="container mb-5 pb-lg-3">

    <div class="d-flex flex-wrap justify-content-between align-items-center border-bottom pb-4 mb-4">

        <h2 class="h3 mb-0 pt-2">{{ Helper::translation(4260,$translate) }}</h2>

    </div>

    <div class="row pt-2 mx-n2">

        <!-- Product-->

        @php $no = 1; @endphp

        @foreach($related['product'] as $featured)

            @php

                $price = Helper::price_info($featured->product_flash_sale,$featured->regular_price,$featured->product_flash_sale_percentage);

                $count_rating = Helper::count_rating($featured->ratings);

            @endphp

            <div class="col-lg-3 col-md-4 col-sm-6 px-2 mb-grid-gutter prod-item">

                <!-- Product-->

                <div class="card product-card-alt">

                    <div class="product-thumb">

                        @if(Auth::guest())

                            <a class="btn-wishlist btn-sm" href="{{ URL::to('/login') }}"><i class="dwg-heart"></i></a>

                        @endif

                        @if (Auth::check())

                            @if($featured->user_id != Auth::user()->id)

                                <a class="btn-wishlist btn-sm" href="{{ url('/item') }}/{{ base64_encode($featured->product_id) }}/favorite/{{ base64_encode($featured->product_liked) }}"><i
                                        class="dwg-heart"></i></a>

                            @endif

                        @endif

                        <div class="product-card-actions"><a class="btn btn-light btn-icon btn-shadow font-size-base mx-2" href="{{ URL::to('/product') }}/{{ $featured->product_slug }}"><i
                                    class="dwg-eye"></i></a>

                            <a class="btn btn-light btn-icon btn-shadow font-size-base mx-2" href="{{ URL::to('/product') }}/{{ $featured->product_slug }}"><i class="dwg-cart"></i></a>

                        </div>
                        <a class="product-thumb-overlay" href="{{ URL::to('/product') }}/{{ $featured->product_slug }}"></a>

                        @if($featured->product_image!='')

                            <img src="{{ url('/') }}/public/storage/product/{{ $featured->product_image }}" alt="{{ $featured->product_name }}">

                        @else

                            <img src="{{ url('/') }}/public/img/no-image.png" alt="{{ $featured->product_name }}">

                        @endif

                    </div>

                    <div class="card-body">

                        <div class="d-flex flex-wrap justify-content-between align-items-start pb-2">

                            <div class="text-muted font-size-xs mr-1"><a class="product-meta font-weight-medium"
                                                                         href="{{ URL::to('/shop') }}/category/{{ $featured->category_slug }}">{{ $featured->category_name }}</a></div>

                            <div class="star-rating">

                                @if($count_rating == 0)

                                    <i class="sr-star dwg-star"></i>

                                    <i class="sr-star dwg-star"></i>

                                    <i class="sr-star dwg-star"></i>

                                    <i class="sr-star dwg-star"></i>

                                    <i class="sr-star dwg-star"></i>

                                @endif

                                @if($count_rating == 1)

                                    <i class="sr-star dwg-star-filled active"></i>

                                    <i class="sr-star dwg-star"></i>

                                    <i class="sr-star dwg-star"></i>

                                    <i class="sr-star dwg-star"></i>

                                    <i class="sr-star dwg-star"></i>

                                @endif

                                @if($count_rating == 2)

                                    <i class="sr-star dwg-star-filled active"></i>

                                    <i class="sr-star dwg-star-filled active"></i>

                                    <i class="sr-star dwg-star"></i>

                                    <i class="sr-star dwg-star"></i>

                                    <i class="sr-star dwg-star"></i>

                                @endif

                                @if($count_rating == 3)

                                    <i class="sr-star dwg-star-filled active"></i>

                                    <i class="sr-star dwg-star-filled active"></i>

                                    <i class="sr-star dwg-star-filled active"></i>

                                    <i class="sr-star dwg-star"></i>

                                    <i class="sr-star dwg-star"></i>

                                @endif

                                @if($count_rating == 4)

                                    <i class="sr-star dwg-star-filled active"></i>

                                    <i class="sr-star dwg-star-filled active"></i>

                                    <i class="sr-star dwg-star-filled active"></i>

                                    <i class="sr-star dwg-star-filled active"></i>

                                    <i class="sr-star dwg-star"></i>

                                @endif

                                @if($count_rating == 5)

                                    <i class="sr-star dwg-star-filled active"></i>

                                    <i class="sr-star dwg-star-filled active"></i>

                                    <i class="sr-star dwg-star-filled active"></i>

                                    <i class="sr-star dwg-star-filled active"></i>

                                    <i class="sr-star dwg-star-filled active"></i>

                                @endif

                            </div>

                        </div>

                        <h3 class="product-title font-size-sm mb-2"><a href="{{ URL::to('/product') }}/{{ $featured->product_slug }}">{{ $featured->product_name }}</a></h3>

                        <div class="d-flex flex-wrap justify-content-between align-items-center">

                            <div class="font-size-sm mr-2"><i class="dwg-download text-muted mr-1"></i>{{ $featured->product_sold }}<span
                                    class="font-size-xs ml-1">{{ Helper::translation(4050,$translate) }}</span>

                            </div>

                            <div>@if($featured->product_flash_sale == 1)
                                    <del class="price-old">{{ $allsettings->site_currency_symbol }}{{ $featured->regular_price }}</del>@endif <span
                                    class="bg-faded-accent text-accent rounded-sm py-1 px-2">{{ $allsettings->site_currency_symbol }}{{ $price }}</span></div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- Product-->

            @php $no++; @endphp

        @endforeach

    </div>

</section>

@include('footer')

@include('script')

</body>
<script>
    $(document).ready(function(){
        var main_price = $('#product_text_price').text();
        var e_main_price = $('.main_encoded_price').val();
        $('#product_package').change(function(){
            var id_price = $(this).val();
            if(id_price){
                var data = id_price.split(",");
                var price = data[1];
                var e_price  = data[2];
                $('.main_encoded_price').val(e_price+'_regular');
                $('#product_text_price').text(price);

            }else{
                $('#product_text_price').val(e_main_price);
                $('#product_text_price').text(main_price)

            }
             
        });
    });
</script>

</html>
