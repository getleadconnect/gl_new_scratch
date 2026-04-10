<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Oxygen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="{{url('scratchonam/3/bootstrap.css')}}" rel="stylesheet">
    <script src="https://kit.fontawesome.com/66aa7c98b3.js" crossorigin="anonymous"></script>
    <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 0;
        background-color: #f5f5f5;
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
    <link href="scratchonam/3/bootstrap-responsive.css" rel="stylesheet">
    <link href="scratchonam/3/otp-form.css" rel="stylesheet">
    <link href="scratchonam/3/responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/apple-touch-icon-114-precomposed.png">
      <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/apple-touch-icon-72-precomposed.png">
                    <link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon-57-precomposed.png">
                                   <link rel="shortcut icon" href="images/favicon.png">





  </head>

<body>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header logo-align">
                <a class="navbar-brand" href="#">
                <img alt="Brand" src="images/oxygen.png" class="header-logo">
                </a>
            </div>
        </div>
    </nav>

    <div class="wrapper2   kt-login kt-login--v2 kt-login--signin">
        <div class="test"><h1>Helllllo</h1></div>
        <div class="kt-grid kt-grid--hor kt-grid--root kt-login kt-login--v2 kt-login--signin" id="kt_login">
            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor ">
                <div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
                    <div class="kt-login__container">
                        <div class="kt-login__signup">
                            <div class="kt-login__head">
                                <div class="kt-login__desc"><img src="images/verification.png" class="verification-img"></div>
                            </div>
                            <form class="kt-login__form kt-form" id="gl-scratch-form" method="POST">
                                <input type="hidden" name="_token" value="">                                <div class="input-group">
                                    <div class="col-12">
                                        <input class="form-control required " type="text" id="customer_name_id"
                                               placeholder="Full Name" name="name" autocomplete="off">
                                    </div>
                                </div>

                                                                <div class="input-group ">
                                    <div class="col-12  mobile-input">

                                        
                                        <div class=" ">
                                            <input class="form-control" type="phone" id="mobile_number_id"
                                                   placeholder="Mobile number" name="mobile"
                                                   autocomplete="off">
                                        </div>
                                    </div>


                                </div>

                                
                                <div class="kt-login__actions" id="submit-btn">
                                    <button id="kt_login_forgot" class="btn btn-pill kt-login__btn-primary">Verify
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
    <div class="footerr py-3">
        <div  class="container">
            <div class="row">
                <div class="col-md-9 text-left">
                    <div class=" terms-div">
                        <h2>Terms and Conditions</h2>
                            <ul>
                            <li>Offer redeemable only on Minimum Purchase of Rs.5000 </li>
                            <li>Valid till 31st October 2021.</li>
                            <li>Each code can be redeemed only once.</li>
                            <li>This deal is non-transferable, non-negotiable and non-encashable.</li>
                            <li>In case of any issues or questions relating to this deal, users may contact at 9020100100</li>
                            <li>This deal is not refundable.</li>
                            </ul>
                    </div>
                </div>
                <div class="col-md-3 text-right">
                    <div class="social-menu">
                        <ul>
                            <li><a href="https://github.com/sanketbodke" target="blank"><i class="fab fa-instagram"></i></a></li>
                            <li><a href="https://www.instagram.com/imsanketbodke/" target="blank"><i class="fab fa-facebook"></i></a></li>
                            <li><a href="https://www.linkedin.com/in/sanket-bodake-995b5b205/" target="blank"><i class="fab fa-twitter"></i></a></li>
                            <li><a href="https://codepen.io/sanketbodke"><i class="fab fa-whatsapp" target="blank"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div> 
    </div>

    



  <!-- Footer -->
  
  <!-- Footer -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="{{url('glscratch-web/assets/js/demo2/pages/login/login-general.js')}}" type="text/javascript"></script>


  </body>
</html>
