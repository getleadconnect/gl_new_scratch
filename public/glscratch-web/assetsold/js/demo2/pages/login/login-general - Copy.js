"use strict";


// Class Definition

var otp = '';

var KTLoginGeneral = function() {

    var login = $('#kt_login');
    var scratch_count = 1;

    var BASE_URL = window.location.origin;

    var showErrorMsg = function(form, type, msg) {

        var alert = $(`<div class="alert alert-' + type + ' alert-dismissible" role="alert">\

                        <div class="alert-text">'+msg+'</div>\

                        <div class="alert-close">\

                <i class="flaticon2-cross kt-icon-sm" data-dismiss="alert"></i>\

            </div>\

                </div>`);



        form.find('.alert').remove();

        alert.prependTo(form);

        //alert.animateClass('fadeIn animated');

        KTUtil.animateClass(alert[0], 'fadeIn animated');

        alert.find('span').html(msg);

    }
	

    function glScrtachView(offerListing) {
        var promoCode = '';
        var selectBG = '';
        var bg3 = 'url("/glscratch-web/assets/media/logos/tt-betterluck.png")';
        var status = offerListing.int_winning_status;

        if (status == 1) {
            selectBG = offerListing.image;
            promoCode =   '<span style="color:#ffa01d">Congratulations '+offerListing.customer_name+'!!</span><br> <span style="color:#000;font-size:12px">You have won:</span> ' + offerListing.txt_description;

        } else {
            selectBG = offerListing.image;
            promoCode = 'Oops Try Again!! Next Time';
        }

        $('#promo').wScratchPad({
            size: 50,
            bg: selectBG,
            realtime: true,
            // The overlay image
            fg: '/glscratch-web/assetsold/media/logos/scratch-here.svg',

            'cursor': 'url("/glscratch-web/assetsold/media/logos/coin.png"), auto',

            scratchMove: function (e, percent) {

                if ((percent > 20) && (promoCode != '')) {
                    if (scratch_count == 1) {
                        scratch_count = 2;
                        var customer_id = offerListing.customer_id;
                        var url = BASE_URL + '/scr/gl-scratched/' + customer_id + '/scratch_api';
                        $.ajax({
                            url: url,
                            method: 'POST',
                            data: {
                                _token:$("#_csrf_token").val(),
                                company_name:$('#company_name').val(),
                                insurance:$('#insurance').val(),
                                event_name:$('#event_name').val()
                        }
                        }).done(function (res) {
							
                           if (res.status === true) {
                                $('.promo-container').show();
                                $('body').removeClass('not-selectable');
                                $('.promo-code').html(promoCode);
                                $('#promo').wScratchPad('clear');
                                confetti();
                                $('.uniqueHidden').val(offerListing.unique_id)
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
                            //      $
                        });
                    }
                }
            }
        });
    }


    var verify_otp = function($button) {
        var vendor_id = parseInt($("#vendor_id").val(), 10);
				
		var otp1=$('#otp1').val();
		var otp2=$('#otp2').val();
		var otp3=$('#otp3').val();
		var otp4=$('#otp4').val();
		
		var full_otp=otp1+otp2+otp3+otp4;
		
        var data = {
			_token:$('#_csrf_token').val(),
            bill_no: $('#bill_no').val(),
            otp: null,
            mobile: $('#mobile_number').val(),
            name: $('#full_name').val(),
            email: $('#customer_email').val(),
            branch: $('#customer_branch').val(),
            country_code: $("#country_code_id").val(),
            short_link: shortlink.link,
            offer_id:$("#offer_id").val(),
            user_id:user.pk_int_user_id,
            vendor_id: $("#vendor_id").val()
        }
		
		var bypas_ids=bypass_ids.split(',');
		var status=findValueInArray(vendor_id, bypas_ids);

		//if(bypas_ids.includes(vendor_id))
		//if(status==true)
		if($("#otp_verify_status").val()==0)
		{
            data.otp = null;
            verify_user(data,$button);
        }else{
			
            if(otp1!='' && otp2!="" && otp3!="" && otp4!="")
            {
				data.otp = full_otp;
                verify_user(data,$button);
            }else{
                $.alert({
                    title: 'Error',
                    type: 'red',
                    content: 'Enter otp !!!',
                });
                hideSpinner($button,'VERIFY')
            }
        }
    }


    var verify_user = function(data,$button){
        var url = BASE_URL + '/scr/scratch-web-customer';
        $.ajax({
            url: url,
            method: 'POST',
            data: data,
        }).done(function (res) {
            if (res.status === true) {
                var offerListing = res.offerListing
                $('.code').html(offerListing.unique_id)
                glScrtachView(offerListing)
                displayForgotForm();
            } else if (res.status === false)
				{
                $.alert({
                    title: 'Error',
                    type: 'red',
                    content: res.msg,
                });

                $("#kt_login_forgot").html("VERIFY");
                $('#kt_login_forgot').attr("disabled", false);
            }
        }).fail(function () {
            $("#kt_login_forgot").html("VERIFY");
            $('#kt_login_forgot').attr("disabled", false);
            //$('#countdown-model').modal('hide');
        }).always(function (com) {
            $("#kt_login_forgot").html("VERIFY");
            $('#kt_login_forgot').attr("disabled", false);
            hideSpinner($button,'')
        })
    }
	
	
    var displaySignUpForm = function() {
        login.removeClass('kt-login--forgot');
        login.removeClass('kt-login--signin');
        login.removeClass('kt-login--otp');
        login.addClass('kt-login--signup');
        $('.carousel').addClass('hide-banner')
        KTUtil.animateClass(login.find('.kt-login__signup')[0], 'fadeIn animated');

    }

    var  displayOtpForm = function() {
        login.removeClass('kt-login--forgot');
        login.removeClass('kt-login--signin');
        login.removeClass('kt-login--signup');
        login.addClass('kt-login--otp');
        $('.carousel').addClass('hide-banner')
        KTUtil.animateClass(login.find('.kt-login__otp')[0], 'fadeIn animated');
    }

    var displaySignInForm = function() {
        login.removeClass('kt-login--forgot');
        login.removeClass('kt-login--signup');
        login.removeClass('kt-login--otp');
        login.addClass('kt-login--signin');
        $('.carousel').addClass('hide-banner')
        KTUtil.animateClass(login.find('.kt-login__signin')[0], 'fadeIn animated');
    }

    var displayForgotForm = function() {
        login.removeClass('kt-login--signin');
        login.removeClass('kt-login--otp');
        login.removeClass('kt-login--signup');
        login.addClass('kt-login--forgot');
        $('.carousel').addClass('hide-banner')
        KTUtil.animateClass(login.find('.kt-login__forgot')[0], 'fadeIn animated');
    }
	
	
    var handleFormSwitch = function() {

        $('#kt_login_forgot').click(function(e) {
            e.preventDefault();
            var $button = $(this);
            loadSpinner($button)
            verify_otp($button)
        });


        $('#kt_login_forgot_cancel').click(function(e) {

            e.preventDefault();
            displaySignInForm();
        });


        $('#kt_login_signup').click(function(e) {

            e.preventDefault();
            displaySignUpForm();

        });

        $('#kt_login_signup_cancel').click(function(e) {

            e.preventDefault();
            displaySignInForm();

        });

        //onclick of verify number button
        $('#kt_login_otp').click(function(e) {
            // e.preventDefault();
            var bill_req =  $('#bill_no').data('bill')
            var email_req = $('#customer_email').data('email')
            var branch_req = $('#customer_branch').data('email')
            var rule = '';
            if(bill_req == 1){
                rule =  "bill_no: {required: true}";
            }
            if(email_req == 1){
                rule =  "customer_email: {required: true,email:true}";
            }
            if(branch_req == 1){
                rule =  "customer_branch: {required: true}";
            }
            var form = $('#details-page');
            form.validate({
                errorPlacement: function(label, element) {
                    label.addClass("arrow")
                    label.insertAfter(element);
                },
                rules:
                {
                    full_name: {
                        required: true
                    },
                    mobile_number: {
                        required: true,
                        maxlength: 15,
                        minlength: 5,
                        digits:true,
                    },
                    rule
                },
                messages: {
                    full_name: {
                    required: "Please enter full name",
                },
                customer_email: {
                    required: "Please enter Email",
                    email: "Please enter valid email id",
                },
                customer_branch: {
                    required: "Please choose Branch",
                },
                mobile_number: {
                    required: "Please enter phone number",
                    digits: "Please enter valid phone number",
                    minlength: "Number field accept only 5 digits",
                    maxlength: "Number field accept only 15 digits",
                },
                bill_no: {
                    required: "Please enter bill number",
                },
                },
            });
            if (!form.valid()) {
                return;
            }else{
                e.preventDefault();
                    var $button = $(this);
                    //loadSpinner($button);

                    var custom_field = $('#custom_field').val();
                    var mobile = $('#mobile_number').val();
                    var bill_no = $('#bill_no').val();
                    var country_code = $("#country_code_id").val();
                    var offer_id = $("#offer_id").val();
                    var vendor_id = $("#vendor_id").val();
                    var url = BASE_URL + '/scr/gl-verify-mobile';
                    var email = $('#customer_email').val();
                    var user_id = user.pk_int_user_id;
                    var name = $('#full_name').val();
					var token=$("#_csrf_token").val();

                $.ajax({
                    type:'POST',
                    url:url,
                    data: {
						_token:token,
                        user_id: user_id,
                        name: name,
                        mobile: mobile,
                        email: email,
                        country_code: country_code,
                        offer_id: offer_id,
                        vendor_id: vendor_id,
                        link : window.location.href,
                        bill_no: bill_no,
                        custom_field: custom_field,
                        company_name: $('#company_name').val(),
                        insurance: $('#insurance').val(),
                        event_name: $('#event_name').val(),

                    },
                    success:function(data) {
                        if(data.status == true)
                        {
                            var vendor_id = parseInt($("#vendor_id").val(), 10);
							
							var bypas_ids=bypass_ids.split(',');
							var status=findValueInArray(vendor_id, bypas_ids);

                            //if(bypas_ids.includes(vendor_id))
							if(status==true)
							{
                                verify_otp($button)
                            }else{
                                displayOtpForm()
                                $(".user_number").html('Enter the verification code that we sent your <b> Mobile </b>')
                            }
                        }else{
                            $.alert({
                                title: 'Warning',
                                type: 'red',
                                content: data.msg,
                            });
                        }

                        hideSpinner($button,'');
                    }
                });

            }
        });
    }
	
	
function findValueInArray(value, arr){
  let result = false;
 
  for(let i=0; i<arr.length; i++){
    let name = arr[i];
    if(name == value){
      result = true;
      break;
    }
  }
  return result;
}
		
	
	
 var handleSignUpFormSubmit = function() {
        $('#kt_login_signup_submit').click(function(e) {

            e.preventDefault();



            var btn = $(this);

            var form = $(this).closest('form');



            form.validate({

                rules: {

                    fullname: {

                        required: true

                    },

                    email: {

                        required: true,

                        email: true

                    },

                    password: {

                        required: true

                    },

                    rpassword: {

                        required: true

                    },

                    agree: {

                        required: true

                    }

                }

            });



            if (!form.valid()) {

                return;

            }



            btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);



            form.ajaxSubmit({

                url: '',

                success: function(response, status, xhr, $form) {

                        // similate 2s delay

                        setTimeout(function() {

                            btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);

                            form.clearForm();

                            form.validate().resetForm();



                            // display signup form

                            displaySignInForm();

                            var signInForm = login.find('.kt-login__signin_form');

                            signInForm.clearForm();

                            signInForm.validate().resetForm();



                            showErrorMsg(signInForm, 'success', 'Thank you. To complete your registration please check your email.');

                        }, 2000);

                }

            });

        });
    }



    var handleForgotFormSubmit = function() {

        $('#kt_login_forgot_submit').click(function(e) {

            e.preventDefault();

            var btn = $(this);

            var form = $(this).closest('form');

            form.validate({

                rules: {
    email: {

                        required: true,

                        email: true

                    }

                }

            });

            if (!form.valid()) {
                return;
            }

            btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            form.ajaxSubmit({

                url: '',

                success: function(response, status, xhr, $form) {

                        // similate 2s delay

                        setTimeout(function() {

                                btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false); // remove

                            form.clearForm(); // clear form

                            form.validate().resetForm(); // reset validation states



                            // display signup form

                            displaySignInForm();

                            var signInForm = login.find('.kt-login__signin form');

                            signInForm.clearForm();

                            signInForm.validate().resetForm();



                            showErrorMsg(signInForm, 'success', 'Cool! Password recovery instruction has been sent to your email.');

                        }, 2000);

                }

            });

        });

    }
 // Public Functions

    return {

        // public functions

        init: function() {
            $('.carousel').removeClass('hide-banner')
            handleSignUpFormSubmit();
            handleFormSwitch();
            handleForgotFormSubmit();
        }

    };

}();


var loadSpinner = function($button){
    // Show spinner and disable button
    $button.prop('disabled', true);
    $button.find('.spinner-border').show();
    $button.contents().filter(function() {
        return this.nodeType === 3; // Node.TEXT_NODE
    }).remove(); // Remove the "SUBMIT" text
}

var hideSpinner = function($button,$text){
    $button.prop('disabled', false);
    $button.find('.spinner-border').hide();
    $button.append(' '+$text);
}


// Class Initialization

jQuery(document).ready(function() {

    KTLoginGeneral.init();

});


