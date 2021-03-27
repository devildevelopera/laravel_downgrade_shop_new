<!DOCTYPE HTML>
<html lang="en">
<head>
<title>{{ Helper::translation(4092,$translate) }} - {{ $allsettings->site_title }}</title>
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
              <li class="breadcrumb-item text-nowrap active" aria-current="page">{{ Helper::translation(4092,$translate) }}</li>
            </ol>
          </nav>
        </div>
        <div class="order-lg-1 pr-lg-4 text-center text-lg-left">
          <h1 class="h3 mb-0 text-white">{{ Helper::translation(4092,$translate) }}</h1>
        </div>
      </div>
      </div>
    </section>
<div class="container py-4 py-lg-5 my-4">
      <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
          <div class="card py-2 mt-4">
            <form method="POST" action="{{ route('reset') }}"  id="reset_form" class="card-body needs-validation">
               @csrf
              <div class="form-group">
                <label for="recover-email">{{ Helper::translation(4275,$translate) }}</label>
                <input type="password" class="form-control" name="password" placeholder="Password" id="password" data-bvalidator="required">
              </div>
              <div class="form-group">
                <label for="recover-email">{{ Helper::translation(4431,$translate) }}</label>
                <input type="password" class="form-control input-lg" placeholder="Confirm Password" name="password_confirmation" id="password_confirmation" data-bvalidator="equal[password],required">
              </div>
              <button class="btn btn-primary" type="submit">{{ Helper::translation(4092,$translate) }}</button>
            </form>
          </div>
        </div>
      </div>
    </div>
@include('footer')
@include('script')
</body>
</html>