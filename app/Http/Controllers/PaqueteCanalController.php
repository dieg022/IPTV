<?php

namespace App\Http\Controllers;

use App\PaqueteCanal;
use App\Canal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Facades\Session;

class PaqueteCanalController extends Controller
{

    /**
     * @param $id compruebo que ese id es deeste tio, sino.. al index de este recurso
     */
    private function comprobarPropietarioRecurso($id){

        if(!Auth::user()->paquetescanales->contains('id',$id)) {
            abort(301, '', ['Location' => url('paquetecanal')]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //obtenemos todos paquetes de canales de ese usuario
        $objs = Auth::user()->paquetescanales;

        //los pasamos a la vista
        return View('paquetecanal/index')->with('objs',$objs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //obtenemos todos los canales de ese usuario
        $canales= Auth::user()->canals;
        $arrayIDs = array();

        $numeroCanales= $canales->count();


        //obtenemos los ids de los canales permitidos para ese user y los metemos en un array
        foreach($canales as $canal)
        {
            array_push($arrayIDs,$canal->id);
        }

        $canales = \App\Canal::whereIn("id", $arrayIDs)->orderby('acronimopais',"desc")->get();


        $paises=array();
        $paises = DB::select( DB::raw("SELECT pais from canales group by pais"));

       // dd($paises);

//        dd($paises[0]);

      /*  foreach($paises as $pais){
            echo $pais->pais;
        }*/

        return View('paquetecanal/create')->with('canales',$canales)->with('paises',$paises)->with('numeroCanales',$numeroCanales);
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

            $paqueteCanal=new PaqueteCanal();

            $userID=Auth::user()->id;

            if(PaqueteCanal::where('nombrepaquete', '=', $request->nombrepaquete)->where('user_id', '=', $userID)->first())
                throw new \Exception("Ya existe un paquete de canales con dicho nombre: $request->nombrepaquete");

            $paqueteCanal->nombrepaquete= $request->nombrepaquete;

            //almaceno directamente el paquetecanal y su relacion con el usuario!!
            Auth::user()->paquetescanales()->save($paqueteCanal);

            //ahora le asocio los canales que vienen:
            $parametros = $request->All();
            foreach($parametros as $param => $valor)
            {
                $pos = strpos($valor, '-');

                if ($pos === false) {
                    //echo "La cadena '$findme' no fue encontrada en la cadena '$mystring'";
                } else {
                    $idcanal = explode("-",$valor);
                    $idcanal = $idcanal[1];
                    //dd($orden);

                    //echo "idcanal:".$idcanal." ".$param."=>".$valor."<br>";
                    $orden = explode(";",$param);
                    $orden = $orden[1];
                    //echo "idcanal:".$idcanal." orden: $orden";
                    //lo meto en la relacion en la tabla pivote y especifico el orden canal que es atributo
                    //de la relacion, en paqueteCanal he tenido que especificar la tabla pivote con pivot
                    $paqueteCanal->canales()->attach($idcanal, ['ordencanal' => $orden]);

                }

            }

        }
        catch (QueryException $e) {
            $error=true;
            //$error_code = $e->errorInfo[2];
            $msgError="QEx:Error creando paquete de canales: ".$e->getMessage();
            dd($e);
        }
        catch (PDOException $e) {
            $error=true;
            $msgError="PDOEx:Error creando paquete de canales: ".$e->getMessage();
        }
        catch (\Exception $e)
        {
            $error=true;
            $msgError="[Ex] Error creando paquete de canales: ".$e->getMessage();
        }

        //obtenemos todos paquetes de canales de ese usuario
        $objs = Auth::user()->paquetescanales;



        //borramos los datos anteriores de sesiones
        ///////////////////// Session::flush();

        if(!$error) {
            Session::flash('message', 'Paquete canal añadido con éxito');
            Session::forget('errors');
            //vamos a la vista
            return View('paquetecanal/index')->with('objs',$objs);
        }
        else{
            Session::flash('errors',$msgError);
            //los pasamos a la vista

            //borramos los datos anteriores de sesiones
            Session::forget('message');

            $canales= Auth::user()->canals;

            $request->flash();
           return View('paquetecanal/create')->with('canales',$canales);



        }


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
        //compruebo el propietario
        $this->comprobarPropietarioRecurso($id);

        //obtengo el usuario en cuestion
        $paquetecanal = PaqueteCanal::find($id);


        //borramos los datos anteriores de sesiones
        Session::forget('errors');
        Session::forget('message');

        //obtengo un listado de los canales de este paquete ordenados y de que pais son
        $result=DB::select('select ordencanal,canal_id,pais from canales_paquetes,canales where canal_id=id and paquetecanal_id=? order by ordencanal',[$id]);

        //dd($result);

        $vector=array();
        foreach($result as $valor){

            //echo "=>".$valor."<br>";
           // dd($valor["paquetecanal_id"]);
            foreach ($valor as $key => $value){
                //echo $key."=> val:".$value."<br>";

                if($key=='ordencanal'){
                    $ordencanal=$value;
                }
                if($key=='canal_id'){
                    $canal_id=$value;
                }
                if($key=='pais'){
                    $pais=$value;
                }

            };
           // echo "almacenar $ordencanal => $value <br>";
           // $vector['ordencanal']=$ordencanal;
            $vector[$ordencanal]['canal_id']=$canal_id;
            $vector[$ordencanal]['pais']=$pais;


        };

        //dd($vector);

        $canales= Auth::user()->canals;
        $arrayIDs = array();


        //obtenemos los ids de los canales permitidos para ese user y los metemos en un array
        foreach($canales as $canal)
        {
            array_push($arrayIDs,$canal->id);
        }

        $canales = \App\Canal::whereIn("id", $arrayIDs)->orderby('acronimopais',"desc")->get();

        $numeroCanales= $canales->count();

        $paises=array();
        $paises = DB::select( DB::raw("SELECT pais from canales group by pais"));


        return View('paquetecanal/edit')->with('paquetecanal',$paquetecanal)
        ->with('vectorOrden',$vector)->with('canales',$canales)->with('paises',$paises)->with('numeroCanales',$numeroCanales);
        //->with('canalesSeleccionados',$canalesSeleccionados)
        //->with('canalesNoSeleccionados',$canalesNoSeleccionados);


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
        //compruebo el propietario
        $this->comprobarPropietarioRecurso($id);

        $error=false;
        try{

            $paqueteCanal=PaqueteCanal::findOrFail($id);

            $userID=Auth::user()->id;

            if(  $paqueteCanal->nombrepaquete!=$request->nombrepaquete && PaqueteCanal::where('nombrepaquete', '=', $request->nombrepaquete)->where('user_id', '=', $userID)->first())
                throw new \Exception("Ya existe un paquete de canales con dicho nombre: $request->nombrepaquete");

            $paqueteCanal->nombrepaquete=$request->nombrepaquete;

            //borro todos los canales de ese paquete
            $paqueteCanal->canales()->detach();


            //ahora le asocio los canales que vienen:
            $parametros = $request->All();
            foreach($parametros as $param => $valor)
            {
                $pos = strpos($valor, '-');

                if ($pos === false) {
                    //echo "La cadena '$findme' no fue encontrada en la cadena '$mystring'";
                } else {
                    $idcanal = explode("-",$valor);
                    $idcanal = $idcanal[1];
                    //dd($orden);

                    //echo "idcanal:".$idcanal." ".$param."=>".$valor."<br>";
                    $orden = explode(";",$param);
                    $orden = $orden[1];
                    //echo "idcanal:".$idcanal." orden: $orden";
                    //lo meto en la relacion en la tabla pivote y especifico el orden canal que es atributo
                    //de la relacion, en paqueteCanal he tenido que especificar la tabla pivote con pivot
                    $paqueteCanal->canales()->attach($idcanal, ['ordencanal' => $orden]);

                }

            }



            /*
            //ahora le asocio los canales que vienen:
            $parametros = $request->All();
            foreach($parametros as $param => $valor)
            {
                //si es un numero => es de los canales seleccionados
                if(is_numeric($param))
                {
                    //echo "viene $param <br>";
                    //lo meto en la relacion en la tabla pivote y especifico el orden canal que es atributo
                    //de la relacion, en paqueteCanal he tenido que especificar la tabla pivote con pivot
                    $paqueteCanal->canales()->attach($param, ['ordencanal' => $param]);
                    //$paqueteCanal->canales()->syncWithoutDetaching( array( $param => array( 'ordencanal' => $param ) ) );
                }

            }*/





            //todos los iptvs instalados que tengan este paquete de canales han de actualizarlo
            DB::table('clientes_iptvs')
                ->where('paquetecanal_id', $id)
                ->update([
                    'flagpaquetecanalact' => 1,
                ]);

            $paqueteCanal->save();


        }
        catch (QueryException $e) {
            $error=true;
            //$error_code = $e->errorInfo[2];
            $msgError="QEx:Error actualizando paquete de canales: ".$e->getMessage();
            dd($e);
        }
        catch (PDOException $e) {
            $error=true;
            $msgError="PDOEx:Error actualizando paquete de canales: ".$e->getMessage();
        }
        catch (\Exception $e)
        {
            $error=true;
            $msgError="[Ex] Error actualizando paquete de canales: ".$e->getMessage();
        }

        //obtenemos todos paquetes de canales de ese usuario
        $objs = Auth::user()->paquetescanales;

        //borramos los datos anteriores de sesiones
        ///////////////////// Session::flush();

        if(!$error) {
            Session::flash('message', 'Paquete canal actualizado con éxito');
            Session::forget('errors');
            //vamos a la vista
            return View('paquetecanal/index')->with('objs',$objs);
        }
        else{
            Session::flash('errors',$msgError);
            //los pasamos a la vista

            $canalesSeleccionados = $paqueteCanal->canales;

            //obtengo una coleccion de objetos que son los canales que tiene disponible este usuario pero que no estan en este paquete
            $result=DB::select('select * from canales where id not in( select canal_id from canales_paquetes where paquetecanal_id=?) and canales.id in(select canal_id from canales_users where user_id=?)',[$id,Auth::user()->id]);
            $canalesNoSeleccionados = Canal::hydrate($result);

            //borramos los datos anteriores de sesiones
            Session::forget('message');

            return View('paquetecanal/edit')->with('paquetecanal',$paqueteCanal)->with('canalesSeleccionados',$canalesSeleccionados)->with('canalesNoSeleccionados',$canalesNoSeleccionados);

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
        $error=false;
        try {
            //busco si actualmente hay iptvs que usan este paquete canal, si es asi, aborto la eliminacion
            //hasta que no lo tenga nadie, si no.. chungo...

            $iptvs = DB::select(DB::raw("SELECT iptv_id from clientes_iptvs where paquetecanal_id = :id "), array(
                'id' => $id,
            ));

            if (!empty($iptvs)) {
                //echo "no se puede borrar, hay iptvs con ese pakete";
                throw new \Exception("No se puede eliminar este paquete de canales ya que existen IPTVs con él asignado");
            }
            else {
                //borro el paquete con dicho id
                PaqueteCanal::destroy($id);
            }
        }
        catch (QueryException $e) {
            $error=true;
            $msgError="QEx:Error eliminando paquete de canales: ".$e->getMessage();
            dd($e);
        }
        catch (PDOException $e) {
            $error=true;
            $msgError="PDOEx:Error eliminando paquete de canales: ".$e->getMessage();
        }
        catch (\Exception $e)
        {
            $error=true;
            $msgError="[Ex] Error eliminando paquete de canales: ".$e->getMessage();
        }

        //retorno la vista
        //obtenemos todos paquetes de canales de ese usuario
        $objs = Auth::user()->paquetescanales;

        if(!$error) {
            Session::flash('message', 'Paquete canal eliminado con éxito');
            Session::forget('errors');
        }
        else{
            Session::flash('errors',$msgError);
            Session::forget('message');
        }
        return View('/paquetecanal/index')->with('objs', $objs);
    }
}
