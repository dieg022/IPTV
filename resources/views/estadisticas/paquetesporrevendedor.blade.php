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
            <h3>Importes por canal</h3>
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

                     <?php
                     use App\User;
                        $totalProductor=0;
                     ?>

                    @foreach($canales as $canal)

                    <p> <span style="font-size: 30px">{{ $canal->canal }}</span></p>

                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Revendedor</th>
                                <th>Unidades de este canal</th>
                                <th>Precio</th>
                                <th>Precio Mensual Poblacion</th>
                                <th>Subtotal</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php

                            //obtengo todos los reventas del tipo reventa que no se llamen demo (ojo, no hoteles)
                            $reventas=User::where('tipocliente','REVENTA')->where('name', 'NOT LIKE', '%demo%')->get();

                            $subTotalGrupo=0;
                            foreach($reventas as $reventa)
                            {
                                $subTotalCanal=0;
                                //echo $reventa->nombre_completo;
                                //para cada reventa saco cuantos clientes tienen el canal en cuestion:

                                $unidadesCanal =
                            DB::select(DB::raw(" select count(*) as unidades
                                from clientes_iptvs
                                where cliente_id in
                                    ( select clientes.id from clientes where user_id=:uid)
                                and paquetecanal_id in
                                    ( select canales_paquetes.paquetecanal_id from canales_paquetes where canal_id=:cid);"), array(
                            'uid' => $reventa->id,'cid' => $canal->id, ));

                                   // echo $reventa->nombre_completo."---con id=".$reventa->id." tiene canales=".$unidadesCanal[0]->unidades." precio individual=$canal->precio y precio por poblacion = $canal->precioporpoblacion <br>";


                                    $subTotalCanal= $subTotalCanal+$unidadesCanal[0]->unidades * $canal->precio + $canal->precioporpoblacion;


                            echo "<tr><td>$reventa->nombre_completo ($reventa->poblacion)</td>";
                                echo "<td>".$unidadesCanal[0]->unidades."</td>";
                                echo "<td>$canal->precio Eur/Unid.</td>";
                                echo "<td>".$canal->precioporpoblacion."</td>";



                                echo "<td>$subTotalCanal Eur.</td><tr>";

                                $subTotalGrupo=$subTotalGrupo + $subTotalCanal;



                            }
                                echo "<td></td><td></td><td><td>Total para este canal</td><td>$subTotalGrupo Eur.</td>";



                            ?>

                            </tbody>

                        </table>

                            <?php
                                $totalProductor = $totalProductor +$subTotalGrupo;

                             ?>


                    @endforeach

                    <br><br>
                    <p>

                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <td></td>
                            <td></td>
                            <td align="right"><span style="font-size: 18px">Total Global</span></td>
                            <td align="right"><span style="font-size: 18px">{{$totalProductor}} Eur.</span></td>
                        </tr>
                        </thead>


                    </table>

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