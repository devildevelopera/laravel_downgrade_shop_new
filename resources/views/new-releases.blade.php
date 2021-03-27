<!DOCTYPE HTML>

<html lang="en">

<head>

<title>{{ Helper::translation(4119,$translate) }} - {{ $allsettings->site_title }}</title>

@include('meta')

@include('style')

</head>

<body>

@include('header')

<section class="bg-position-center-top" style="background-image: url('{{ url('/') }}/public/storage/settings/{{ $allsettings->site_banner }}');">

      <div class="py-4">

        <div class="container d-lg-flex justify-content-between py-2 py-lg-3">

        <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">

          <nav aria-label="breadcrumb">

            <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">

              <li class="breadcrumb-item"><a class="text-nowrap" href="{{ URL::to('/') }}"><i class="dwg-home"></i>{{ Helper::translation(3849,$translate) }}</a></li>

              <li class="breadcrumb($featured->product_flash_sale,$featured->regular_price,$featured->product_flash_sale_percentage):translation(4119,$translate) }}</li>

            </ol>

          </nav>

        </div>

        <div class="order-lg-1 pr-lg-4 text-center text-lg-left">

          <h1 class="h3 mb-0 text-white">{{ Helper::translation(4119,$translate) }}</h1>

        </div>

      </div>

      </div>

    </section>

<div class="container py-5 mt-md-2 mb-2">

      <div class="row pt-2 mx-n2">

        <!-- Product-->

        @php $no = 1; @endphp

        @foreach($newest['product'] as $featured)

        @php

        $price = Helper::price_info($featured->product_flash_sale,$featured->regular_price);

        $count_rating = Helper::count_rating($featured->ratings);

        @endphp

        <div class="col-lg-3 col-md-4 col-sm-6 px-2 mb-grid-gutter prod-item" data-aos="fade-up" data-aos-delay="200">

          <!-- Product-->

          <div class="card product-card-alt">

            <div class="product-thumb">

              @if(Auth::guest()) 

              <a class="btn-wishlist btn-sm" href="{{ URL::to('/login') }}"><i class="dwg-heart"></i></a>

              @endif

              @if (Auth::check())

              @if($featured->user_id != Auth::user()->id)

              <a class="btn-wishlist btn-sm" href="{{ url('/item') }}/{{ base64_encode($featured->product_id) }}/favorite/{{ base64_encode($featured->product_liked) }}"><i class="dwg-heart"></i></a>

              @endif

              @endif

              <div class="product-card-actions"><a class="btn btn-light btn-icon btn-shadow font-size-base mx-2" href="{{ URL::to('/product') }}/{{ $featured->product_slug }}"><i class="dwg-eye"></i></a>

                <a class="btn btn-light btn-icon btn-shadow font-size-base mx-2" href="{{ URL::to('/product') }}/{{ $featured->product_slug }}"><i class="dwg-cart"></i></a>

              </div><a class="product-thumb-overlay" href="{{ URL::to('/product') }}/{{ $featured->product_slug }}"></a>

              @if($featured->product_image!='')

              <img src="{{ url('/') }}/public/storage/product/{{ $featured->product_image }}" alt="{{ $featured->product_name }}">

              @else

              <img src="{{ url('/') }}/public/img/no-image.png" alt="{{ $featured->product_name }}">

              @endif

            </div>

            <div class="card-body">

              <div class="d-flex flex-wrap justify-content-between align-items-start pb-2">

                <div class="text-muted font-size-xs mr-1"><a class="product-meta font-weight-medium" href="{{ URL::to('/shop') }}/category/{{ $featured->category_slug }}">{{ $featured->category_name }}</a></div>

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

                <div class="font-size-sm mr-2"><i class="dwg-download text-muted mr-1"></i>{{ $featured->product_sold }}<span class="font-size-xs ml-1">{{ Helper::translation(4050,$translate) }}</span>

                </div>

                <div>@if($featured->product_flash_sale == 1)<del class="price-old">{{ $allsettings->site_currency_symbol }}{{ $featured->regular_price }}</del>@endif <span class="bg-faded-accent text-accent rounded-sm py-1 px-2">{{ $allsettings->site_currency_symbol }}{{ $price }}</span></div>

              </div>

            </div>

          </div>

        </div>

        <!-- Product-->

        @php $no++; @endphp

	    @endforeach

       </div>

       <div class="row mb-3">

       <div class="col-md-12  text-right">

            <div class="turn-page" id="itempager"></div>

       </div>         

       </div>

    </div>

@include('footer')

@include('script')

</body>

</html>