<!DOCTYPE html>
<html lang="en" >
<!-- begin::Head -->
<head>
<!--begin::Base Path (base relative path for assets of this page) -->
<!--<base href="../../../../">-->
<!--end::Base Path -->
<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <title> {{ isset($title) ? $title : 'Get an existing offer,click the logo link' }} </title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="description" content="Get a Existing offer, click the logo link">
  <meta name="robots" content="index">
  
  <link rel="canonical" href="http://gl1.in">
  
  <meta name="image" content="http://getlead.co.uk/resources/share.png">
  <meta property="og:image" itemprop="image" content="{{url('backend/images/favicon/android-icon-192x192.png')}}">
  <meta property="og:site_name" content="Getlead">
  <meta property="og:title" content="{{ isset($title) ? $title : 'Get an existing offer,click the logo link' }}">    
  <meta name="author" content="{{ isset($title) ? $title : 'Get an existing offer,click the logo link' }}">    
  <meta property="og:description" content="Get a Existing offer, click the logo link" />
  <meta property="og:type" content="website"/>
  <meta property="og:image:type" content="image/jpeg">    
  <meta property="og:url" content="http://gl1.in"> 
  <meta name="description" content="Get a Existing offer, click the logo link">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!--<link rel="shortcut icon" href="./assets/media/logos/favicon.ico" />-->

@include('gl-scratch-web.layouts-new.styles')

<style>
  body{font-family: 'Sora', sans-serif; height: 100vh; } 
    .scratchpad {
      width: 275px;
      height: 275px;
      margin: 50px auto;
    }
    .scratch-container {
      -webkit-touch-callout: none;
      -webkit-user-select: none;
      -khtml-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
      width: 100%;
    }
    .promo-container {
      background:none;
      border-radius: 5px;
      -moz-border-radius: 5px;
      -webkit-border-radius: 5px;
      
      /* padding: 0px 20px; */
      margin: 0 auto;
      text-align: center;
      color: #fff;
      font-size: 16px;
      
    }
    .promo-code{   font-weight: bold;
      font-size: 17px;
      color: #B4CD1A;
    }
    .btn {
      height: 50px;
      display: inline-block;
      text-align: center;
      padding-left: 4rem;
      padding-right: 4rem;
      
      border-radius: 14px;
      background: linear-gradient(270deg, #B100EF -3.36%, #C80084 103.17%);
      box-shadow: 0px 4px 4px 0px rgba(244, 200, 255, 0.89);
      
      width: 100%;
      color: #FFF;
      font-family: sora;
      font-size: 16px;
      font-style: normal;
      font-weight: 700;
      line-height: normal;
      transition: 0.5s;
      
      text-decoration: none;
      
      
      
    }
    .btn:hover{background: linear-gradient(270deg, #FF5733 16.1%, #FF36BB 88.36%) !important;color: #fff !important;
    }
    
    a {
      color: #6b6b6b;
    }
    a:hover {
      color: #dcdcdc;
    }
    
    @media only screen and (max-width : 480px) {
      .scratchpad {
        width: 300px;
        /* height: 300px; */
      }
      .scratch-container {
        /*width: 400px !important;*/
      }
    }
    
    @media only screen and (max-width : 320px) {
      .scratchpad {
        width: 300px;
        height: 274px;
      }
      .scratch-container {
        /*width: 290px !important;*/
      }
    }
    .kt-link:hover:after {
      border-bottom: 0px !important;
      opacity: 0.3;
    }
    
    .kt-login__signup,
    .kt-login__otp {
      position: relative;
      z-index: 1;
      /* height: 100vh; */
    }
    .kt-login__signup::before,
    .kt-login__otp::before {
      content: url(/glscratch-web/assetsold/new/media/logos/element.svg);
      position: absolute;
      z-index: -1;
      right: 5%;
      top: -15%;
    }
    
    .kt-login__signup::after {
      content: url(/glscratch-web/assetsold/new/media/logos/reg-after.svg);
      position: absolute;
      z-index: -1;
      left: -8%;
      bottom: -18px;
    }
    
    .kt-login__forgot {
      position: relative;
      margin-top: 15%;
    }
    
    
    .kt-login__otp::after {
      content: url(/glscratch-web/assetsold/new/media/logos/element-after.svg);
      position: absolute;
      z-index: -1;
      right: -26px;
      bottom: 150px;
    }
    
    .kt-login__forgot {
      position: relative !important;z-index: 1;
    }
    .kt-login__forgot::before {
      content: url(/glscratch-web/assetsold/new/media/logos/element.svg);
      position: absolute;
      z-index: -1;
      right: -10%;
      top: 0%;
    }
    
    .kt-login__forgot::after {
      content: url(/glscratch-web/assetsold/new/media/logos/element-after.svg);
      position: absolute;
      z-index: -1;
      right: -10%;
      bottom: 38%;
    }
    label.error {
        height:17px;
        font-size:small;
        color: #c40000;
    }
    .captureBtn {
      text-align : center !important;
      margin-top: 10px;
    }
    
</style>

  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-144098362-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-144098362-1');
</script>

{{-- Clarity --}}
<script type="text/javascript">
  (function(c,l,a,r,i,t,y){
      c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
      t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
      y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
  })(window, document, "clarity", "script", "dgbpmk8h68");
</script>

</head>
<body  class="kt-page--loading-enabled kt-page--loading kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header--minimize-topbar kt-header-mobile--fixed kt-subheader--enabled kt-subheader--transparent kt-page--loading"  >
    @yield('content')
    @stack('scratch-script')
    @include('gl-scratch-web.layouts-old.scripts')
    @stack('footer.script')
</body>
</html>
