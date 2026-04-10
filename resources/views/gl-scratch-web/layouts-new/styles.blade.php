@isset($user)
@if($user->vchr_logo !=NULL)  
  <link rel="icon" type="image/png"  href="{{ url($user->vchr_logo) }}">
@else
  <link rel="icon" type="image/png"  href="{{url('glscratch-web/img/gl-icon.png')}}">
@endif
@endisset

@empty($user)
  <link rel="icon" type="image/png"  href="{{url('glscratch-web/img/gl-icon.png')}}">
@endempty
  
<link href="{{ url('glscratch-web/assetsold/new/css/login-2.css') }}" rel="stylesheet" type="text/css" />
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="{{ url('glscratch-web/assetsold/vendors/general/animate.css/animate.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ url('glscratch-web/assetsold/new/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />

<link  href="{{ url('backend/css/jquery-ui.css')}}" rel="stylesheet" type="text/css">
<link  href="{{ url('backend/libs/jquery-confirm/jquery-confirm.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{url('glscratch-web/css/select2.min.css')}}" rel="stylesheet" />

