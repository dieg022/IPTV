<?php

namespace App\Http\Controllers;

use App\Background;
use App\PaqueteCanal;
use App\PerfilInstalacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PerfilInstalacionController extends Controller
{

    /**
     * @param $id compruebo que ese id es deeste tio, sino.. al index de este recurso
     */
    private function comprobarPropietarioRecurso($id){

        if(!Auth::user()->perfilesinstalacion->contains('id',$id)) {
            abort(301, '', ['Location' => url('perfilinstalacion')]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //obtenemos todos los clientes de este usuario
        $perfiles=Auth::user()->perfilesinstalacion;

        Return View('perfilinstalacion/index')->with('perfilesinstalacion',$perfiles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //para los desplegables necesito:
        $backgrounds = Auth::user()->backgrounds;
        $paquetescanales= Auth::user()->paquetescanales;

        return View('perfilinstalacion/create')->with('backgrounds',$backgrounds)->with('paquetescanales',$paquetescanales);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);

        $error=false;
        try{

            $perfil= new PerfilInstalacion();
            $perfil->nombre= $request->nombre;
            $perfil->paquetecanal_id=$request->paquetecanal_id;
            //$perfil->paquetecanal_id=1;
            $perfil->background_id=$request->background_id;

            if($request->wifienabled==false){
                $perfil->wifienabled=0;
            }
            else {
                $perfil->wifienabled = 1;
                $perfil->ssid = $request->ssid;
                $perfil->password = $request->password;
                $perfil->wificanal = $request->wificanal;
            }

            $userID=Auth::user()->id;

            if(PerfilInstalacion::where('nombre', '=', $request->nombre)->where('user_id', '=', $userID)->first())
                throw new \Exception("Ya existe un perfil con dicho nombre: $request->nombre");
            else
                Auth::user()->perfilesinstalacion()->save($perfil);

        }
        catch (QueryException $e) {
            $error=true;
            //$error_code = $e->errorInfo[2];
            $msgError="QEx:Error creando perfil de instalacion: ".$e->getMessage();
            dd($e);
        }
        catch (PDOException $e) {
            $error=true;
            $msgError="PDOEx:Error creando perfil de instalacion: ".$e->getMessage();
        }
        catch (\Exception $e)
        {
            $error=true;
            $msgError="[Ex] Error creando perfil de instalacion: ".$e->getMessage();
        }

        //obtenemos todos los perfiles de este usuario
        $pf=Auth::user()->perfilesinstalacion;

        //borramos los datos anteriores de sesiones
        ///////////////////// Session::flush();

        if(!$error) {
            Session::flash('message', 'Perfil instalación añadido con éxito');
            //Session::forget('errors');
            //vamos a la vista
            return View('perfilinstalacion/index')->with('perfilesinstalacion', $pf);
        }
        else{
            Session::flash('errors',$msgError);
            //vamos a la vista de creacion con el usuario... no se...
            //Input::flash();  //ESTABLECE VARIABLE DE SESIÓN

            $backgrounds = Auth::user()->backgrounds;
            $paquetescanales= Auth::user()->paquetescanales;

            $request->flash();
            return View('perfilinstalacion/create')->with('backgrounds',$backgrounds)->with('paquetescanales',$paquetescanales);
        }



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PerfilInstalacion  $perfilInstalacion
     * @return \Illuminate\Http\Response
     */
    public function show(PerfilInstalacion $perfilInstalacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PerfilInstalacion  $perfilInstalacion
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //compruebo el propietario
        $this->comprobarPropietarioRecurso($id);

        $perfil = PerfilInstalacion::findOrFail($id);

        //para los desplegables necesito:
        $backgrounds = Auth::user()->backgrounds;
        $paquetescanales= Auth::user()->paquetescanales;


        Session::forget('errors');
        Session::forget('message');

        return View('perfilinstalacion/edit')->with('perfilinstalacion',$perfil)->with('backgrounds',$backgrounds)->with('paquetescanales',$paquetescanales);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PerfilInstalacion  $perfilInstalacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //compruebo el propietario
        $this->comprobarPropietarioRecurso($id);

        $error=false;
        try{

            $perfil= PerfilInstalacion::findOrFail($id);

            $userID=Auth::user()->id;

            //si el nombre que viene es distinto del que exisitia y ese que han puesto nuevo ya existe => error
            if($perfil->nombre!=$request->nombre && PerfilInstalacion::where('nombre', '=', $request->nombre)->where('user_id', '=', $userID)->first())
                throw new \Exception("Ya existe un perfil con dicho nombre: $request->nombre");
            else
                Auth::user()->perfilesinstalacion()->save($perfil);



            $perfil->nombre= $request->nombre;
             $perfil->paquetecanal_id=$request->paquetecanal_id;
            //$perfil->paquetecanal_id=1;
            $perfil->background_id=$request->background_id;

            if($request->wifienabled==false){
                $perfil->wifienabled=0;

            }
            else {
                $perfil->wifienabled = 1;
                $perfil->ssid = $request->ssid;
                $perfil->password = $request->password;
                $perfil->wificanal = $request->wificanal;
            }

            $perfil->save();


        }
        catch (QueryException $e) {
            $error=true;
            //$error_code = $e->errorInfo[2];
            $msgError="QEx:Error actualizando perfil: ".$e->getMessage();
            dd($e);
        }
        catch (PDOException $e) {
            $error=true;
            $msgError="PDOEx:Error actualizando perfil: ".$e->getMessage();
        }
        catch (\Exception $e)
        {
            $error=true;
            $msgError="[Ex] Error actualizando perfil: ".$e->getMessage();
        }

        //obtenemos todos los perfiles de este usuario
        $pf=Auth::user()->perfilesinstalacion;

        //borramos los datos anteriores de sesiones
        ///////////////////// Session::flush();

        if(!$error) {
            Session::flash('message', 'Perfil instalación modificado con éxito');
            //Session::forget('errors');
            //vamos a la vista
            return View('perfilinstalacion/index')->with('perfilesinstalacion', $pf);
        }
        else{
            Session::flash('errors',$msgError);
            //vamos a la vista de creacion con el usuario... no se...
            //Input::flash();  //ESTABLECE VARIABLE DE SESIÓN

            $backgrounds = Auth::user()->backgrounds;
            $paquetescanales= Auth::user()->paquetescanales;

            $request->flash();
            return View('perfilinstalacion/edit')->with('backgrounds',$backgrounds)->with('paquetescanales',$paquetescanales)->with('perfilinstalacion',$perfil);
        }




    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PerfilInstalacion  $perfilInstalacion
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //borro el cliente con dicho id
        PerfilInstalacion::destroy($id);

        //retorno la vista
        //obtenemos todos perfiles ese usuario
        $objs = Auth::user()->perfilesinstalacion;

        Session::flash('message', 'Perfil instalación eliminado con éxito');
        Session::forget('errors');

        return View('perfilinstalacion/index')->with('perfilesinstalacion', $objs);
    }
}
