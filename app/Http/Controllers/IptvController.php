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

class IptvController extends Controller
{
    /**
     * @param $id compruebo que ese id es deeste tio, sino.. al index de este recurso
     */
    private function comprobarPropietarioRecurso($id){

        if(!Auth::user()->iptvs->contains('id',$id)) {
            abort(301, '', ['Location' => url('iptv/stock')]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function test()
    {
        return "test";
    }


    /**
     * recopila los clientes y perfiles de este cliente para poder seleccionar done
     * asociar este iptv, a que cliente y con que parámetros.
     */
    public function prepararAsociarIPTV($idIPTV)
    {
        //comprobamos el propietario del recurso
        $this->comprobarPropietarioRecurso($idIPTV);

        //obtenemos todos los clientes de ese usuario
        $clientes= Auth::user()->clientes;
        $perfiles= Auth::user()->perfilesinstalacion;


        if(!$clientes->first()) {
            $clientes = collect();

            if( Auth::user()->tipocliente=="hotel")
                Session::flash('errors','Debe tener al menos una habitación creada donde asociar el IPTV');
            else
                Session::flash('errors','Debe tener al menos un cliente creado donde asociar el IPTV');

            return $this->listarStock();

        }

        if(!$perfiles->first()) {
            $perfiles = collect();
            Session::flash('errors','Debe tener al menos un perfil de instalación creado para poder asociar el IPTV');
           // return "no hay perfiles";
           return $this->listarStock();
        }

        //los pasamos a la vista
        $iptv = Iptv::findOrFail($idIPTV);
        $backgrounds = Auth::user()->backgrounds;
        $paquetescanales= Auth::user()->paquetescanales;

        $switchs= Auth::user()->switchpoes;


        return View('iptv/asociar')->with('clientes',$clientes)->with('perfiles',$perfiles)
            ->with('iptv',$iptv)->with('backgrounds',$backgrounds)->with('paquetescanales',$paquetescanales)
            ->with('switchs',$switchs);

    }



    /**
     * viene un cliente seleccionado, un perfil seleccionado y un id iptv y se hace la asociacion
     *
     */
    public function asociarIPTVefectivo(Request $request){

        //echo "asociar el iptv $request->iptv_id con el cliente $request->cliente_id con el perfil $request->perfilinstalacion_id";

        $cliente = Cliente::find($request->cliente_id);

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

            $cliente->iptvs()->attach($request->cliente_id,
                [
                    'iptv_id' => $request->iptv_id,
                    'paquetecanal_id' => $request->paquetecanal_id,
                    'background_id' =>  $request->background_id,
                    'wifienabled'  => $wifienabled,
                    'ssid' =>  $ssid,
                    'password' =>  $password,
                    'wificanal' => $wificanal,
                    'ipactual' => "",
                    'flagwifiact' =>  1,
                    'flagbackgroundact' =>  1,
                    'flagpaquetecanalact' =>  1,
                    'switchpoe_id' => $request->switchpoe_id,
                    'switchpoepuerto' => $request->puerto,
                    'observaciones' => $request->observaciones
                ]);

            //ese iptv pasa a estado INSTALADO
            $iptv = Iptv::find($request->iptv_id);
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
            return $this->listarStock();
        }
        else{
            Session::flash('errors',$msgError);
            //vamos a la vista
            return $this->listarStock();
        }

    }


    /**
     * lista los iptv en stock para este user
     */
    public function listarStock(){

        //obtenemos todos los iptvs de ese usuario
        $iptvs= Auth::user()->iptvsStock();

        //$iptvs= Iptv::all();

        //dd($iptvs);
        //si no tengo nada devuelvo una coleccion vacia

        if(!$iptvs)
            $iptvs= collect();

        //los pasamos a la vista
        $usuario=Auth::user();

        return View('iptv/stock')->with('iptvs',$iptvs)->with('usuario',$usuario);

    }

    /**
     * dEVUEVLE LOS IPTVS EN ESTADO RETIRADO
     * @return $this
     */
    public function listarRetirados(){

        //obtenemos todos los iptvs de ese usuario
        $iptvs= Auth::user()->iptvsRetirados();

        //$iptvs= Iptv::all();

        //dd($iptvs);
        //si no tengo nada devuelvo una coleccion vacia

        if(!$iptvs)
            $iptvs= collect();

        //los pasamos a la vista
        $usuario=Auth::user();

        return View('iptv/retirados')->with('iptvs',$iptvs)->with('usuario',$usuario);

    }

    /**
     * lista los iptv en stock para este user
     */
    public function listarStockFree(){


        //obtenemos todos los clientes de ese usuario
        $iptvs= Auth::user()->iptvsStock();

        //$iptvs= Iptv::all();

        //dd($iptvs);
        //si no tengo nada devuelvo una coleccion vacia

        if(!$iptvs)
            $iptvs= collect();

        //los pasamos a la vista
        $usuario=Auth::user();

        return View('iptv/stockfree')->with('iptvs',$iptvs)->with('usuario',$usuario);
    }

    /**
     * Lista los RMA iptvs para este user
     * @return $this
     */
    public function listarRMA(){

        //obtenemos todos los clientes de ese usuario
        $iptvs= Auth::user()->iptvsRMA();

        //$iptvs= Iptv::all();

        //dd($iptvs);
        //si no tengo nada devuelvo una coleccion vacia

        if(!$iptvs)
            $iptvs= collect();

        //los pasamos a la vista
        $usuario=Auth::user();

        return View('iptv/rma')->with('iptvs',$iptvs)->with('usuario',$usuario);

    }


    /**
     * lista los iptv instalados para este user
     */
    public function listarInstalados(){

        //obtenemos todos los clientes de ese usuario
        $iptvs= Auth::user()->iptvsInstalados();

        //$iptvs= Iptv::all();

        //dd($iptvs);
        //si no tengo nada devuelvo una coleccion vacia

        if(!$iptvs)
            $iptvs= collect();

        //los pasamos a la vista
        $usuario=Auth::user();

        return View('iptv/instalados')->with('iptvs',$iptvs)->with('usuario',$usuario);


    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //envio el listado de proveedores
        $proveedores = Proveedor::all();
        $usuarios = User::all();

        Session::forget('errors');
        Session::forget('message');
        return View('iptv/create')->with('proveedores',$proveedores)->with('usuarios',$usuarios);
       // return View('iptv/create')->with(compact('proveedores', 'usuarios'));
    }


    /**
     * @return $this Preparar formulario de alta masivo
     */
    public function createMasivo()
    {
        //envio el listado de proveedores
        $proveedores = Proveedor::all();
        $usuarios = User::all();

        Session::forget('errors');
        Session::forget('message');
        return View('iptv/create-masivo')->with('proveedores',$proveedores)->with('usuarios',$usuarios);

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

        if(isset($request->masiva))
            $esMasiva=true;
        else
            $esMasiva=false;

        try{

            if($esMasiva==false) {

                $iptv = new Iptv();

                //es otra forma de comprobar que no hay mac repetidas, haciendolo en un metodo de la clase
                //en otros objectos lo hemos hecho de forma implicita en el controlador

                $iptv->setMac($request->mac);
                $iptv->fechacompra = $request->fechacompra;
                $iptv->tipo = $request->tipo;
                $iptv->proveedor_id = $request->proveedor_id;
                $iptv->numeroserie = $request->numeroserie;
                $iptv->estado = "STOCK";
                //por defecto el propietario es admin
                $iptv->user_id = $request->usuario_id;

                $iptv->save();
            }
            else
            {
                $macs=explode(PHP_EOL,$request->mac);
                $numerosSerie=explode(PHP_EOL,$request->numeroserie);

                if(count($macs)!=count($numerosSerie))
                    throw new \Exception("No coinciden el numero de MACS y Numeros de Serie");
                //dd($macs);

                $i=0;
                foreach($macs as $mac){

                    $numeroSerie=$numerosSerie[$i];

                    $iptv = new Iptv();

                    $mac=trim($mac);
                    $numeroSerie=trim($numeroSerie);
                    $iptv->setMac($mac);
                    $iptv->fechacompra = $request->fechacompra;
                    $iptv->tipo = $request->tipo;
                    $iptv->proveedor_id = $request->proveedor_id;
                    $iptv->numeroserie =  $numeroSerie;
                    $iptv->estado = "STOCK";
                    //por defecto el propietario es admin
                    $iptv->user_id = $request->usuario_id;

                    $iptv->save();

                    $i++;


                }



            }

            $flagAsociado=false;
            //si ya he indicado un usuario => los relaciono
            /*if($request->usuario_id!=-1)
            {
                 $user=User::find($request->usuario_id);
                 $user->iptvs()->save($iptv);
                 $flagAsociado=true;
            }*/

        }
        catch (QueryException $e) {
            $error=true;
            //$error_code = $e->errorInfo[2];
            $msgError="QEx:Error creando iptv masivo: ".$e->getMessage();
            dd($e);
        }
        catch (PDOException $e) {
            $error=true;
            $msgError="PDOEx:Error creando iptv masivo: ".$e->getMessage();
        }
        catch (\Exception $e)
        {
            $error=true;
            $msgError="[Ex] Error creando iptv masivo: ".$e->getMessage();
        }

        //obtenemos todos los elementos de la vista
        $iptvs = Iptv::all();
        $proveedores = Proveedor::all();
        $usuarios = User::all();


        if(!$error) {
            if ($flagAsociado) {
                if($esMasiva==true)
                    Session::flash('message', 'Iptv añadido y asociado al cliente con éxito');
                else
                    Session::flash('message', 'Iptvs añadidos y asociados al cliente con éxito');
            } else {
                if($esMasiva==true)
                    Session::flash('message', 'Iptv añadido con éxito');
                else
                    Session::flash('message', 'Iptvs añadidos con éxito');
            }
            Session::forget('errors');
            $usuario=Auth::user();
            //vamos a la vista
            return View('iptv/stock')->with('iptvs', $iptvs)->with('usuario',$usuario);
        }
        else{
            Session::flash('errors',$msgError);
            //Input::flash();  //ESTABLECE VARIABLE DE SESIÓN
            $request->flash();
            if($esMasiva==false)
                return View('iptv/create')->with('proveedores',$proveedores)->with('usuarios',$usuarios)->with('iptvs', $iptvs);
            else
                return View('iptv/create-masivo')->with('proveedores',$proveedores)->with('usuarios',$usuarios)->with('iptvs', $iptvs);
        }



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\IPTV  $iPTV
     * @return \Illuminate\Http\Response
     */
    public function show(IPTV $iPTV)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\IPTV  $iPTV
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        //obtengo el IPTV en cuestion
        $iptv = Iptv::find($id);
        $proveedores = Proveedor::all();
        $usuarios = User::all();


        //$usuario = User::find(1);
        //dd($usuario->iptvs);
        //dd(User::find($iptv->user_id));

       // dd($iptv->getUsuario()->id);

        //borramos los datos anteriores de sesiones
        //ESTA PUTA COMANDA ME JODIDA EL TOKEN!!! DEL FORM CON TO SUS MUERTO
        //Session::flush();
        Session::forget('errors');
        Session::forget('message');



        return View('iptv/edit')->with('iptv',$iptv)->with('proveedores',$proveedores)->with('usuarios',$usuarios);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\IPTV  $iPTV
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $error=false;
        //actualizo, puede lanzar excepcion si he puesto un mail o clientename repetidos
        try {

            $iptv = Iptv::findOrFail($id);
            $iptv->fechacompra = $request->fechacompra;
            $iptv->tipo = $request->tipo;
            $iptv->proveedor_id = $request->proveedor_id;

            //si la mac que viene es distinta a la que tenia, intento asignarla
            if($iptv->mac != $request->mac)
                $iptv->setMac($request->mac);

            $iptv->numeroserie=$request->numeroserie;
            $iptv->user_id = $request->usuario_id;
            $iptv->save();

            //si el usuario que viene es distinto del que tenia=> reasigno
          /*  if($request->usuario_id != $iptv->getUsuario()->id)
            {
                //borro el anterior
                $iptv->getUsuario()->iptvs()->detach($iptv->id);//akiii petaaaaaaaaaPACOOOOOOOOOOOOOOOOOOO

                //añado el nuevo
                $user=User::find($request->usuario_id);
                $user->iptvs()->attach($iptv->id);

            }*/



        }
        catch (QueryException $e) {
            $error=true;
            //$error_code = $e->errorInfo[2];
            $msgError="QEx:Error actualizando iptv: ".$e->getMessage();
            dd($e);
        }
        catch (PDOException $e) {
            $error=true;
            $msgError="PDOEx:Error actualizando iptv: ".$e->getMessage();
        }
        catch (\Exception $e)
        {
            $error=true;
            $msgError="[Ex] Error actualizando iptv: ".$e->getMessage();
        }

        //obtenemos todos los clientes de dicho usuario
        $iptvs= Iptv::all();

        if(!$error) {
            Session::flash('message', 'Iptv actualizado con éxito');
            Session::forget('errors');
            //vamos a la vista
            //return View('user/index')->with('users', $users);
            return Redirect('/iptv/stockfree')->with('iptv', $iptvs);
        }
        else{
            Session::forget('message');
            Session::flash('errors',$msgError);
            //vamos a la vista de creacion con el usuario... no se...
            //Input::flash();  //ESTABLECE VARIABLE DE SESIÓN
            $proveedores = Proveedor::all();
            $usuarios = User::all();

            return View('iptv/'.$request->id.'/edit')->withInput($request)->with('iptv',$iptv)->with('proveedores',$proveedores)->with('usuarios',$usuarios);
            //return "errociloo";
        }





    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\IPTV  $iPTV
     * @return \Illuminate\Http\Response
     */
    public function destroy(IPTV $iPTV)
    {
        //
    }
}
