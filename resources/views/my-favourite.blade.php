<!DOCTYPE HTML>

<html lang="en">

<head>

<title>{{ Helper::translation(4038,$translate) }} - {{ $allsettings->site_title }}</title>

@include('meta')

@include('style')

</head>

<body>

@include('header')

<div class="page-title-overlap pt-4" style="background-image: url('{{ url('/') }}/public/storage/settings/{{ $allsettings->site_banner }}');">

      <div class="container d-lg-flex justify-content-between py-2 py-lg-3">

        <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">

          <nav aria-label="breadcrumb">

            <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">

              <li class="breadcrumb-item"><a class="text-nowrap" href="{{ URL::to('/') }}"><i class="dwg-home"></i>{{ Helper::translation(3849,$translate) }}</a></li>

              <li class="breadcrumb-item text-nowrap active" aria-current="page">{{ Helper::translation(4038,$translate) }}</li>

              </li>

             </ol>

          </nav>

        </div>

        <div class="order-lg-1 pr-lg-4 text-center text-lg-left">

          <h1 class="h3 mb-0 text-white">{{ Helper::translation(4038,$translate) }}</h1>

        </div>($featured->product_flash_sale,$featured->regular_price,$featured->product_flash_sale_percentage)

      </div>

    </div>

<div class="container mb-5 pb-3">

      <div class="bg-light box-shadow-lg rounded-lg overflow-hidden">

        <div class="row">

          <!-- Sidebar-->

          <aside class="col-lg-4">

            <!-- Account menu toggler (hidden on screens larger 992px)-->

            <div class="d-block d-lg-none p-4">

            <a class="btn btn-outline-accent d-block" href="#account-menu" data-toggle="collapse"><i class="dwg-menu mr-2"></i>{{ Helper::translation(4263,$translate) }}</a></div>

            <!-- Actual menu-->

            @include('dashboard-menu')

          </aside>

          <!-- Content-->

          <section class="col-lg-8 pt-lg-4 pb-4 mb-3">

            <div class="pt-2 px-4 pl-lg-0 pr-xl-5">

              <h2 class="h3 pt-2 pb-4 mb-0 text-center text-sm-left border-bottom">{{ Helper::translation(4038,$translate) }}<span class="badge badge-secondary font-size-sm text-body align-middle ml-2">{{ count($fav['product']) }}</span></h2>

              <!-- Product-->

                @php $no = 1; @endphp

                @foreach($fav['product'] as $featured)

                @php

                $price = Helper::price_info($featured->product_flash_sale,$featured->regular_price);

                @endphp

              <div class="media d-block d-sm-flex align-items-center py-4 border-bottom">

              <a class="d-block position-relative mb-3 mb-sm-0 mr-sm-4 mx-auto cart-img" href="{{ url('/my-favourite') }}/{{ base64_encode($featured->fav_id) }}/{{ base64_encode($featured->product_id) }}" onClick="return confirm('{{ Helper::translation(4557,$translate).'?' }}');">

              @if($featured->product_image!='')

              <img class="rounded-lg" src="{{ url('/') }}/public/storage/product/{{ $featured->product_image }}" alt="{{ $featured->product_name }}">

              @else

              <img class="rounded-lg" src="{{ url('/') }}/public/img/no-image.png" alt="{{ $featured->product_name }}">

              @endif

              <span class="close-floating" data-toggle="tooltip" title="Remove from Favorites"><i class="dwg-close"></i></span></a>

                <div class="media-body text-center text-sm-left">

                  <h3 class="h6 product-title mb-2"><a href="{{ URL::to('/product') }}/{{ $featured->product_slug }}">{{ $featured->product_name }}</a></h3>

                  <div class="d-inline-block text-accent">{{ $allsettings->site_currency_symbol }}{{ $price }}</div><a class="d-inline-block text-accent font-size-ms border-left ml-2 pl-2" href="{{ URL::to('/shop') }}/category/{{ $featured->category_slug }}">{{ $featured->category_name }}</a>

                  <div class="form-inline pt-2">

                    {{ substr($featured->product_short_desc,0,60).'...' }}

                    <a class="btn btn-primary btn-sm mx-auto mx-sm-0 my-2" href="{{ URL::to('/product') }}/{{ $featured->product_slug }}"><i class="dwg-cart mr-1"></i>{{ Helper::translation(4266,$translate) }}</a></div>

                </div>

              </div>

              @php $no++; @endphp

              @endforeach

            </div>

          </section>

        </div>

      </div>

    </div>

@include('footer')

@include('script')

</body>

</html>