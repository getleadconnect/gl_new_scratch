<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scratch card</title>

    <link rel="stylesheet" href="{{asset('scratchonam/assets/bootstrap/css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('scratchonam/style.css')}}">
    <link href="{{url('glscratch-web/assets/css/demo2/pages/login/login-3.css')}}" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="{{asset('scratchonam/style.css')}}">
</head>
<body>

    <section class="body" id="baner2page">
        <img src="{{asset('scratchonam/images/yellow-color.png')}}" class="yellow_clr">
        <img src="{{asset('scratchonam/images/flower1.png')}}" class="flower1">
        <img src="{{asset('scratchonam/images/flower2.png')}}" class="flower2">
        <img src="{{asset('scratchonam/images/flower3.png')}}" class="flower3">

        <div class="container">
            <div class="col-lg-6 offset-lg-3">
                <div class="col-md-8 pt-3 mobile">
                    <img src="{{asset('scratchonam/images/mobile.png')}}" class="mobile_img" id="img_mble">
                </div>
            </div>

            <div class="pt-2 step1">
                <h4>സ്ക്രാച് <span> ചെയ്യൂ,</span> <br>
                    സമ്മാനങ്ങൾ <span>നേടൂ</span> </h4>


                        <div class="clickhere_btn col-md-3">
                            <a href="#" class="btn click_btn ">ക്ലിക്ക് ചെയ്യൂ </a>    
                        </div>


                    <h3>സ്ക്രാച്  ചെയ്യുന്നതിന്  മുൻപ് വെരിഫിക്കേഷൻ പൂർത്തിയാക്കാനായി നിങ്ങളുടെ പേരും മൊബൈൽ നമ്പറും നൽകുക  </h3>
            </div>
            <div class="pt-4 step2" style="display:none">
                <h4>വെരിഫിക്കേഷനു  വേണ്ടി താഴെ പറയുന്നവ രേഖപ്പെടുത്തുക.</h4>

                <h3>ആകർഷകമായ സമ്മാനങ്ങൾ നേടാം  </h3>
                <div class="col-lg-6 offset-lg-3" id="onam_form">
                <form class="kt-login__form kt-form" id="gl-scratch-form" method="POST">
                @csrf
                <div class="form-group sendotp">
                    <input class="form_textarea form-control required " type="text" id="customer_name_id"
                                               placeholder="Full Name" name="name" autocomplete="off">
                </div>
                <input type="hidden" id="offer_id" value="{{$shortlink->offer_id}}" name="offer_id">
                                <input type="hidden" id="vendor_id" value="{{$shortlink->vendor_id}}" name="vendor_id">

                <div class="form-group sendotp">
                   
                    <div class="row">
                        <div class="col-md-3" id="code_space">
                            <select class="form-select form-control cntry_code" aria-label=""  id="country_code_id" name="country_code">
                                <option value="91" selected>+91</option>
                              </select>
                        </div>
                        <div class="col-md-9" id="code_space2">
                            <input type="text" class="form_textarea form-control"  type="number" id="mobile_number_id"
                                                   placeholder="Mobile number" name="mobile"
                                                   autocomplete="off">
                        </div>
                        <div class="col-md-4 mt-4">
                            <a style="cursor:pointer;" type="button" class="btn btn-success"  id="kt_login_forgot">Submit</a>
                        </div>

                    </div>
                </div>
                </form>
                <div class="form-group verifyotp" style="display:none">
                   
                    <div class="row">
                       
                        <div class="col-md-12" >
                            <input class="form_textarea form-control" type="number" id="gl_otp_id" placeholder="OTP" name="gl_otp_id"
                               autocomplete="off">
                        </div>
                        <div class="col-md-4 mt-4">
                            <a style="cursor:pointer;" type="button" class="btn btn-success btn-verify" id="kt_login_forgot_2">Verify OTP</a>
                        </div>

                    </div>
                </div>

            </div>

            </div>
            <div class="pt-4 step3" style="display:none">
                <div class="kt-login__forgot">
                    <div class="scratch-container">
                        <div id="promo" class="scratchpad"></div>
                    </div>
                    <div class="promo-container" style="display: none">
                        <div class="promo-code"></div>
                    </div>
                </div>
            </div>
        </div>

    </section>


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

    {{-- Clarity --}}
    <script type="text/javascript">
        (function(c,l,a,r,i,t,y){
            c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
            t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
            y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
        })(window, document, "clarity", "script", "dgbpmk8h68");
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
                        },
                        gl_otp_id: {
                            required: true,
                        },


                    },

                });

                if ($('#gl-scratch-form').valid()) {
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
                            glScrtachView(offerListing)
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

                KTUtil.animateClass(login.find('.kt-login__forgot')[0], 'flipInX animated');


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