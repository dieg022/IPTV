@extends('template')

@section('contenido')

<div class="page-title">
    <div class="title_left">
        <h3>Crear Paquete Canales</h3>
    </div>

</div>
<div class="clearfix"></div>
@include('sessionmessages/details')

@include('deletedialog/deletedialog')

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            Seleccione los canales que desee en este paquete, como mínimo uno

            <div class="x_content">
                <br />
                <form id="main" name="main" class="form-horizontal" action="{{ URL::to('paquetecanal')}}" method="post" novalidate>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="nombrepaquete">Nombre</label>
                        <div class="col-sm-5">
                            <input id="nombrepaquete" class="form-control" type="text" placeholder="Nombre Paquete" name="nombrepaquete" value="{{ old('nombrepaquete') }}">
                        </div>
                        <div class="col-sm-5 messages">
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="nombrepaquete">Seleccione canales en este paquete</label>
                        <div class="col-sm-5">


                            <table id="datatable2" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>Orden</th>
                                    <th>Seleccione Pais</th>
                                    <th>Seleccione Canal</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i=1;?>
                                @foreach($canales as $canal)
                                <tr>
                                    <td align="center">{{ $i }}<input type="hidden" name="ordencanal" value="{{$canal->id}}"/></td>
                                    <td>
                                            <select id="pais-<?php echo $i;?>" class="form-control" name="pais<?php echo $i;?>" onChange="cambiador(this)">

                                                <option value="">Seleccione</option>
                                                @foreach($paises as $pais)
                                                <option value="{{$pais->pais}}" >{{$pais->pais}}</option>

                                                @endforeach

                                            </select>
                                    </td>
                                    <td align="center">
                                        <select id="canalpais-<?php echo $i;?>" class="form-control" name="canal;<?php echo $i;?>" onChange="cambiador(this)">
                                            <option value="">Seleccione</option>

                                            @foreach($canales as $canal)
                                            <option value="{{$canal->pais}} {{$canal->canal}}-{{$canal->id}}" >{{$canal->acronimopais}} {{$canal->canal}}</option>
                                            @endforeach


                                        </select>


                                    </td>
                                </tr>
                                <?php $i++;?>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>



                    <div class="clearfix">&nbsp;</div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-md-2"> <a href="{{ url('paquetecanal')}}" class="btn btn-danger">Cancelar</a></div>
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


<script>
    //al principio todos los select de canales deshabilitados, cuando seleccionas un pais, se habilita
    $(document).ready(function(){
        //alert("completado");
        for (var j = 1; j < {{$numeroCanales}}; j++) {

            var id= '#'+j;
            //alert(valor);
            //alert(valor.id);
            /*var clasecita = $(id).find(':selected').attr('class');
             //var clasecita = $(valor.id).find(':selected').attr('class'));
             alert("la clase del subproducto seleccionado es: "+clasecita);
             */
            nbrCompuesto="canal"+"pais-"+j;
            //alert(nbrCompuesto);
            selectobject = document.getElementById(nbrCompuesto);
            //alert("la longitud es "+selectobject.length);

            selectobject.disabled=true;
        }
    });


</script>


<script>

    function cambiador(valor)
    {
        //alert("el valor seleccionado es "+ valor.value);
        //alert("el id es "+ valor.id);
        //valor = document.getElementById(valor.id);
        var id= '#'+valor.id;
        /*var clasecita = $(id).find(':selected').attr('class');
         //var clasecita = $(valor.id).find(':selected').attr('class'));
         alert("la clase del subproducto seleccionado es: "+clasecita);
         */
        nbrCompuesto="canal"+valor.id;
        //alert(nbrCompuesto);
        selectobject = document.getElementById(nbrCompuesto);
        //cuando se selecciona un pais, habilito los canales y selecciono el vacio por defecto
        selectobject.disabled=false;
        selectobject.getElementsByTagName('option')[0].selected = 'selected';
        //alert("la longitud es "+selectobject.length);

        var idCompuesto = '#' +nbrCompuesto;
        var longitud = selectobject.length;

        //cada vez que borro cambia la longitud del select => jode un poco todo
        //lpo recorro del reves !!
        var longitud2 = selectobject.length;

        //cada vez que hay un cambio habilito todos ( los canales)
        for (var i=longitud2-1; i>=0; i--){
            selectobject.options[i].disabled=false;
            selectobject.options[i].style.background="white";
            selectobject.options[i].style.color="black";
        }

        //for (var i=0; i<longitud2; i++){
        for (var i=longitud2-1; i>=0; i--){

            //alert(selectobject.options[i].value);

            if(!selectobject.options[i].value.includes(valor.value)){
                //alert(selectobject.options[i].value + " no lo tiene => lo borro");
                //selectobject.remove(i);
                selectobject.options[i].disabled=true;
            }
            else{
                selectobject.options[i].style.background="#1abb9c";
                selectobject.options[i].style.color="white";
                //alert(selectobject.options[i].value + " si l otiene => no lo borro");
            }
        }
        /*j++;
         alert("j="+j+" longitud es:"+longitud);
         }*/



        /*  for (var i=0; i<selectobject.length; i++){
         if (selectobject.options[i].value == 'A' )
         selectobject.remove(i);
         }*/

    }

    //esta funcion coge el canal seleccionado y lo pone a no disponible en los demas.. mmm... chungo...
    function updatecanal(valor)
    {
       /*el tema es el siguiente:
       * pones espana tve, pues ese ya no esa disponible en los de abajo pero...
       * si se deshace... como lo vuelvo a habilitar.... pffff
       * tiene un curre weno...
       *
       * cuando se selecciona uno he de recorrer todos los demas selecte de canales y desactivar ese
       * pero claro... cuando se cambia... como se el valor del anterior... he de crear un input hiden asociado
       * a cada subselect o select de canales que guarde el valor anterior
       *
       * */
    }



</script>


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

            nombrepaquete: {
                // You need to pick a username too
                presence: {message: "^El nombre no puede estar vacio"},
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