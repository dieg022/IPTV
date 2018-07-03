<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Exception;
use DB;

class Iptv extends Model
{
    protected $table="iptvs";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fechacompra','proveedor_id','mac','tipo','estado','numeroserie',
    ];

    //para  evitar que salgan los datos pivot al realizar peticiones json
    protected $hidden = ['pivot'];

    public $timestamps = true;

    /**
     * Override create method, para hacer comprobaciones adicionales
     * @param array $attributes
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    public static function create(array $attributes = []){

 
        $mac=$attributes['mac'];
        $existe=Iptv::where('mac', $mac)->first();
 

        if($existe)
        {
            throw new Exception("Ya existe un iptv con mac $mac");
        }
       
     
        $model = static::query()->create($attributes);
        return $model;
    }
    public static function cambiarEstado($request)
    {
        $iptv=Iptv::where('id', $request->iptv_id)->first();
        if(empty($iptv))
        {
            $errores=true;
            return \App\Exceptions\Handler::messageError("405","El iptv pasado no existe.");
        }
        else
        {
            if($request->estado=="RETIRADO" ||$request->estado=="RMA" || $request->estado=="STOCK" || $request->estado=="INSTALADO")
            {
                $iptv->estado=$request->estado;
                $iptv->save();
                return $iptv;
            }
            return \App\Exceptions\Handler::messageError("405","El estado solo puede ser RMA/RETIRADO.");
        }
    }
    public static function asignarIptvCliente($request)
    {
        $cliente= \App\Cliente::where('id', $request->cliente_id)->first();
        $iptv=Iptv::where('id', $request->iptv_id)->first();
        $paquete= \App\PaqueteCanal::where('id', $request->paquete_id)->first();
        $background= \App\Background::where('id', $request->background_id)->first();
     
        $errores=false; //Flag para controlar si hubo algun dato erroneo
    
        if(empty($cliente))
        {
            $errores=true;
            return \App\Exceptions\Handler::messageError("405","El cliente pasado no existe.");
        }
        if(empty($iptv))
        {
            $errores=true;
            return \App\Exceptions\Handler::messageError("405","El iptv pasado no existe.");
        }
         if(empty($paquete))
         {
             $errores=true;
            return \App\Exceptions\Handler::messageError("405","El paquete pasado no existe.");
         }
        if(empty($background))
        {
            $errores=true;
            return \App\Exceptions\Handler::messageError("405","El background pasado no existe.");
        }
        
        if($errores==false)
        {
                    $error=false;
                    $ssid="";
                    $wificanal="";
                    $password="";
                    
        try{
                $cliente->iptvs()->attach($cliente->id,
                   [
                       'iptv_id' => $request->iptv_id,
                       'paquetecanal_id' => $request->paquete_id,
                       'background_id' =>  $request->background_id,
                       'wifienabled'  => 0,
                       'ssid' =>  $ssid,
                       'password' =>  $password,
                       'wificanal' => 6,
                       'ipactual' => "",
                       'flagwifiact' =>  1,
                       'flagbackgroundact' =>  1,
                       'flagpaquetecanalact' =>  1,
                       'switchpoe_id' => $request->switchpoe_id,
                       'switchpoepuerto' => $request->puerto,
                       'observaciones' => $request->observaciones
                   ]);
                
             $iptv->estado="INSTALADO";
             $iptv->save();
             return $iptv;
                
        } catch (\Exception $e) {
             
             return \App\Exceptions\Handler::messageError("500","Error ya existe en base de datos.");
        }
       

           
        }
       
        
        
      
    }
     public static function actualizar(array $attributes=[])
    {
        $iptv=Iptv::where('id', $attributes['id'])->first();
        $user=\App\User::find($attributes['user_id']);
        
        //Comprobamos si el iptv existe al igual que el cliente.
        if($iptv!=null)
        {
            if($user!=null)
            {
                    $iptv->user_id = $attributes['user_id'];
                    $iptv->save();
                    return Iptv::where('id', $iptv->id)->first();   
            }
            else return \App\Exceptions\Handler::messageError("405","El cliente pasado no existe.");
        }
        else
            return \App\Exceptions\Handler::messageError("405","El iptv pasado no existe.");
       
        
        $userObject=User::find($attributes['id']);
        
        
    }

    public static function deleteIPTV($request)
    {
        $iptv=Iptv::where('id', $request->iptv_id)->first();
         $cliente= \App\Cliente::where('id', $request->cliente_id)->first();
        if(empty($iptv))
            return json_encode(array("status"=>405,"message"=>"El iptv que se quiere desasignar no existe."));
        else
        {
            if(empty($cliente))
                 return json_encode(array("status"=>405,"message"=>"El cliente pasado no existe."));
            else
            {
                try{
                $cliente->iptvs()->desatach($cliente->id,
                   [
                       'iptv_id' => $request->iptv_id
                   ]);
                
             $iptv->save();
             return $iptv;
                
                    } catch (\Exception $e) 
                    {
           
                    }
            
        }
            
            
       // $iptv->delete();
    }}
    public  function getIptvUsuario($idUsuario)
    {
        
                     return DB::table('iptvs')
                    ->where('user_id',$idUsuario)->get();

    }
    
    private static function IsValid($mac)
    {
        return (preg_match('/([A-F0-9]{2}[:]?){6}/', $mac) == 1);
    }

    /**
     * @param $email
     * @throws Exception
     */
    public function setMac($mac){

        if(!self::IsValid($mac))
            throw new Exception("Mac:$mac con formato incorrecto: sólo numeros, mayusculas y :");

        //chequeo si ya existe el email,si no, lo asigno
        $existe=Iptv::where('mac', $mac)->first();

        if($existe){
            throw new Exception("Ya existe un iptv con dicha mac $mac");
        }
        else
            $this->mac=$mac;

    }

    /**
 * Devuelve el usuario poseedor de este iptv si existiese
 */
    public function getUsuario(){

       // $this->belongsToMany('App\User', 'user_iptvs','iptv_id');
        //return $this->hasOne('App\User');
        return User::find($this->user_id);
    }


    /**
     * Devuelve el usuario poseedor de este iptv si existiese
     */
    public function getCliente(){

        return $this->belongsToMany('App\Cliente', 'clientes_iptvs')->first();
    }

}
