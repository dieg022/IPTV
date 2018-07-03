@extends('template')

@section('contenido')

<div class="page-title">
    <div class="title_left">
        <h3>Alta IPTV</h3>
    </div>

</div>
<div class="clearfix"></div>
@include('sessionmessages/details')

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">

            <div class="x_content">
                <br />
                <form id="main" name="main" class="form-horizontal" action="{{ URL::to('iptv')}}" method="post" novalidate>


                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="tipo">Fecha Compra</label>
                        <div class="col-sm-5">
                            <div class='input-group date' id='myDatepicker2'>
                                <input type='text' id="fechacompra" name="fechacompra" class="form-control" value="{{ old('fechacompra') }}" />
                                <span class="input-group-addon">
                               <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            </div>
                        </div>
                        <div class="col-sm-5 messages">
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="tipo">Modelo</label>
                        <div class="col-sm-5">
                            <select id="tipo" class="form-control" name="tipo" value="{{ old('tipo') }}">
                                <option value=""></option>

                                @if (old('tipo') == "IPTVM11")
                                <option value="IPTVM11" selected>IPTVM11</option>
                                @else
                                <option value="IPTVM11">IPTVM11</option>
                                @endif

                                @if (old('tipo') == "IPTVM12")
                                <option value="IPTVM12" selected>IPTVM12</option>
                                @else
                                <option value="IPTVM12">IPTMV12</option>
                                @endif

                                @if (old('tipo') == "IPTVM22")
                                <option value="IPTVM22" selected>IPTVM22</option>
                                @else
                                <option value="IPTVM22">IPTMV22</option>
                                @endif


                            </select>
                        </div>
                        <div class="col-sm-5 messages"></div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="tipo">Proveedor</label>
                        <div class="col-sm-5">
                            <select id="proveedor_id" class="form-control" name="proveedor_id" value="{{ old('proveedor_id') }}">
                                <option value=""></option>
                                @foreach($proveedores as $proveedor)


                                @if (old('proveedor_id') == $proveedor->id)
                                <option value="{{ $proveedor->id }}" selected >{{ $proveedor->proveedor }}</option>
                                @else
                                <option value="{{ $proveedor->id }}">{{ $proveedor->proveedor }}</option>
                                @endif


                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-5 messages"></div>
                    </div>




                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="mac">MAC</label>
                        <div class="col-sm-5">
                            <input id="mac" class="form-control" type="text" placeholder="MAC" name="mac" data-inputmask="'mask' : '**:**:**:**:**:**'"
                                   value="{{ old('mac') }}">
                        </div>
                        <div class="col-sm-5 messages">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="numeroserie">Numero Serie</label>
                        <div class="col-sm-5">
                            <input id="numeroserie" class="form-control" type="text" placeholder="Numero Serie" name="numeroserie" value="{{ old('numeroserie') }}">
                        </div>
                        <div class="col-sm-5 messages">
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="tipo">Asignar a Usuario</label>
                        <div class="col-sm-5">
                            <select id="usuario_id" class="form-control" name="usuario_id" value="{{ old('usuario_id') }}">

                                @foreach($usuarios as $usuario)
                                    @if (old('usuario_id') == $usuario->id)
                                    <option value="{{ $usuario->id }}" selected >{{ $usuario->nombre_completo }}</option>
                                    @else
                                    <option value="{{ $usuario->id }}">{{ $usuario->nombre_completo }}</option>
                                    @endif
                                @endforeach

                            </select>
                        </div>
                        <div class="col-sm-5 messages"></div>
                    </div>












                    <div class="clearfix">&nbsp;</div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-md-2"> <a href="{{ url('iptv')}}" class="btn btn-danger">Cancelar</a></div>
                        <div class="col-sm-offset-2 col-md-4"><button style="align:right;" type="submit" class="btn btn-primary">Alta</button> </div>
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

            numeroserie: {
                // You need to pick a username too
                presence: {message: "^Indique un numero de serie"}

            },
            proveedor_id: {
                // You need to pick a username too
                presence: {message: "^Indique un proveedor"}

            },
            tipo: {
                // You need to pick a username too
                presence: {message: "^Indique un modelo"}
            },
            fechacompra: {
                // You need to pick a username too
                presence: {message: "^Indique una fecha de compra"}

            },
            mac: {
                presence: {message: "^Indique una MAC"},
                format: {
                 // We don't allow anything that a-z and 0-9
                 pattern:  "^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$",
                 message: "^Incorrecto, solo nums y mayusculas"
                 },
            }
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

    $('#myDatepicker2').datetimepicker({
        format: 'YYYY-MM-DD'
    });
</script>


@endsection