<!DOCTYPE HTML>
<html lang="en">
<head>
<title>{{ Helper::translation(4068,$translate) }} - {{ $allsettings->site_title }}</title>
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
              <li class="breadcrumb-item text-nowrap active" aria-current="page">{{ Helper::translation(4068,$translate) }}</li>
            </ol>
          </nav>
        </div>
        <div class="order-lg-1 pr-lg-4 text-center text-lg-left">
          <h1 class="h3 mb-0 text-white">{{ Helper::translation(4068,$translate) }}</h1>
        </div>
      </div>
      </div>
    </section>
<div class="container py-5 mt-md-2 mb-2">
      <div class="row pt-3 mx-n2">
         <aside class="col-lg-4">
          <!-- Sidebar-->
          <form action="{{ route('shop') }}" id="search_form2" method="post"  enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="cz-sidebar rounded-lg box-shadow-lg" id="shop-sidebar">
            <div class="cz-sidebar-header box-shadow-sm">
              <button class="close ml-auto" type="button" data-dismiss="sidebar" aria-label="Close"><span class="d-inline-block font-size-xs font-weight-normal align-middle">{{ Helper::translation(4455,$translate) }}</span><span class="d-inline-block align-middle ml-2" aria-hidden="true">&times;</span></button>
            </div>
            <div class="cz-sidebar-body" data-simplebar data-simplebar-auto-hide="true">
              <!-- Categories-->
              <div class="widget cz-filter mb-4 pb-4 border-bottom">
                <h3 class="widget-title">{{ Helper::translation(3870,$translate) }}</h3>
                <div class="input-group-overlay input-group-sm mb-2">
                  <input class="cz-filter-search form-control form-control-sm appended-form-control" type="text" placeholder="Search">
                  <div class="input-group-append-overlay"><span class="input-group-text"><i class="dwg-search"></i></span></div>
                </div>
                @if(count($category['view']) != 0)
                <ul class="widget-list cz-filter-list list-unstyled pt-1" style="max-height: 12rem;" data-simplebar data-simplebar-auto-hide="false">
                  @foreach($category['view'] as $cat)
                  <li class="cz-filter-item d-flex justify-content-between align-items-center mb-1">
                    <div class="custom-control custom-checkbox">
                      <input class="custom-control-input" type="checkbox" id="{{ $cat->cat_id }}" name="category_names[]" value="{{ $cat->cat_id }}">
                      <label class="custom-control-label cz-filter-item-text" for="{{ $cat->cat_id }}">{{ $cat->category_name }}</label>
                    </div><span class="font-size-xs text-muted">{{ $count_item->has($cat->cat_id) ? count($count_item[$cat->cat_id]) : 0 }}</span>
                  </li>
                  @endforeach 
                </ul>
                @endif
              </div>
              <div class="widget cz-filter mb-4 pb-4 border-bottom">
                <h3 class="widget-title">{{ Helper::translation(4458,$translate) }}</h3>
                <div class="input-group-overlay input-group-sm mb-2">
                  <select class="cz-filter-search form-control form-control-sm appended-form-control" name="orderby">
                  <option value="desc">{{ Helper::translation(4461,$translate) }}</option>
                  <option value="asc">{{ Helper::translation(4464,$translate) }}</option>
                  <option value="desc">{{ Helper::translation(4467,$translate) }}</option>
                 </select>            
                </div>
              </div>
              <!-- Price range-->
              <div class="widget mb-4 pb-4 border-bottom">
                <h3 class="widget-title">{{ Helper::translation(4290,$translate) }}</h3>
                <div class="cz-range-slider" data-start-min="{{ $minprice['price']->regular_price }}" data-start-max="{{ $maxprice['price']->extended_price }}" data-min="{{ $allsettings->site_range_min_price }}" data-max="{{ $allsettings->site_range_max_price }}" data-step="1">
                  <div class="cz-range-slider-ui"></div>
                  <div class="d-flex pb-1">
                    <div class="w-50 pr-2 mr-2">
                      <div class="input-group input-group-sm">
                        <div class="input-group-prepend"><span class="input-group-text">{{ $allsettings->site_currency_symbol }}</span></div>
                        <input class="form-control cz-range-slider-value-min" type="text" name="min_price">
                      </div>
                    </div>
                    <div class="w-50 pl-2">
                      <div class="input-group input-group-sm">
                        <div class="input-group-prepend"><span class="input-group-text">{{ $allsettings->site_currency_symbol }}</span></div>
                        <input class="form-control cz-range-slider-value-max" type="text" name="max_price">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="input-group-append">
                  <button class="btn btn-primary btn-sm font-size-base" type="submit">{{ Helper::translation(4104,$translate) }}</button>
             </div>
              <!-- Filter by Brand-->
           </div>
          </div>
          </form>
        </aside>
        <div class="col-lg-8">
         <div class="row pt-2 mx-n2">
        <!-- Product-->
        @if(count($itemData['item']) != 0)
        @php $no = 1; @endphp
        @foreach($itemData['item'] as $featured)
        @php
        $price = Helper::price_info($featured->product_flash_sale,$featured->regular_price);
        $count_rating = Helper::count_rating($featured->ratings);
        @endphp
        <div class="col-lg-4 col-md-4 col-sm-6 px-2 mb-3 prod-item">
          <!-- Product-->
          <div class="card product-card-alt">
            <div class="product-thumb">
              @if(Auth::guest()) 
              <a class="btn-wishlist btn-sm" href="{{ URL::to('/login') }}"><i class="dwg-heart"></i></a>
              @endif
              @if (Auth::check())
              @if($featured->user_id != Auth::user()->id)
              <a class="btn-wishlist btn-sm" href="{{ url('/product') }}/{{ base64_encode($featured->product_id) }}/favorite/{{ base64_encode($featured->product_liked) }}"><i class="dwg-heart"></i></a>
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
              <h3 class="product-title font-size-sm mb-2 title"><a href="{{ URL::to('/product') }}/{{ $featured->product_slug }}">{{ $featured->product_name }}</a></h3>
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
       <div class="text-right">
            <div class="turn-page" id="itempager"></div>
       </div>
       @else
       <div>{{ Helper::translation(4470,$translate) }}</div>
       @endif
       </div>
        </div>
      </div>
    </div>
@include('footer')
@include('script')
</body>
</html>