<?php
/**
 * Created by PhpStorm.
 * User: telefonia
 * Date: 10/11/2017
 * Time: 9:06
 */

?>

        <!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>IPTVM Total Control</title>

    <!-- Bootstrap -->

    <!-- Bootstrap -->
    <!--<link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">-->
    <link href="{{ URL::asset('../vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <!--<link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">-->
    <link href="{{ URL::asset('../vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{ URL::asset('../vendors/nprogress/nprogress.css') }}" rel="stylesheet">
    <!-- iCheck -->
    <link href="{{ URL::asset('../vendors/iCheck/skins/flat/green.css') }}" rel="stylesheet">

    <!-- bootstrap-progressbar -->
    <link href="{{ URL::asset('../vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css') }}" rel="stylesheet">
    <!-- JQVMap -->
    <link href="{{ URL::asset('../vendors/jqvmap/dist/jqvmap.min.css') }}" rel="stylesheet"/>

    <!-- Custom Theme Style -->
    <link href="{{ URL::asset('css/custom.min.css') }}" rel="stylesheet">











</head>

<body class="login">
<div>
    <a class="hiddenanchor" id="signup"></a>
    <a class="hiddenanchor" id="signin"></a>

    <div class="login_wrapper">
        <div class="animate form login_form">
            <section class="login_content">
                <img  witdh="180px" height="180px" src="{{ URL::asset('images/iptvimg2rebordeblancomini.png') }}" alt="">
                <form  method="post" id="loginform" action="{{ url('login') }}">


                    <h1>IPTVM Total Control</h1>
                    @include('sessionmessages/details')

                    <div id="errorMessageLogin" class="alert alert-danger alert-dismissible" role="alert" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" arial-label="close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div>
                        <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                        <input type="text" id="username" name="username" class="form-control" placeholder="Usuario" required="" />
                    </div>
                    <div>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Contraseña" required="" />
                    </div>


                    <div align="center">
                        <?php
                               if( isset($intentoslogin))
                               {
                                    if($intentoslogin >=3)
                                    {
                        ?>
                                        {!! Recaptcha::render() !!}
                                        <input type="hidden" id="haycaptcha" name="haycaptcha" value="true">
                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <input type="hidden" id="haycaptcha" name="haycaptcha" value="false">
                                        <?php
                                    }


                                }
                                else
                                {
                        ?>

                                    <input type="hidden" id="haycaptcha" name="haycaptcha" value="false">

                        <?php
                           }
                        ?>



                    </div>

                    <br>

                    <div>
                        <button type="button"  id="submitbutton" value="submit" class="btn btn-default" onclick="checkCaptcha()">Acceder</button>
                    </div>

                    <div class="clearfix"></div>

                    <div class="separator">

                        <div class="clearfix"></div>


                        <div>
                            <h1><i class="fa fa-television"></i> IPTVM Total Control</h1>
                            <p>Grupo Nexwrf ©2017 Todos los derechos reservados. </p>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
</body>

<!-- jQuery -->
<script type="text/javascript" src="{{ URL::asset('../vendors/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap -->
<script type="text/javascript" src="{{ URL::asset('../vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- FastClick -->
<script type="text/javascript" src="{{ URL::asset('../vendors/fastclick/lib/fastclick.js') }}"></script>
<!-- NProgress -->
<script type="text/javascript" src="{{ URL::asset('../vendors/nprogress/nprogress.js') }}"></script>
<!-- Chart.js -->
<script type="text/javascript" src="{{ URL::asset('../vendors/Chart.js/dist/Chart.min.js') }}"></script>
<!-- gauge.js -->
<script type="text/javascript" src="{{ URL::asset('../vendors/gauge.js/dist/gauge.min.js') }}"></script>
<!-- bootstrap-progressbar -->
<script src="{{URL::asset('../vendors/bootstrap-progressbar/bootstrap-progressbar.min.js') }}"></script>
<!-- iCheck -->
<script src="{{URL::asset('../vendors/iCheck/icheck.min.js') }}"></script>
<!-- Skycons -->
<script src="{{URL::asset('../vendors/skycons/skycons.js') }}"></script>
<!-- Flot -->
<script src="{{URL::asset('../vendors/Flot/jquery.flot.js') }}"></script>
<script src="{{URL::asset('../vendors/Flot/jquery.flot.pie.js') }}"></script>
<script src="{{URL::asset('../vendors/Flot/jquery.flot.time.js') }}"></script>
<script src="{{URL::asset('../vendors/Flot/jquery.flot.stack.js') }}"></script>
<script src="{{URL::asset('../vendors/Flot/jquery.flot.resize.js') }}"></script>
<!-- Flot plugins -->
<script src="{{URL::asset('../vendors/flot.orderbars/js/jquery.flot.orderBars.js') }}"></script>
<script src="{{URL::asset('../vendors/flot-spline/js/jquery.flot.spline.min.js') }}"></script>
<script src="{{URL::asset('../vendors/flot.curvedlines/curvedLines.js') }}"></script>
<!-- DateJS -->
<script src="{{URL::asset('../vendors/DateJS/build/date.js') }}"></script>
<!-- JQVMap -->
<script src="{{URL::asset('../vendors/jqvmap/dist/jquery.vmap.js') }}"></script>
<script src="{{URL::asset('../vendors/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
<script src="{{URL::asset('../vendors/jqvmap/examples/js/jquery.vmap.sampledata.js') }}"></script>
<!-- bootstrap-daterangepicker -->
<script src="{{URL::asset('../vendors/moment/min/moment.min.js') }}"></script>
<script src="{{URL::asset('../vendors/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

<!--scripts de otras vistas yield aqui-->
@yield('scripts');


<!-- Custom Theme Scripts -->
<script src="{{URL::asset('js/custom.min.js') }}"></script>


<script>

    $(function() {
        $("form input").keypress(function (e) {
            if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
                $('#submitbutton').click();
                return false;
            } else {
                return true;
            }
        });
    });


</script>


<script>
    //escondemos los mensajes de error /exito de forma automatica a los 15 segs
    setTimeout(function() {
        $('#successMessage').fadeOut('fast');
        $('#errorMessage').fadeOut('fast');
        $('#errorMessageLogin').fadeOut('fast');
    }, 15000); // <-- time in milliseconds
</script>


<script>
    //compruebo que el captcha esta relleno
    function checkCaptcha() {

         var googleResponse = jQuery('#g-recaptcha-response').val();
         var x = document.getElementById("errorMessageLogin");

         var username = document.getElementById("username");
         var password = document.getElementById("password");

         var hayCaptcha= document.getElementById("haycaptcha");

         //alert(hayCaptcha.value);


        if(username.value==""){
            x.style.display = "block";
            document.getElementById("errorMessageLogin").innerHTML = "Introduzca usuario";
            return false;
        }

        if(password.value==""){
            x.style.display = "block";
            document.getElementById("errorMessageLogin").innerHTML = "Introduzca contraseña";
            return false;
        }

        //si en este form hay captcha => verifico que este completeo, vamos, que hayan escrito algo
        if(hayCaptcha.value=="true") {
            //si no esta relleno el captcha => error
            if (!googleResponse) {
                x.style.display = "block";
                document.getElementById("errorMessageLogin").innerHTML = "Debe completar Captcha";
                return false;
            } else {
                //todo ok, hago submit
                //document.getElementById("errorMessage").innerHTML = "TODO OK";
                document.getElementById("loginform").submit();
                return true;
            }
        }
        else{
            document.getElementById("loginform").submit();
        }
     }

</script>

</html>

