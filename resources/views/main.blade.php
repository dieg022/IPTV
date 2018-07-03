@extends('template')

@section('contenido')


<div class="">


    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Bienvenido al sistema</h2>


                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="col-md-3 col-sm-3 col-xs-12 profile_left">
                        <div class="profile_img">
                            <div id="crop-avatar">
                                <!-- Current avatar -->
                                <br><br>
                                <img class="img-responsive avatar-view"  src="{{ URL::asset('images/iptvimgmini.png') }}" alt="Avatar" title="Change the avatar">
                            </div>
                        </div>
                        <br>
                        <h3>IPTVM Total Control</h3>

                        <ul class="list-unstyled user_data">
                            <li><i class="fa fa-map-marker user-profile-icon"></i> Jerez de la Frontera, Cádiz, ES
                            </li>

                            <li>
                                <i class="fa fa-briefcase user-profile-icon"></i> División Contenido Multimedia
                            </li>

                            <li class="m-top-xs">
                                <i class="fa fa-external-link user-profile-icon"></i>
                                <a href="http://www.nexwrf.es" target="_blank">www.nexwrf.es</a>
                            </li>
                        </ul>


                        <br />

                    </div>
                    <div class="col-md-9 col-sm-9 col-xs-12">


                        <!-- start of user-activity-graph -->
                        <div id="graph_bar" style="width:100%; height:280px;margin-bottom: 140px;">
                            <blockquote class="message" align="justify">IPTVM Total Control &reg; es una plataforma online orientada distribuidores de productos de la serie IPTVM.<br><br>
                            IPTVM Total Control &reg; permite gestionar la cartera de clientes que acceden a los servicios demandados así como aprovisionar en tiempo real sus paquetes
                                multimedia y dispositivos de acceso. Con estos tres sencillos pasos se obtiene la máxima versatilidad e independencia:<br><br>
                                1) Configurar<br>
                                2) Aprovisionar<br>
                                3) Aplicar
                            </blockquote>
                            <div align="center" style="margin-top: -30px">
                            <blockquote class="message">
                                <img style="margin-right:10px" width="100px" src="{{ URL::asset('images/1configure.png') }}">
                                <img width="100px" src="{{ URL::asset('images/2download.png') }}">&nbsp;
                                <img width="100px" src="{{ URL::asset('images/3ok.png') }}">
                            </blockquote>
                            </div>
                        </div>
                        <!-- end of user-activity-graph -->


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection