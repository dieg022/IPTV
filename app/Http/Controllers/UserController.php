<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Hash;
use Illuminate\Support\Facades\Redirect;
use Session;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Input;
use DB;
use App\Canal;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //obtenemos todos los usuarios de la BD
        $users= User::all();

        //los pasamos a la vista
        return View('user/index')->with('users',$users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View('user/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       /* $user = new User();
        $user->name = $request->usuario;
        $user->password = $request->password;
        $user->email= $request->email;
        $user->tipocliente= $request->tipocliente;
        $user->nombrecompleto= $request->nombrecompleto;

        $user->save();*/


        $error=false;
        try{
            $request->merge(['password' => Hash::make($request->password)]);

            $user = User::create($request->all());
        }
        catch (QueryException $e) {
            $error=true;
            //$error_code = $e->errorInfo[2];
            $msgError="QEx:Error creando usuario: ".$e->getMessage();
            dd($e);
        }
        catch (PDOException $e) {
            $error=true;
            $msgError="PDOEx:Error creando usuario: ".$e->getMessage();
        }
        catch (\Exception $e)
        {
            $error=true;
            $msgError="[Ex] Error creando usuario: ".$e->getMessage();
        }

        //obtenemos todos los usuarios
        $users = User::all();

        //borramos los datos anteriores de sesiones
       ///////////////////// Session::flush();

        if(!$error) {
            Session::flash('message', 'Usuario añadido con éxito');
            Session::forget('errors');
            //vamos a la vista
            return View('user/index')->with('users', $users);
        }
        else{
            Session::flash('errors',$msgError);
            //vamos a la vista de creacion con el usuario... no se...
            //Input::flash();  //ESTABLECE VARIABLE DE SESIÓN
            //return View('user/create')->withInput($request);
            $request->flash();
            return View('user/create')->withInput($request);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return "show";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //obtengo el usuario en cuestion
        $user = User::find($id);

        //borramos los datos anteriores de sesiones
        //ESTA PUTA COMANDA ME JODIDA EL TOKEN!!! DEL FORM CON TO SUS MUERTO
        //Session::flush();
        Session::forget('errors');
        Session::forget('message');

        return View('user/edit')->with('user',$user);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $error=false;
        //actualizo, puede lanzar excepcion si he puesto un mail o username repetidos
        try {

            $user = User::findOrFail($id);
            $user->nombre_completo = $request->nombre_completo;
            $user->tipocliente = $request->tipocliente;
            $user->cif = $request->cif;
            $user->direccion = $request->direccion;
            $user->poblacion = $request->poblacion;
            $user->ipwowzalocal = $request->ipwowzalocal;
            //$user->preciopoblacion = $request->preciopoblacion;


            if($request->name!=$user->name)
                $user->setName($request->name);

            if($request->email!=$user->email)
            $user->setEmail($request->email);


            if ($request->password != "*****") {
                //el pass ha sido cambiado
                $request->password = Hash::make($request->password);
                $user->password=$request->password;
            }

            $user->save();


        }
        catch (QueryException $e) {
            $error=true;
            //$error_code = $e->errorInfo[2];
            $msgError="QEx:Error actualizando usuario: ".$e->getMessage();
            dd($e);
        }
        catch (PDOException $e) {
            $error=true;
            $msgError="PDOEx:Error actualizando usuario: ".$e->getMessage();
        }
        catch (\Exception $e)
        {
            $error=true;
            $msgError="[Ex] Error actualizando usuario: ".$e->getMessage();
        }

        //obtenemos todos los usuarios
        $users = User::all();
        if(!$error) {
            Session::flash('message', 'Usuario actualizado con éxito');
            Session::forget('errors');
            //vamos a la vista
            //return View('user/index')->with('users', $users);
            return Redirect('/user')->with('users', $users);
        }
        else{
            Session::forget('message');
            Session::flash('errors',$msgError);
            //vamos a la vista de creacion con el usuario... no se...
            //Input::flash();  //ESTABLECE VARIABLE DE SESIÓN
            return View('user/create')->withInput($request);
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //pongo los iptvs de este usuario a stock y luego borro esa relacion => se quedan en el stock general de superadmin
        $iptvs = User::find($id)->iptvs;

        foreach($iptvs as $iptv){
            $iptv->estado="STOCK";
            $iptv->save();
        }

        //borro el cliente con dicho id, con delete cascade caerá todo lo demas
        User::destroy($id);

        //retorno la vista
        //obtenemos todos perfiles ese usuario

        Session::flash('message', "Usuario eliminado con éxito, tambien se elimino toda su informacion relacionada: clientes,perfiles,paquetes,etc...<br>
Sus iptvs han pasado a stock general sin asignar a nadie");
        Session::forget('errors');

        //obtenemos todos los usuarios de la BD
        $users= User::all();
        //los pasamos a la vista
        return View('user/index')->with('users',$users);
    }


    public function canalesAsociados($id){

        $canalesCliente = User::find($id)->canals()->get();

        //obtengo una coleccion de objetos que son los canales que existen en plataforma y que no tiene este usuario
        $result=DB::select('select * from canales where id not in( select canal_id from canales_users where user_id=?)',[$id]);
        $canalesNoCliente = Canal::hydrate($result);

        //dd($canalesCliente);
        //dd($canalesNoCliente);

        return View('user/canales')->with('canalesCliente',$canalesCliente)->with('canalesNoCliente',$canalesNoCliente)->with('user',User::find($id));

    }


    public function canalesAsociadosSet(Request $request,$id)
    {
        $error=false;
        try{

            $user=User::find($id);

            //borro todos los canales de ese user
            $user->canals()->detach();

            //ahora le asocio los canales que vienen:
            $parametros = $request->All();
            foreach($parametros as $param => $valor)
            {
                //si es un numero => es de los canales seleccionados
                if(is_numeric($param))
                {
                    //lo meto en la relacion
                    $user->canals()->attach($id,['canal_id' => $param]);
                }

            }

        }
        catch (QueryException $e) {
            $error=true;
            //$error_code = $e->errorInfo[2];
            $msgError="QEx:Error actualizando canales: ".$e->getMessage();
            dd($e);
        }
        catch (PDOException $e) {
            $error=true;
            $msgError="PDOEx:Error actualizando canales: ".$e->getMessage();
        }
        catch (\Exception $e)
        {
            $error=true;
            $msgError="[Ex] Error actualizando canales: ".$e->getMessage();
        }

        //obtenemos todos los usuarios de la BD
        $users= User::all();



        if(!$error) {
            Session::flash('message', 'Canales actualizados con éxito');
            Session::forget('errors');

            //los pasamos a la vista
            return View('user/index')->with('users',$users);
        }
        else{
            Session::flash('errors',$msgError);
            Session::forget('message');
            //los pasamos a la vista
            return View('user/index')->with('users',$users);

        }






    }




}
