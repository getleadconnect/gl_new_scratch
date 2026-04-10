
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
    .select2-container--default .select2-selection--single{
            padding: 0.5rem 0.28rem 0.25rem 0px !important;
             height: 42px !important;
             background: #F5F5F5 !important;
            min-height: 45px;
            height: auto !important;
            font-family: Poppins;
            font-style: normal;
            font-weight: 500;
            font-size: 14px;
            line-height: 19px;
            color: #353535 !important;
            border: 1px whitesmoke solid !important;
            box-sizing: border-box !important;
            border-radius: 4px !important;
            /* margin-top: 0.5rem; */
            font-family: 'Manrope', sans-serif;
            /* margin-bottom: 1.5rem; */
        }
       .select2-selection__rendered{
        color: #6c6e74 !important;
       }

        .select2-selection__arrow:before{
            content: none !important;
       }
       .branch > .arrow{
        position: absolute;
        top: 77px;
       }
       .select2-dropdown.select2-dropdown--below{
        position: absolute;
        top: -55px;
        }
        .select2-dropdown.select2-dropdown--above{
        position: absolute;
        top: 70px;
        }
    </style>
@section('content')
<!-- begin::Body -->
<div class="kt-grid kt-grid--ver kt-grid--root kt-page">
    <div class="kt-grid kt-grid--hor kt-grid--root kt-login kt-login--v2 kt-login--signin" id="kt_login">
        
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
                @if($offer->mobile_image)
                    <img src="{{ Storage::disk('s3')->url($offer->mobile_image) }}" class="d-block img-fluid"/>
                @elseif($user->vchr_logo)
                    <img src="{{ Storage::disk('s3')->url('uploads/user-profile/' . $user->vchr_logo) }}" class="d-block img-fluid"/>
                @else
                    <img src="{{url('glscratch-web/assets/media/logos/logo-mini-2-md.png')}}"
                        class="d-block img-fluid"/>
                @endif
              </div>
            </div>
        </div>

        <div class="clear"></div>
            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="demo" style="background: #fff" >
                <div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
                    <div class="kt-login__container">
                        
                        <div class="kt-login__signin">
                            <div class="kt-login__head"> 
                                <h3 class="kt-login__title">
                                Find your 
                                Digital scratch card</h3>
                                <div class="kt-login__desc">{{-- Enter your details to get access to your account --}}</div>
                                    <div class="kt-login__actions">
                                    <button id="kt_login_signup" class="btn btn-pill kt-login__btn-primary">Get my scratch card</button>
                                    &nbsp;&nbsp; </div>
                                
                                <div class="kt-login__logo "> <a href="javascript:;" class="kt-link kt-link--light kt-login__account-link"> 
                                 </a>
                                </div>
                                
                                <a href="#" style="text-align: center;display: block;margin-top: 10px;font-size: 12px;color:#ccc" data-toggle="modal" data-target="#exampleModalCenter">{{-- Terms & conditions --}}</a>
                            </div>
                        </div>
                        <div class="kt-login__signup">
                            <div class="kt-login__head">
                                <h3 class="kt-login__title">Find your Digital scratch card</h3>
                                <div class="kt-login__desc">Enter your details to get access
                                to your account</div>
                            </div>
                            <form class="kt-login__form kt-form" action="" id="details-page">
                                <div class="input-group">
                                    <label for="full_name">Name</label>
                                        <input class="form-control" type="text" placeholder="Fullname" name="full_name" id="full_name">
                                </div>
                                <div class="input-group row">
                                        <div class="col-4 p-0">
                                            <label for="mobile_number">Code</label>
                                            <select class="form-control " id="country_code_id" name="country_code"
                                                    style=" padding-left: 1rem; padding-right: .5rem;"
                                                    autocomplete="off">
                                            @if(in_array($shortlink->vendor_id, App\Common\Variables::getScratchBypass()))
                                                <option value="91" selected>+91    
                                                <option value="971">+971
                                                <option value="973">+973
                                                <option value="974">+974
                                                <option value="966">+966
                                            @else
                                                <option value="91" selected>+91
                                                <option value="973">+973
                                                <option value="971">+971
                                            @endif
                                               
                                            </select>
                                        </div>
                                        <div class="col-8 pr-0">
                                            <label for="mobile_number">Mobile number</label>
                                            <input class="form-control" type="number" placeholder="Mobile number" name="mobile_number" autocomplete="off" id="mobile_number">
                                        </div>                                    
                                </div>
                                <input type="hidden" id="offer_id" value="{{$shortlink->offer_id}}" name="offer_id">
                                <input type="hidden" id="vendor_id" value="{{$shortlink->vendor_id}}" name="vendor_id">
                                @if($shortlink->email_required == 1)
                                    <div class="input-group">
                                        <label for="customer_email">Email</label>
                                            <input class="form-control required" type="text" id="customer_email"
                                                   placeholder="Email" name="customer_email" autocomplete="off" data-email="1">
                                    </div>
                                @endif
                                @if($shortlink->branch_required == 1)
                                    <div class="input-group branch">
                                        <label for="customer_branch">Store</label>
                                            <select name="customer_branch" id="customer_branch" class="customer_branch js-data-example-ajax form-control required" style="width: 100%" data-branch="1">
                                                    
                                            </select>
                                    </div>
                                @endif    
                                @if($shortlink->custom_field == App\BackendModel\ShortLink::BILL_NO)
                                    <div class="input-group">
                                            <input type="hidden" name="custom_field" id="custom_field"
                                                autocomplete="off"
                                                value="{{$shortlink->custom_field}}">
                                                <input class="form-control required " type="text" id="bill_no"
                                                placeholder="Bill number" name="bill_no" autocomplete="off" data-bill="1">
                                    </div>
                                @endif
                                @if($shortlink->vendor_id == 3286)
                                    <div class="input-group">
                                        <label for="company_name">Company Name</label>
                                            <input class="form-control" type="text" id="company_name"
                                                   placeholder="Company name" name="company_name" autocomplete="off">
                                    </div>
                                    <div class="input-group">
                                        <label for="insurance">Insurance</label>
                                            <input class="form-control" type="text" id="insurance"
                                                   placeholder="Insurance" name="insurance" autocomplete="off">
                                    </div>
                                    <div class="input-group">
                                        <label for="event_name">Event Name</label>
                                            <input class="form-control" type="text" id="event_name"
                                                   placeholder="Event Name" name="event_name" autocomplete="off">
                                    </div>
                                @endif

                               
                                <div class="kt-login__actions">
                                <button id="kt_login_otp" class="btn btn-pill kt-login__btn-primary" type="button">SUBMIT</button>
                                &nbsp;&nbsp; </div>
                            </form>
                        </div>
                        <div class="kt-login__otp">
                            <div class="kt-login__head">
                                <h3 class="kt-login__title">Verify OTP</h3>
                                <div class="kt-login__desc user_number"></div>
                            </div>
                            <form class="kt-login__form kt-form" action="">
                                <div class="input-group">
                                <label>OTP</label>
                                <input class="form-control" type="password" placeholder="Enter OTP" name="otp_submit" autocomplete="off" id="otp_submit">
                                </div>               
                                <div class="kt-login__actions">
                                <button id="kt_login_forgot" class="btn btn-pill kt-login__btn-primary">SUBMIT</button>
                                &nbsp;&nbsp; </div>
                            </form>
                        </div>
                        <div class="kt-login__forgot">
                            <h3 class="kt-login__title kt-code" style="color:#FF5733;text-align: center;">Scratch the card below & you could earn exciting gift</h3>
                            <div class="scratch-container">
                                <div class="kt-login__head">
                                    <div class="kt-login__desc">Your redeem code is :<h4 class="code kt-code"></h4></div>
                                </div>
                                <div id="promo" class="scratchpad"></div>
                            </div>
                            <div class="promo-container" style="display: none">
                                <div class="promo-code"> </div>
                            </div>
                            <div class="widget-wrap captureBtn  d-none screenSt">
                                <button onclick="capture()">
                                  Take a screenshot.
                                </button>
                                <input type="hidden" name="uniqueHidden" class="uniqueHidden">
                            </div>
                        </div>
                        
                    </div>
                    <div class="codeqr">
                        <div id="qrcode-2" class="text-center"></div>
                    </div>
                </div>
            </div>
    </div>
</div>
 <script>
    var KTAppOptions = {"colors":{"state":{"brand":"#374afb","light":"#ffffff","dark":"#282a3c","primary":"#5867dd","success":"#34bfa3","info":"#36a3f7","warning":"#ffb822","danger":"#fd3995"},"base":{"label":["#c5cbe3","#a1a8c3","#3d4465","#3e4466"],"shape":["#f0f3ff","#d9dffa","#afb4d4","#646c9a"]}}};
</script> 

@endsection

@push('scratch-script')
    <script>
         var user = {!! json_encode($user) !!}
         var shortlink = {!! json_encode($shortlink) !!}
         var url = location.origin
         var bypass_ids = @json(\App\Common\Variables::getScratchBypass());
    </script>
@endpush

@push('footer.script')
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.4/html2canvas.min.js"></script>
<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>-->

<script src="{{asset('assets/htmlcanvas/js/html2canvas-1.3.4.min.js')}}"></script>
<script src="{{asset('assets/qrcode/js/qrcode.min.js')}}"></script>

@push('get-branch')
    <script>
        $('.js-data-example-ajax').select2({     
            ajax: {              
                url: url +'/api/get-branch-autocomplete/'+user.pk_int_user_id,
                dataType: 'json',
                delay: 250,
                type: "GET",
                contentType: "application/json",
                processResults: function (data) {               
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                text: item.branch,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            },
            placeholder: 'Choose store',
            minimumInputLength: 2           

        });
    </script>
@endpush

<script type="text/javascript">
    $(document).ready(function () {
       
        
        var bill_no = getUrlParameter('billno');

        if (bill_no) {
            $('#bill_no_id').val(bill_no);
        }

        var BASE_URL = window.location.origin;

        var login = $('#kt_login');
        var scratch_count = 1;

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

    var scratch_count = 1;
    var BASE_URL = window.location.origin;
  
    function capture () {
        var uniqueId  = $('.uniqueHidden').val()
        generateQr(uniqueId);
        $('.kt-login__title').hide()
            var target = document.getElementById('demo');
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

    $(document).on('change','.lead_id',function(){
        $.ajax({
            url: {!! json_encode(url('/')) !!} + '/user/get-lead-company',
            type: 'POST',
            dataType: 'JSON',
            data:{
                id:$(this).val(),
            }
        })
        .done(function(res) {
            if(res.status == true){
                $('.company_name').val(res.data)
            }
        })
        .fail(function() {
        });
    });
</script>

@endpush
