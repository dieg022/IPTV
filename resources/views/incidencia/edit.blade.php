@extends('template')

@section('contenido')

<?php

use App\Iptv;

?>

<div class="page-title">
    <div class="title_left">
        <h3>Editar Incidencia</h3>
    </div>

</div>
<div class="clearfix"></div>
@include('sessionmessages/details')

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">

            <div class="x_content">
                <br />
                <form id="main" name="main" class="form-horizontal" action="{{ URL::to('rma/'.$incidencia->id )}}" method="post" novalidate>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="fechaapertura">Fecha Apertura</label>
                        <div class="col-sm-5">
                            <input id="fechaapertura" class="form-control" type="text" placeholder="Fecha Apertura" readonly=yes name="fechaapertura"
                                   value="{{ $incidencia->fecha_apertura_incidencia }}">
                        </div>
                        <div class="col-sm-5 messages">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="iptv">IPTV</label>
                        <div class="col-sm-5">

                            <?php
                                    $iptv = Iptv::find($incidencia->iptv_id);
                                    $snMac = $iptv->numeroserie." (".$iptv->mac.") ";
                            ?>

                            <input id="iptv" class="form-control" type="text" placeholder="Iptv" readonly=yes name="iptv"
                                   value="{{ $snMac }}">
                        </div>
                        <div class="col-sm-5 messages">
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="problema">Problema</label>
                        <div class="col-sm-5">
                            <textarea   style="width: 100%" readonly=yes>{{ $incidencia->problema }}</textarea>

                        </div>
                        <div class="col-sm-5 messages">
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="resolucion">Resolucion</label>
                        <div class="col-sm-5">
                            <textarea  id="resolucion" name="resolucion" style="width: 100%" >{{ $incidencia->resolucion }}</textarea>
                        </div>
                        <div class="col-sm-5 messages">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="Estado">Estado</label>
                        <div class="col-sm-5">
                            <select id="estado" class="form-control" name="estado" value="{{ old('estado',$incidencia->estado) }}">


                                @if ($incidencia->estado =="ABIERTA")
                                <option  value="ABIERTA" selected >ABIERTA</option>
                                @else
                                <option  value="ABIERTA" >ABIERTA</option>
                                @endif

                                @if ($incidencia->estado =="REVISANDO")
                                <option  value="REVISANDO" selected >REVISANDO</option>
                                @else
                                <option  value="REVISANDO">REVISANDO</option>
                                @endif

                                @if ($incidencia->estado =="CERRADA")
                                <option  value="CERRADA" selected >CERRADA</option>
                                @else
                                <option  value="CERRADA" >CERRADA</option>
                                @endif

                            </select>
                        </div>
                        <div class="col-sm-5 messages"></div>
                    </div>



                    <div class="form-group">
                        <div class="col-sm-5">
                            <input name="_method" type="hidden" value="PATCH">
                        </div>
                        <!-- como kite esto se va a la picha-->
                        <div class="col-sm-5 messages">
                        </div>
                    </div>





                    <div class="clearfix">&nbsp;</div>

                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-5">
                            <div class="col-md-5"> <a href="{{ url('rma-almacen')}}" style="margin-left: -10px;" class="btn btn-danger">Cancelar</a></div>
                            <div class="col-sm-offset-4 col-md-3"><button style="align:right;" type="submit" class="btn btn-primary">Actualizar</button> </div>
                        </div>
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



<script>
    //escondemos los mensajes de error /exito de forma automatica a los 15 segs
    setTimeout(function() {
        $('#successMessage').fadeOut('fast');
        $('#errorMessage').fadeOut('fast');
    }, 15000); // <-- time in milliseconds
</script>



@endsection