<!DOCTYPE HTML>
<html lang="en">
<head>
<title>{{ Helper::translation(4434,$translate) }} - {{ $allsettings->site_title }}</title>
@include('meta')
@include('style')
</head>
<body>
@include('header')
<section class="bg-position-center-top" style="background-image: url('{{ url('/') }}/public/storage/settings/{{ $allsettings->site_banner }}');">
      <div class="container d-flex flex-column">
        <div class="row mt-auto">
        <div class="col-lg-8 col-sm-12 text-center mx-auto">
        <h2 class="mb-4 pt-5 title-page text-white">{{ Helper::translation(4434,$translate) }}</h2>
        <h3 class="lead mb-5 text-white">{{ Helper::translation(4437,$translate) }}</h3>
    </div>
</div>
<div class="row">
    <div class="col-lg-7 col-md-7 col-sm-12 mx-auto text-center mb-3 pb-3">
        <div class="countdown-timer">
                        <ul id="example">
                        <li class="pt-2 pb-1 mb-2"><span class="days">00</span><div>{{ Helper::translation(4440,$translate) }}</div></li>
                        <li class="pt-2 pb-1 mb-2"><span class="hours">00</span><div>{{ Helper::translation(4443,$translate) }}</div></li>
                        <li class="pt-2 pb-1 mb-2"><span class="minutes">00</span><div>{{ Helper::translation(4446,$translate) }}</div></li>
		                <li class="pt-2 pb-1 mb-2"><span class="seconds">00</span><div>{{ Helper::translation(4449,$translate) }}</div></li>
                    </ul>
               </div>
        </div>
    </div> 
</div>
</section>
<div class="container py-5 mt-md-2 mb-2 flash-sale">
      <div class="row pt-2 mx-n2">
        <!-- Product-->
        @php $no = 1; @endphp
        @foreach($flash['product'] as $featured)
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
                <div>@if($featured->product_flash_sale == 1)<del class="price-old">{{ $allsettings->site_currency_symbol }}{{ $featured->regular_price }}</del>@endif <span class="price-badge rounded-sm py-1 px-2">{{ $allsettings->site_currency_symbol }}{{ $price }}</span></div>
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
@if(!empty($allsettings->site_flash_end_date))
	<script type="text/javascript">
            $('#example').countdown({
                date: '{{ date("m/d/Y H:i:s", strtotime($allsettings->site_flash_end_date)) }}',
                offset: -8,
                day: "{{ Helper::translation(4452,$translate) }}",
                days: "{{ Helper::translation(4440,$translate) }}"
            }, function () {
                
            });
    </script>
    @endif
</body>
</html>