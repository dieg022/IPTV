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
            <h3>Mis Canales</h3>
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
                    <table id="datatable2" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th width="100px" align="center">Logo</th>
                            <th>Pais</th>
                            <th>Canal</th>
                            <?php
                                if($user->tipocliente=="PRODUCTOR")
                                    echo "<th>Importe por canal</th>";
                            ?>

                        </tr>
                        </thead>
                        <tbody>

                        @foreach($canales as $canal)
                        <tr>
                            <td align="center"><img src="{{ URL::asset('images/'.$canal->urllogo) }}" alt="{{ $canal->canal }}" width="60px" height="60px"></td>
                            <td>{{ $canal->acronimopais }}</td>
                            <td>{{ $canal->canal }}</td>
                            <?php
                                if($user->tipocliente=="PRODUCTOR")
                                    echo "<td>$canal->precio Eur.</td>"
                            ?>

                        </tr>
                        @endforeach

                        </tbody>

                    </table>
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

<script>
    $(document).ready(function() {
        $('#datatable2').DataTable( {
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
            }
        } );
    } );
</script>

@endsection