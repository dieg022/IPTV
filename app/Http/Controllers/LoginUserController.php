<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Route;
use Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\User;

class LoginUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return "index";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return "create";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return "store";
    }


    public function login(Request $request)
    {
        //de momento todo ok,no hay control de login, voy a la ruta main, alli devuelvo la vista main
        //return redirect('main');

        //dd($this->captchaCheck());
        /*$this->validate($request,[
            'username' => 'required',
            'recaptcha_response_field' => 'required',
        ]);*/

        //si viene captcha => la valido
        if($request->haycaptcha == "true")
            $this->validate($request,['g-recaptcha-response' => 'required']);

        if(Auth::attempt(['name' => $request->username , 'password' => $request->password]))
        {
            //echo "logueando con $request->username y $request->password";
            //pongo a cero los intenteos de login para este user
            $usuario=User::where('name',$request->username)->first();
            
            $usuario->intentoslogin=0;
            $usuario->save();

            return Redirect::to('main');
        }
        else
        {

            //comprobar si hay intentos erroneos de sesion para este user
            //si hay 3 o mas => saco captcha y paso variable de que captcha esta on con isset... kizas...
            $usuario=User::where('name', '=' ,$request->username)->first();

            if($usuario == NULL) {
                //echo "no existe ese tio";
                $intentoslogin=0;
            }else{
                $usuario->intentoslogin=$usuario->intentoslogin+1;
                $usuario->save();
                $intentoslogin=$usuario->intentoslogin;
            }

            Session::flash('errors','Usuario/Contraseña incorrectos');
            //return Redirect::to('/')->with('intentosLogin',$usuario->intentoslogin);
            return View('login')->with('intentoslogin',$intentoslogin);
        }

    }

    /**
     * Desconecta al user de la plataforma y va al index
     * @return mixed
     */
     public function logout(){
         Auth::logout();
         Session::flash('message','Sesión cerrada');
         return Redirect::to('/');
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
        return "edit";
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
        return "update";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return "destroy";
    }
}
