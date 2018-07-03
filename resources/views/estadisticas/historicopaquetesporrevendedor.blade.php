@extends('template')

@section('css')


<!-- Datatables -->
<link href="{{ URL::asset('../vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('../vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('../vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('../vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('../vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" rel="stylesheet">

@endsection

@section('contenido')
<!-- viene de <div class="right_col" role="main">-->

<div class="">
    <div class="page-title">
        <div class="title_left">
            <h3>Historico facturación</h3>
        </div>

    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    @include('sessionmessages/details')
                    <ul class="nav navbar-right panel_toolbox">
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Importe</th>
                        </tr>
                        </thead>
                        <tr>

                        <tbody>
                        @foreach($facturacion as $fac)

                            <?php
                                $fecha_facturacion = explode(" ",$fac->fecha_facturacion);
                                $fecha_facturacion = $fecha_facturacion[0];
                            ?>

                            <tr><td>{{$fecha_facturacion}}</td>
                            <td>{{$fac->importe}} Eur</td></tr>


                        @endforeach
                        </tbody>

                    </table>

                    <br><br>
                    <p>


                    </p>

                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts');

<!-- Datatables -->
<!-- Datatables -->
<script type="text/javascript" src="{{ URL::asset('../vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('../vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('../vendors/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('../vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('../vendors/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('../vendors/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('../vendors/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('../vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('../vendors/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('../vendors/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('../vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('../vendors/datatables.net-scroller/js/dataTables.scroller.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('../vendors/jszip/dist/jszip.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('../vendors/pdfmake/build/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('../vendors/pdfmake/build/vfs_fonts.js') }}"></script>



@endsection