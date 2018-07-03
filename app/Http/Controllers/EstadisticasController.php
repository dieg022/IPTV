<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;

class EstadisticasController extends Controller
{
    public function reventaPaquetes(){



        if(Auth::user()->tipocliente!="PRODUCTOR")
            abort(301, '', ['Location' => url('main')]);

        //obtenemos todos canales de este usuario productor
        $canales= Auth::user()->canals;




        //los pasamos a la vista
        return View('estadisticas/paquetesporrevendedor')->with('canales',$canales)->with('user',Auth::user());


    /*    foreach ($canales as $canal) {

            echo $canal->id . "---" . $canal->canal . "<br>";

            $unidadesReventa = DB::select(DB::raw("select canales.canal,count(*) as unidades, users.nombre_completo as reventa from users,clientes,clientes_iptvs,canales
        where
        canales.id =:iddos
        and
        clientes.user_id=users.id and clientes.id=clientes_iptvs.cliente_id and paquetecanal_id
         in
           (select id from paquetescanales where id in
             ( select paquetecanal_id from canales_paquetes where canal_id=:id

             )
           ) group by users.nombre_completo, canales.canal;"), array(
                'iddos' => $canal->id, 'id' => $canal->id,
            ));

           // dd($unidadesReventa);

            foreach ($unidadesReventa as $unidades)
            {
                   // dd($unidades);
                echo $unidades->unidades . "<br>";
                echo $unidades->reventa . "<br><br><br>";
            }
        }*/


        //hay que buscar todos los iptvs que tiene un paquete que tienen dicho canal por cada reventa

        /*

    select users.nombre_completo,count(*) as unidades,canales.canal from users,clientes,clientes_iptvs,canales
        where
        canales.id in
                    (select canal_id from canales_users where user_id = 7)
        and
        clientes.user_id=users.id and clientes.id=clientes_iptvs.cliente_id and paquetecanal_id
         in
           (select id from paquetescanales where id in
             ( select paquetecanal_id from canales_paquetes where canal_id in
                ( select id from canales where id in
                    (select canal_id from canales_users where user_id = 7)
                )
             )
           ) group by users.nombre_completo, canales.canal;




        //lo hago con un foreach por cada canal del productor: tve=id2 , sexta id=3


    select canales.canal,count(*) as unidades, users.nombre_completo as reventa from users,clientes,clientes_iptvs,canales
        where
        canales.id =2
        and
        clientes.user_id=users.id and clientes.id=clientes_iptvs.cliente_id and paquetecanal_id
         in
           (select id from paquetescanales where id in
             ( select paquetecanal_id from canales_paquetes where canal_id=2

             )
           ) group by users.nombre_completo, canales.canal;


*/



    }


    /**
     * @return $this invoca la vista del historico de facturacion
     */
    public function reventaPaquetesHistorico()
    {

        if (Auth::user()->tipocliente != "PRODUCTOR")
            abort(301, '', ['Location' => url('main')]);

            //tabla historico
            $facturacion=Auth::user()->getFacturacionProductor()->get();

           // dd($facturacion);
          /*  foreach ($facturacion as $fac)
            {

                echo $fac->user_id."--->".$fac->fecha_facturacion."--->".$fac->importe;

            }*/


        //los pasamos a la vista
        return View('estadisticas/historicopaquetesporrevendedor')->with('user', Auth::user())->with('facturacion',$facturacion);

    }

}
