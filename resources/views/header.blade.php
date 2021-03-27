<header class="bg-light box-shadow-sm navbar-sticky">
      <!-- Topbar-->
      @if($allsettings->site_header_top_bar == 1)
      <div class="topbar topbar-dark bg-dark">
        <div class="container">
          <div>
            @if($allsettings->site_google_translate == 1)
            <div class="topbar-text dropdown disable-autohide"><a class="topbar-link dropdown-toggle" href="javascript:void(0);" data-toggle="dropdown">{{ $language_title }}</a>
              <ul class="dropdown-menu">
                @foreach($languages['view'] as $language) 
                <li><a class="dropdown-item pb-1" href="{{ URL::to('/translate') }}/{{ $language->language_code }}">{{ $language->language_name }}</a></li>
                @endforeach
              </ul>
            </div>
            @endif
          </div>
          <div class="topbar-text dropdown d-md-none"><a class="topbar-link" href="tel:{{ $allsettings->office_phone }}" data-toggle="dropdown"><i class="dwg-phone mr-2"></i>{{ $allsettings->office_phone }}</a>
          </div>
          <div class="topbar-text text-nowrap d-none d-md-inline-block"><i class="dwg-phone"></i><span class="text-muted mr-1">{{ Helper::translation(4101,$translate) }}</span><a class="topbar-link" href="tel:{{ $allsettings->office_phone }}">{{ $allsettings->office_phone }}</a></div>
        </div>
      </div>
      @endif
      <!-- Remove "navbar-sticky" class to make navigation bar scrollable with the page.-->
      <div class="navbar-sticky">
        <div class="navbar navbar-expand-lg navbar-light bg-light">
          <div class="container">
          @if($allsettings->site_logo != '')
          <a class="navbar-brand d-none d-sm-block mr-4 order-lg-1" href="{{ URL::to('/') }}" style="min-width: 7rem;">
             <img width="200" src="{{ url('/') }}/public/storage/settings/{{ $allsettings->site_logo }}" alt="{{ $allsettings->site_title }}"/>
          </a>
          @endif
          @if($allsettings->site_favicon != '')
          <a class="navbar-brand d-sm-none mr-2 order-lg-1" href="{{ URL::to('/') }}" style="min-width: 4.625rem;">
             <img width="74" src="{{ url('/') }}/public/storage/settings/{{ $allsettings->site_favicon }}" alt="{{ $allsettings->site_title }}"/>
          </a>
          @endif
            <div class="navbar-toolbar d-flex align-items-center order-lg-3">
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"><span class="navbar-toggler-icon"></span></button><a class="navbar-tool d-none d-lg-flex" href="#searchBox" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="searchBox"><span class="navbar-tool-tooltip">{{ Helper::translation(4104,$translate) }}</span>
                <div class="navbar-tool-icon-box"><i class="navbar-tool-icon dwg-search"></i></div></a>
                <a class="navbar-tool d-none d-lg-flex" href="{{ url('/my-favourite') }}"><span class="navbar-tool-tooltip">{{ Helper::translation(4107,$translate) }}</span>
                <div class="navbar-tool-icon-box"><i class="navbar-tool-icon dwg-heart"></i></div></a>
                @if(Auth::guest())
                <a class="navbar-tool ml-1 mr-n1" href="{{ URL::to('/login') }}"><span class="navbar-tool-tooltip">{{ Helper::translation(4029,$translate) }}</span>
                <div class="navbar-tool-icon-box"><i class="navbar-tool-icon dwg-user"></i></div></a>
                @endif
                @if (Auth::check())
                <div class="navbar-tool dropdown ml-2">
                <a class="navbar-tool-icon-box border dropdown-toggle" @if(Auth::user()->id == 1) href="{{ url('/admin') }}" target="_blank" @else href="{{ url('/my-profile') }}" @endif>         @if(!empty(Auth::user()->user_photo))
                <img width="32" src="{{ url('/') }}/public/storage/users/{{ Auth::user()->user_photo }}" alt="{{ Auth::user()->name }}"/>
                @else
                <img src="{{ url('/') }}/public/img/no-user.png" alt="{{ Auth::user()->name }}">
                @endif
                </a>
                <a class="navbar-tool-text ml-n1" @if(Auth::user()->id == 1) href="{{ url('/admin') }}" target="_blank" @else href="{{ url('/my-profile') }}" @endif>
                <small>{{ Auth::user()->name }}</small>{{ $allsettings->site_currency_symbol }}{{ Auth::user()->earnings }}
                </a>
                <div class="dropdown-menu dropdown-menu-right" style="min-width: 14rem;">
                @if(Auth::user()->id != 1)
                <a class="dropdown-item d-flex align-items-center" href="{{ url('/my-profile') }}"><i class="dwg-settings opacity-60 mr-2"></i>{{ Helper::translation(4032,$translate) }}</a>
                <a class="dropdown-item d-flex align-items-center" href="{{ url('/my-purchases') }}"><i class="dwg-basket opacity-60 mr-2"></i>{{ Helper::translation(4035,$translate) }}</a>
                <a class="dropdown-item d-flex align-items-center" href="{{ url('/my-favourite') }}"><i class="dwg-heart opacity-60 mr-2"></i>{{ Helper::translation(4038,$translate) }}</a>
                <a class="dropdown-item d-flex align-items-center" href="{{ url('/withdrawal') }}"><i class="dwg-currency-exchange opacity-60 mr-2"></i>{{ Helper::translation(4041,$translate) }}</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item d-flex align-items-center" href="{{ url('/logout') }}"><i class="dwg-sign-out opacity-60 mr-2"></i>{{ Helper::translation(4044,$translate) }}</a>
                @endif
                @if(Auth::user()->id == 1)
                <a class="dropdown-item d-flex align-items-center" href="{{ url('/admin') }}"><i class="dwg-settings opacity-60 mr-2"></i>{{ Helper::translation(4110,$translate) }}</a>
                <a class="dropdown-item d-flex align-items-center" href="{{ url('/logout') }}"><i class="dwg-sign-out opacity-60 mr-2"></i>{{ Helper::translation(4044,$translate) }}</a>
                @endif
              </div>
              </div>
              @endif
              <div class="navbar-tool dropdown ml-3"><a class="navbar-tool-icon-box bg-secondary dropdown-toggle" href="{{ url('/cart') }}"><span class="navbar-tool-label">{{ $cartcount }}</span><i class="navbar-tool-icon dwg-cart"></i></a>
                <!-- Cart dropdown-->
                @if($cartcount != 0)
                <div class="dropdown-menu dropdown-menu-right" style="width: 20rem;">
                  <div class="widget widget-cart px-3 pt-2 pb-3">
                    <div data-simplebar data-simplebar-auto-hide="false">
                      @php $subtotall = 0; @endphp
                      @foreach($cartitem['item'] as $cart)
                      <div class="widget-cart-item pb-2 mb-2 border-bottom">
                        <a href="{{ url('/cart') }}/{{ base64_encode($cart->ord_id) }}" class="close text-danger" onClick="return confirm('{{ __('Are you sure you want to delete?') }}');"><span aria-hidden="true">&times;</span></a>
                        <div class="media align-items-center"><a class="d-block mr-2" href="{{ url('/item') }}/{{ $cart->product_slug }}">
                        @if($cart->product_image!='')
                        <img width="64" src="{{ url('/') }}/public/storage/product/{{ $cart->product_image }}" alt="{{ $cart->product_name }}"/>
                        @else
                        <img width="64" src="{{ url('/') }}/public/img/no-image.png" alt="{{ $cart->product_name }}"/>
                        @endif
                        </a>
                          <div class="media-body">
                            <h6 class="widget-product-title"><a href="{{ url('/item') }}/{{ $cart->product_slug }}">{{ $cart->product_name }}</a></h6>
                            <div class="widget-product-meta"><span class="text-accent mr-2">{{ $allsettings->site_currency_symbol }} {{ $cart->product_price }}</span></div>
                          </div>
                        </div>
                      </div>
                      @php $subtotall += $cart->product_price; @endphp
                      @endforeach
                     </div>
                    <div class="d-flex flex-wrap justify-content-between align-items-center py-3">
                      <div class="font-size-sm mr-2 py-2"><span class="text-muted">{{ Helper::translation(3960,$translate) }}</span><span class="text-accent font-size-base ml-1">{{ $allsettings->site_currency_symbol }} {{ $subtotall }}</span></div><a class="btn btn-outline-secondary btn-sm" href="{{ url('/cart') }}">{{ Helper::translation(4113,$translate) }}<i class="dwg-arrow-right ml-1 mr-n1"></i></a></div><a class="btn btn-primary btn-sm btn-block" href="{{ url('/checkout') }}"><i class="dwg-card mr-2 font-size-base align-middle"></i>{{ Helper::translation(3924,$translate) }}</a>
                  </div>
                </div>
                @endif
              </div>
            </div>
            <div class="collapse navbar-collapse mr-auto order-lg-2" id="navbarCollapse">
              <!-- Search-->
              <div class="input-group-overlay d-lg-none my-3">
                <div class="input-group-prepend-overlay"><span class="input-group-text"><i class="dwg-search"></i></span></div>
                <form action="{{ route('shop') }}" id="search_form1" method="post"  enctype="multipart/form-data">
                {{ csrf_field() }}
                <input class="form-control prepended-form-control" type="text" name="product_item" placeholder="{{ Helper::translation(4116,$translate) }}">
                </form>
              </div>
              <!-- Primary menu-->
              <ul class="navbar-nav">
                <li class="nav-item dropdown"><a class="nav-link" href="{{ URL::to('/') }}">{{ Helper::translation(3849,$translate) }}</a>
                </li>
                <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="{{ URL::to('/shop') }}">{{ Helper::translation(4068,$translate) }}</a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ URL::to('/featured-items') }}">{{ Helper::translation(4047,$translate) }}</a></li>
                    <li><a class="dropdown-item" href="{{ URL::to('/free-items') }}">{{ Helper::translation(4095,$translate) }}</a></li>
                    <li><a class="dropdown-item" href="{{ URL::to('/new-releases') }}">{{ Helper::translation(4119,$translate) }}</a></li>
                    <li><a class="dropdown-item" href="{{ URL::to('/popular-items') }}">{{ Helper::translation(4122,$translate) }}</a></li>
                  </ul>
                </li>
                <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown">{{ Helper::translation(3870,$translate) }}</a>
                  <ul class="dropdown-menu">
                    @foreach($main_menu['category'] as $menu) 
                    <li><a class="dropdown-item" href="{{ URL::to('/shop/category') }}/{{ $menu->category_slug }}">{{ $menu->category_name }}</a></li>
                    @endforeach
                  </ul>
                </li>
                @if($mainmenu_count != 0)
                <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown">{{ Helper::translation(4125,$translate) }}</a>
                  <ul class="dropdown-menu">
                    @foreach($allpages['pages'] as $pages)
                    <li><a class="dropdown-item" href="{{ URL::to('/page') }}/{{ $pages->page_slug }}">{{ $pages->page_title }}</a></li>
                    @endforeach
                  </ul>
                </li>
                @endif
                @if($allsettings->site_blog_display == 1)
                <li class="nav-item dropdown"><a class="nav-link" href="{{ URL::to('/blog') }}">{{ Helper::translation(3867,$translate) }}</a></li>
                @endif
                <li class="nav-item dropdown"><a class="nav-link" href="{{ URL::to('/contact') }}">{{ Helper::translation(3999,$translate) }}</a></li>
                <li class="nav-item dropdown"><a class="nav-link" href="{{ URL::to('/sale') }}">{{ Helper::translation(4128,$translate) }}</a></li>
               </ul>
            </div>
          </div>
        </div>
        <!-- Search collapse-->
        <div class="search-box collapse" id="searchBox">
          <div class="card pt-2 pb-4 border-0 rounded-0">
            <div class="container">
              <div class="input-group-overlay">
                <div class="input-group-prepend-overlay"><span class="input-group-text"><i class="dwg-search"></i></span></div>
                <form action="{{ route('shop') }}" id="search_form2" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input class="form-control prepended-form-control" type="text" name="product_item" id="product_item_top" placeholder="{{ Helper::translation(4116,$translate) }}">
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>