
@extends('gl-scratch-web.layouts.master')
<link href="{{url('glscratch-web/assets/css/demo2/pages/login/login-2.css')}}" rel="stylesheet" type="text/css" />
@section('content')
<div class="flag">
	<div class="float-left"><img src="{{url('glscratch-web/assets/media/logos/left.png')}}"  /></div>
	<div class="float-right"><img src="{{url('glscratch-web/assets/media/logos/right.png')}}"  /></div>
	
</div>
<div class="kt-grid kt-grid--ver kt-grid--root kt-page">
    <div class="kt-grid kt-grid--hor kt-grid--root kt-login kt-login--v2 kt-login--signin" id="kt_login">
      <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor"
        style="background:#330843">
        <div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
          <div class="kt-login__container">
            <div class="kt-login__logo"> 
                <a href="#"> 
                  @if($offer->vchr_scratch_offers_image)              
                  
                    <img src="{{ Storage::disk('s3')->url($offer->vchr_scratch_offers_image) }}" class="main-logo" /> 
                  @elseif($user->vchr_logo)
                    <img src="{{ Storage::disk('s3')->url('uploads/user-profile/' . $user->vchr_logo) }}" class="main-logo"/> 
                  @else
                      <img src="{{url('glscratch-web/assets/media/logos/logo-mini-2-md.png')}}" class="main-logo"/> 
                  @endif
                </a> 
            </div>
            <div class="kt-login__signin">
              <div class="kt-login__head">
                <h2 class="kt-login__title" style="font-size: 20px;font-weight: bold">{{$offer->vchr_scratch_offers_name}}</h2>
                <div class="kt-login__logo "> <a href="javascript:;" id="kt_login_signup"
                    class="kt-link kt-link--light kt-login__account-link"> <img
                      src="{{url('glscratch-web/assets/media/logos/lock-scratch-cr.png')}}"> </a> </div>
                <h3 class="kt-login__title">Verify your
                  Mobile number to unlock</h3>
                <a href="#" style="text-align: center;display: block;margin-top: 10px" data-toggle="modal"
                  data-target="#exampleModalCenter">Terms & conditions</a>
              </div>
            </div>
            <div class="kt-login__signup">
              <div class="kt-login__head">
                <h3 class="kt-login__title">{{$user->vchr_user_name}}</h3>
                <div class="kt-login__desc">Enter your details :</div>
              </div>
              <form class="kt-login__form kt-form" id="gl-scratch-form"action="POST">
                @csrf
                <div class="input-group">
                  
                  <input class="form-control " type="text" id="customer_name_id" placeholder="Fullname" name="name">
                </div>
                <div class="input-group row">
                  <div class= "col-3 p-0 pr-1">
                      <select class="form-control " id="country_code_id" name="country_code" style=" padding-left: 1rem; padding-right: .5rem;"
                        autocomplete="off">
                        <option value="91" selected>+91
                        <option value="973">+973
                        
                      </select>
                  </div>
                  <div class="col-9 p-0 "> 
                      <input class="form-control  " type="number" id="mobile_number_id" placeholder="Mobile number" name="mobile"
                           autocomplete="off">
                  </div>
                  <div class="input-group">
                    <input class="form-control  " type="number" id="gl_otp_id" placeholder="OTP" name="gl_otp_id"
                      autocomplete="off">
                  </div>
               
                  
                </div>
               
                <div class="kt-login__actions" id="submit-btn">
                  <button id="kt_login_forgot"  class="btn btn-pill kt-login__btn-primary">Verify Mobile</button>
                  &nbsp;&nbsp; </div>
              </form>
            </div>
           
            <div class="kt-login__forgot">
              <div class="scratch-container">
                <div id="promo" class="scratchpad"></div>
              </div>
              <div class="promo-container" style="display: none">
                <div class="promo-code"> </div>
              </div>
            </div>

            

            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
              aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Terms & Conditions</h5>
                    
                  </div>
                  
                </div>
              </div>
            </div>
              <div class="kt-login__account"> <span class="kt-login__account-msg"> <a href="http://gdealapp.com"
                  target="new"> Powered by Gdeal</a> </span> 
                </div>
          </div>
        </div>
      </div>
    </div>
</div>

   

  <div id="countdown-model" hide='true'  style="
    width: 100%;
    position:absolute;
    height: 100%;
    background: rgba(0,0,0, .9);
    display: flex;
    align-items: center;
    justify-content: center;">
    <div class="">
      <div class="" style="background:none !important; border:none !important;text-align:center;">
      
        <div class="">
               
            
            <div id="countdown"></div>
            <div class="kt-login__head">                
                    <div class="kt-login__desc" id="phone-number-id" style="color:white"></div>
                </div>
        </div>
        
      </div>
    </div>
  </div>


<!-- Modal -->
  <div class="modal fade" id="countdown-model" tabindex="-1" role="dialog" aria-labelledby="countdown-modelTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content" style="background:none !important; border:none !important;text-align:center;">
      
        <div class="modal-body">
               
            
            <div id="countdown"></div>
            <div class="kt-login__head">                
                    <div class="kt-login__desc" id="phone-number-id" style="color:white"></div>
                </div>
        </div>
        
      </div>
    </div>
  </div>
 
  <!--*********** End Countdown Model  ********-->
  
@endsection


@push('footer.script')

  <script type="text/javascript">
      $(document).ready(function () {

       
          var BASE_URL = window.location.origin;

          $('#countdown-model').hide();
          var login = $('#kt_login');
          var scratch_count=1;
  
          
          $('#kt_login_forgot').on('click',function(e){
              $('#gl-scratch-form').validate({ 
                rules: {
                  name: {
                      required: true,},

                  mobile: {
                      required: true,
                      maxlength:12,
                      minlength:8,                                       
                  },
                  gl_otp_id: {
                      required: true,                   
                  },
                            
                  
                },          
            
              });
              
            if($('#gl-scratch-form').valid()){
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
                var otp=$("#gl_otp_id").val();
                var mobile=$("#mobile_number_id").val();
                var country_code=$("#country_code_id").val();
                var name= $("#customer_name_id").val();

                  // countdown();
                  // $('#countdown-model').show();
                
                var data={   
                              otp: otp,
                              mobile: mobile ,
                              name: name,
                              country_code :country_code,
                              _token: ' {{csrf_token()}}',
                              short_link : '{{$shortlink->link}}',
                              offer_id :{{$offer->pk_int_scratch_offers_id}},
                              user_id : {{$user->pk_int_user_id}}
                            }  
                var url = BASE_URL + '/scr/scratch-web-customer';
                $.ajax({
                  url:url,
                  method:'POST',
                  data:data,
                }).done(function(res){
                  if(res.status===true){
                      $("#kt_login_forgot").html("Verify Mobile");
                      $('#kt_login_forgot').attr("disabled", false);
                      
                      // $('#countdown-model').hide();
                      var offerListing= res.offerListing
                      glScrtachView(offerListing)
                      displayForgotForm();
                      
                     
                      
                  }
                  else if(res.status ===false){
                      $.alert({
                        title: 'Error',
                        type: 'red',
                        content: res.msg,
                      });
                      $("#kt_login_forgot").html("Verify Mobile");
                      $('#kt_login_forgot').attr("disabled", false);
                      $('#countdown-model').hide();
                  }
                }).fail(function () {
                  $("#kt_login_forgot").html("Verify Mobile");
                      $('#kt_login_forgot').attr("disabled", false);
                      $('#countdown-model').modal('hide');
                }).always(function (com) {
                  $("#kt_login_forgot").html("Verify Mobile");
                      $('#kt_login_forgot').attr("disabled", false);
                      $('#countdown-model').hide();
                   
                })
                

              } 
             

            });
           function countdown()
            {
              var countdown =  $("#countdown").countdown360({
                  radius      : 60,
                  seconds     : 45,
                  fontColor   : '#FFFFFF',
                  autostart   : false,
                  onComplete  : function () { window.location.reload(); }
              });
              countdown.start();
              console.log('countdown360 ',countdown);
              $(document).on("click","button",function(e){
                e.preventDefault();
                var type = $(this).attr("data-type");
                if(type === "time-remaining")
                {
                  var timeRemaining = countdown.getTimeRemaining();
                 
                }
                else
                {
                  var timeElapsed = countdown.getElapsedTime();
                 
                }
              });
            }



 

          var displayForgotForm = function() {

            login.removeClass('kt-login--signin');

            login.removeClass('kt-login--signup');



            login.addClass('kt-login--forgot');

          

            KTUtil.animateClass(login.find('.kt-login__forgot')[0], 'flipInX animated');



          }



            function  glScrtachView(offerListing)
            {
              var promoCode = '';
                  var bg1 = "{{url('glscratch-web/assets/media/logos/gift-2.png')}}";
                  var bg3 = "{{url('glscratch-web/assets/media/logos/betterluck.png')}}";
                  var status= offerListing.int_winning_status;
                  if(status==1){
                      selectBG = bg1;
                      promoCode = offerListing.txt_description;
                  }
                  else{

                    selectBG = bg3;
                    var promoCode = '';
                  }
                  
                  
                  $('#promo').wScratchPad({
                  
                    size: 50,
                  
                    bg: selectBG,
                  
                    realtime: true,
                    // The overlay image
                    fg: "{{url('glscratch-web/assets/media/logos/scratch-here-cr.png')}}",
                   
                    'cursor': 'url("https://jennamolby.com/scratch-and-win/images/coin1.png") 5 5, default',

                    scratchMove: function (e, percent) {
                      // Show the plain-text promo code and call-to-action when the scratch area is 50% scratched
                      if ((percent > 60) && (promoCode != '')) {
                        if(scratch_count==1){
                            scratch_count =2;
                            var customer_id=offerListing.customer_id;
                            var url = BASE_URL + '/scr/gl-scratched/'+ customer_id ;
                            $.ajax({
                              url: url,
                              method: 'POST',
                              data: { _token: ' {{csrf_token()}}'}
                            }).done(function (res) {
                            if (res.status === true) {  
                              $('.promo-container').show();
                              $('body').removeClass('not-selectable');
                              $('.promo-code').html('Your gift is: ' + promoCode);                         
                                
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

          $('#mobile_number_id').on('focusout',function(){
              $('#gl-scratch-form').validate({ 
                rules: {
                          

                  mobile: {
                      required: true,
                      maxlength:12,
                      minlength:8,                                       
                  },             
                            
                },
              });
              if($('#gl-scratch-form').valid()){

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                var mobile=$('#mobile_number_id').val();
                var country_code = $("#country_code_id").val();
                var url = BASE_URL + '/scr/gl-verify-mobile';
                $.ajax({
                        url: url,
                        method: 'POST',
                        data: {mobile: mobile,country_code:country_code, _token: ' {{csrf_token()}}'}
                    }).done(function (res) {
                        if (res.status === true) {                           
                            $.alert({
                                title: 'Success',
                                type: 'green',
                                content: res.msg,
                            });
                            //$('#gl_otp_id').a
                        } else if (res.status === false) {
                            alert(res.msg);
                        }
                    }).fail(function () {
                    }).always(function (com) {
                        //      $
                    });
              }
          });
        
        });
  </script>
   


  @endpush