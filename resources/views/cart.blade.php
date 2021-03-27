<?php 
use DownGrade\Models\Product;
?>

<!DOCTYPE HTML>

<html lang="en">

<head>

<title>{{ Helper::translation(3885,$translate) }} - {{ $allsettings->site_title }}</title>

@include('meta')

@include('style')

</head>

<body>

@include('header')

@if($cart_count != 0) 

<div class="page-title-overlap pt-4" style="background-image: url('{{ url('/') }}/public/storage/settings/{{ $allsettings->site_banner }}');">

      <div class="container d-lg-flex justify-content-between py-2 py-lg-3">

        <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">

          <nav aria-label="breadcrumb">

            <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">

              <li class="breadcrumb-item"><a class="text-nowrap" href="{{ URL::to('/') }}"><i class="dwg-home"></i>{{ Helper::translation(3849,$translate) }}</a></li>

              <li class="breadcrumb-item text-nowrap active" aria-current="page">{{ Helper::translation(3885,$translate) }}</li>

              </li>

             </ol>

          </nav>

        </div>

        <div class="order-lg-1 pr-lg-4 text-center text-lg-left">

          <h1 class="h3 mb-0 text-white">{{ Helper::translation(3885,$translate) }}</h1>

        </div>

      </div>

    </div>

<div class="container mb-5 pb-3">

      <div class="bg-light box-shadow-lg rounded-lg overflow-hidden">

        <div class="row">

          <!-- Content-->

          <section class="col-lg-8 pt-2 pt-lg-4 pb-4 mb-3">

            <div class="pt-2 px-4 pr-lg-0 pl-xl-5">

              <!-- Header-->

              <div class="d-flex flex-wrap justify-content-between align-items-center border-bottom pb-3">

                <div class="py-1"><a class="btn btn-outline-accent btn-sm" href="{{ url('/shop') }}"><i class="dwg-arrow-left mr-1 ml-n1"></i>{{ Helper::translation(3888,$translate) }}</a></div>

                <div class="d-none d-sm-block py-1 font-size-ms">{{ Helper::translation(3891,$translate) }} {{ $cart_count }} {{ Helper::translation(3894,$translate) }}</div>

                @if(Auth::check())
                
                <div class="py-1"><a class="btn btn-outline-danger btn-sm" href="{{ url('/clear-cart') }}" onClick="return confirm('{{ Helper::translation(3897,$translate) }}');"><i class="dwg-close font-size-xs mr-1 ml-n1"></i>{{ Helper::translation(3900,$translate) }}</a></div>
                
                @else

                <div class="py-1"><a class="btn btn-outline-danger btn-sm" href="{{ url('/clear-guest-cart') }}" onClick="return confirm('{{ Helper::translation(3897,$translate) }}');"><i class="dwg-close font-size-xs mr-1 ml-n1"></i>{{ Helper::translation(3900,$translate) }}</a></div>

                @endif

              </div>

              <!-- Product-->

              @php $subtotal = 0; @endphp

              @foreach($cart['item'] as $cart)

              <div class="media d-block d-sm-flex align-items-center py-4 border-bottom">

              @if(Auth::check())

              <a class="d-block position-relative mb-3 mb-sm-0 mr-sm-4 mx-auto cart-img" href="{{ url('/cart') }}/{{ base64_encode($cart->ord_id) }}" onClick="return confirm('{{ Helper::translation(3903,$translate) }}');">

              @else

              <a class="d-block position-relative mb-3 mb-sm-0 mr-sm-4 mx-auto cart-img" href="{{ url('/guest-cart') }}/{{ base64_encode($cart->product_id) }}" onClick="return confirm('{{ Helper::translation(3903,$translate) }}');">

              @endif

              @if($cart->product_image!='')

              <img class="rounded-lg" src="{{ url('/') }}/public/storage/product/{{ $cart->product_image }}" alt="{{ $cart->product_name }}">

              @else

              <img src="{{ url('/') }}/public/img/no-image.png" alt="{{ $cart->product_name }}" width="70" class="rounded-lg">

              @endif

              <span class="close-floating" data-toggle="tooltip" title="Remove from Cart"><i class="dwg-close"></i></span></a>

                <div class="media-body text-center text-sm-left">

                  <h3 class="h6 product-title mb-2"><a href="{{ url('/product') }}/{{ $cart->product_slug }}">{{ $cart->product_name }}

                  @if(Auth::check())
                    @if($cart->package_id)
                        - Package ({{Product::getproductsinglePackagename($cart->package_id)}})
                    @endif
                  @else
                    @if($cart->package_name)
                        - Package ({{$cart->package_name}})
                    @endif
                  @endif

                  </a></h3>
                  
                  <?php
                  if(Auth::check()) {
                    $item_price= $cart->product_price;
                  } else {
                    if($cart->package_name) {
                      if($cart->product_flash_sale) {
                        $item_price = round($cart->package_price - ($cart->package_price * $cart->product_flash_sale_percentage / 100));
                      } else {
                        $item_price = $cart->package_price;
                      }
                    } else {
                      $item_price = $cart->regular_price;
                    }
                  }
                ?>

                  <div class="d-inline-block text-accent">{{ $allsettings->site_currency_symbol }} {{ $item_price }}</div>
                  
                  @if(Auth::check())
                  
                  <a class="d-inline-block text-accent font-size-ms border-left ml-2 pl-2" href="{{ url('/product') }}/{{ $cart->product_slug }}">{{ $cart->license }}@if($cart->license == 'regular') ({{ __('6 months') }}) @elseif($cart->license == 'extended') ({{ __('12 months') }}) @endif</a>
                  
                  @endif

                </div>

              </div>

              @php $subtotal += $item_price; @endphp

              @endforeach

            </div>

          </section>

          @php $total_price = $subtotal + $allsettings->site_extra_fee; @endphp

          <!-- Sidebar-->

          <aside class="col-lg-4">

            <hr class="d-lg-none">

            <div class="cz-sidebar-static h-100 ml-auto border-left">

              <ul class="list-unstyled font-size-sm pt-3 pb-2 border-bottom">

                  <li class="d-flex justify-content-between align-items-center"><span class="mr-2">{{ Helper::translation(3906,$translate) }}</span><span class="text-right">{{ $allsettings->site_currency_symbol }} {{ $subtotal }}</span></li>

                  @if($allsettings->site_extra_fee != 0)

                  <li class="d-flex justify-content-between align-items-center"><span class="mr-2">{{ Helper::translation(3909,$translate) }}</span><span class="text-right">{{ $allsettings->site_currency_symbol }} {{ $allsettings->site_extra_fee }}</span></li>

                  @endif

                </ul>

              <div class="text-center mb-4 pb-3 border-bottom">

                <h2 class="h6 mb-3 pb-1">{{ Helper::translation(3912,$translate) }}</h2>

                <h3 class="font-weight-normal">{{ $allsettings->site_currency_symbol }} {{ $total_price }}</h3>

              </div>

              <?php /*?><div class="text-center mb-4 pb-3 border-bottom">

                <h2 class="h6 mb-3 pb-1">Promo code</h2>

                <form class="needs-validation pb-2" method="post" novalidate>

                  <div class="form-group">

                    <input class="form-control" type="text" placeholder="Promo code" required>

                    <div class="invalid-feedback">Please provide promo code.</div>

                  </div>

                  <button class="btn btn-secondary btn-block" type="submit">Apply promo code</button>

                </form>

              </div><?php */?>
              @if (Auth::check())

              <a class="btn btn-primary btn-shadow btn-block mt-4" href="{{ url('/checkout') }}"><i class="dwg-locked font-size-lg mr-2"></i>{{ Helper::translation(3915,$translate) }}</a>
              
              @else

              <a class="btn btn-primary btn-shadow btn-block mt-4" href="{{ url('/login') }}"><i class="dwg-locked font-size-lg mr-2"></i>{{ Helper::translation(3915,$translate) }}</a>
              
              @endif
              <div class="text-center pt-2"><small class="text-form text-muted">{{ Helper::translation(3918,$translate) }}</small></div>

            </div>

          </aside>

        </div>

      </div>

    </div>

    @else

    <section class="bg-position-center-top" style="background-image: url('{{ url('/') }}/public/storage/settings/{{ $allsettings->site_banner }}');">

      <div class="py-4">

        <div class="container d-lg-flex justify-content-between py-2 py-lg-3">

        <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">

          <nav aria-label="breadcrumb">

            <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">

              <li class="breadcrumb-item"><a class="text-nowrap" href="{{ URL::to('/') }}"><i class="dwg-home"></i>{{ Helper::translation(3849,$translate) }}</a></li>

              <li class="breadcrumb-item text-nowrap active" aria-current="page">{{ Helper::translation(3885,$translate) }}</li>

            </ol>

          </nav>

        </div>

        <div class="order-lg-1 pr-lg-4 text-center text-lg-left">

          <h1 class="h3 mb-0 text-white">{{ Helper::translation(3885,$translate) }}</h1>

        </div>

      </div>

      </div>

    </section>

<div class="container py-5 mt-md-2 mb-2">

      <div class="row">

        <div class="col-lg-12">

          <div class="font-size-md">{{ Helper::translation(3921,$translate) }}</div>

         </div>

      </div>

    </div>    

    @endif

@include('footer')

@include('script')

</body>

</html>