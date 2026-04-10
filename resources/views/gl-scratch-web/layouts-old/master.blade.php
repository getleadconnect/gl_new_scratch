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

  <meta name="apple-mobile-web-app-capable" content="yes">
  
  
  <meta name="description" content="Get a Existing offer, click the logo link">
  
  <meta name="robots" content="index">
  <link rel="canonical" href="http://gl1.in">
  
    <meta name="image" content="http://getlead.co.uk/resources/share.png">
    <meta property="og:image" itemprop="image" content="{{url('backend/images/favicon/android-icon-192x192.png')}}"">
    <meta property="og:site_name" content="Getlead">
    <meta property="og:title" content="{{ isset($title) ? $title : 'Get an existing offer,click the logo link' }}">    
    <meta name="author" content="{{ isset($title) ? $title : 'Get an existing offer,click the logo link' }}">    
    <meta property="og:description" content="Get a Existing offer, click the logo link" />
    <meta property="og:type" content="website"/>
    <meta property="og:image:type" content="image/jpeg">    
    <meta property="og:url" content="http://gl1.in"> 


  <meta name="description" content="Get a Existing offer, click the logo link">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;400;500;700&family=Noto+Sans&display=swap">
<link href="{{url('glscratch-web/assetsold/css/demo2/pages/login/login-2.css')}}" rel="stylesheet" type="text/css" />

<link href="{{url('glscratch-web/assetsold/vendors/general/animate.css/animate.css')}}" rel="stylesheet" type="text/css" />
<link href="{{url('glscratch-web/assetsold/css/demo2/style.bundle.css')}}" rel="stylesheet" type="text/css" />

<!--<link rel="shortcut icon" href="./assets/media/logos/favicon.ico" />-->
<style type="text/css">
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
	 
	padding: 0px 20px;
	margin: 0 auto;
	text-align: center;
	color: #fff;
	font-size: 16px;
 
}
	.promo-code{   font-weight: bold;
    font-size: 17px;
    color: #586fdd;
	}
.btn {
  height: 50px;
    display: inline-block;
    text-align: center;
    padding-left: 4rem;
    padding-right: 4rem;
    margin-top: 1rem;
    background: linear-gradient(270deg, #FF5733 16.1%, #FF3649 88.36%);
    border-radius: 4px !important;
    width: 100%;
	color: #FFF;
  transition: 0.5s;
 
	text-decoration: none;
 
	text-transform: uppercase;
  
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
	height: 274px;
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

.kt-login__signup {position: relative;}
.kt-login__signup::before {
  content:url('../../glscratch-web/assetsold/media/logos/black-star.svg');
  position: absolute;
  z-index: 100000;
  right: -10%;
  top:30%;}

  .kt-login__signup::after {
  content:url('../../glscratch-web/assetsold/media/logos/yellow-star.svg');
  position: absolute;
  z-index: 100000;
  left: -20%;
  bottom:30%;}



.kt-login__forgot {position: relative;}
.kt-login__forgot::before {
  content:url('../../glscratch-web/assetsold/media/logos/black-star.svg');
  position: absolute;
  z-index: 100000;
  right: -10%;
  top:30%;}

  .kt-login__forgot::after {
  content:url('../../glscratch-web/assetsold/media/logos/yellow-star.svg');
  position: absolute;
  z-index: 100000;
  left: -20%;
  bottom:10%;
}
.d-image{
  display: flex;
  justify-content: center;
}
.d-image img{
  width: 100%;
    justify-content: center;
}
.kt-code{
  font-weight: 600 !important; 
}

label.error {
    height:17px;
    /* margin-left:9px; */
    padding:1px 5px 0px 5px;
    font-size:small;
    color: red;
}
.hide-banner{
  display: none !important;
}
  
</style>

@include('gl-scratch-web.layouts-new.styles')

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
