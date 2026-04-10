@extends('gl-scratch-web.layouts-new.master')
@section('content')
<!-- begin::Body -->
<style>
    /* Style for OTP input container */
#ot {
    display: flex;
    justify-content: center;
}

/* Style for OTP input fields */
.otp_submit {
    width: 40px;
    height: 40px;
    text-align: center;
    font-size: 18px;
    margin: 0 5px;
}
.redeem_div{
    display: flex !important;
    place-content: center !important;
}
.btn-screen-shot{
    height: 36px !important;
    width: 300px !important;
}
/* For Webkit browsers (Chrome, Safari) */
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* For Firefox */
input[type="number"] {
    -moz-appearance: textfield;
}

/* For IE and Edge */
input[type="number"]::-ms-clear,
input[type="number"]::-ms-reveal {
    display: none;
    width: 0;
    height: 0;
}
.logo__title {
    color: #222;
    text-align: center;
    font-family: Sora;
    font-size: 14px;
    font-style: normal;
    margin-bottom: 10px;
}
.kt-login__title
 {
    padding-top:10px !important;
}
.carousel-item img {
   margin-top:2px !important;
}
.kt-login.kt-login--v2.kt-login--signup .kt-login__signup {
    display: block;
    margin-top: 10% !important;
}
.kt-login.kt-login--v2 .kt-login__wrapper .kt-login__container .kt-form
{
    margin: 2rem auto !important;
}
.scratchpad
{
    margin: 30px auto !important;
}
</style>
<div class="kt-grid kt-grid--ver kt-grid--root kt-page">
    <div class="kt-grid kt-grid--hor kt-grid--root kt-login kt-login--v2 kt-login--signin" id="kt_login">
      <div class="container">
        <div id="carouselExampleIndicators" class="carousel slide desktop" data-ride="carousel">
          <div class="kt-login-head">

            @if(!empty($logo))
               <div class="kt-login__logo mt-3"> <a href="javascript:;" class="kt-link kt-link--light kt-login__account-link">
		    	<!--<img src="{{asset('glscratch-web/assetsold/new/media/logos/brand.png')}}" class="img-fluid invisible" width="120"> </a> -->
                    <img src="{{asset('uploads/'.$logo->logo_image)}}" class="img-fluid " width="120"> </a> 
                    <label class="logo__title" >{{ucwords($logo->name)}}</label>
               </div>
           @endif
            <h3 class="kt-login__title">
              Find your<br> 
              Digital scratch card</h3>
              <div class="kt-login__desc">Enter your details to get your scratch and win offer</div>
          </div>

          {{-- <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
          </ol> --}}

          <div class="carousel-inner">
                <div class="carousel-item active">
                    @if($offer->mobile_image)
                    <img src="{{ url('uploads/'.$offer->mobile_image) }}" class="d-block img-fluid"/>
					@elseif($offer->campaign_image)
                    <img src="{{ url('uploads/'.$offer->campaign_image) }}" class="d-block img-fluid"/>
					@else
                        <img src="{{url('glscratch-web/assets/media/logos/logo-mini-2-md.png')}}"
                            class="d-block img-fluid"/>
                    @endif
                </div>
            </div>
        </div>

        <div id="carouselExampleIndicators" class="carousel slide mob" data-ride="carousel">
        @if(!empty($logo))  
            <div class="kt-login__logo mt-3"> <a href="javascript:;" class="kt-link kt-link--light kt-login__account-link"> 
                <!--<img src="{{asset('glscratch-web/assetsold/new/media/logos/brand.png')}}" class="img-fluid invisible" width="120"> </a> -->
                <img src="{{asset('uploads/'.$logo->logo_image)}}" class="img-fluid " width="120"> </a>
                <label class="logo__title" >{{ucwords($logo->name)}}</label>
            </div>
        @endif
          <h3 class="kt-login__title">
            Find your <br>
            Digital scratch card</h3>
            <div class="kt-login__desc">Enter your details to get your scratch and win offer</div>
            {{-- <ol class="carousel-indicators">
              <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            </ol> --}}
            <div class="carousel-inner">
              <div class="carousel-item active">
                @if($offer->mobile_image)
                    <img src="{{ url('uploads/'.$offer->mobile_image) }}" class="d-block img-fluid"/>
				@elseif($offer->campaign_image)
                    <img src="{{ url('uploads/'.$offer->campaign_image) }}" class="d-block img-fluid"/>
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
                          <div class="kt-login__actions">
                            <button id="kt_login_signup" class="btn btn-pill kt-login__btn-primary">Get my scratch card <img src="{{asset('glscratch-web/assetsold/new/media/logos/arrow.svg')}}"></button>
                              &nbsp;&nbsp; 
                          </div>
                          {{-- <a href="#" style="text-align: center;display: block;margin-top: 10px;font-size: 12px;color:#ccc" data-toggle="modal" data-target="#exampleModalCenter">Terms & conditions</a>  --}}
                        </div>
                    </div>
                    <div class="kt-login__signup">
                        <div class="kt-login__head">
                            <h3 class="kt-login__title">Find your Digital scratch card</h3>
                            <div class="kt-login__desc">Enter your details to get your scratch and win offer</div>
                        </div>
						
                        <form class="kt-login__form kt-form" id="details-page">
						@csrf

                        <input type="hidden" name="_csrf_token" id="_csrf_token" value="{{csrf_token()}}">

                            <div class="input-group">
                                <label for="full_name">Name</label>
                                    <input class="form-control" type="text" placeholder="Full Name" name="full_name" id="full_name">
                            </div>
							
                            <div class="input-group row">
								<div class="col-4 p-0">
									<label for="mobile_number">Code</label>
									<select class="form-control " id="country_code_id" name="country_code" style=" padding-left: 1rem; padding-right: .5rem;" autocomplete="off">
										<option value="91" selected>+91</option>
										<option value="971">+971</option>
								   </select>
								</div>
								<div class="col-8 pr-0">
									<label for="mobile_number">Whatsapp Number</label>
									<input class="form-control" type="number" placeholder="Mobile Number" name="mobile_number" autocomplete="off" id="mobile_number">
								</div>                                    
                            </div>
							
                            <input type="hidden" id="offer_id" value="{{$scratchlink->campaign_id}}" name="offer_id">
                            <input type="hidden" id="vendor_id" value="{{$scratchlink->user_id}}" name="vendor_id">
							<input type="hidden" id="scratch_otp_enabled" value="{{$otp_enabled}}" name="scratch_otp_enabled">
							<input type="hidden" id="short_link_code" value="{{$scratchlink->short_code}}" name="short_link_code">
														
                            @if($scratchlink->email_required == 1)
                                <div class="input-group">
                                    <label for="customer_email">Email</label>
                                        <input class="form-control required" type="text" id="customer_email"
                                              placeholder="Email Address" name="customer_email" autocomplete="off" data-email="1">
                                </div>
                            @endif
                            @if($scratchlink->branch_required == 1)
                                <div class="input-group branch">
                                    <label for="customer_branch">Select Shop</label>
                                        <select name="customer_branch" id="customer_branch" class="customer_branch js-data-example-ajax form-control required" style="width: 100%" data-branch="1">
                              
                                        </select>
                                </div>
                            @endif   
                            @if($scratchlink->bill_number_required == 1)
                                <div class="input-group mt-3">
										<label for="customer_branch">Bill No</label>
                                        <input type="hidden" name="custom_field" id="custom_field"
                                            autocomplete="off"
                                            value="{{$scratchlink->bill_number_required}}">
                                            <input class="form-control required " type="text" id="bill_no"
                                            placeholder="Bill number" name="bill_no" autocomplete="off" data-bill="1">
                                </div>
                            @endif
                      
                            <div class="kt-login__actions">
							
							<input type="hidden" name="_csrf_token" id="_csrf_token" value="{{csrf_token()}}">
							
                            <button id="kt_login_otp" class="btn btn-pill kt-login__btn-primary" type="button">
                                <span class="spinner-border spinner-border-md" role="status" aria-hidden="true" style="display: none;"></span>
                                SUBMIT
                            </button> 
                            &nbsp;&nbsp; </div>
                        </form>
                    </div>
					
					
                    <div class="kt-login__otp">
                        <div class="kt-login__head">
                            <h3 class="kt-login__title">Verify OTP<span class="gbox"></span></h3>
                            <div class="kt-login__desc user_number"></div>
                        </div>
                        <form class="kt-login__form kt-form" action="">
						@csrf
	
                            <div class="input-group" id="ot">
                              <label>OTP</label>
                                                            
                              <input class="form-control otp_submit" type="number" placeholder="-" name="otp_submit[]" id="otp1" autocomplete="on" maxlength="1"> 
                              <input class="form-control otp_submit" type="number" placeholder="-" name="otp_submit[]" id="otp2" autocomplete="on" maxlength="1"> 
                              <input class="form-control otp_submit" type="number" placeholder="-" name="otp_submit[]" id="otp3" autocomplete="on" maxlength="1"> 
                              <input class="form-control otp_submit" type="number" placeholder="-" name="otp_submit[]" id="otp4" autocomplete="on" maxlength="1"> 
                            </div>               
                            <div class="kt-login__actions">
                            <button id="kt_login_forgot" class="btn btn-pill kt-login__btn-primary">
                                <span class="spinner-border spinner-border-md" role="status" aria-hidden="true" style="display: none;"></span>
                                VERIFY
                            </button>
                            &nbsp;&nbsp; </div>
                        </form>
                    </div>
					
					
                    <div class="kt-login__forgot">
                        <div class="scratch-container">
                            <div class="kt-login__head">
                                <h3 class="price_title">Scratch the card below & you could earn exciting gift.</h3>
                            </div>
                            <div class=" redeem_div">Your redeem code is :<h4 class="code kt-code"></h4></div>
                            <div id="promo" class="scratchpad"></div>
                        </div>
                        <div class="promo-container" style="display: none">
                            <div class="promo-code">  </div>
                        </div>
                        <div class="widget-wrap captureBtn  d-none screenSt">
                            <button onclick="capture()" class="btn btn-screen-shot">
                             Download  screenshot
                            </button>
                            <input type="hidden" name="uniqueHidden" class="uniqueHidden">
                        </div>
                    </div>
                    
                    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Terms & Conditions</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                          </div>
                          <div class="modal-body">
                            <p> Content Here..</p>
                          </div>
                        </div>
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
</div>


<script>
    var KTAppOptions = {
	"colors":{
		"state":{
		"brand":"#374afb",
		"light":"#ffffff",
		"dark":"#282a3c",
		"primary":"#5867dd",
		"success":"#34bfa3",
		"info":"#36a3f7",
		"warning":"#ffb822",
		"danger":"#fd3995"},
		"base":
		{"label":["#c5cbe3","#a1a8c3","#3d4465","#3e4466"],
		"shape":["#f0f3ff","#d9dffa","#afb4d4","#646c9a"]
		}
	}
};
</script> 

@endsection

@push('scratch-script')
    <script>
         var user = {!! json_encode($user) !!}
         var scratchlink = {!! json_encode($scratchlink) !!}
         var url = location.origin
         var baseUrl=location.origin+"/uploads/";
    </script>
@endpush

@push('footer.script')

<script src="{{asset('assets/htmlcanvas/js/html2canvas-1.3.4.min.js')}}"></script>
<script src="{{asset('assets/qrcode/js/qrcode.min.js')}}"></script>

@push('get-branch')
    <script>
        $('.js-data-example-ajax').select2({     
            ajax: {              
                url: url +'/sc/get-branch-autocomplete/'+user.id,
                dataType: 'json',
                delay: 250,
                type: "GET",
                contentType: "application/json",
                processResults: function (data) {               
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                text: item.branch_name,
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
        // Focus the first OTP input field
    $('.otp_submit').first().focus();

    // Listen for input in OTP fields
    $('.otp_submit').on('input', function () {
        var $this = $(this);

        // Move to the next input field if a digit is entered
        if ($this.val().length === 1) {
            $this.next('.otp_submit').focus();
        }

        // Move to the previous input field if backspace is pressed
        if (event.keyCode === 8 && $this.val().length === 0) {
            $this.prev('.otp_submit').focus();
        }
    });
        
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
   
    var scratch_count = 1;
    var BASE_URL = window.location.origin;
  
    function capture() {
        var uniqueId  = $('.uniqueHidden').val()
        generateQr(uniqueId);
        $('.kt-login__title').hide()
            var target = document.getElementById('demo');
            html2canvas(target,{
					  useCORS: true,
					  allowTaint: true
					}).then((canvas) => {
               let a = document.createElement("a");
                a.download = uniqueId+".png";
                a.href = canvas.toDataURL("image/png");
                a.click(); // MAY NOT ALWAYS WORK!
            });
            $('.kt-login__title').show()  
            $('.widget-wrap').show()
            $('#qrcode-2').hide()  
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
        // $('#qrcode-2').hide()    
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

    $("#kt_login_signup").click(function(){
        $(".carousel").hide().attr('style','display:none !important');
        
    });
    
    /*$('.carousel').carousel({
        interval: 600
    })*/
            
</script>

@endpush
