"use strict";



// Class Definition

var otp = '0000';

var KTLoginGeneral = function() {



    var login = $('#kt_login');

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

    // function glScrtachView(offerListing) {

    //     var promoCode = '';
    //     var bg3 = "{{url('glscratch-web/assets/media/logos/tt-betterluck.png')}}";
    //     var status = offerListing.int_winning_status;

    //     if (status == 1) {
    //         selectBG = offerListing.image;
    //         promoCode = 'Your Gift is: ' + offerListing.txt_description;
    //     } else {
    //         selectBG = bg3;
    //         promoCode = 'Oops Try Again!! Next Time';
    //     }


    //     $('#promo').wScratchPad({

    //         size: 50,

    //         bg: selectBG,

    //         realtime: true,
    //         // The overlay image
    //         fg: "{{url('glscratch-web/assets/media/logos/tt-scratchhere.png')}}",

    //         'cursor': 'url("https://jennamolby.com/scratch-and-win/images/coin1.png") 5 5, default',

    //         scratchMove: function (e, percent) {
    //             // Show the plain-text promo code and call-to-action when the scratch area is 50% scratched
    //             if ((percent > 60) && (promoCode != '')) {
    //                 if (scratch_count == 1) {
    //                     scratch_count = 2;
    //                     var customer_id = offerListing.customer_id;
    //                     var url = BASE_URL + '/scr/gl-scratched/' + customer_id;
    //                     $.ajax({
    //                         url: url,
    //                         method: 'POST',
    //                         data: {_token: ' {{csrf_token()}}'}
    //                     }).done(function (res) {
    //                         if (res.status === true) {
    //                             $('.promo-container').show();
    //                             $('body').removeClass('not-selectable');
    //                             $('.promo-code').html(promoCode);

    //                         } else if (res.status === false) {
    //                             $.alert({
    //                                 title: 'Error',
    //                                 type: 'red',
    //                                 content: res.msg,
    //                             });
    //                         }
    //                     }).fail(function () {
    //                     }).always(function (com) {
    //                         //      $
    //                     });


    //                 }


    //             }
    //         }
    //     });

    // }




   


    // Public Functions

    return {

        // public functions

        init: function() {

            // handleSignInFormSubmit();

            // handleSignUpFormSubmit();

            // handleFormSwitch();
            // handleForgotFormSubmit();

            

        }

    };

}();



// Class Initialization

jQuery(document).ready(function() {

    KTLoginGeneral.init();

});

