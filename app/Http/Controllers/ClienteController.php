<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Incidencia;
use App\Iptv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Redirect;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @param $id compruebo que ese id es deeste tio, sino.. al index de este recurso
     */
    private function comprobarPropietarioRecurso($id){

        if(!Auth::user()->clientes->contains('id',$id)) {
            abort(301, '', ['Location' => url('cliente')]);
        }
    }

    /**
     * @param $id compruebo que ese iptv es de este usuario, es una copia de la que esta en IPTV
     */
    private function comprobarPropietarioRecursoIPTV($id){

        if(!Auth::user()->iptvs->contains('id',$id)) {
            abort(301, '', ['Location' => url('cliente')]);
        }
    }

    public function index()
    {
        //obtenemos todos los clientes de ese usuario
       //$clientes= Cliente::where('user_id',Auth::user()->id)->get();

        $clientes = Auth::user()->clientes;

        //dd($clientes);
       //$clientes = Cliente::all();

        //los pasamos a la vista
        return View('cliente/index')->with('clientes',$clientes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->tipocliente =='HOTEL')
            return View('cliente/createhabitacion');
        else
            return View('cliente/create');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createHabitacion()
    {
        return View('cliente/createhabitacion');
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
            $request->request->add(['user_id' => Auth::user()->id]);
            //Cliente::create($request->all());
            $cliente = new Cliente();

            $cliente->user_id=Auth::user()->id;

            $cliente->cif=$request->cif;
            if( Auth::user()->tipocliente=="HOTEL")
            {
                $cliente->direccion=$request->cif;
                $cliente->poblacion=$request->cif;
                $cliente->nombrecompleto=$request->cif;
            }
            else
            {
                $cliente->direccion=$request->direccion;
                $cliente->poblacion=$request->poblacion;
                $cliente->nombrecompleto=$request->nombrecompleto;

            }


            $userID=Auth::user()->id;

             if(Cliente::where('cif', '=', $request->cif)->where('user_id', '=', $userID)->first())
             {
                 if( Auth::user()->tipocliente=="HOTEL")
                    throw new \Exception("Ya existe una habitación con dicho número: $request->cif");
                 else
                    throw new \Exception("Ya existe un cliente con dicho CIF: $request->cif");

             }
            $cliente->save();

        }
        catch (QueryException $e) {
            $error=true;
            //$error_code = $e->errorInfo[2];
            $msgError="QEx:Error creando cliente: ".$e->getMessage();
            dd($e);
        }
        catch (PDOException $e) {
            $error=true;
            $msgError="PDOEx:Error creando cliente: ".$e->getMessage();
        }
        catch (\Exception $e)
        {
            $error=true;
            $msgError="[Ex] Error creando cliente: ".$e->getMessage();
        }

        //obtenemos todos los clientes de este usuario
        $clientes= Cliente::where('user_id',Auth::user()->id)->get();

        //borramos los datos anteriores de sesiones
        ///////////////////// Session::flush();

        if(!$error) {
            if( Auth::user()->tipocliente=="HOTEL")
                Session::flash('message', 'Habitación añadida con éxito');
            else
                Session::flash('message', 'Cliente añadido con éxito');
            //Session::forget('errors');
            //vamos a la vista
            return View('cliente/index')->with('clientes', $clientes);
        }
        else{
            Session::flash('errors',$msgError);
            //vamos a la vista de creacion con el usuario... no se...
            //Input::flash();  //ESTABLECE VARIABLE DE SESIÓN
            $request->flash();
            if( Auth::user()->tipocliente=="HOTEL")
                return View('cliente/createhabitacion');
            else
                return View('cliente/create');
        }




    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //compruebo el propietario
        $this->comprobarPropietarioRecurso($id);

        //obtengo el usuario en cuestion
        $cliente = Cliente::findOrFail($id);

        //borramos los datos anteriores de sesiones
        //ESTA PUTA COMANDA ME JODIDA EL TOKEN!!! DEL FORM CON TO SUS MUERTO
        //Session::flush();
        Session::forget('errors');
        Session::forget('message');

        //return Auth::user()->tipocliente;

        if(Auth::user()->tipocliente =='HOTEL')
            return View('cliente/edithabitacion')->with('cliente',$cliente);
        else
            return View('cliente/edit')->with('cliente',$cliente);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //compruebo el propietario
        $this->comprobarPropietarioRecurso($id);

        $error=false;
        //actualizo, puede lanzar excepcion si he puesto un mail o clientename repetidos
        try {

            $cliente = Cliente::findOrFail($id);

            //si es un hotel la logica cambia
            if (Auth::user()->tipocliente == "HOTEL") {
                $cliente->cif = $request->cif;
                $cliente->nombrecompleto = $request->cif;
                $cliente->direccion = $request->cif;
                $cliente->poblacion = $request->cif;

                $userID = Auth::user()->id;

                if ($cliente->cif != $request->cif && Cliente::where('cif', '=', $request->cif)->where('user_id', '=', $userID)->first())
                    throw new \Exception("Ya existe una habitacion con dicho numero: $request->cif");

            } else {
                $cliente->nombrecompleto = $request->nombrecompleto;
                $cliente->cif = $request->cif;
                $cliente->direccion = $request->direccion;
                $cliente->poblacion = $request->poblacion;

                $userID = Auth::user()->id;
                if ($cliente->cif != $request->cif && Cliente::where('cif', '=', $request->cif)->where('user_id', '=', $userID)->first())
                    throw new \Exception("Ya existe un cliente con dicho CIF: $request->cif");

            }
            $cliente->save();


        }
        catch (QueryException $e) {
            $error=true;
            //$error_code = $e->errorInfo[2];
            $msgError="QEx:Error actualizando cliente: ".$e->getMessage();
            dd($e);
        }
        catch (PDOException $e) {
            $error=true;
            $msgError="PDOEx:Error actualizando cliente: ".$e->getMessage();
        }
        catch (\Exception $e)
        {
            $error=true;
            $msgError="[Ex] Error actualizando cliente: ".$e->getMessage();
        }

        //obtenemos todos los clientes de dicho usuario
        //$clientes= Cliente::where('user_id',Auth::user()->id)->get();

        $clientes = Auth::user()->clientes;

        if(!$error) {
            Session::flash('message', 'Actualizado con éxito');
            Session::forget('errors');
            //vamos a la vista
            //return View('user/index')->with('users', $users);
            return Redirect('/cliente')->with('clientes', $clientes);
        }
        else{
            Session::forget('message');
            Session::flash('errors',$msgError);
            //vamos a la vista de creacion con el usuario... no se...
            //Input::flash();  //ESTABLECE VARIABLE DE SESIÓN
            //return View('cliente/create')->withInput($request);
            return View('cliente/'.$request->id.'/edit')->with('cliente',$cliente);
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        //cojo sus iptv si existiesen y los paso a stock
        $iptvs = Cliente::find($id)->iptvs;

        foreach($iptvs as $iptv){
            $iptv->estado="STOCK";
            $iptv->save();
        }

        //borro el cliente con dicho id
        Cliente::destroy($id);

        //retorno la vista
        //obtenemos todos los clientes de dicho usuario
        //$clientes= Cliente::where('user_id',Auth::user()->id)->get();
        $clientes = Auth::user()->clientes;

        Session::flash('message', 'Cliente eliminado con éxito');
        Session::forget('errors');

        return Redirect('/cliente')->with('clientes', $clientes);


    }

    /**
     * @param $id
     */
    public function IndexIptvsAsociados($id){

        //comprobamos el propietario del recurso
        $this->comprobarPropietarioRecurso($id);

        //return "iptvs para el cliente $id";
        $iptvs = Cliente::findOrFail($id)->iptvs;
        $cliente = Cliente::findOrFail($id);
        $user=Auth::user();


        return View('cliente/iptvs')->with('iptvs',$iptvs)->with('usuario',$user)->with('cliente',$cliente);

    }

    /*
     * obtengo los iptvs en stock para ese cliente y lo paso a la vista junto con los parametros seleccionables
     */
    public function CreateIptvAsociado($idCliente){

        //comprobamos el propietario del recurso
        $this->comprobarPropietarioRecurso($idCliente);

        $iptvsStock=Auth::user()->iptvsStock();
        $cliente = Cliente::findOrFail($idCliente);


        $perfiles= Auth::user()->perfilesinstalacion;

        $backgrounds = Auth::user()->backgrounds;
        $paquetescanales= Auth::user()->paquetescanales;

        $switchs= Auth::user()->switchpoes;


        return View('cliente/asociar')->with('cliente',$cliente)->with('perfiles',$perfiles)
            ->with('backgrounds',$backgrounds)->with('paquetescanales',$paquetescanales)
            ->with('switchs',$switchs)
            ->with('iptvs',$iptvsStock);

    }


    /**
     * Almaceno una asociacion entre un cliente y un iptv desde cliente->iptv->asociar
     * @param Request $request
     * @return mixed
     */
    public function StoreIptvAsociado(Request $request,$idcliente){

        $iptvSeleccionado = $request->iptv_id;

        $cliente = Cliente::find($idcliente);

        $error=false;
        $ssid="";
        $wificanal="";
        $password="";

        if($request->wifienabled==false){
            $wifienabled=0;
        }
        else {
            $wifienabled = 1;
            $ssid = $request->ssid;
            $password = $request->password;
            $wificanal = $request->wificanal;

        }


        try{

            //si es un cliente => no hay actu de wifi,pk wifi esta apagado
            if (Auth::user()->tipocliente == "HOTEL")
                $wifiact=1;
            else
                $wifiact=0;


            $cliente->iptvs()->attach($idcliente,
                [
                    'iptv_id' => $iptvSeleccionado,
                    'paquetecanal_id' => $request->paquetecanal_id,
                    'background_id' =>  $request->background_id,
                    'wifienabled'  => $wifienabled,
                    'ssid' =>  $ssid,
                    'password' =>  $password,
                    'wificanal' => $wificanal,
                    'ipactual' => "",
                    'flagwifiact' =>  $wifiact,
                    'flagbackgroundact' =>  1,
                    'flagpaquetecanalact' =>  1,
                    'switchpoe_id' => $request->switchpoe_id,
                    'switchpoepuerto' => $request->puerto,
                    'observaciones' => $request->observaciones
                ]);

            //ese iptv pasa a estado INSTALADO
            $iptv = Iptv::find($iptvSeleccionado);
            $iptv->estado='INSTALADO';
            $iptv->save();

        }
        catch (QueryException $e) {
            $error=true;
            //$error_code = $e->errorInfo[2];
            $msgError="QEx:Error asociando iptv: ".$e->getMessage();
            //dd($e);
        }
        catch (PDOException $e) {
            $error=true;
            $msgError="PDOEx:Error asociando iptv: ".$e->getMessage();
        }
        catch (\Exception $e)
        {
            $error=true;
            $msgError="[Ex] Error asociando iptv: ".$e->getMessage();
        }


        if(!$error) {
            Session::flash('message', 'Iptv asociado con éxito');
            Session::forget('errors');
            //vamos a la vista
            return $this->IndexIptvsAsociados($idcliente);
        }
        else{
            Session::flash('errors',$msgError);
            //vamos a la vista
            return $this->IndexIptvsAsociados($idcliente);
        }

    }



    /**
     * Preparo la vista para editar los cambios en unaasociacion iptv para un cliente X
     * @param $idcliente
     * @param $idiptv
     */
    public function EditIptvAsociado($idcliente,$idiptv){
        //return "editando iptv con id=$idiptv para cliente $idcliente";

        //comprobamos el propietario del recurso
        $this->comprobarPropietarioRecurso($idcliente);
        $this->comprobarPropietarioRecursoIPTV($idiptv);


        $cliente = Cliente::find($idcliente);

        foreach($cliente->iptvs as $iptvRelacion) {
            if ($iptvRelacion->pivot->iptv_id == $idiptv) {

                $paquetecanal_id=$iptvRelacion->pivot->paquetecanal_id;
                $background_id=$iptvRelacion->pivot->background_id;
                $wifienabled=$iptvRelacion->pivot->wifienabled;
                $ssid=$iptvRelacion->pivot->ssid;
                $password=$iptvRelacion->pivot->password;
                $wificanal=$iptvRelacion->pivot->wificanal;
                $switchpoe_id=$iptvRelacion->pivot->switchpoe_id;
                $puerto=$iptvRelacion->pivot->switchpoepuerto;
                $obs=$iptvRelacion->pivot->observaciones;

                
                $iptv=Iptv::find($idiptv);

            }
        }

        $backgrounds = Auth::user()->backgrounds;
        $paquetescanales= Auth::user()->paquetescanales;
        $perfiles= Auth::user()->perfilesinstalacion;
        $switchs= Auth::user()->switchpoes;



        return View('cliente/editariptv')->with('paquetecanal_id',$paquetecanal_id)->with('background_id',$background_id)->with('wifienabled',$wifienabled)
            ->with('ssid',$ssid)->with('password',$password)->with('wificanal',$wificanal)->with('backgrounds',$backgrounds)->with('paquetescanales',$paquetescanales)->with('perfiles',$perfiles)
            ->with('iptv',$iptv)
            ->with('cliente',$cliente)
            ->with('switchs',$switchs)
            ->with('switchpoe_id',$switchpoe_id)
            ->with('puerto',$puerto)
            ->with('observaciones',$obs);

    }


    /**
     * Editamos una asociacion de un cliente y un iptv, desde el menu clientes->iptvs->editar y guardamos los cambios
     * @param Request $request
     * @return $this
     */
    public function UpdateIptvAsociado(Request $request,$idcliente,$idiptv){

        //echo "asociar el iptv $request->iptv_id con el cliente $request->cliente_id con el perfil $request->perfilinstalacion_id";

        //return "el cliente $idcliente con iptv $idiptv";

        $cliente = Cliente::find($idcliente);

        $error=false;
        $ssid="";
        $wificanal="";
        $password="";

        if($request->wifienabled==false){
            $wifienabled=0;
        }
        else {
            $wifienabled = 1;
            $ssid = $request->ssid;
            $password = $request->password;
            $wificanal = $request->wificanal;
        }


        //seteo de los flags si hay cambio en sus respectivos campos
        $flagwifiact=0;
        $flagbackgroundact=0;
        $flagpaquetecanalact=0;

        if($cliente->iptvs()->find($idiptv)->pivot->ssid != $ssid || $cliente->iptvs()->find($idiptv)->pivot->password != $password
            || $cliente->iptvs()->find($idiptv)->pivot->wificanal != $wificanal || $cliente->iptvs()->find($idiptv)->pivot->wifienabled != $wifienabled)
        {
            $flagwifiact=1;
        }

        if($cliente->iptvs()->find($idiptv)->pivot->background_id != $request->background_id){
            $flagbackgroundact=1;
        }

        if($cliente->iptvs()->find($idiptv)->pivot->paquetecanal_id != $request->paquetecanal_id)
            $flagpaquetecanalact=1;

            try{
            //actualizo en la tabla pivote
            $cliente->iptvs()->updateExistingPivot($idiptv,
                [
                    'paquetecanal_id' => $request->paquetecanal_id,
                    'background_id' =>  $request->background_id,
                    'wifienabled'  => $wifienabled,
                    'ssid' =>  $ssid,
                    'password' =>  $password,
                    'wificanal' => $wificanal,
                    'ipactual' => "",
                    'flagwifiact' =>  $flagwifiact,
                    'flagbackgroundact' =>  $flagbackgroundact,
                    'flagpaquetecanalact' =>  $flagpaquetecanalact,
                    'switchpoe_id' => $request->switchpoe_id,
                    'switchpoepuerto' => $request->puerto,
                    'observaciones' => $request->observaciones
                ]);

        }
        catch (QueryException $e) {
            $error=true;
            //$error_code = $e->errorInfo[2];
            $msgError="QEx:Error modificando iptv: ".$e->getMessage();
            //dd($e);
        }
        catch (PDOException $e) {
            $error=true;
            $msgError="PDOEx:Error modificando iptv: ".$e->getMessage();
        }
        catch (\Exception $e)
        {
            $error=true;
            $msgError="[Ex] Error modificando iptv: ".$e->getMessage();
        }


        if(!$error) {
            Session::flash('message', 'Iptv modificado con éxito');
            Session::forget('errors');
            //vamos a la vista
            return $this->IndexIptvsAsociados($idcliente);
        }
        else{
            Session::flash('errors',$msgError);
            //vamos a la vista
            return $this->IndexIptvsAsociados($idcliente);
        }

    }


    /**
     * Preparo la vista para reemplazar un iptv por otro al pesote y copiar la config
     * @param $idcliente
     * @param $idiptv
     */
    public function ReemplazarIptvAsociado($idcliente,$idiptv){

        $cliente = Cliente::find($idcliente);

        $iptv = Iptv::find($idiptv);

        $iptvsStock=Auth::user()->iptvsStock();

        return View('cliente/reemplazariptv')
            ->with('iptv',$iptv)
            ->with('cliente',$cliente)
            ->with('iptvsStock',$iptvsStock);

    }

    /**
     * Reemplaza un iptv por otro para este cliente
     * @param $idcliente
     * @param $idiptv
     */
    public function UpdateReemplazarIptvAsociado(Request $request,$idcliente,$idiptv){

        //return "reemplaznaro para el clente $idcliente, el iptv $idiptv por $request->iptv_id";

        $error=false;
        try {
            //pongo el iptv trucho en RMA
            $iptv = Iptv::find($idiptv);
            $iptv->estado="RMA";
            $iptv->save();

            //creamos la incidencia de RMA

            $incidencia = new Incidencia();
            $incidencia->fecha_apertura_incidencia = date("Y-m-d H:i:s");
            $incidencia->iptv_id = $idiptv;
            $incidencia->problema = $request->causa;
            $incidencia->tipo="REEMPLAZO";
            $incidencia->estado = "ABIERTA";
            $incidencia->user_id=Auth::user()->id;
            $incidencia->save();


            //ponemos el nuevo iptv en instalador
            $iptv = Iptv::find($request->iptv_id);
            $iptv->estado="INSTALADO";
            $iptv->save();

            //cambiamos en la tabla clientes_iptv por el iptv nuevo y seteamos los flags para que se actualice
            DB::table('clientes_iptvs')
                ->where('iptv_id', $idiptv)
                ->update(['iptv_id' => $request->iptv_id,
                          'flagwifiact' => 1,
                          'flagbackgroundact' => 1,
                          'flagpaquetecanalact' => 1,
                        ]);

        }
        catch (QueryException $e) {
            $error=true;
            //$error_code = $e->errorInfo[2];
            $msgError="QEx:Error reemplazando iptv: ".$e->getMessage();
            //dd($e);
        }
        catch (PDOException $e) {
            $error=true;
            $msgError="PDOEx:Error reemplazando iptv: ".$e->getMessage();
        }
        catch (\Exception $e)
        {
            $error=true;
            $msgError="[Ex] Error reemplazando iptv: ".$e->getMessage();
        }


        if(!$error) {
            Session::flash('message', 'Iptv reemplzado con éxito');
            Session::forget('errors');
            //vamos a la vista
            return $this->IndexIptvsAsociados($idcliente);
        }
        else{
            Session::flash('errors',$msgError);
            //vamos a la vista
            return $this->IndexIptvsAsociados($idcliente);
        }



    }


    /**
     * Preparo la vista para retirar un iptv
     * @param $idiptv
     */
    public function RetirarIptvAsociado($idcliente,$idiptv){

        $cliente = Cliente::find($idcliente);

        $iptv = Iptv::find($idiptv);

        return View('cliente/retirariptv')
            ->with('iptv',$iptv)
            ->with('cliente',$cliente);

    }

    /**
     * Hago efectivo la retirada del iptv
     * @param $idiptv
     * @return string
     */
    public function RetirarIptvAsociadoEfectivo($idcliente,$idiptv){


        $error=false;
        try {

            //update del iptv

            $iptv = Iptv::find($idiptv);
            $iptv->estado="RETIRADO";
            $iptv->save();

            //quitamos el iptvs de instalaciones
            DB::table('clientes_iptvs')
                ->where('iptv_id', $idiptv)
                ->delete();

        }
        catch (QueryException $e) {
            $error=true;
            //$error_code = $e->errorInfo[2];
            $msgError="QEx:Error reemplazando iptv: ".$e->getMessage();
            //dd($e);
        }
        catch (PDOException $e) {
            $error=true;
            $msgError="PDOEx:Error reemplazando iptv: ".$e->getMessage();
        }
        catch (\Exception $e)
        {
            $error=true;
            $msgError="[Ex] Error reemplazando iptv: ".$e->getMessage();
        }


        if(!$error) {
            Session::flash('message', 'Iptv retirado con éxito');
            Session::forget('errors');
            //vamos a la vista
            return $this->IndexIptvsAsociados($idcliente);
        }
        else{
            Session::flash('errors',$msgError);
            //vamos a la vista
            return $this->IndexIptvsAsociados($idcliente);
        }


    }




    /**
     * Preparo la vista para poner por defecto un iptv
     * @param $idiptv
     */
    public function DefaultIptvAsociado($idcliente,$idiptv){

        $cliente = Cliente::find($idcliente);

        $iptv = Iptv::find($idiptv);

        return View('cliente/defaultiptv')
            ->with('iptv',$iptv)
            ->with('cliente',$cliente);

    }

    /**
     * Hago efectivo la puesta a default del iptv
     * @param $idiptv
     * @return string
     */
    public function DefaultIptvAsociadoEfectivo($idcliente,$idiptv){


        $error=false;
        try {

            //update del iptv

            $iptv = Iptv::find($idiptv);
            $iptv->estado="DEFAULT";
            $iptv->save();

            //Asumimos que esta instalado y lo vamos a seguir dejando instalado
            //si no se arregla asi pues ya se reemplaza o  se retira
          /*  DB::table('clientes_iptvs')
                ->where('iptv_id', $idiptv)
                ->delete();*/

        }
        catch (QueryException $e) {
            $error=true;
            //$error_code = $e->errorInfo[2];
            $msgError="QEx:Error poniendo por defecto iptv: ".$e->getMessage();
            //dd($e);
        }
        catch (PDOException $e) {
            $error=true;
            $msgError="PDOEx:Error poniendo por defecto iptv: ".$e->getMessage();
        }
        catch (\Exception $e)
        {
            $error=true;
            $msgError="[Ex] Error poniendo por defecto iptv: ".$e->getMessage();
        }


        if(!$error) {
            Session::flash('message', 'Comando de reset interfaz enviado con éxito');
            Session::forget('errors');
            //vamos a la vista
            return $this->IndexIptvsAsociados($idcliente);
        }
        else{
            Session::flash('errors',$msgError);
            //vamos a la vista
            return $this->IndexIptvsAsociados($idcliente);
        }


    }




}
