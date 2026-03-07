@extends('gl-scratch-web.layouts-old.master')
<link href="{{url('glscratch-web/assets/css/demo2/pages/login/login-2.css')}}" rel="stylesheet" type="text/css" />
@section('content')
<div class="kt-grid kt-grid--ver kt-grid--root kt-page">
    <div class="kt-grid kt-grid--hor kt-grid--root kt-login kt-login--v2 kt-login--signin" id="kt_login">
      <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor"
        style="background-image: url({{url('glscratch-web/assetsold/media/logos/Frame.png')}});">
        <div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
          <div class="kt-login__container">
           
            <div class="kt-login__signin">
              <div class="kt-login__head">
                <h2 class="kt-login__title" style="font-size: 28px;font-weight: bold;color:black">{{$messageText}}</h2>
            </div>
            <div class="kt-login__account"> <span class="kt-login__account-msg"> <a href="https://getlead.co.uk"
                  target="new"> Powered by GetLead</a> </span> </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('footer.script')
<script type="text/javascript">
      $(document).ready(function () {
          var BASE_URL = window.location.origin;

      });
</script>
@endpush