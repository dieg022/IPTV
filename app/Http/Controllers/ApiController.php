<?php

namespace App\Http\Controllers;

use App\Iptv;
use App\User;
use App\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Illuminate\Support\Facades\Auth;
use App\Cliente;

class ApiController extends Controller
{

    /******************************************************** IPTVS *************************************************/

    /**
     * Devolver iptvs de un usuario segun su estado
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Support\Collection|mixed|static
     */
    public function iptvs(Request $request)
    {
        $estado=$request->tipo;
        $id=$request->idusuario;

        //obtenemos el usuario
        $user=\App\User::find($id);

        //dependiendo del tipo devolvemos unos u otros
        if($estado=="stock")
        {
            $iptvs= $user->iptvsStock();
        }
        else if($estado=="instalados")
        {
            $iptvs= $user->iptvsInstalados();
        }
        else if($estado=="retirados")
        {
            $iptvs= $user->iptvsRetirados();
        }
        else if($estado=="rma")
        {
            $iptvs= $user->iptvsRMA();
        }
        else
            return response()->json(['error' =>'Bad Request', 'code' => 404],404);


       return  $iptvs;
    }


    /** Crear un IPTV */
    public function iptvCreate(Request $request)
    {
        $iptv = new Iptv();
        $iptv->setMac($request->mac);
        $iptv->fechacompra = $request->fechacompra;
        $iptv->tipo = $request->tipo;
        $iptv->proveedor_id = $request->proveedor_id;
        $iptv->numeroserie = $request->numeroserie;
        $iptv->estado = "STOCK";
        $iptv->user_id = $request->usuario_id;

        return  $iptv;
    }

    /******************************************************** CLIENTES *************************************************/

    /**
     * Devuelve listado de clientes para un usuario
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Support\Collection|mixed|static
     */
    public function clientes(Request $request)
    {
        $id=$request->usuario;
        //obtenemos el usuario
        $user=\App\User::find($id);

        return  $user->clientes();
    }

}
