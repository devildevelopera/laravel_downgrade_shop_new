<!DOCTYPE HTML>
<html lang="en">
<head>
<title>{{ Helper::translation(4032,$translate) }} - {{ $allsettings->site_title }}</title>
@include('meta')
@include('style')
</head>
<body>
@include('header')
<div class="page-title-overlap pt-4" style="background-image: url('{{ url('/') }}/public/storage/settings/{{ $allsettings->site_banner }}');">
      <div class="container d-lg-flex justify-content-between py-2 py-lg-3">
        <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-light flex-lg-nowrap justify-content-center justify-content-lg-star">
              <li class="breadcrumb-item"><a class="text-nowrap" href="{{ URL::to('/') }}"><i class="dwg-home"></i>{{ Helper::translation(3849,$translate) }}</a></li>
              <li class="breadcrumb-item text-nowrap active" aria-current="page">{{ Helper::translation(4032,$translate) }}</li>
            </ol>
          </nav>
        </div>
        <div class="order-lg-1 pr-lg-4 text-center text-lg-left">
          <h1 class="h3 mb-0 text-white">{{ Helper::translation(4032,$translate) }}</h1>
        </div>
      </div>
    </div>
<div class="container pb-5 mb-2 mb-md-3">
      <div class="row">
        <!-- Sidebar-->
        <aside class="col-lg-4 pt-4 pt-lg-0">
          @include('dashboard-menu')
        </aside>
        <!-- Content  -->
        <section class="col-lg-8">
          <!-- Toolbar-->
          <div class="d-none d-lg-flex justify-content-between align-items-center pt-lg-3 pb-4 pb-lg-5 mb-lg-3">
            <h6 class="font-size-base text-light mb-0">{{ Helper::translation(4269,$translate) }}</h6><a class="btn btn-primary btn-sm" href="{{ url('/logout') }}"><i class="dwg-sign-out mr-2"></i>{{ Helper::translation(4044,$translate) }}</a>
          </div>
          <!-- Profile form-->
          <form action="{{ route('my-profile') }}" class="needs-validation" id="profile_form" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="account-fn">{{ Helper::translation(4020,$translate) }}</label>
                  <input id="name" name="name" type="text" class="form-control" value="{{ Auth::user()->name }}" data-bvalidator="required">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="account-ln">{{ Helper::translation(4272,$translate) }}</label>
                  <input id="username" name="username" type="text" class="form-control" value="{{ Auth::user()->username }}" data-bvalidator="required">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="account-email">{{ Helper::translation(3936,$translate) }}</label>
                  <input id="email" name="email" type="text" class="form-control" value="{{ Auth::user()->email }}" data-bvalidator="email,required">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="account-pass">{{ Helper::translation(4275,$translate) }}</label>
                  <div class="password-toggle">
                    <input id="password" name="password" type="text" class="form-control" data-bvalidator="min[6]">
                    <label class="password-toggle-btn">
                      <input class="custom-control-input" type="checkbox"><i class="dwg-eye password-toggle-indicator"></i><span class="sr-only">{{ Helper::translation(4278,$translate) }}</span>
                    </label>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
              <div class="form-group pb-2">
                  <label for="account-confirm-pass">{{ Helper::translation(4281,$translate) }}</label>
                  <div class="custom-file">
                    <input class="custom-file-input" type="file" id="unp-product-files" name="user_photo" data-bvalidator="extension[jpg:png:jpeg]" data-bvalidator-msg="{{ Helper::translation(4284,$translate) }}">
                    <label class="custom-file-label" for="unp-product-files"></label>
                  </div>
                </div>
              </div> 
              <input type="hidden" name="save_photo" value="{{ Auth::user()->user_photo }}">
              <input type="hidden" name="save_password" value="{{ Auth::user()->password }}">
              <input type="hidden" name="user_token" value="{{ Auth::user()->user_token }}">
              <input type="hidden" name="image_size" value="{{ $allsettings->site_max_image_size }}"> 
              <div class="col-12">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                  <button class="btn btn-primary mt-3 mt-sm-0" type="submit">{{ Helper::translation(4287,$translate) }}</button>
                </div>
              </div>
            </div>
          </form>
        </section>
      </div>
    </div>
@include('footer')
@include('script')
</body>
</html>