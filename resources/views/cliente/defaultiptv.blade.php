@extends('template')

@section('contenido')

<div class="page-title">
    <div class="title_left">
        <h3>Resetear por defecto IPTV  {{$iptv->mac}}</h3>
    </div>

</div>
<div class="clearfix"></div>
@include('sessionmessages/details')

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">

            <div class="x_content">
                <br />
                <form id="main" name="main" class="form-horizontal" action='{{ URL::to("cliente/$cliente->id/iptv/$iptv->id/default") }}' method="post" novalidate>

                    <div class="row" >
                        <div style="text-align: center">¿Confirma que desea establecer por defecto la interfaz de este IPTV?</div>
                    </div>

                    <br>
                    <br>


                    <div class="center-block text-center">
                        <a href="{{ url('cliente/'.$cliente->id.'/iptvs')}}" class="btn btn-danger">Cancelar</a>
                        <button style="align:right;" type="submit" class="btn btn-primary">Confirmar</button>
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
    //escondemos los mensajes de error /exito de forma automatica a los 15 segs
    setTimeout(function() {
        $('#successMessage').fadeOut('fast');
        $('#errorMessage').fadeOut('fast');
    }, 15000); // <-- time in milliseconds
</script>



@endsection