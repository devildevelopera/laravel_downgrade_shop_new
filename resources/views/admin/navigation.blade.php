<aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">
            <div class="navbar-header">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                </button>
                @if($allsettings->site_logo != '')
                <a class="navbar-brand" href="{{ url('/') }}"><img src="{{ url('/') }}/public/storage/settings/{{ $allsettings->site_logo }}"  alt="{{ $allsettings->site_title }}" width="180"/></a>
                @else
                <a class="navbar-brand" href="{{ url('/') }}">{{ substr($allsettings->site_title,0,10) }}</a>
                @endif
                @if($allsettings->site_favicon != '')
                <a class="navbar-brand hidden" href="{{ url('/') }}"><img src="{{ url('/') }}/public/storage/settings/{{ $allsettings->site_favicon }}"  alt="{{ $allsettings->site_title }}" width="24"/></a>
                @else
                <a class="navbar-brand hidden" href="{{ url('/') }}">{{ substr($allsettings->site_title,0,1) }}</a>
                @endif
            </div>
            <div id="main-menu" class="main-menu collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    @if(in_array('dashboard',$avilable))
                    <li>
                        <a href="{{ url('/admin') }}"> <i class="menu-icon fa fa-dashboard"></i>Dashboard </a>
                    </li>
                    @endif
                    @if(in_array('settings',$avilable))
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-gears"></i>Settings</a>
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="fa fa-gear"></i><a href="{{ url('/admin/general-settings') }}">General Settings</a></li>
                            <li><i class="fa fa-gear"></i><a href="{{ url('/admin/color-settings') }}">Color Settings</a></li>
                            <li><i class="fa fa-gear"></i><a href="{{ url('/admin/email-settings') }}">Email Settings</a></li>
                            <li><i class="fa fa-gear"></i><a href="{{ url('/admin/media-settings') }}">Media Settings</a></li>
                            <li><i class="fa fa-gear"></i><a href="{{ url('/admin/currency-settings') }}">Currency Settings</a></li>
                            <li><i class="fa fa-gear"></i><a href="{{ url('/admin/payment-settings') }}">Payment Settings</a></li>
                            <li><i class="fa fa-gear"></i><a href="{{ url('/admin/social-settings') }}">Social Settings</a></li>
                            <li><i class="fa fa-gear"></i><a href="{{ url('/admin/limitation-settings') }}">Limitation Settings</a></li>
                            <?php /*?><li><i class="fa fa-gear"></i><a href="{{ url('/admin/preferred-settings') }}">Preferred Settings</a></li><?php */?>
                        </ul>
                    </li>
                    @endif
                    @if(in_array('country',$avilable))
                    <li>
                        <a href="{{ url('/admin/country-settings') }}"> <i class="menu-icon fa fa-flag"></i>Country</a>
                    </li>
                    @endif
                    @if(Auth::user()->id == 1)
                    <li>
                        <a href="{{ url('/admin/administrator') }}"> <i class="menu-icon ti-user"></i>Sub Administrator </a>
                    </li>
                    @endif
                    @if(in_array('customers',$avilable))
                    <li>
                        <a href="{{ url('/admin/customer') }}"> <i class="menu-icon ti-user"></i>Customers </a>
                    </li>
                    @endif
                    @if(in_array('category',$avilable))
                    <li>
                        <a href="{{ url('/admin/category') }}"> <i class="menu-icon fa fa-location-arrow"></i>Category </a>
                    </li>
                    @endif
                    @if(in_array('manage-products',$avilable))
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-shopping-cart"></i>Manage Products</a>
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="menu-icon fa fa-shopping-cart"></i><a href="{{ url('/admin/products') }}">Products</a></li>
                            <li><i class="menu-icon fa fa-shopping-cart"></i><a href="{{ url('/admin/package-includes') }}">Package Includes</a></li>
                            <li><i class="menu-icon fa fa-shopping-cart"></i><a href="{{ url('/admin/compatible-browsers') }}">Compatible Browsers</a></li>
                        </ul>
                    </li>
                    @endif
                    @if(in_array('orders',$avilable))
                    <li>
                        <a href="{{ url('/admin/orders') }}"> <i class="menu-icon fa fa-first-order"></i>Orders </a>
                    </li>
                    @endif
                    @if(in_array('refund-request',$avilable))
                    <li>
                        <a href="{{ url('/admin/refund') }}"> <i class="menu-icon fa fa-undo"></i>Refund Request </a>
                    </li>
                    @endif
                    @if(in_array('rating-reviews',$avilable))
                    <li>
                        <a href="{{ url('/admin/rating') }}"> <i class="menu-icon fa fa-star"></i>Rating & Reviews</a>
                    </li>
                    @endif
                    @if(in_array('withdrawal',$avilable))
                    <li>
                        <a href="{{ url('/admin/withdrawal') }}"> <i class="menu-icon fa fa-money"></i>Withdrawal Request</a>
                    </li>
                    @endif
                    <?php /*?>@if($allsettings->site_development_display == 1)                     
                    <li>
                        <a href="{{ url('/admin/development') }}"> <i class="menu-icon fa fa-image"></i>Development Logo </a>
                    </li>
                    @endif<?php */?>
                    @if($allsettings->site_blog_display == 1)
                    @if(in_array('blog',$avilable))  
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-comments-o"></i>Blog</a>
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="menu-icon fa fa-comments-o"></i><a href="{{ url('/admin/blog-category') }}">Category</a></li>
                            <li><i class="menu-icon fa fa-comments-o"></i><a href="{{ url('/admin/post') }}">Post</a></li>
                        </ul>
                    </li>
                    @endif
                    @endif
                    @if(in_array('pages',$avilable)) 
                    <li>
                        <a href="{{ url('/admin/pages') }}"> <i class="menu-icon fa fa-file-text-o"></i>Pages </a>
                    </li>
                    @endif
                    @if(in_array('contact',$avilable))
                    <li>
                        <a href="{{ url('/admin/contact') }}"> <i class="menu-icon fa fa-address-book-o"></i>Contact </a>
                    </li>
                    @endif
                    @if(in_array('languages',$avilable))
                    <li>
                        <a href="{{ url('/admin/languages') }}"> <i class="menu-icon fa fa-language"></i>Languages </a>
                    </li>
                    @endif
                  </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
    </aside>