<!DOCTYPE HTML>

<html lang="en">

<head>

<title>{{ Helper::translation(4035,$translate) }} - {{ $allsettings->site_title }}</title>

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

              <li class="breadcrumb-item text-nowrap active" aria-current="page">{{ Helper::translation(4035,$translate) }}</li>

            </ol>

          </nav>

        </div>

        <div class="order-lg-1 pr-lg-4 text-center text-lg-left">{{ url('/product') }}

          <h1 class="h3 mb-0 text-white">{{ Helper::translation(4035,$translate) }}</h1>

        </div>

      </div>

    </div>{{ url('/product') }}

<div class="container mb-5 pb-3">

      <div class="bg-light box-shadow-lg rounded-lg overflow-hidden">

        <div class="row">

          <!-- Sidebar-->

          <aside class="col-lg-4">

             @include('dashboard-menu')

          </aside>

          <!-- Content-->

          @if(count($orderData['item']) != 0)

          <section class="col-lg-8 pt-lg-4 pb-4 mb-3">

            <div class="pt-2 px-4 pl-lg-0 pr-xl-5">

              <!-- Title-->

              @foreach($orderData['item'] as $item)

              <div class="media d-block d-sm-flex align-items-center py-4 border-bottom">

              <a class="d-block mb-3 mb-sm-0 mr-sm-4 mx-auto" href="{{ url('/item') }}/{{ $item->product_slug }}" style="width: 12.5rem;">

              @if($item->product_image!='')

              <img class="rounded-lg" src="{{ url('/') }}/public/storage/product/{{ $item->product_image }}" alt="{{ $item->product_name }}">

              @else

              <img class="rounded-lg" src="{{ url('/') }}/public/img/no-image.png" alt="{{ $item->product_name }}">

              @endif

              </a>

                <div class="d-block mb-3 mb-sm-0 mr-sm-4 mx-auto">

                  <h3 class="h6 product-title mb-2"><a href="{{ url('/item') }}/{{ $item->product_slug }}">{{ $item->product_name }}</a></h3>

                  <div class="text-accent font-size-sm"><strong>{{ Helper::translation(4290,$translate) }}:</strong> {{ $allsettings->site_currency_symbol }}{{ $item->product_price }}</div>

                  <div class="d-flex align-items-center justify-content-center justify-content-sm-start">

                   @if($item->approval_status != 'payment released to customer')

                    @if($item->rating != 0)

                    <a class="d-block text-muted text-center my-2" href="javascript:void(0);" data-toggle="modal" data-target="#myModal_{{ $item->ord_id }}">

                      <div class="star-rating">

                      @if($item->rating == 1)

                      <i class="sr-star dwg-star-filled active"></i>

                      <i class="sr-star dwg-star"></i>

                      <i class="sr-star dwg-star"></i>

                      <i class="sr-star dwg-star"></i>

                      <i class="sr-star dwg-star"></i>

                      @endif

                      @if($item->rating == 2)

                      <i class="sr-star dwg-star-filled active"></i>

                      <i class="sr-star dwg-star-filled active"></i>

                      <i class="sr-star dwg-star"></i>

                      <i class="sr-star dwg-star"></i>

                      <i class="sr-star dwg-star"></i>

                      @endif

                      @if($item->rating == 3)

                      <i class="sr-star dwg-star-filled active"></i>

                      <i class="sr-star dwg-star-filled active"></i>

                      <i class="sr-star dwg-star-filled active"></i{{ url('/product') }}

                      <i class="sr-star dwg-star"></i>

                      <i class="sr-star dwg-star"></i>

                      @endif

                      @if($item->rating == 4)

                      <i class="sr-star dwg-star-filled active"></i>

                      <i class="sr-star dwg-star-filled active"></i>

                      <i class="sr-star dwg-star-filled active"></i>

                      <i class="sr-star dwg-star-filled active"></i>

                      <i class="sr-star dwg-star"></i>

                      @endif

                      @if($item->rating == 5)

                      <i class="sr-star dwg-star-filled active"></i>

                      <i class="sr-star dwg-star-filled active"></i>

                      <i class="sr-star dwg-star-filled active"></i>

                      <i class="sr-star dwg-star-filled active"></i>

                      <i class="sr-star dwg-star-filled active"></i>

                      @endif

                      </div>

                      <div class="font-size-xs">{{ Helper::translation(4293,$translate) }}</div>

                      </a>

                      @else

                      <a class="d-block text-muted text-center my-2" href="javascript:void(0);" data-toggle="modal" data-target="#myModal_{{ $item->ord_id }}">

                      <div class="star-rating">

                      <i class="sr-star dwg-star"></i>

                      <i class="sr-star dwg-star"></i>

                      <i class="sr-star dwg-star"></i>
{{ url('/product') }}
                      <i class="sr-star dwg-star"></i>

                      <i class="sr-star dwg-star"></i>

                      </div>

                      <div class="font-size-xs">{{ Helper::translation(4293,$translate) }}</div>

                      </a>

                      @endif

                      @endif

                  </div>

                  @if($item->approval_status != 'payment released to customer')

                  <div class="d-flex">

                  <a href="{{ url('/my-purchases') }}/{{ $item->product_token }}" class="btn btn-primary btn-sm mr-3"><i class="dwg-download mr-1"></i>{{ Helper::translation(4296,$translate) }}</a>

                  <a href="{{ url('/invoice') }}/{{ $item->product_token }}/{{ $item->ord_id }}" class="btn btn-danger btn-sm mr-3"><i class="dwg-download mr-1"></i>{{ Helper::translation(4299,$translate) }}</a>

                  </div>

                  @endif

                </div>

                <div class="d-block mb-3 mb-sm-0 mr-sm-4 mx-auto">

                <div class="text-accent font-size-sm mb-1"><strong>{{ Helper::translation(4302,$translate) }}</strong> {{ $item->ord_id }}</div>

                <div class="text-accent font-size-sm mb-1"><strong>{{ Helper::translation(4305,$translate) }}</strong> {{ $item->purchase_token }}</div>

                <div class="text-accent font-size-sm mb-1"><strong>{{ Helper::translation(4308,$translate) }}</strong> {{ date("d M Y", strtotime($item->start_date)) }}</div>

                <div class="text-accent font-size-sm mb-1"><strong>{{ Helper::translation(4311,$translate) }}</strong> {{ date("d M Y", strtotime($item->end_date)) }}</div>

                <div class="text-accent font-size-sm mb-1"><strong>{{ Helper::translation(4314,$translate) }}</strong> {{ $item->license }}</div>

                  @if($item->approval_status != 'payment released to customer')

                  <div class="text-accent font-size-sm mb-1"><strong>{{ Helper::translation(4317,$translate) }}</strong> <a href="javascript:void(0);" data-toggle="modal" data-target="#refund_{{ $item->ord_id }}"> {{ Helper::translation(4320,$translate) }}</a></div>

                  @endif

                </div>

              </div>

              <div class="modal fade" id="myModal_{{ $item->ord_id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <div class="modal-dialog" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <h5 class="modal-title" id="exampleModalLabel">{{ Helper::translation(4323,$translate) }}</h5>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

          <span aria-hidden="true">&times;</span>

        </button>

      </div>

       <form action="{{ route('my-purchases') }}" method="post" id="profile_form" enctype="multipart/form-data">

      {{ csrf_field() }} 

      <div class="modal-body">

                    <input type="hidden" name="product_id" value="{{ $item->product_id }}">

                    <input type="hidden" name="ord_id" value="{{ $item->ord_id }}">

                    <input type="hidden" name="product_token" value="{{ $item->product_token }}">

                    <input type="hidden" name="user_id" value="{{ $item->user_id }}">

                    <input type="hidden" name="product_user_id" value="{{ $item->product_user_id }}">

                    <input type="hidden" name="product_url" value="{{ url('/item') }}/{{ $item->product_slug }}">

          <div class="form-group">

            <label for="recipient-name" class="col-form-label">{{ Helper::translation(4326,$translate) }}</label>

            <select name="rating" class="form-control" required>

                                        <option value="1" @if($item->rating == 1) selected @endif>1</option>

                                        <option value="2" @if($item->rating == 2) selected @endif>2</option>

                                        <option value="3" @if($item->rating == 3) selected @endif>3</option>

                                        <option value="4" @if($item->rating == 4) selected @endif>4</option>

                                        <option value="5" @if($item->rating == 5) selected @endif>5</option>

                                    </select>

          </div>

          <div class="form-group">

            <label for="message-text" class="col-form-label">{{ Helper::translation(4329,$translate) }}</label>

           <select name="rating_reason" class="form-control" required>

                                            <option value="design" @if($item->rating_reason == 'design') selected @endif>{{ Helper::translation(4332,$translate) }}</option>

                                            <option value="customization" @if($item->rating_reason == 'customization') selected @endif>{{ Helper::translation(4335,$translate) }}</option>

                                            <option value="support" @if($item->rating_reason == 'support') selected @endif>{{ Helper::translation(4101,$translate) }}</option>

                                            <option value="performance" @if($item->rating_reason == 'performance') selected @endif>{{ Helper::translation(4338,$translate) }}</option>

                                            <option value="documentation" @if($item->rating_reason == 'documentation') selected @endif>{{ Helper::translation(4341,$translate) }}</option>

                                        </select>

          </div>

          <div class="form-group">

          <label for="message-text" class="col-form-label">{{ Helper::translation(4191,$translate) }}</label>

          <textarea name="rating_comment" id="rating_comment" class="form-control" required>{{ $item->rating_comment }}</textarea>

                            <p>{{ Helper::translation(4344,$translate) }}</p>

          </div>

      </div>

      <div class="modal-footer">

        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">{{ Helper::translation(4347,$translate) }}</button>

        <button type="submit" class="btn btn-primary btn-sm">{{ Helper::translation(4350,$translate) }}</button>

      </div>

      </form>

    </div>

  </div>

</div>

<div class="modal fade" id="refund_{{ $item->ord_id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <div class="modal-dialog" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <h5 class="modal-title" id="exampleModalLabel">{{ Helper::translation(4317,$translate) }}</h5>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

          <span aria-hidden="true">&times;</span>

        </button>

      </div>

      <form action="{{ route('refund') }}" method="post" id="profile_form" enctype="multipart/form-data">

      {{ csrf_field() }}

      <div class="modal-body">

          <input type="hidden" name="product_id" value="{{ $item->product_id }}">

                    <input type="hidden" name="ord_id" value="{{ $item->ord_id }}">

                    <input type="hidden" name="purchased_token" value="{{ $item->purchase_token }}">

                    <input type="hidden" name="product_token" value="{{ $item->product_token }}">

                    <input type="hidden" name="user_id" value="{{ $item->user_id }}">

                    <input type="hidden" name="product_user_id" value="{{ $item->product_user_id }}">

                    <input type="hidden" name="product_url" value="{{ url('/item') }}/{{ $item->product_slug }}">

          <div class="form-group">

            <label for="recipient-name" class="col-form-label">{{ Helper::translation(4353,$translate) }}</label>

            <select name="refund_reason" class="form-control" required>

                             <option value="Item is not as described or the item does not work the way it should">

                             {{ Helper::translation(4356,$translate) }}</option>

                                            <option value="Item has a security vulnerability">{{ Helper::translation(4359,$translate) }}</option>

                                            <option value="Item support is promised but not provided">{{ Helper::translation(4362,$translate) }}</option>

                                            <option value="Item support extension not used">{{ Helper::translation(4365,$translate) }}</option>

                                            <option value="Items that have not been downloaded">{{ Helper::translation(4368,$translate) }}</option>

                                        </select>

          </div>

          <div class="form-group">

            <label for="message-text" class="col-form-label">{{ Helper::translation(4191,$translate) }}</label>

            <textarea name="refund_comment" id="refund_comment" class="form-control" required></textarea>

          </div>

      </div>

      <div class="modal-footer">

        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">{{ Helper::translation(4347,$translate) }}</button>

        <button type="submit" class="btn btn-primary btn-sm">{{ Helper::translation(4371,$translate) }}</button>

      </div>

      </form>

    </div>

  </div>

 </div>

     @endforeach

      <!-- Product-->

       </div>

          </section>

          @else

          <section class="col-lg-8 pt-lg-4 pb-4 mb-3">

             <div class="pt-2 px-4 pl-lg-0 pr-xl-5" align="center">

             {{ Helper::translation(4374,$translate) }}

             </div>

             </section>

              @endif

        </div>

      </div>

    </div>

@include('footer')

@include('script')

</body>

</html>