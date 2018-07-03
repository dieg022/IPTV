@extends('template')

@section('contenido')

<div class="page-title">
    <div class="title_left">
        <h3>Reemplazar IPTV para {{$cliente->cif}}</h3>
    </div>

</div>
<div class="clearfix"></div>
@include('sessionmessages/details')

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">

            <div class="x_content">
                <br />
                <form id="main" name="main" class="form-horizontal" action='{{ URL::to("cliente/$cliente->id/iptv/$iptv->id/reemplazar") }}' method="post" novalidate>

                    <p>Seleccionar un IPTV del stock disponible para reemplazar por el actual, la configuración integra se copiará de un dispositivo a otro. Especifique una causa de reemplazo
                    para el registro de RMA</p>
                    <br>
                    <br>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="iptv_id">Seleccione un iptv del stock disponible</label>

                        <div class="col-sm-5">
                            <select id="iptv_id" class="form-control" name="iptv_id">

                                <option value=""></option>
                                @foreach($iptvsStock as $iptv)
                                <option value="{{ $iptv->id }}">{{ $iptv->numeroserie }} ({{ $iptv->mac }})</option>
                                @endforeach

                            </select>
                        </div>

                        <div class="col-sm-5 messages"></div>

                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label" id="textarealabel" for="causa">Causa Reemplazo</label>
                        <div class="col-sm-5">
                            <textarea id="causa" class="form-control" type="text" placeholder="Causa Reemplazo" name="causa"></textarea>
                        </div>
                        <div class="col-sm-5 messages" id="textareaerrores">
                        </div>
                    </div>



                    <div class="clearfix">&nbsp;</div>


                    <div class="form-group">
                        <div class="col-sm-offset-2 col-md-2"> <a href="{{ url('cliente/'.$cliente->id.'/iptvs')}}" class="btn btn-danger">Cancelar</a></div>
                        <div class="col-sm-offset-2 col-md-2"><button style="align:right;" type="submit" class="btn btn-primary">Cambiar</button> </div>
                    </div>


                    <div class="form-group">

                        <div class="col-sm-5">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        </div>
                        <!-- como kite esto se va a la picha-->
                        <div class="col-sm-5 messages">
                        </div>


                    </div>


                </form>
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')

<!-- librerias para la validacion -->
<script src="{{URL::asset('../vendors/validatejsnuevo/validate.min.js') }}"></script>
<script src="{{URL::asset('../vendors/validatejsnuevo/underscore-min.js') }}"></script>
<script src="{{URL::asset('../vendors/validatejsnuevo/moment.min.js') }}"></script>

<!-- jquery.inputmask -->
<script src="{{URL::asset('../vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js') }}"></script>


<script>
//control manual del validate del checkbox, esto es psara los colores, abajo hay un trozo para los errores
jQuery(document).ready(function($){
    $('#causa').change(function(){

        textarea=$('#causa').val();
        if(textarea =="") {
            //alert("esta vacio");
            $('#textareaerrores').html("<p class='help-block error' style='color:#a94442'>relleneeee</p>");
            $('#textarealabel').css("color", "#a94442");
            $('#causa').css("border-color", "#a94442");
            errors=1;
        }
        else{
            //alert("esta lleno");
            $('#textareaerrores').html("<p class='help-block error' style='color:#a94442'></p>");
            $('#textarealabel').css("color", "#3c763d");
            $('#causa').css("border-color", "#3c763d");
        }

    });

});

</script>

<script>
    (function() {
        // Before using it we must add the parse and format functions
        // Here is a sample implementation using moment.js
        validate.extend(validate.validators.datetime, {
            // The value is guaranteed not to be null or undefined but otherwise it
            // could be anything.
            parse: function(value, options) {
                return +moment.utc(value);
            },
            // Input is a unix timestamp
            format: function(value, options) {
                var format = options.dateOnly ? "YYYY-MM-DD" : "YYYY-MM-DD hh:mm:ss";
                return moment.utc(value).format(format);
            }
        });

        // These are the constraints used to validate the form
        var constraints = {

            iptv_id: {
                // You need to pick a username too
                presence: {message: "^Seleccione un iptv de reemplazo"}

            },
            causa: {
                // You need to pick a username too
                presence: {message: "^Especifique causa de reemplazo"}

            },
        };



        //customizar mensaje error email
        validate.validators.email.message="no tiene formato válido";

        //el cant be blank
        validate.validators.presence.message="no puede estar vacío";



        // Hook up the form so we can prevent it from being posted
        var form = document.querySelector("form#main");
        form.addEventListener("submit", function(ev) {
            ev.preventDefault();
            handleFormSubmit(form);
        });

        // Hook up the inputs to validate on the fly
        var inputs = document.querySelectorAll("input, textarea, select")
        for (var i = 0; i < inputs.length; ++i) {
            inputs.item(i).addEventListener("change", function(ev) {
                var errors = validate(form, constraints) || {};
                showErrorsForInput(this, errors[this.name])
            });
        }

        function handleFormSubmit(form, input) {
            // validate the form aainst the constraints
            var errors = validate(form, constraints);
            // then we update the form to reflect the results
            showErrors(form, errors || {});
            //alert("hola");

            //check manual del textarea
            textarea=$('#causa').val();
            if(textarea =="") {
                //alert("esta vacio");
                $('#textareaerrores').html("<p class='help-block error' style='color:#a94442'>relleneeee</p>");
                $('#textarealabel').css("color", "#a94442");
                $('#causa').css("border-color", "#a94442");
                errors=1;
            }

            if (!errors) {
                //showSuccess();
                $("#main").submit();

            }

        }

        // Updates the inputs with the validation errors
        function showErrors(form, errors) {
            // We loop through all the inputs and show the errors for that input
            _.each(form.querySelectorAll("input[name], select[name]"), function(input) {
                // Since the errors can be null if no errors were found we need to handle
                // that
                showErrorsForInput(input, errors && errors[input.name]);
            });
        }

        // Shows the errors for a specific input
        function showErrorsForInput(input, errors) {
            // This is the root of the input
            var formGroup = closestParent(input.parentNode, "form-group")
                // Find where the error messages will be insert into
                , messages = formGroup.querySelector(".messages");
            // First we remove any old messages and resets the classes
            resetFormGroup(formGroup);
            // If we have errors
            if (errors) {
                // we first mark the group has having errors
                formGroup.classList.add("has-error");
                // then we append all the errors
                _.each(errors, function(error) {
                    addError(messages, error);
                });
            } else {
                // otherwise we simply mark it as success
                formGroup.classList.add("has-success");
            }
        }

        // Recusively finds the closest parent that has the specified class
        function closestParent(child, className) {
            if (!child || child == document) {
                return null;
            }
            if (child.classList.contains(className)) {
                return child;
            } else {
                return closestParent(child.parentNode, className);
            }
        }

        function resetFormGroup(formGroup) {
            // Remove the success and error classes
            formGroup.classList.remove("has-error");
            formGroup.classList.remove("has-success");
            // and remove any old messages
            _.each(formGroup.querySelectorAll(".help-block.error"), function(el) {
                el.parentNode.removeChild(el);
            });
        }

        // Adds the specified error with the following markup
        // <p class="help-block error">[message]</p>
        function addError(messages, error) {
            var block = document.createElement("p");
            block.classList.add("help-block");
            block.classList.add("error");
            block.innerText = error;
            messages.appendChild(block);
        }

        function showSuccess() {
            // We made it \:D/
            alert("Success!");
        }
    })();
</script>

<script>
    //escondemos los mensajes de error /exito de forma automatica a los 15 segs
    setTimeout(function() {
        $('#successMessage').fadeOut('fast');
        $('#errorMessage').fadeOut('fast');
    }, 15000); // <-- time in milliseconds
</script>


<script>
    /*ajax para rellenar los parametros seleccionados del perfil de instalacion */

    jQuery(document).ready(function($){
        $('#perfilinstalacion_id').change(function(){
            //alert($(this).val());
            $.get("{{ URL('getparametrosperfilinstalacion') }}?perfil=" + $(this).val(),
                function(data) {
                    //alert("yea");
                    //recorro el json obtenido
                    $.each(data, function(i, item) {
                        //el indice esta en i y el item es el valor
                        if(i=="paquetecanal_id"){
                            $('select[name="paquetecanal_id"]').find('option[value="' + item + '"]').attr("selected",true);
                            //alert("paquetecanlid="+item);
                        }
                        if(i=="background_id"){
                            $('select[name="background_id"]').find('option[value="' + item + '"]').attr("selected",true);

                        }
                        if(i=="wifienabled"){
                            //si el wifi esta seleccionado
                            if(item==1)
                            {
                                $('#wifienabled').prop('checked', true);
                                $('#ssid').prop('disabled', false);
                                $('#password').prop('disabled', false);
                                $('#wificanal').prop('disabled', false);
                            }
                            else{
                                $('#wifienabled').prop('checked', false);
                            }
                        }

                        if(i=='ssid'){
                            $('#ssid').val(item);
                        }
                        if(i=='password'){
                            $('#password').val(item);
                        }
                        if(i=='wificanal'){
                            //alert("wificanal:"+item+"|");
                            //aki pasa algo raro, cuando vengo de un perfil sin wifi a uno con wifi el canal se queda vacio... :S
                            $('select[name="wificanal"]').find('option[value="' + item + '"]').attr("selected", true);
                        }

                    });

                });
        });

        /*
         ver si la url se consigue con exito o error
         $.ajax({
         url: "{{ URL('pruebaajax') }}/create/ajax-state?pais_id=1",
         type: 'GET',
         success: function(data){
         alert("exito");
         },
         error: function(data) {
         alert('woops!'); //or whatever
         }
         });*/


    });

    /* });*/


</script>


<!--gestion del checked unchecked del habilitar wifi-->
<script>
    $(document).ready(function() {

        if($('#wifienabled').is(':checked'))
        {
            //alert("inicio esta habilitado");
            $('#ssid').prop('disabled', false);
            $('#password').prop('disabled', false);
            $('#wificanal').prop('disabled', false);
        }
        else
        {
            //alert("inicio no esta habilitado");
            $('#ssid').prop('disabled', true);
            $('#password').prop('disabled', true);
            $('#wificanal').prop('disabled', true);
        }


        $('#wifienabled').change(function() {
            if(this.checked) {
                $('#textbox1').val(this.checked);
                $('#ssid').prop('disabled', false);
                $('#password').prop('disabled', false);
                $('#wificanal').prop('disabled', false);

            }
            else{
                $('#ssid').prop('disabled', true);
                $('#password').prop('disabled', true);
                $('#wificanal').prop('disabled', true);
            }

        });
    });

</script>


@endsection