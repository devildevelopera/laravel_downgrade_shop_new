<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <title>{{ $product_name }}</title>
</head>
<body>
  <table width="100%" border="0">
      <tr>
        <td colspan="3">
          @if($allsettings->site_logo != '')
          <a href="{{ URL::to('/') }}" target="_blank">
          <img width="200" src="{{ url('/') }}/public/storage/settings/{{ $allsettings->site_logo }}" alt="{{ $allsettings->site_title }}"/>
          </a>
          @endif
        </td>
      </tr>
      <tr>
        <td colspan="3">
        <h3>{{ Helper::translation(4383,$translate) }}</h3>
        <p>{{ Helper::translation(4386,$translate) }}<strong>{{ $license }}</strong><br/>
        {{ Helper::translation(4389,$translate) }}
        </p>
        </td>
      </tr> 
      <tr>
       <td colspan="3">
         <table cellpadding="0" cellspacing="10">
          <tr>
            <td><strong>{{ Helper::translation(4392,$translate) }}</strong></td>
            <td>:</td>
            <td>{{ $product_name }}</td>
          </tr>
          <tr>
            <td><strong>{{ Helper::translation(3993,$translate) }}</strong></td>
            <td>:</td>
            <td><a href="{{ URL::to('/') }}/item/{{ $product_slug }}" target="_blank">{{ URL::to('/') }}/item/{{ $product_slug }}</a></td>
          </tr>
          <tr>
            <td><strong>{{ Helper::translation(4290,$translate) }}</strong></td>
            <td>:</td>
            <td>{{ $allsettings->site_currency_symbol }}{{ $product_price }}</td>
          </tr>
          <tr>
            <td><strong>{{ Helper::translation(4395,$translate) }}</strong></td>
            <td>:</td>
            <td>{{ $username }}</td>
          </tr>
          <tr>
            <td><strong>{{ Helper::translation(4302,$translate) }}</strong></td>
            <td>:</td>
            <td>{{ $order_id }}</td>
          </tr>
          <tr>
            <td><strong>{{ Helper::translation(4305,$translate) }}</strong></td>
            <td>:</td>
            <td>{{ $purchase_id }}</td>
          </tr>
          <tr>
            <td><strong>{{ Helper::translation(4308,$translate) }}</strong></td>
            <td>:</td>
            <td>{{ date("d M Y", strtotime($purchase_date)) }}</td>
          </tr>
          <tr>
            <td><strong>{{ Helper::translation(4311,$translate) }}</strong></td>
            <td>:</td>
            <td>{{ date("d M Y", strtotime($expiry_date)) }}</td>
          </tr>
          <tr>
            <td><strong>{{ Helper::translation(4398,$translate) }}</strong></td>
            <td>:</td>
            <td>{{ $payment_type }}</td>
          </tr>
          <tr>
            <td><strong>{{ Helper::translation(4398,$translate) }}</strong></td>
            <td>:</td>
            <td>{{ $payment_token }}</td>
          </tr>
        </table>
      </td>
    </tr>  
    <tr>
      <td colspan="3">
      <p>{{ Helper::translation(4404,$translate) }} <a href="{{ URL::to('/') }}" target="_blank"><strong>{{ URL::to('/') }}</strong></a></p>
      </td>
    </tr>      
    </table>
</body>
</body>
</html>