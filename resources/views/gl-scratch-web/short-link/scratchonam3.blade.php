<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Oxygen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="{{asset('scratchonam/3/css/bootstrap.css')}}" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 0;
        background-color: #f5f5f5;
      }
      canvas,#promo img {
        margin: auto;
        display: block;
      }

      .form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }

    </style>
    <link href="{{asset('scratchonam/3/css/bootstrap-responsive.css')}}" rel="stylesheet">
    <link href="{{asset('scratchonam/3/css/otp-form.css')}}" rel="stylesheet">
    <link href="{{asset('scratchonam/3/css/responsive.css')}}" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{asset('scratchonam/3/images/apple-touch-icon-144-precomposed.png')}}">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{asset('scratchonam/3/images/apple-touch-icon-114-precomposed.png')}}">
      <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{asset('scratchonam/3/images/apple-touch-icon-72-precomposed.png')}}">
                    <link rel="apple-touch-icon-precomposed" href="{{asset('scratchonam/3/images/apple-touch-icon-57-precomposed.png')}}">
                                   <link rel="shortcut icon" href="{{asset('scratchonam/3/images/favicon.png')}}">


 <!-- Facebook Pixel Code -->
 <script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '2688679058053948');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=2688679058053948&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-179241331-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-179241331-1');
</script>
<!-- End Google Analytics -->

  </head>

  <body>
    <nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header logo-align">
      <a class="navbar-brand" href="#">
        <img alt="Brand" src="{{asset('scratchonam/3/images/oxygen.png')}}" class="header-logo">
      </a>
    </div>
  </div>
</nav>

  <div class="wrapper step1">
    
    <div  class="container">
    <div class="row">
              <div class=" col-sm-12 col-md-6 ">
                  <div class="scratch-div">
                <img src="{{asset('scratchonam/3/images/scratch.png')}}" class="img-responsive scratch-img">
                   <img src="{{asset('scratchonam/3/images/oxygen-f.png')}}" class="img-responsive steps-img d-sm-none d-none d-md-block">

                   </div>

              </div>

              <div class="col-md-6 col-sm-12 text-center">
                <a href=""><img src="{{asset('scratchonam/3/images/text.png')}}" class="img-responsive img-div"></a>
                <a href="#" class="clickhere_btn"><img src="{{asset('scratchonam/3/images/button.png')}}" class="img-responsive button-div"></a>
              </div>
        </div>
    </div>

    <div class="container">
      <div class="row">
        <div class="col-md-12 text-center">
        <img src="{{asset('scratchonam/3/images/oxygen-f2.png')}}" class="img-responsive steps-img  d-md-none d-lg-none ">
      </div>
      </div>
    </div>  

  </div>
  <div class="wrapper2   kt-login kt-login--v2 kt-login--signin step2" style="display:none">
    <div  class="">
        <div class="">
          <div class="">

              <div class="kt-grid kt-grid--hor kt-grid--root kt-login kt-login--v2 kt-login--signin" id="kt_login">
                    <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor ">
                        <div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
                            <div class="kt-login__container">
                                <div class="kt-login__signup">
                                    <div class="kt-login__head">
                                        <div class="kt-login__desc"><img src="{{asset('scratchonam/3/images/verification.png')}}" class="verification-img"></div>
                                    </div>
                                    <form class="kt-login__form kt-form" id="gl-scratch-form" method="POST">
                                    @csrf   
                                        <div class="input-group sendotp">
                                            <div class="col-12">
                                                <input class="form-control required " type="text" id="customer_name_id"
                                                      placeholder="Full Name" name="name" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="input-group sendotp">
                                            <div class="row col-12 pr-1 mobile-input">

                                                <div class="col-4 ">
                                                    <select class="form-control minimal" id="country_code_id" name="country_code"
                                                            style=" padding-left: 1rem; padding-right: 1rem;"
                                                            autocomplete="off">
                                                        <option value="91" selected>+91
                                                        <option value="973">+973

                                                    </select>
                                                </div>
                                                <div class="col-8 pr-0 pl-1 ">
                                                    <input class="form-control  " type="number" id="mobile_number_id"
                                                          placeholder="Mobile number" name="mobile"
                                                          autocomplete="off">
                                                </div>
                                            </div>


                                        </div>

                                        
                                        <div class="kt-login__actions sendotp" id="submit-btn">
                                            <button id="kt_login_forgot" class="btn btn-pill kt-login__btn-primary">Verify
                                                Mobile
                                            </button>
                                            &nbsp;&nbsp;
                                        </div>
                                    </form>
                                    <form class="kt-login__form kt-form">
                                        <div class="input-group verifyotp" style="display:none">
                    
                                            <div class="col-12" >
                                                <input class="form_textarea form-control" type="number" id="gl_otp_id" placeholder="OTP" name="gl_otp_id"
                                                autocomplete="off">
                                            </div>
                                            

                                        </div>
                                        <div class="kt-login__actions verifyotp" id="submit-btn2" style="display:none">
                                                <button class="btn btn-pill kt-login__btn-primary btn-verify" id="kt_login_forgot_2">Verify
                                                    OTP
                                                </button>
                                                &nbsp;&nbsp;
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
          </div>
        </div>
    </div>

  </div>
  <div class="wrapper2  step3" style="display:none" >
    <div  class="">
        <div class="">
              <div class="">
                  <div class="kt-grid kt-grid--hor kt-grid--root kt-login kt-login--v2 kt-login--signin" id="kt_login">
                            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor ">
                                <div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
                                    <div class="kt-login__container">
                                        <div class="kt-login__signup">
                                            
                                                <form class="kt-login__form kt-form" id="gl-scratch-form" method="POST">
                                                                
                                                        <div>
                                                            <div>
                                                                <div id="promo" class="scratchpad">
                                                                    
                                                                </div>
                                                            </div>
                                                            <div class="promo-container" style="display: none">
                                                                <div class="promo-code"></div>
                                                            </div>
                                                        </div>
                                                </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
<div class="footerr py-3">
 <div class="container">
  <div class="row">
    <div class="col-md-6">
     <div class="footer-copyright text-left ">
      <a href="/scratch/terms"> Terms & Conditions</a>
    </div>
    <!-- Copyright -->
    </div>
    <!-- Copyright -->

    <div class="col-md-6">
    <!-- Copyright -->
    <div class="footer-copyright text-right" style="color: #fff"> © 2021 
      <a href="#"> Oxygen.</a> All Rights Reserved.
    </div>
    <!-- Copyright -->

    </div>
  </div>
</div>


<script>
    var KTAppOptions = {
      "colors": {
        "state": {
          "brand": "#374afb",
          "light": "#ffffff",
          "dark": "#282a3c",
          "primary": "#5867dd",
          "success": "#34bfa3",
          "info": "#36a3f7",
          "warning": "#ffb822",
          "danger": "#fd3995"
        },
        "base": {
          "label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
          "shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
        }
      }
    };
  </script>



    <script src="{{url('glscratch-web/assets/vendors/general/jquery/dist/jquery.js')}}" type="text/javascript"></script>
    <script src="{{url('glscratch-web/assets/vendors/general/jquery/dist/jquery-migrate-1.4.1.min.js')}}"></script>
    <script src="{{url('glscratch-web/assets/vendors/general/popper.js/dist/umd/popper.js')}}" type="text/javascript"></script>
    
    <script src="{{url('glscratch-web/assets/vendors/general/bootstrap/dist/js/bootstrap.min.js')}}" type="text/javascript"></script>
    <script src="{{url('glscratch-web/assets/js/demo2/pages/login/login-general.js')}}" type="text/javascript"></script>
    <!-- <script src="{{url('glscratch-web/assets/js/demo2/pages/login/jq.js')}}" type="text/javascript"></script> -->
    <script src="{{url('glscratch-web/assets/js/demo2/pages/login/scratchie.js')}}" type="text/javascript"></script>
    <script src="{{url('glscratch-web/assets/js/demo2/pages/login/jquery.countdown360.js')}}" type="text/javascript"></script>
    <script src="{{url('glscratch-web/assets/js/demo2/pages/login/jquery.countdown360.js')}}" type="text/javascript"></script>
    <script type= "text/javascript" src="{{url('js/jquery-validation/dist/jquery.validate.min.js')}}"></script>
    <script type="text/javascript" src="{{url('js/jquery-validation/dist/additional-methods.min.js')}}"></script>
    <!-- start -->

    <script type="text/javascript" src="{{ url('backend/libs/jquery-confirm/jquery-confirm.min.js') }}"></script>
    <script src="{{asset('scratchonam/assets/notify.min.js')}}" ></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-131111019-5"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-131111019-5');
    </script>

<script>
        var KTAppOptions = {
            "colors": {
                "state": {
                    "brand": "#374afb",
                    "light": "#ffffff",
                    "dark": "#282a3c",
                    "primary": "#5867dd",
                    "success": "#34bfa3",
                    "info": "#36a3f7",
                    "warning": "#ffb822",
                    "danger": "#fd3995"
                },
                "base": {
                    "label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
                    "shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
                }
            }
        };
    </script>



    <script>
        $(document).ready(function(){
            $('.clickhere_btn').on('click',function(){
                $('.step1').toggle();
                $('.step2').toggle();
            })
        })
    </script>
    <script type="text/javascript">
    jQuery.validator.addMethod("phoneStartingWithout0", function(phone_number, element) {
        return phone_number.startsWith("0") ? false : true;
    }, "Phone number should not start with 0");
        $(document).ready(function () {


            // $('#countdown-model').modal('show');
            // $('#countdown-model').show();
            var BASE_URL = window.location.origin;

            // $('#countdown-model').hide();

            var login = $('#kt_login');
            var scratch_count = 1;


            $('#kt_login_forgot_2').on('click', function (e) {

                $('#gl-scratch-form').validate({
                    rules: {
                        name: {
                            required: true,
                        },

                        mobile: {
                            required: true,
                            maxlength: 12,
                            minlength: 8,
                            phoneStartingWithout0: true
                        },
                        gl_otp_id: {
                            required: true,
                        },


                    },

                });

                if ($('#gl-scratch-form').valid()) {
                   // alert('here');
                    // var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
                    // if (isMobile) {
                    //     window.open('tel:+919649778222');
                    //   } else {
                    //       var phone_html=`Please Give a Missed Call to  <strong> +91 9649778222 </strong>  For Verification`;
                    //         $("#phone-number-id").html(phone_html)
                    //   }
                    $("#kt_login_forgot").html("Verifying");
                    $('#kt_login_forgot').attr("disabled", true);
                    e.preventDefault();

                    var otp = $("#gl_otp_id").val();
                    var mobile = $("#mobile_number_id").val();
                    var country_code = $("#country_code_id").val();
                    var name = $("#customer_name_id").val();
                    var bill_no = $('#bill_no_id').val() || '';
                    var email = $('#customer_email_id').val();

                    // countdown();
                    // $('#countdown-model').show();

                    var data = {
                        bill_no: bill_no,
                        otp: otp,
                        mobile: mobile,
                        name: name,
                        email: email,
                        country_code: country_code,
                        _token: ' {{csrf_token()}}',
                        short_link: '{{$shortlink->link}}',
                        offer_id:{{$offer->pk_int_scratch_offers_id}},
                        user_id: {{$user->pk_int_user_id}}
                    }
                    var url = BASE_URL + '/scr/scratch-web-customer';
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: data,
                    }).done(function (res) {
                        if (res.status === true) {
                            $("#kt_login_forgot").html("Verify Mobile");
                            $('#kt_login_forgot').attr("disabled", false);
                            $('#countdown-model').modal('hide');
                           $('.step2').toggle();
                            $('.step3').toggle();
                            // $('#countdown-model').hide();
                            var offerListing = res.offerListing
                            console.log(offerListing);
                            glScrtachView(offerListing);
                            displayForgotForm();

                        } else if (res.status === false) {
                            //$.notify(res.msg, "error");
                            Swal.fire({
                            title: 'Error!',
                            text: res.msg,
                            icon: 'error',
                            confirmButtonText: 'OK'
                            })
                            $("#kt_login_forgot").html("Verify Mobile");
                            $('#kt_login_forgot').attr("disabled", false);
                            $('#countdown-model').modal('hide');
                            // $('#countdown-model').hide();
                        }
                    }).fail(function () {
                        $("#kt_login_forgot").html("Verify Mobile");
                        $('#kt_login_forgot').attr("disabled", false);
                        $('#countdown-model').modal('hide');
                    }).always(function (com) {
                        $("#kt_login_forgot").html("Verify Mobile");
                        $('#kt_login_forgot').attr("disabled", false);
                        // $('#countdown-model').hide();
                        $('#countdown-model').modal('hide');

                    })


                }


            });

            function countdown() {
                var countdown = $("#countdown").countdown360({
                    radius: 60,
                    seconds: 45,
                    fontColor: '#FFFFFF',
                    autostart: false,
                    onComplete: function () {
                        window.location.reload();
                    }
                });
                countdown.start();
                console.log('countdown360 ', countdown);
                $(document).on("click", "button", function (e) {
                    e.preventDefault();
                    var type = $(this).attr("data-type");
                    if (type === "time-remaining") {
                        var timeRemaining = countdown.getTimeRemaining();

                    } else {
                        var timeElapsed = countdown.getElapsedTime();

                    }
                });
            }


            var displayForgotForm = function () {

                login.removeClass('kt-login--signin');

                login.removeClass('kt-login--signup');
                login.addClass('kt-login--forgot');

            //    KTUtil.animateClass(login.find('.kt-login__forgot')[0], 'flipInX animated');


            }


            function glScrtachView(offerListing) {

                var promoCode = '';
                //   var bg4 = "{{url('glscratch-web/assets/media/logos/banglore-pub/beer.png')}}";
                //   var bg5 = "{{url('glscratch-web/assets/media/logos/banglore-pub/trimmer.png')}}";
                //   var bg1 = "{{url('glscratch-web/assets/media/logos/gift-2.png')}}";
                var bg3 = "{{url('glscratch-web/assets/media/logos/tt-betterluck.png')}}";
                var status = offerListing.int_winning_status;

                if (status == 1) {
                    selectBG = offerListing.image;
                    promoCode = 'Your Gift is: ' + offerListing.txt_description;
                } else {
                    selectBG = bg3;
                    promoCode = 'Oops Try Agin!! Next Time';
                }


                $('#promo').wScratchPad({

                    size: 50,

                    bg: selectBG,

                    realtime: true,
                    // The overlay image
                    fg: "{{url('glscratch-web/assets/media/logos/tt-scratchhere.png')}}",

                    'cursor': 'url("https://jennamolby.com/scratch-and-win/images/coin1.png") 5 5, default',

                    scratchMove: function (e, percent) {
                        // Show the plain-text promo code and call-to-action when the scratch area is 50% scratched
                        if ((percent > 60) && (promoCode != '')) {
                            if (scratch_count == 1) {
                                scratch_count = 2;
                                var customer_id = offerListing.customer_id;
                                var url = BASE_URL + '/scr/gl-scratched/' + customer_id;
                                $.ajax({
                                    url: url,
                                    method: 'POST',
                                    data: {_token: ' {{csrf_token()}}'}
                                }).done(function (res) {
                                    if (res.status === true) {
                                        $('.promo-container').show();
                                        $('body').removeClass('not-selectable');
                                        $('.promo-code').html(promoCode);
                                        setTimeout(function(){
                                            window.location.href="/scratch/thank-you";
                                        },2000)

                                    } else if (res.status === false) {
                                        //$.notify(res.msg, "error");
                                        Swal.fire({
                                        title: 'Error!',
                                        text: res.msg,
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                        })
                                    }
                                }).fail(function () {
                                }).always(function (com) {
                                    //      $
                                });


                            }


                        }
                        else if ((percent > 60) && (promoCode == '')) {
                            setTimeout(function(){
                                window.location.href="/scratch/thank-you";
                            },2000)
                        }
                    }
                });

            }

            // $('#mobile_number_id').on('focusout',function(){
            $('#kt_login_forgot').on('click', function (e) {
                e.preventDefault();
                $('#gl-scratch-form').validate({    
                    rules: {


                        mobile: {
                            required: true,
                            maxlength: 12,
                            minlength: 8,
                            phoneStartingWithout0: true
                        },

                    },
                });
                if ($('#gl-scratch-form').valid()) {
                    $("#kt_login_forgot").html("Verifying");
                    $('#kt_login_forgot').attr("disabled", true);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });

                    var custom_field = $('#custom_field').val();
                    var mobile = $('#mobile_number_id').val();
                    var bill_no = $('#bill_no_id').val();
                    var country_code = $("#country_code_id").val();
                    var offer_id = $("#offer_id").val();
                    var vendor_id = $("#vendor_id").val();
                    var url = BASE_URL + '/scr/gl-verify-mobile';
                    var email = $('#customer_email_id').val();
                    var user_id = {{$user->pk_int_user_id}};
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {
                            user_id: user_id,
                            mobile: mobile,
                            email: email,
                            country_code: country_code,
                            _token: ' {{csrf_token()}}',
                            offer_id: offer_id,
                            vendor_id: vendor_id,
                            bill_no: bill_no,
                            custom_field: custom_field
                        }
                    }).done(function (res) {
                        if (res.status === true) {
                            $('#countdown-model').modal('show');

                            $('.sendotp').toggle();
                            $('.verifyotp').toggle();

                            // $('#countdown-model').show();
                            // $.alert({
                            //     title: 'Success',
                            //     type: 'green',
                            //     content: res.msg,
                            // });

                            //$('#gl_otp_id').a
                        } else if (res.status === false) {
                            //$.notify(res.msg, "error");
                            Swal.fire({
                            title: 'Error!',
                            text: res.msg,
                            icon: 'error',
                            confirmButtonText: 'OK'
                            })
                        }
                    }).fail(function () {
                        $.notify('Something Went Wrong Try Again', "error");
                    }).always(function (com) {
                        $("#kt_login_forgot").html("Verify Mobile");
                        $('#kt_login_forgot').attr("disabled", false);
                    });
                }
            });
        });
    </script>
    <script>
        var KTAppOptions = {
            "colors": {
                "state": {
                    "brand": "#374afb",
                    "light": "#ffffff",
                    "dark": "#282a3c",
                    "primary": "#5867dd",
                    "success": "#34bfa3",
                    "info": "#36a3f7",
                    "warning": "#ffb822",
                    "danger": "#fd3995"
                },
                "base": {
                    "label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
                    "shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
                }
            }
        };
    </script>




  </body>
</html>
