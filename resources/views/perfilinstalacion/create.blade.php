@extends('template')

@section('contenido')

<div class="page-title">
    <div class="title_left">
        <h3>Alta Perfil Instalación</h3>
    </div>

</div>
<div class="clearfix"></div>
@include('sessionmessages/details')

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">

            <div class="x_content">
                <br />
                <form id="main" name="main" class="form-horizontal" action="{{ URL::to('perfilinstalacion')}}" method="post" novalidate>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="nombre">Nombre Perfil</label>
                        <div class="col-sm-5">
                            <input id="nombre" class="form-control" type="text" placeholder="Nombre Perfil" name="nombre" value="{{ old('nombre') }}">
                        </div>
                        <div class="col-sm-5 messages">
                        </div>
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

                    @endif

                    <div class="clearfix">&nbsp;</div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-md-2"> <a href="{{ url('perfilinstalacion')}}" class="btn btn-danger">Cancelar</a></div>
                        <div class="col-sm-offset-2 col-md-4"><button style="align:right;" type="submit" class="btn btn-primary">Crear</button> </div>
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

            nombre: {
                // You need to pick a username too
                presence: {message: "^Especifique un nombre para el perfil"},
                // And it must be between 3 and 20 characters long
                length: {
                    minimum: 5,
                    maximum: 40,
                    message: "^Debe contener al menos 5 characteres,máximo 40"
                },
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



@endsection