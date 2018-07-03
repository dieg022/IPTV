<?php

namespace App\Http\Controllers;

use App\SwitchPoe;
use Illuminate\Http\Request;
use Auth;
use Session;

class SwitchPoeController extends Controller
{

    /**
     * @param $id compruebo que ese id es deeste tio, sino.. al index de este recurso
     */
    private function comprobarPropietarioRecurso($id){

        if(!Auth::user()->switchpoes->contains('id',$id)) {
            abort(301, '', ['Location' => url('switchpoe')]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $swis= Auth::user()->switchpoes;

        return View('/switchpoe/index')->with('objs', $swis);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View('switchpoe/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $error=false;
        try{
            $poe= new SwitchPoe();
            $poe->nombre= $request->nombre;
            $poe->ubicacion=$request->ubicacion;
            $poe->user_acceso=$request->user_acceso;
            $poe->pass_acceso=$request->pass_acceso;
            $poe->direccionip=$request->direccionip;

            //comprobamos que no hay nombre repetido o una ip repetida
            $userID=Auth::user()->id;

            if(SwitchPoe::where('nombre', '=', $request->nombre)->where('user_id', '=', $userID)->first())
                throw new \Exception("Ya existe un switch con dicho nombre: $request->nombre");

            if(SwitchPoe::where('direccionip', '=', $request->direccionip)->where('user_id', '=', $userID)->first())
                throw new \Exception("Ya existe un switch con dicha dirección ip: $request->direccionip");

            Auth::user()->switchpoes()->save($poe);

        }
        catch (QueryException $e) {
            $error=true;
            //$error_code = $e->errorInfo[2];
            $msgError="QEx:Error creando switchpoe: ".$e->getMessage();
            dd($e);
        }
        catch (PDOException $e) {
            $error=true;
            $msgError="PDOEx:Error creando switchpoe: ".$e->getMessage();
        }
        catch (\Exception $e)
        {
            $error=true;
            $msgError="[Ex] Error creando switchpoe: ".$e->getMessage();
        }

        //obtenemos todos los perfiles de este usuario
        $swis=Auth::user()->switchpoes;

        //borramos los datos anteriores de sesiones
        ///////////////////// Session::flush();

        if(!$error) {
            Session::flash('message', 'SwitchPoE añadido con éxito');
            Session::forget('errors');
            //vamos a la vista
            return View('switchpoe/index')->with('objs', $swis);
        }
        else{
            Session::flash('errors',$msgError);

            $request->flash();
            return View('switchpoe/create');
        }



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SwitchPoe  $switchPoe
     * @return \Illuminate\Http\Response
     */
    public function show(SwitchPoe $switchPoe)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SwitchPoe  $switchPoe
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //comprobamos el propietario
        $this->comprobarPropietarioRecurso($id);

        $poe=SwitchPoe::findOrFail($id);

        return View('switchpoe/edit')->with('switchpoe',$poe);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SwitchPoe  $switchPoe
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $error=false;
        try{

            $switch= SwitchPoe::findOrFail($id);
            $switch->nombre= $request->nombre;
            $switch->ubicacion=$request->ubicacion;
            $switch->user_acceso=$request->user_acceso;
            $switch->direccionip=$request->direccionip;

            if ($request->pass_acceso != "*****") {
                //el pass ha sido cambiado
                $switch->pass_acceso = $request->pass_acceso;
            }

            //comprobamos que no hay nombre repetido o una ip repetida
            $userID=Auth::user()->id;

            if(SwitchPoe::where('nombre', '=', $request->nombre)->where('user_id', '=', $userID)->first())
                throw new \Exception("Ya existe un switch con dicho nombre: $request->nombre");

            if(SwitchPoe::where('direccionip', '=', $request->direccionip)->where('user_id', '=', $userID)->first())
                throw new \Exception("Ya existe un switch con dicha dirección ip: $request->direccionip");

            Auth::user()->switchpoes()->save($switch);


        }
        catch (QueryException $e) {
            $error=true;
            //$error_code = $e->errorInfo[2];
            $msgError="QEx:Error creando switchpoe: ".$e->getMessage();
            dd($e);
        }
        catch (PDOException $e) {
            $error=true;
            $msgError="PDOEx:Error creando switchpoe: ".$e->getMessage();
        }
        catch (\Exception $e)
        {
            $error=true;
            $msgError="[Ex] Error creando switchpoe: ".$e->getMessage();
        }

        //obtenemos todos los perfiles de este usuario
        $swis=Auth::user()->switchpoes;



        if(!$error) {
            Session::flash('message', 'SwitchPOE modificado con éxito');
            //Session::forget('errors');
            //vamos a la vista
            return View('switchpoe/index')->with('objs', $swis);
        }
        else{
            Session::flash('errors',$msgError);
            //vamos a la vista de creacion con el usuario... no se...
            //Input::flash();  //ESTABLECE VARIABLE DE SESIÓN
            $request->flash();
            return View('switchpoe/edit')->with('switchpoe',$switch);
        }



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SwitchPoe  $switchPoe
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //borro el cliente con dicho id
        SwitchPoe::destroy($id);

        //retorno la vista
        //obtenemos todos perfiles ese usuario
        $objs = Auth::user()->switchpoes;

        Session::flash('message', 'SwitchPoe eliminado con éxito');
        Session::forget('errors');

        return View('switchpoe/index')->with('objs', $objs);
    }
}
