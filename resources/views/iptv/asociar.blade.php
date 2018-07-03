@extends('template')

@section('contenido')

<div class="page-title">
    <div class="title_left">
        <h3>Asociando IPTV</h3>
    </div>

</div>
<div class="clearfix"></div>
@include('sessionmessages/details')

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">

            <div class="x_content">
                <br />
                <form id="main" name="main" class="form-horizontal" action="{{ URL::to('iptvasociar')}}" method="post" novalidate>


                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="cliente_id">
                            @if( Auth::user()->tipocliente=='hotel' )
                                Seleccione una habitacion
                            @else
                                Seleccione un cliente
                            @endif

                        </label>
                        <div class="col-sm-5">
                            <select id="cliente_id" class="form-control" name="cliente_id" value="{{ old('cliente_id') }}">

                                <option value=""></option>
                                @foreach($clientes as $cliente)
                                    @if (old('cliente_id') == $cliente->id)
                                    <option value="{{ $cliente->id }}" selected >{{ $cliente->cif }}</option>
                                    @else
                                    <option value="{{ $cliente->id }}">{{ $cliente->cif }}</option>
                                    @endif
                                @endforeach

                            </select>
                        </div>
                        <div class="col-sm-5 messages"></div>

                    </div>



                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="perfilinstalacion_id">Seleccione un perfil de instalación (para autocompletar)</label>

                        <div class="col-sm-5">
                            <select id="perfilinstalacion_id" class="form-control" name="perfilinstalacion_id">

                                <option value=""></option>
                                @foreach($perfiles as $perfil)
                                <option value="{{ $perfil->id }}">{{ $perfil->nombre }}</option>
                                @endforeach

                            </select>
                        </div>

                        <div class="col-sm-5 messages"></div>

                    </div>



                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="paquetecanal_id">Paquete de canales</label>
                        <div class="col-sm-5">
                            <select id="paquetecanal_id" class="form-control" name="paquetecanal_id" value="{{ old('paquetecanal_id') }}">
                                <option value=""></option>
                                @foreach($paquetescanales as $pack)


                                @if (old('paquetecanal_id') == $pack->id)
                                <option value="{{ $pack->id }}" selected >{{ $pack->nombrepaquete }}</option>
                                @else
                                <option value="{{ $pack->id }}">{{ $pack->nombrepaquete }}</option>
                                @endif


                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-5 messages"></div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="background_id">Fondo</label>
                        <div class="col-sm-5">
                            <select id="background_id" class="form-control" name="background_id" value="{{ old('background_id') }}">
                                <option value=""></option>
                                @foreach($backgrounds as $bg)


                                @if (old('background_id') == $bg->id)
                                <option value="{{ $bg->id }}" selected >{{ $bg->background }}</option>
                                @else
                                <option value="{{ $bg->id }}">{{ $bg->background }}</option>
                                @endif


                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-5 messages"></div>
                    </div>


                    @if( Auth::user()->tipocliente=='HOTEL' )

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="wifienabled">Wifi Habilitado?</label>
                        <div class="col-sm-5">
                            @if (old('wifienabled') == 1)
                            <input id="wifienabled" name="wifienabled" type="checkbox" checked/>
                            @else
                            <input id="wifienabled" name="wifienabled" type="checkbox"/>
                            @endif
                        </div>
                        <div class="col-sm-5 messages">
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="ssid">SSID</label>
                        <div class="col-sm-5">
                            <input id="ssid" class="form-control" type="text" placeholder="SSID" name="ssid" value="{{ old('ssid') }}">
                        </div>
                        <div class="col-sm-5 messages">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="password">Password Wifi</label>
                        <div class="col-sm-5">
                            <input id="password" class="form-control" type="text" placeholder="Password Wifi" name="password" value="{{ old('password') }}">
                        </div>
                        <div class="col-sm-5 messages">
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="wificanal">Canal Wifi</label>
                        <div class="col-sm-5">
                            <select id="wificanal" class="form-control" name="wificanal" value="{{ old('wificanal') }}">
                                <option value=""></option>

                                @for ($i = 1; $i < 14; $i++)
                                @if (old('wificanal') == "$i")
                                <option value="{{$i}}" selected>{{$i}}</option>
                                @else
                                <option value="{{$i}}" >{{$i}}</option>
                                @endif
                                @endfor

                            </select>
                        </div>
                        <div class="col-sm-5 messages"></div>
                    </div>


                    <div class="clearfix">&nbsp;</div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="switchpoe_id">Switch POE</label>
                        <div class="col-sm-5">
                            <select id="switchpoe_id" class="form-control" name="switchpoe_id" value="{{ old('switchpoe_id') }}">
                                <option value=""></option>
                                @foreach($switchs as $bg)


                                @if (old('switchpoe_id') == $bg->id)
                                <option value="{{ $bg->id }}" selected >{{ $bg->nombre }}({{ $bg->ubicacion }})</option>
                                @else
                                <option value="{{ $bg->id }}">{{ $bg->nombre }} ({{ $bg->ubicacion }})</option>
                                @endif


                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-5 messages"></div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="puerto">Puerto POE</label>
                        <div class="col-sm-5">
                            <select id="puerto" class="form-control" name="puerto" value="{{ old('puerto') }}">
                                <option value=""></option>

                                @for ($i = 1; $i <= 48; $i++)

                                @if (old('puerto') == $i)
                                <option value="{{ $i}}" selected >{{ $i }}</option>
                                @else
                                <option value="{{ $i }}">{{ $i }}</option>
                                @endif


                                @endfor

                            </select>
                        </div>
                        <div class="col-sm-5 messages"></div>
                    </div>

                    @endif

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="ssid">Observaciones</label>
                        <div class="col-sm-5">
                            <input id="observaciones" class="form-control" type="text" placeholder="Observaciones" name="observaciones" value="{{ old('observaciones') }}">
                        </div>
                        <div class="col-sm-5 messages">
                        </div>
                    </div>

                    <div class="clearfix">&nbsp;</div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-md-2"> <a href="{{ url('iptv/stock')}}" class="btn btn-danger">Cancelar</a></div>
                        <div class="col-sm-offset-2 col-md-4"><button style="align:right;" type="submit" class="btn btn-primary">Asociar</button> </div>
                    </div>


                    <div class="form-group">

                        <div class="col-sm-5">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        </div>
                        <!-- como kite esto se va a la picha-->
                        <div class="col-sm-5 messages">
                        </div>


                        <div class="col-sm-5">
                            <input type="hidden" name="iptv_id" value="<?php echo $iptv->id; ?>">
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

            cliente_id: {
                // You need to pick a username too
                presence: {message: "^Debe seleccionar uno"}
            },
            perfilinstalacion_id: {
                // You need to pick a username too
                presence: {message: "^Debe seleccionar un perfil"}
            },
            paquetecanal_id: {
                // You need to pick a username too
                presence: {message: "^Seleccione un paquete de canales"}

            },
            background_id: {
                // You need to pick a username too
                presence: {message: "^Seleccione un fondo"}

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
                        }

                        if(i=='ssid'){
                            $('#ssid').val(item);
                        }
                        if(i=='password'){
                            $('#password').val(item);
                        }
                        if(i=='wificanal'){
                            $('select[name="wificanal"]').find('option[value="' + item + '"]').attr("selected",true);
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

        //estado inicial
        $('#wifienabled').val($(this).is(':checked'));

        if($('#wifienabled').val()==true){
            $('#ssid').prop('disabled', false);
            $('#password').prop('disabled', false);
            $('#wificanal').prop('disabled', false);
        }
        else {
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