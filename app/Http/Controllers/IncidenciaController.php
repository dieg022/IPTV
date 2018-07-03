<?php

namespace App\Http\Controllers;

use App\Incidencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Iptv;
use Session;

class IncidenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //obtenemos todos paquetes de canales de ese usuario
        if(Auth::user()->name!='almacen')
            $objs = Auth::user()->incidencias;  ///ME KEDO POR AKI, LISTAR LAS INCIDENCIAS DE LOS IPTVS DE LOS CLIENTES DE ESTE USUARIO
        else
            $objs = Incidencia::all();
        //los pasamos a la vista
        return View('incidencia/index')->with('objs',$objs);


    }

    public function rmaAlmacen()
    {
        //obtenemos todos paquetes de canales de ese usuario
        $objs = Incidencia::all();
        //los pasamos a la vista
        return View('incidencia/indexalmacen')->with('objs',$objs);


    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PaqueteCanal  $paqueteCanal
     * @return \Illuminate\Http\Response
     */
    public function show(PaqueteCanal $paqueteCanal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PaqueteCanal  $paqueteCanal
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //obtengo el usuario en cuestion
        $incidencia = Incidencia::find($id);

        //borramos los datos anteriores de sesiones
        Session::forget('errors');
        Session::forget('message');

        return View('incidencia/edit')->with('incidencia',$incidencia);


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PaqueteCanal  $paqueteCanal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $error=false;
        try{

            $incidencia=Incidencia::findOrFail($id);

            $incidencia->estado=$request->estado;
            $incidencia->resolucion=$request->resolucion;
            $incidencia->estado=$request->estado;

            if($request->estado=="CERRADA")
                $incidencia->fecha_cierre_incidencia=date("Y-m-d H:i:s");

            $incidencia->save();


        }
        catch (QueryException $e) {
            $error=true;
            //$error_code = $e->errorInfo[2];
            $msgError="QEx:Error actualizando incidencia: ".$e->getMessage();
            dd($e);
        }
        catch (PDOException $e) {
            $error=true;
            $msgError="PDOEx:Error actualizando incidencia: ".$e->getMessage();
        }
        catch (\Exception $e)
        {
            $error=true;
            $msgError="[Ex] Error actualizando incidencia: ".$e->getMessage();
        }

        //obtenemos todos paquetes de canales de ese usuario
        $objs = Incidencia::all();


        if(!$error) {
            Session::flash('message', 'Incidencia actualizada con éxito');
            Session::forget('errors');
            //vamos a la vista
            return View('incidencia/indexalmacen')->with('objs',$objs);
        }
        else{
            Session::flash('errors',$msgError);
            //los pasamos a la vista

            //borramos los datos anteriores de sesiones
            Session::forget('message');

            return View('incidencia/indexalmacen')->with('objs',$objs);

        }



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PaqueteCanal  $paqueteCanal
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //borro el cliente con dicho id
        PaqueteCanal::destroy($id);

        //retorno la vista
        //obtenemos todos paquetes de canales de ese usuario
        $objs = Auth::user()->paquetescanales;

        Session::flash('message', 'Paquete canal eliminado con éxito');
        Session::forget('errors');

        return View('/paquetecanal/index')->with('objs', $objs);
    }
}
