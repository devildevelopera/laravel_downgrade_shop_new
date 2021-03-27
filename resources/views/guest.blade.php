<!doctype html>

<html class="no-js" lang="en">

<head>

<title>{{ __('Guest') }} - {{ $allsettings->site_title }}</title>

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

              <li class="breadcrumb-item"><a class="text-nowrap" href="{{ URL::to('/') }}"><i class="dwg-home"></i>{{ __('Home') }}</a></li>

              <li class="breadcrumb-item text-nowrap active" aria-current="page">{{ __('Guest') }}</li>

            </ol>

          </nav>

        </div>

        <div class="order-lg-1 pr-lg-4 text-center text-lg-left">

          <h1 class="h3 mb-0 text-white">{{ __('Guest') }}</h1>

        </div>

      </div>

      </div>

    </section>

<div class="container py-4 py-lg-5 my-4">

      <div class="row">

        <div class="col-md-6 mx-auto">

          <div class="card border-0 box-shadow">

            <div class="card-body">

              <h2 class="h4 mb-4">{{ __('Check out as guest user') }}</h2>

              <form action="{{ route('guest') }}" method="POST" id="guest_form">

                @csrf

                <div class="input-group-overlay form-group">

                  <div class="input-group-prepend-overlay"><span class="input-group-text"><i class="dwg-mail"></i></span></div>

                  <input class="form-control prepended-form-control" type="text" name="email" placeholder="{{ __('E-Mail Address') }}" data-bvalidator="required">

                </div>

                <div class="d-flex flex-wrap justify-content-between">
                  <a class="nav-link-inline font-size-sm" href="{{ URL::to('/login') }}">{{ __('Sign in to check out?') }}</a>
                </div>

                <hr class="mt-4">

                <div class="text-right pt-4">
                  <button class="btn btn-primary" type="submit">{{ __('Continue') }}</button>
                </div>

              </form>

            </div>

          </div>

        </div>

      </div>

    </div>

@include('footer')

@include('script')

</body>

</html>