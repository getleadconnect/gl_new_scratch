@extends('gl-scratch-web.layouts.master')
<link href="{{url('glscratch-web/assets/css/demo2/pages/login/login-3.css')}}" rel="stylesheet" type="text/css"/>

@section('content')

    <div class="flag">
        <div class="float-left"><img src="{{url('glscratch-web/assets/media/logos/left.png')}}"/></div>
        <div class="float-right"><img src="{{url('glscratch-web/assets/media/logos/right.png')}}"/></div>

    </div>
    <div class="kt-grid kt-grid--ver kt-grid--root kt-page">
        <div class="kt-grid kt-grid--hor kt-grid--root kt-login kt-login--v2 kt-login--signin" id="kt_login">
            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor ">
                <div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
                    <div class="kt-login__container">
                        <div class="kt-login__logo">
                            <a href="#">
                                @if($offer->vchr_scratch_offers_image)

                                    <img src="{{  Storage::disk('s3')->url($offer->vchr_scratch_offers_image) }}" class="main-logo"/>
                                @elseif($user->vchr_logo)
                                    <img src="{{ Storage::disk('s3')->url('uploads/user-profile/' . $user->vchr_logo) }}" class="main-logo"/>
                                @else
                                    <img src="{{url('glscratch-web/assets/media/logos/logo-mini-2-md.png')}}"
                                         class="main-logo"/>
                                @endif
                            </a>
                        </div>
                        
                        {{-- <div class="kt-login__signup">
                            <div class="kt-login__head">
                                <h3 class="kt-login__title">{{$user->vchr_user_name}}</h3>
                                <div class="kt-login__desc">Enter your details to Get Existing Offer:</div>
                            </div>
                            <form class="kt-login__form kt-form" id="gl-scratch-form" method="POST">
                                @csrf
                                <div class="input-group">
                                    <div class="col-12">
                                        <input class="form-control required " type="text" id="customer_name_id"
                                               placeholder="Full Name" name="name" autocomplete="off">
                                    </div>
                                </div>
                                <input type="hidden" id="offer_id" value="{{$shortlink->offer_id}}" name="offer_id">
                                <input type="hidden" id="vendor_id" value="{{$shortlink->vendor_id}}" name="vendor_id">
                               
                            </form>
                        </div> --}}

                        <div class="kt-login__forgot">
                            <div class="scratch-container">
                                <div id="promo" class="scratchpad"></div>
                            </div>
                            <div class="promo-container" style="display: none">
                                <div class="promo-code"></div>
                            </div>
                        </div>


                        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
                             aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">Terms & Conditions</h5>

                                    </div>
                                    <div class="modal-body">
                                        <p>Conditions</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                       


                        <div class="kt-login__account"> <span class="kt-login__account-msg"> <a
                                        href="http://gdealapp.com"
                                        target="new"> Powered by Gdeal</a> </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection


@push('footer.script')

    <script type="text/javascript">
        $(document).ready(function () {

            // var offerListing = {!! json_encode($offer) !!}
            var offerListing = {!! json_encode($offerList) !!}
            // console.log(offerListing)
            glScrtachView(offerListing)
            // displayForgotForm();
               

            var bill_no = getUrlParameter('billno');

            if (bill_no) {
                $('#bill_no_id').val(bill_no);
            }
            var BASE_URL = window.location.origin;
            var login = $('#kt_login');
            var scratch_count = 1;
            login.removeClass('kt-login--signin');
            login.removeClass('kt-login--signup');
            login.addClass('kt-login--forgot');
            KTUtil.animateClass(login.find('.kt-login__forgot')[0], 'flipInX animated');

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
                console.log(offerListing)
                var promoCode = '';
                //   var bg4 = "{{url('glscratch-web/assets/media/logos/banglore-pub/beer.png')}}";
                //   var bg5 = "{{url('glscratch-web/assets/media/logos/banglore-pub/trimmer.png')}}";
                //   var bg1 = "{{url('glscratch-web/assets/media/logos/gift-2.png')}}";
                var bg3 = "{{url('glscratch-web/assets/media/logos/tt-betterluck.png')}}";
                var status = offerListing.int_status;

                if (status == 1) {
                    selectBG = offerListing.image;
                    promoCode = 'Your Gift is: ' + offerListing.txt_description;
                } else {
                    selectBG = bg3;
                    promoCode = 'Oops Try Again!! Next Time';
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
                                var url = BASE_URL + '/scr/gl-scratched/' + customer_id + '/scratch_api';
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
                                        $.alert({
                                            title: 'Error',
                                            type: 'red',
                                            content: res.msg,
                                        });
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

        });

        function getUrlParameter(sParam) {
            var sPageURL = window.location.search.substring(1),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return typeof sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
                }
            }
            return false;
        };
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




@endpush