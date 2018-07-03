<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
|--------------------------------------------------------------------------
| 00:API Routes:USUARIOS
|--------------------------------------------------------------------------

*/

//OBTENER TODOS LOS USUARIOS
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/** RECURSO users */

//OBTENER TODOS LOS USUARIOS
Route::get('usuario', function() {
    // If the Content-Type and Accept headers are set to 'application/json',
    // this will return a JSON structure. This will be cleaned up later.
    return \App\User::all()->makeHidden('password');
});
//OBTENER TODOS LOS USUARIOS POR UN ID
Route::get('usuario/{id}', function($id) 
{
    return \App\User::find($id)->makeHidden('password');
});

//CREAR UN USUARIO
Route::post('usuario/crear', function (Request $request) 
{
    $usuarioObject=new App\User();
    return $usuarioObject->create($request->all());
});
//ACTUALIZAR UN USUARIO
Route::patch('usuario/actualizar', function (Request $request) 
{
    $usuarioObject=new App\User();
    return $usuarioObject->actualizar($request->all());
});

Route::get('usuario/{id}/iptvretirados', function($id) {
    $user=\App\User::find($id);
    if($user!=null)
        $iptvs= $user->iptvsRetirados();
    else
         return json_encode(array("status"=>405,"message"=>"El usuario buscado no existe."));
    
    return $iptvs;

});

Route::get('usuario/{id}/iptvstock', function($id) {
    $user=\App\User::find($id);
    if($user!=null)
        $iptvs= $user->iptvsStock();
    else
         return json_encode(array("status"=>405,"message"=>"El usuario buscado no existe."));
  
  return $iptvs;

});

Route::get('usuario/{id}/canales', function($id) {
    $user=\App\User::find($id);
    
    if($user!=null)
        return  $user->canals()->get();
    else
         return json_encode(array("status"=>405,"message"=>"El usuario buscado no existe."));
});

Route::get('usuario/{id}/paquetes', function($id) {
    $user=\App\User::find($id);
    if($user!=null)
        return  $user->paquetescanales()->get();
    else
         return json_encode(array("status"=>405,"message"=>"El usuario buscado no existe."));

});

Route::get('usuario/{id}/iptvinstalados', function($id) {
    $user=\App\User::find($id);
    $iptvs= $user->iptvsInstalados();
    return $iptvs;

});




/*
Route::get('usuario/{id}/paquetesCompletos', function($id) {
    $user=\App\User::find($id);
    if($user!=null)
    {
    $user->obtenerPaquetesConCanales();

    }
    else
      return json_encode(array("status"=>405,"message"=>"El usuario buscado no existe."));

});*/



/*
|--------------------------------------------------------------------------
| 01.API Routes:CANALES
|--------------------------------------------------------------------------

*/

Route::get('canal', function() {
    // If the Content-Type and Accept headers are set to 'application/json',
    // this will return a JSON structure. This will be cleaned up later.
    return \App\Canal::all();
});

Route::get('canal/{id}', function($id) {
    // If the Content-Type and Accept headers are set to 'application/json',
    // this will return a JSON structure. This will be cleaned up later.

    return \App\Canal::find($id);
});

Route::post('canal/crear', function (Request $request) 
{
    $canalObject=new \App\Canal();
    return $canalObject->crear($request);
});

Route::patch('canal/actualizar',function (Request $request)
{
     $canalObject=new \App\Canal();
     return $canalObject->actualizar($request);
});

Route::delete('canal/{id}', function($id) {
    // If the Content-Type and Accept headers are set to 'application/json',
    // this will return a JSON structure. This will be cleaned up later.
    return \App\Canal::destroy($id);
});

/*
|--------------------------------------------------------------------------
| 02.API Routes:PAQUETES
|--------------------------------------------------------------------------

*/
//OBTENER TODOS LOS USUARIOS
Route::get('paquete', function() {
    // If the Content-Type and Accept headers are set to 'application/json',
    // this will return a JSON structure. This will be cleaned up later.
    return \App\PaqueteCanal::all();
});

Route::get('paquete/{id}', function($id) {
    // If the Content-Type and Accept headers are set to 'application/json',
    // this will return a JSON structure. This will be cleaned up later.
    return \App\PaqueteCanal::find($id);
});
Route::get('paquete/{id}/canales', function($id) 
{
    // If the Content-Type and Accept headers are set to 'application/json',
    // this will return a JSON structure. This will be cleaned up later.
    $paquetes= \App\PaqueteCanal::find($id);
    return  $paquetes->canales()->get();
   // return $object->getCanalesPaquete($id);
});
Route::get('paquete/usuario/{id}', function($idReseller) 
{
    $user=\App\User::find($idReseller);
    return  $user->paquetescanales()->get();
});
Route::post('paquete/crear', function (Request $request) 
{
    $object=new \App\PaqueteCanal();
    return $object->crear($request->all());
});
Route::patch('paquete/actualizar', function (Request $request) 
{
    $object=new \App\PaqueteCanal();
    return $object->actualizar($request->all());
});
//AÑADIR CANALES A UN PAQUETE
Route::post('paquete/canal', function (Request $request) 
{
    $object=new \App\PaqueteCanal();
    return $object->setCanal($request->all());
});
//ELIMINAR CANALES DE UN PAQUETE
Route::delete('paquete/canal', function (Request $request) 
{
    $object=new \App\PaqueteCanal();
    return $object->deleteCanal($request->all());
});
/*
|--------------------------------------------------------------------------
| 03.API Routes:IPTVS
|--------------------------------------------------------------------------

*/
//OBTENER TODOS LOS USUARIOS
Route::get('iptv', function() {
    // If the Content-Type and Accept headers are set to 'application/json',
    // this will return a JSON structure. This will be cleaned up later.
    return \App\Iptv::all();
});

Route::get('iptv/usuario/{id}', function($idReseller) 
{
    $user=\App\User::find($idReseller);
    if($user==null)
         return json_encode(array("status"=>405,"message"=>"El usuario buscado no existe."));
    else
        return (string) $user->iptvs()->get();
});

Route::get('iptv/{id}', function($id) 
{

    // If the Content-Type and Accept headers are set to 'application/json',
    // this will return a JSON structure. This will be cleaned up later.
    return \App\Iptv::find($id);
});

/*
 * Búsca una mac en el sistema.
 */

Route::get('iptv/mac/{id}', function($mac) 
{
        $existe=\App\Iptv::where('mac', $mac)->first();
        
        if($existe==null)
             return json_encode(array("status"=>200,"message"=>"Mac no registrada"));
        return $existe;

});
Route::post('iptv/crear', function (Request $request) 
{
    
    $object=new \App\Iptv();
    return $object->create($request->all());
});

Route::patch('iptv/actualizar', function (Request $request) 
{
    $object=new \App\Iptv();
    return $object->actualizar($request->all());
});

Route::post('iptv/asignar', function (Request $request) 
{
    $object=new \App\Iptv();
    return $object->asignarIptvCliente($request);
});

Route::delete('iptv/desasignar', function(Request $request) 
{
        $iptv= new \App\Iptv();
        $iptvs= $iptv->deleteIPTV($request);

  
  return $iptvs;

});

Route::patch('iptv/cambiarEstado', function (Request $request) 
{
    $object=new \App\Iptv();
    return $object->cambiarEstado($request);
});



/*
|--------------------------------------------------------------------------
| 04.API CLIENTES:IPTVS
|--------------------------------------------------------------------------

*/
//OBTENER TODOS LOS USUARIOS
Route::get('cliente', function() {
    // If the Content-Type and Accept headers are set to 'application/json',
    // this will return a JSON structure. This will be cleaned up later.
    return \App\Cliente::all();
});
//OBTENER TODOS LOS USUARIOS POR UN ID
Route::get('cliente/{id}', function($id) 
{
    
    $cliente=new \App\Cliente();
     return $cliente->buscarPorId($id);
});

Route::post('cliente/crear', function (Request $request) 
{
    $object=new \App\Cliente();
    return $object->crear($request->all());
});
Route::patch('cliente/actualizar', function (Request $request) 
{
    $object=new \App\Cliente();
    return $object->actualizar($request->all());
});

/*
|--------------------------------------------------------------------------
| 05.API CLIENTES:BACKGROUND
|--------------------------------------------------------------------------

*/
//OBTENER TODOS LOS USUARIOS
Route::get('background', function() {
    // If the Content-Type and Accept headers are set to 'application/json',
    // this will return a JSON structure. This will be cleaned up later.
    return \App\Background::all();
});
Route::get('background/usuario/{id}', function($idUsuario) 
{
    $object=new \App\Background();
    return $object->getBackgroundUsuario($idUsuario);
});

Route::get('background/{id}', function($id) {
    // If the Content-Type and Accept headers are set to 'application/json',
    // this will return a JSON structure. This will be cleaned up later.
    return \App\Background::find($id);
});

Route::post('background/crear', function (Request $request) 
{
    $object=new \App\Background();
    return $object->crear($request);
});
Route::patch('background/actualizar', function (Request $request) 
{
    $object=new \App\Background();
    return $object->actualizar($request->all());
});

/*
|--------------------------------------------------------------------------
| 06.API CLIENTES:PERFILES
|--------------------------------------------------------------------------

*/
//OBTENER TODOS LOS USUARIOS
Route::get('perfil', function() {
    // If the Content-Type and Accept headers are set to 'application/json',
    // this will return a JSON structure. This will be cleaned up later.
    return \App\PerfilInstalacion::all();
});



Route::get('perfil/{id}', function($id) {
    // If the Content-Type and Accept headers are set to 'application/json',
    // this will return a JSON structure. This will be cleaned up later.
    return \App\PerfilInstalacion::find($id);
});

Route::post('perfil/crear', function (Request $request) 
{
    $object=new \App\PerfilInstalacion();
    return $object->crear($request);
});
Route::patch('perfil/actualizar', function (Request $request) 
{
    $object=new \App\PerfilInstalacion();
    return $object->actualizar($request);
});




//Route::get('users/{id}/iptvs', [ 'uses' => 'ApiController@iptvs']);


/** RECURSO IPTVS */

//se pasa por post un parametro "estado" tal que stock,rma,instalado,retirado
/*
Route::post('users/{idusuario}/iptvs', [ 'uses' => 'ApiController@iptvs']);

//C
//crear un iptv
Route::post('iptvs', [ 'uses' => 'ApiController@iptvsCreate']);

//R
Route::get('iptvs/{id}', function($id) {
    return \App\Iptv::find($id);
});


/** RECURSO CLIENTES O HABITACIONES */
/*
Route::get('users/{idusuario}/clientes', [ 'uses' => 'ApiController@clientes']);

Route::post('users/{idusuario}/clientes', function(Request $request) {
    return Article::create($request->all);
});*/







