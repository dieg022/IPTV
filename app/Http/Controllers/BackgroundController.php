<?php

namespace App\Http\Controllers;

use App\Background;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class BackgroundController extends Controller
{

    /**
     * @param $id compruebo que ese id es deeste tio, sino.. al index de este recurso
     */
    private function comprobarPropietarioRecurso($id){

        if(!Auth::user()->backgrounds->contains('id',$id)) {
            abort(301, '', ['Location' => url('background')]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //obtenemos todos los clientes de ese usuario
        //$clientes= Cliente::where('user_id',Auth::user()->id)->get();

        $backgrounds = Auth::user()->backgrounds;

        //dd($clientes);
        //$clientes = Cliente::all();

        //los pasamos a la vista
        return View('background/index')->with('backgrounds', $backgrounds);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View('background/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $error = false;
        try {

            //obtengo el id del usuario
            //he de importar use Illuminate\Support\Facades\Auth;


            //miro si puedo subir la imagen
            //$imageTempName = $request->file('urlbackground')->getPathname();
            $imageName = $request->file('urlbackground')->getClientOriginalName();
            $path = base_path() . '/public/images/';
            $request->file('urlbackground')->move($path , $imageName);

            $background= new Background();

            $background->background=$request->background;
            $background->urlbackground=$imageName;

            $userID=Auth::user()->id;

            if(Background::where('background', '=', $request->background)->where('user_id', '=', $userID)->first())
                throw new \Exception("Ya existe un fondo con dicho nombre: $request->background");


            Auth::user()->backgrounds()->save($background);


        } catch (QueryException $e) {
            $error = true;
            //$error_code = $e->errorInfo[2];
            $msgError = "QEx:Error creando fondo: " . $e->getMessage();
            dd($e);
        } catch (PDOException $e) {
            $error = true;
            $msgError = "PDOEx:Error creando fondo: " . $e->getMessage();
        } catch (\Exception $e) {
            $error = true;
            $msgError = "[Ex] Error creando fondo: " . $e->getMessage();
        }

        //obtenemos todos los clientes de este usuario
        $backgrounds = Auth::user()->backgrounds;

        //borramos los datos anteriores de sesiones
        ///////////////////// Session::flush();

        if (!$error) {
            Session::flash('message', 'Fondo añadido con éxito');
            //Session::forget('errors');
            //vamos a la vista
            return View('background/index')->with('backgrounds', $backgrounds);
        } else {
            Session::flash('errors', $msgError);
            //vamos a la vista de creacion con el usuario... no se...
            //Input::flash();  //ESTABLECE VARIABLE DE SESIÓN
            return View('background/create')->withInput($request);
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cliente $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cliente $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //compruebo el propietario
        $this->comprobarPropietarioRecurso($id);

        //obtengo el usuario en cuestion
        $background = Background::find($id);

        //borramos los datos anteriores de sesiones
        //ESTA PUTA COMANDA ME JODIDA EL TOKEN!!! DEL FORM CON TO SUS MUERTO
        //Session::flush();
        Session::forget('errors');
        Session::forget('message');

        return View('background/edit')->with('background', $background);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Cliente $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //compruebo el propietario
        $this->comprobarPropietarioRecurso($id);

        $error = false;

        //actualizo, puede lanzar excepcion si he puesto un mail o clientename repetidos
        try {

            $background = Background::findOrFail($id);
            $background->background=$request->background;

            //si no viene vacia la nueva foto => la subimos
            if($request->urlbackground !="") {

                $imageName = $request->file('urlbackground')->getClientOriginalName();
                $path = base_path() . '/public/images/';
                $request->file('urlbackground')->move($path , $imageName);

                $background->urlbackground=$imageName;

            }

            $userID=Auth::user()->id;

            if(  $background->background!=$request->background &&Background::where('background', '=', $request->background)->where('user_id', '=', $userID)->first())
                throw new \Exception("Ya existe un fondo con dicho nombre: $request->background");

            $background->save();

            //todos los iptvs instalados que tengan este background han de actualizarlo
            DB::table('clientes_iptvs')
                ->where('background_id', $id)
                ->update([
                    'flagbackgroundact' => 1,
                ]);




        } catch (QueryException $e) {
            $error = true;
            //$error_code = $e->errorInfo[2];
            $msgError = "QEx:Error actualizando background: " . $e->getMessage();
            dd($e);
        } catch (PDOException $e) {
            $error = true;
            $msgError = "PDOEx:Error actualizando background: " . $e->getMessage();
        } catch (\Exception $e) {
            $error = true;
            $msgError = "[Ex] Error actualizando background: " . $e->getMessage();
        }

        //obtenemos todos los clientes de dicho usuario
        //$clientes= Cliente::where('user_id',Auth::user()->id)->get();

        $backgrounds = Auth::user()->backgrounds;

        if (!$error) {
            Session::flash('message', 'Fondo actualizado con éxito');
            Session::forget('errors');
            //vamos a la vista
            //return View('user/index')->with('users', $users);
            return View('background/index')->with('backgrounds', $backgrounds);
        } else {
            Session::forget('message');
            Session::flash('errors', $msgError);

            $request->flash();
            return View('background/edit')->with('background',$background);
            //return View('background/' . $request->id . '/edit')->withInput($request);
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cliente $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //borro el cliente con dicho id
      /*  Background::destroy($id);

        //retorno la vista
        //obtenemos todos los clientes de dicho usuario
        //$clientes= Cliente::where('user_id',Auth::user()->id)->get();
        $backgrounds = Auth::user()->backgrounds;

        Session::flash('message', 'Fondo eliminado con éxito');
        Session::forget('errors');

        return Redirect('/background')->with('backgrounds', $backgrounds);
*/

        $error=false;
        try {
            //busco si actualmente hay iptvs que usan ese background, si es asi, aborto la eliminacion
            //hasta que no lo tenga nadie, si no.. chungo...

            $iptvs = DB::select(DB::raw("SELECT iptv_id from clientes_iptvs where background_id = :id "), array(
                'id' => $id,
            ));

            if (!empty($iptvs)) {
                //echo "no se puede borrar, hay iptvs con ese pakete";
                throw new \Exception("No se puede eliminar este fondo ya que existen IPTVs con él asignado");
            }
            else {
                //borro el paquete con dicho id
                Background::destroy($id);
            }
        }
        catch (QueryException $e) {
            $error=true;
            $msgError="QEx:Error eliminando fondo: ".$e->getMessage();
            dd($e);
        }
        catch (PDOException $e) {
            $error=true;
            $msgError="PDOEx:Error eliminando fondo: ".$e->getMessage();
        }
        catch (\Exception $e)
        {
            $error=true;
            $msgError="[Ex] Error eliminando fondo: ".$e->getMessage();
        }

        //retorno la vista
        //obtenemos todos paquetes de canales de ese usuario
        $objs = Auth::user()->backgrounds;

        if(!$error) {
            Session::flash('message', 'Fondo eliminado con éxito');
            Session::forget('errors');
        }
        else{
            Session::flash('errors',$msgError);
            Session::forget('message');
        }
        return View('/background/index')->with('backgrounds', $objs);
    }


}