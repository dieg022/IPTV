<?php

use Illuminate\Support\Facades\Input;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('login');
});


//Route::resource('login', 'LoginUserController');

//ruta login
//Route::post('login', 'LoginUserController@login');
Route::post('login', [ 'as' => 'login', 'uses' => 'LoginUserController@login']);
Route::get('login', [ 'as' => 'login', 'uses' => 'LoginUserController@login']);

Route::get('logout','LoginUserController@logout');


//ruta principal tras logueo
Route::get('main', function (){
    return View('main');
});



//pruebo con un routing group, perfecto, las rutas dentro de este routing group
//han de estar autenticadas o si no, por defecto se va al /login
Route::group(['middleware' => 'auth'], function () {

    //ruta users

    //asociar a este cliente un iptv de una lista
    Route::get('user/{id}/canales', [ 'uses' => 'UserController@canalesAsociados']);

    //asociacion de canales efectiva para un user
    Route::patch('user/{id}/canalesset', [ 'uses' => 'UserController@canalesAsociadosSet']);

    //user resource rutas
    Route::resource("user","UserController");


    /*nota:
    PODEMOS ASOCIAR UN IPTV A UN CLIENTE DESDE IPTV->STOCK O DESDE CLIENTES-> SELECCIONE UN CLIENTE Y LUEGO LE DIGO EL IPTV QUE LE QUIERO PONER
    PARA LUEGO EDITAR DICHO IPTV LA UNICA FORMA QUE TENGO DE HACERLO ES DESDE EL PROPIO CLIENTE*/

    //////////////////CLIENTES
    ///
    //ver iptvs de un cliente
    Route::get('cliente/{id}/iptvs', [ 'uses' => 'ClienteController@IndexIptvsAsociados']);

    //asociar a este cliente un iptv de una lista
    Route::get('cliente/{id}/asociar', [ 'uses' => 'ClienteController@CreateIptvAsociado']);

    //guadar la asociacion entre ese cliente y un iptv
    Route::post('cliente/{idcliente}/asociar', [ 'uses' => 'ClienteController@StoreIptvAsociado']);

    //editar iptv de un cliente
    Route::get('cliente/{idcliente}/iptv/{idiptv}/editar', [ 'uses' => 'ClienteController@EditIptvAsociado']);

    //almacenar por aki voy
    Route::post("cliente/{idcliente}/iptv/{idiptv}/modificar",[ 'uses' => 'ClienteController@UpdateIptvAsociado']);

    //reemplazar iptv de un cliente
    Route::get('cliente/{idcliente}/iptv/{idiptv}/reemplazar', [ 'uses' => 'ClienteController@ReemplazarIptvAsociado']);

    //retirar un iptv, pasa a stock de nuevo
    Route::get('cliente/{idcliente}/iptv/{idiptv}/retirar', [ 'uses' => 'ClienteController@RetirarIptvAsociado']);

    //retirar un iptv por post, se hace efectiva la misma
    Route::post('cliente/{idcliente}/iptv/{idiptv}/retirar', [ 'uses' => 'ClienteController@RetirarIptvAsociadoEfectivo']);


    //PONER POR DEFECTO un iptv, pasa a stock de nuevo
    Route::get('cliente/{idcliente}/iptv/{idiptv}/default', [ 'uses' => 'ClienteController@DefaultIptvAsociado']);

    //poner por defecto un iptv por post, se hace efectiva la misma
    Route::post('cliente/{idcliente}/iptv/{idiptv}/default', [ 'uses' => 'ClienteController@DefaultIptvAsociadoEfectivo']);


    //remplazar iptv por otro en post, hago el cambio
    Route::post('cliente/{idcliente}/iptv/{idiptv}/reemplazar', [ 'uses' => 'ClienteController@UpdateReemplazarIptvAsociado']);

    //crear habitacion como cliente
    Route::get('cliente/createhabitacion', [ 'uses' => 'ClienteController@createHabitacion']);

    //ruta clientes
    Route::resource("cliente","ClienteController");

    ////////////FIN CLIENTES

    /****rutas iptv*/
    //ruta iptv stock antes del restfull de abajo
    Route::get("iptv/create-masivo",[ 'uses' => 'IptvController@createMasivo']);

    //ruta iptv stock antes del restfull de abajo
    Route::get("iptv/stock",[ 'uses' => 'IptvController@listarStock']);

    //ruta iptv stock antes del restfull de abajo
    Route::get("iptv/retirados",[ 'uses' => 'IptvController@listarRetirados']);

    //ruta iptv stock antes del restfull de abajo
        Route::get("iptv/stockfree",[ 'uses' => 'IptvController@listarStockFree']);

    //ruta iptv stock antes del restfull de abaj
    Route::get("iptv/rma",[ 'uses' => 'IptvController@listarRMA']);

    //ruta iptv instalados
    Route::get("iptv/instalados",[ 'uses' => 'IptvController@listarInstalados']);

    //asociar iptv a un cliente
    Route::get('iptv/{id}/asociar', [ 'uses' => 'IptvController@prepararAsociarIPTV']);


    //ruta iptv formulario post de asociar un iptv a un cliente desde el menu iptvs
    Route::post("iptvasociar",[ 'uses' => 'IptvController@asociarIPTVefectivo']);


    //ruta iptv
    Route::resource("iptv","IptvController");

    /*****fin iptv*/


    //ruta rma almacen
    Route::get("rma-almacen","IncidenciaController@rmaAlmacen");
    //ruta rma
    Route::resource("rma","IncidenciaController");


    //ruta proveeedores
    Route::resource("proveedor","ProveedorController");


    //ruta canales
    Route::resource("canal","CanalController");

    //ruta paquetes canales
    Route::resource("paquetecanal","PaqueteCanalController");

    //ruta background
    Route::resource("background","BackgroundController");

    //ruta perfil instalacion
    Route::resource("perfilinstalacion","PerfilInstalacionController");

    //ruta swichpoe
    Route::resource("switchpoe","SwitchPoeController");



    //estadisticas
    Route::get('reventapaquetes', [ 'uses' => 'EstadisticasController@reventaPaquetes']);

    Route::get('historicoreventapaquetes', [ 'uses' => 'EstadisticasController@reventaPaquetesHistorico']);


    //prueba ajax ruta de procesamiento
        Route::get('getparametrosperfilinstalacion',function()
        {
            $idperfil = Input::get('perfil');
            $perfil=\App\PerfilInstalacion::find($idperfil);

            return $perfil;

        });

       //ajax para los obtener los canales de un pais
    /*Route::get('getcanalespais',function()
    {
        $pais = Input::get('pais');
        $canales = \App\Canal::all()->where('pais',$pais);

        //$perfil=\App\PerfilInstalacion::find($idperfil);

        //return $perfil;
        //dd($canales);
        foreach ($canales as $canal){
            echo $canal->canal;
        }

    CREO QUE LO HARE CON JS EN EL OTRO LADO YA QUE EL LISTADO DE CANALES LO TENGO.
    });*/


});


//ruta principal tras logueo
Route::get('main', function (){
    return View('main');
});


