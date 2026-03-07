
@extends('gl-scratch-web.layouts-old.master')
<style>
  
/* WIDGET */
.widget-wrap {
    max-width: 500px;
    padding: 30px;
    border-radius: 20px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background: #fff;
}
#demo button {
  color: #fff;
  background: #b90a0a;
  border: 0;
  padding: 10px;
  cursor: pointer;
  font-weight: 700;
}
.codeqr {
  display : flex;
  flex-direction : column;
  justify-content : center;
  align-items : center;
  background : #fff;
}

</style>
@section('content')
<!-- begin::Body -->
<div class="kt-grid kt-grid--ver kt-grid--root kt-page">
    <div class="kt-grid kt-grid--hor kt-grid--root kt-login kt-login--v2 kt-login--signin" id="kt_login">
    @if($expired != true)    
        <div id="carouselExampleIndicators" class="carousel slide desktop" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    @if($offer->vchr_scratch_offers_image)
                        <img src="{{ Storage::disk('s3')->url($offer->vchr_scratch_offers_image) }}" class="d-block img-fluid"/>
                    @elseif($user->vchr_logo)
                        <img src="{{ Storage::disk('s3')->url('uploads/user-profile/' . $user->vchr_logo) }}" class="d-block img-fluid"/>
                    @else
                        <img src="{{url('glscratch-web/assets/media/logos/logo-mini-2-md.png')}}"
                            class="d-block img-fluid"/>
                    @endif
                </div>
            </div>
        </div>

        <div id="carouselExampleIndicators" class="carousel slide mob" data-ride="carousel">
            <div class="carousel-inner">
              <div class="carousel-item active">
                @if($offer->vchr_scratch_offers_image)
                    <img src="{{ Storage::disk('s3')->url($offer->vchr_scratch_offers_image) }}" class="d-block img-fluid"/>
                @elseif($user->vchr_logo)
                    <img src="{{ Storage::disk('s3')->url('uploads/user-profile/' . $user->vchr_logo) }}" class="d-block img-fluid"/>
                @else
                    <img src="{{url('glscratch-web/assets/media/logos/logo-mini-2-md.png')}}"
                        class="d-block img-fluid"/>
                @endif
              </div>
            </div>
        </div>
    @endif    
        <div class="clear"></div>
        @if($expired != true)    
            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="demo" style="background: #fff" >
                <div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
                    <div class="kt-login__container">
                
                        <div class="kt-login__forgot fliped">
                            <div class="scratch-container">
                                <div class="kt-login__head">
                                    <h3 class="kt-login__title" style="color:#FF5733">Scratch the card below & you could earn exciting gift</h3>
                                    <div class="kt-login__desc kt-code">Your redeem code is :{{$offerList->uniqueId}}</div>
                                </div>
                                <div id="promo" class="scratchpad"></div>
                            </div>
                            <div class="promo-container" style="display: none">
                                <div class="promo-code"> </div>
                            </div>
                            <div class="widget-wrap captureBtn d-none screenSt">
                                <button onclick="capture('{{$offerList->uniqueId}}')">
                                  Take a screenshot.
                                </button>
                            </div>
                        </div>

                    </div>
                    <div class="codeqr">
                        <div id="qrcode-2" class="text-center"></div>
                    </div>
                </div>
            </div>
        @else
            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor widget-wrap"  id="demo" style="background: #fff" >
                <div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
                    <div class="kt-login__container">
                        <div class="kt-login__forgot">
                            <div class="scratch-container">
                                <h3 class="kt-login__title text-center" style="color:#FF5733">You have already scratched the card,  @if($offerList->int_status == 1) please redeem using below code</h3>
                                <div class="kt-login__head">
                                    
                                    <div class="kt-login__desc kt-code">Your redeem code is :{{$offerList->uniqueId}}</div>@endif
                                </div>
                                <div class="d-image">
                                        <img src="{{ $offerList->image }}" alt="">
                                </div>
                                <div class="widget-wrap captureBtn">
                                    <button onclick="capture('{{$offerList->uniqueId}}')">
                                      Take a screenshot
                                    </button>
                                </div>
                            </div>
                            @if($offerList->int_winning_status == 1)
                            <div class="promo-container" style="display: block">
                                <div class="promo-code">
                                    <span style="color:#ffa01d">Congratulations {{ $offerList->customer_name }}!!</span>
                                    <br> 
                                    <span style="color:#000;font-size:14px">You have won:</span> {{ $offerList->txt_description }}
                                </div>
                            </div>
                            @else
                                <div class="promo-container" style="display: block">
                                    <div class="promo-code">
                                        <span style="color:#ffa01d">Thank you {{ $offerList->customer_name }}!!</span>
                                        <br> 
                                        <span style="color:#000;font-size:14px">Better luck next time
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                    </div>
                    <div class="codeqr">
                        <div id="qrcode-2" class="text-center"></div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

 <script>
    var KTAppOptions = {"colors":{"state":{"brand":"#374afb","light":"#ffffff","dark":"#282a3c","primary":"#5867dd","success":"#34bfa3","info":"#36a3f7","warning":"#ffb822","danger":"#fd3995"},"base":{"label":["#c5cbe3","#a1a8c3","#3d4465","#3e4466"],"shape":["#f0f3ff","#d9dffa","#afb4d4","#646c9a"]}}};
</script> 

@endsection

@push('footer.script')
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.4/html2canvas.min.js"></script>
<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script> -->

<script src="{{asset('assets/htmlcanvas/js/html2canvas-1.3.4.min.js')}}"></script>
<script src="{{asset('assets/qrcode/js/qrcode.min.js')}}"></script>

<script>
    $(document).ready(function () {
        var offerListing = {!! json_encode($offerList) !!}
        glScrtachView(offerListing)        
        var BASE_URL = window.location.origin;
        var login = $('#kt_login');
        var scratch_count = 1;
        login.removeClass('kt-login--signin');
        login.removeClass('kt-login--signup');
        login.addClass('kt-login--forgot');
        if(login.find('.fliped')[0])
        KTUtil.animateClass(login.find('.fliped')[0], 'flipInX animated');

    
        var displayForgotForm = function () {
            login.removeClass('kt-login--signin');
            login.removeClass('kt-login--signup');
            login.addClass('kt-login--forgot');
            if(login.find('.fliped')[0])
            KTUtil.animateClass(login.find('.fliped')[0], 'flipInX animated');
        }

        function glScrtachView(offerListing) {
            var promoCode = '';
            var bg3 = "{{url('glscratch-web/assets/media/logos/tt-betterluck.png')}}";
            var status = offerListing.int_winning_status;

            if (status == 1) {
                selectBG = offerListing.image;
                promoCode =   '<span style="color:#ffa01d">Congratulations '+offerListing.customer_name+'!!</span><br> <span style="color:#000;font-size:14px">You have won:</span> ' + offerListing.txt_description;
                    
            } else {
                selectBG = offerListing.image;
                promoCode = 'Oops Try Again!! Next Time';
            }

            $('#promo').wScratchPad({
                size: 50,
                bg: selectBG,
                realtime: true,
                // The overlay image
                fg: "{{url('glscratch-web/assetsold/media/logos/scratch-here.svg')}}",
                
                'cursor': 'url("../glscratch-web/assetsold/media/logos/coin.png") 5 5, default',

                scratchMove: function (e, percent) {
                    // Show the plain-text promo code and call-to-action when the scratch area is 50% scratched
                    if ((percent > 20) && (promoCode != '')) {
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
                                    $('#promo').wScratchPad('clear');
                                    confetti();
                                    $('.screenSt').removeClass('d-none')

                                } else if (res.status === false) {
                                    $.alert({
                                        title: 'Error',
                                        type: 'red',
                                        content: res.msg,
                                    });
                                }
                            }).fail(function () {
                            }).always(function (com) {
                            });
                        }
                    }
                }
            });
        }
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

    function capture (uniqueId) {
        generateQr(uniqueId);
        $('.kt-login__title').hide()
            var target =document.getElementById('demo');
            html2canvas(target).then((canvas) => {
                let a = document.createElement("a");
                a.download = uniqueId+".png";
                a.href = canvas.toDataURL("image/png");
                a.click(); // MAY NOT ALWAYS WORK!
            });
            $('.kt-login__title').show()  
            $('.widget-wrap').show()
    }

    function generateQr(uniqueId){
        $('.captureBtn').hide()
        $('#qrcode-2').html('')
        var qrcode = new QRCode(document.getElementById("qrcode-2"), {
                text: uniqueId,
                width: 128,
                height: 128,
                colorDark : "#5868bf",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });
    }

    
</script>

@endpush
