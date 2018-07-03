<?php

namespace App\Http\Controllers;

use App\Proveedor;
use Illuminate\Http\Request;
use Session;
use Auth;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //obtenemos todos los clientes de ese usuario
        $proveedores= Proveedor::all();

        //$clientes = Cliente::all();

        //los pasamos a la vista
        return View('proveedor/index')->with('proveedores',$proveedores);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View('proveedor/create');
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

           /*
            Proveedor::create($request->all());



            $userID=Auth::user()->id;
            //comprobamos que no haya ninguno con ese
            if(Proveedor::where('proveedor', '=', $request->nombrepaquete)->where('user_id', '=', $userID)->first())
                throw new \Exception("Ya existe un proveedor con dicho nombre: $request->nombrepaquete");
*/

            $proveedor = new Proveedor();
            $proveedor->proveedor = $request->proveedor;
            $proveedor->telefono=$request->telefono;
            $proveedor->email=$request->email;

            $userID=Auth::user()->id;

            //comprobamos que no haya ninguno con ese
            if(Proveedor::where('proveedor', '=', $request->proveedor)->first())
                throw new \Exception("Ya existe un proveedor con dicho nombre: $request->proveedor");



            $proveedor->save();



        }
        catch (QueryException $e) {
            $error=true;
            //$error_code = $e->errorInfo[2];
            $msgError="QEx:Error creando proveedor: ".$e->getMessage();
            dd($e);
        }
        catch (PDOException $e) {
            $error=true;
            $msgError="PDOEx:Error creando proveedor: ".$e->getMessage();
        }
        catch (\Exception $e)
        {
            $error=true;
            $msgError="[Ex] Error creando proveedor: ".$e->getMessage();
        }

        //obtenemos todos los clientes de este usuario

        $proveedores= Proveedor::all();


        if(!$error) {
            Session::flash('message', 'Proveedor añadido con éxito');
            Session::forget('errors');
            //vamos a la vista
            return View('proveedor/index')->with('proveedores', $proveedores);
        }
        else{
            Session::flash('errors',$msgError);
            //vamos a la vista de creacion con el usuario... no se...
            //Input::flash();  //ESTABLECE VARIABLE DE SESIÓN
            $request->flash();
            return View('proveedor/create');
        }



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function show(Proveedor $proveedor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //obtengo el proveedor en cuestion
        $proveedor = Proveedor::find($id);

        Session::forget('errors');
        Session::forget('message');

        return View('proveedor/edit')->with('proveedor',$proveedor);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $error=false;
        //actualizo, puede lanzar excepcion si he puesto un mail o clientename repetidos
        try {

            $proveedor = Proveedor::findOrFail($id);

            $userID=Auth::user()->id;

            //comprobamos que no haya ninguno con ese
            if(Proveedor::where('proveedor', '=', $request->proveedor)->first())
                throw new \Exception("Ya existe un proveedor con dicho nombre: $request->proveedor");


            $proveedor->proveedor = $request->proveedor;
            $proveedor->telefono=$request->telefono;
            $proveedor->email=$request->email;
            $proveedor->save();



        }
        catch (QueryException $e) {
            $error=true;
            //$error_code = $e->errorInfo[2];
            $msgError="QEx:Error actualizando proveedor: ".$e->getMessage();
            dd($e);
        }
        catch (PDOException $e) {
            $error=true;
            $msgError="PDOEx:Error actualizando proveedor: ".$e->getMessage();
        }
        catch (\Exception $e)
        {
            $error=true;
            $msgError="[Ex] Error actualizando proveedor: ".$e->getMessage();
        }

        //obtenemos todos los clientes de dicho usuario
        $proveedores= Proveedor::all();

        if(!$error) {
            Session::flash('message', 'Proveedor actualizado con éxito');
            Session::forget('errors');
            //vamos a la vista
            return View('/proveedor/index')->with('proveedores', $proveedores);
        }
        else{
            Session::forget('message');
            Session::flash('errors',$msgError);
            //vamos a la vista de creacion con el usuario... no se...
            //Input::flash();  //ESTABLECE VARIABLE DE SESIÓN

            return View('/proveedor/'.$request->id.'/edit')->withInput($request)->with('proveedor',$proveedor);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Proveedor $proveedor)
    {
        //
    }
}
