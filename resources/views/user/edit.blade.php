@extends('template')

@section('contenido')

<div class="page-title">
    <div class="title_left">
        <h3>Editar Usuario</h3>
    </div>

</div>
<div class="clearfix"></div>
@include('sessionmessages/details')

@include('deletedialog/deletedialog')

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">

            <div class="x_content">
                <br />
                <form id="main" name="main" class="form-horizontal" action="{{ URL::to('user/'.$user->id)}}"  method="POST" novalidate>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="nombre_completo">DNI/CIF</label>
                        <div class="col-sm-5">
                            <input id="cif" class="form-control" type="text" placeholder="DNI/CIF" name="cif" value="{{ old('cif',$user->cif) }}">
                        </div>
                        <div class="col-sm-5 messages">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="nombre_completo">Nombre Completo</label>
                        <div class="col-sm-5">
                            <input id="nombre_completo" class="form-control" type="text" placeholder="Nombre Completo" name="nombre_completo" value="{{ old('nombre_completo',$user->nombre_completo) }}">
                        </div>
                        <div class="col-sm-5 messages">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="direccion">Direccion</label>
                        <div class="col-sm-5">
                            <input id="direccion" class="form-control" type="text" placeholder="Direccion" name="direccion"  value="{{ old('direccion',$user->direccion) }}">
                        </div>
                        <div class="col-sm-5 messages">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="poblacion">Poblacion</label>
                        <div class="col-sm-5">
                            <input id="poblacion" class="form-control" type="text" placeholder="Poblacion" name="poblacion"  value="{{ old('poblacion',$user->poblacion) }}">
                        </div>
                        <div class="col-sm-5 messages">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="usuario">Usuario Acceso</label>
                        <div class="col-sm-5">
                            <input id="usuario" class="form-control" type="text" placeholder="Usuario Acceso" name="name" value="{{ old('name',$user->name) }}">
                        </div>
                        <div class="col-sm-5 messages">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="email">Email</label>
                        <div class="col-sm-5">
                            <input id="email" class="form-control" type="email" placeholder="Email" name="email" value="{{ old('email',$user->email) }}">
                        </div>
                        <div class="col-sm-5 messages"></div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="password">Password</label>
                        <div class="col-sm-5">
                            <input id="password" class="form-control" type="password" placeholder="Password" name="password" value="*****">
                        </div>
                        <div class="col-sm-5 messages"></div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="confirm-password">Confirme Password</label>
                        <div class="col-sm-5">
                            <input id="confirm-password" class="form-control" type="password" placeholder="Confirme password" name="confirm-password" value="*****" >
                        </div>
                        <div class="col-sm-5 messages"></div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="tipocliente">Tipo</label>
                        <div class="col-sm-5">
                            <select id="tipocliente" class="form-control" name="tipocliente" >

                                <option value=""></option>
                                @if (old('tipocliente',$user->tipocliente) == "HOTEL")
                                <option value="HOTEL" selected>Hotel</option>
                                @else
                                <option value="HOTEL">Hotel</option>
                                @endif

                                @if (old('tipocliente',$user->tipocliente) == "REVENTA")
                                <option value="REVENTA" selected>Cablero/Wifero</option>
                                @else
                                <option value="REVENTA">Cablero/Wifero</option>
                                @endif

                                @if (old('tipocliente',$user->tipocliente)  == "ADMIN")
                                <option value="ADMIN" selected>Admin</option>
                                @else
                                <option value="ADMIN">Admin</option>
                                @endif

                                @if (old('tipocliente',$user->tipocliente)  == "ALMACEN")
                                <option value="ALMACEN" selected>Almacen</option>
                                @else
                                <option value="ALMACEN">Almacen</option>
                                @endif

                                @if (old('tipocliente',$user->tipocliente)  == "PRODUCTOR")
                                <option value="PRODUCTOR" selected>Productor</option>
                                @else
                                <option value="PRODUCTOR">Productor</option>
                                @endif


                            </select>
                        </div>
                        <div class="col-sm-5 messages"></div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="ipwowzalocal">IP Wowza Local</label>
                        <div class="col-sm-5">
                            <input id="ipwowzalocal" class="form-control" type="text" placeholder="IP Wowza Local" name="ipwowzalocal" value="{{ old('ipwowzalocal',$user->ipwowzalocal) }}">
                        </div>
                        <div class="col-sm-5 messages">
                        </div>
                    </div>


                    <div class="clearfix">&nbsp;</div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-md-2"
                             data-form="#borrarclienteformulario" data-title="Eliminar Usuario" data-message="¿Desea eliminar el este usuario?<br><br><b>NOTA:</b> Se eliminará toda la información
de este usuario: perfiles,backgrounds,clientes,etc... sus iptvs pasarán a stock general de superadmin">
                            <a class = "formConfirm btn btn-danger" href="" class="btn btn-danger">Eliminar</a>
                        </div>
                        <div class="col-md-2"> <a href="{{ url('user')}}" class="btn btn-default">Cancelar</a></div>
                        <div class="col-md-4"><button style="align:right;" type="submit" class="btn btn-primary">Editar</button> </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-5">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        </div>
                        <!-- como kite esto se va a la picha-->
                        <div class="col-sm-5 messages">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-5">
                            <input name="_method" type="hidden" value="PATCH">
                        </div>
                        <!-- como kite esto se va a la picha-->
                        <div class="col-sm-5 messages">
                        </div>
                    </div>

                </form>

                <form id="borrarclienteformulario" name=borrarclienteformulario" action="{{ URL::to('user/'.$user->id)}}" method="post" novalidate>
                    <input name="_method" type="hidden" value="DELETE">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
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
            email: {
                // Email is required
                presence: true,
                // and must be an email (duh)
                email: true,
            },
            password: {
                // Password is also required
                presence: true,
                // And must be at least 5 characters long
                length: {
                    minimum: 5,
                    message: "^Introduzca al menos 5 caracteres"
                }
            },
            "confirm-password": {
                // You need to confirm your password
                presence: true,
                // and it needs to be equal to the other password
                equality: {
                    attribute: "password",
                    message: "^Los passwords no concuerdan"
                }
            },
            name: {
                // You need to pick a username too
                presence: true,
                // And it must be between 3 and 20 characters long
                length: {
                    minimum: 5,
                    maximum: 20,
                    message: "^Debe contener al menos 5 characteres,máximo 20"
                },
                format: {
                    // We don't allow anything that a-z and 0-9
                    pattern: "[a-z0-9]+",
                    // but we don't care if the username is uppercase or lowercase
                    flags: "i",
                    message: "^Solo puede contener a-z y 0-9, sin espacios"
                }
            },
            nombre_completo: {
                // You need to pick a username too
                presence: true,
                // And it must be between 3 and 20 characters long
                length: {
                    minimum: 5,
                    maximum: 40,
                    message: "^Debe contener al menos 5 characteres,máximo 40"
                },
                format: {
                    // We don't allow anything that a-z and 0-9
                    pattern: "[a-zA-Z ]+",
                    // but we don't care if the username is uppercase or lowercase
                    flags: "i",
                    message: "^Solo puede contener a-z y A-Z"
                }
            },
            tipocliente: {
                // You also need to input where you live
                presence: true,
                // And we restrict the countries supported to Sweden
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


<!--delete dialog form modal-->
<script type="text/javascript">
    $('.formConfirm').on('click', function(e) {
        e.preventDefault();
        var el = $(this).parent();
        var title = el.attr('data-title');
        var msg = el.attr('data-message');
        var dataForm = el.attr('data-form');

        $('#formConfirm')
            .find('#frm_body').html(msg)
            .end().find('#frm_title').html(title)
            .end().modal('show');

        $('#formConfirm').find('#frm_submit').attr('data-form', dataForm);
    });

    $('#formConfirm').on('click', '#frm_submit', function(e) {
        var id = $(this).attr('data-form');
        $(id).submit();
        //alert("yea");
        //alert(id);
    });
</script>


@endsection