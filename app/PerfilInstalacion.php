<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PerfilInstalacion extends Model
{

    protected $table="perfilinstalacion";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','nombre','paquetecanal_id','background_id','wifienabled','ssid','password','wificanal'
    ];

    public $timestamps = false;


    /**
     * devuelve el usuario dueño de este perfil de instalacion
     */
    public function usuario(){
        //return $this->belongsTo('App\User');
        return $this->hasOne('App\User');
    }
    
    public static function crear($request)
    {
        $usuario= \App\User::where('id', $request->user_id)->first();
        $paquete= \App\PaqueteCanal::where('id', $request->paquetecanal_id)->first();
        $background= \App\Background::where('id', $request->background_id)->first();
        $wifi=$request->wifienabled;
     
        $errores=false; //Flag para controlar si hubo algun dato erroneo
    
        if(empty($usuario))
        {
            $errores=true;
            return \App\Exceptions\Handler::messageError("405","El usuario pasado no existe.");
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
        if($wifi==1)
        {
            if(empty($request->ssid) || empty($request->password) || empty($request->wificanal) )
            {
                $errores=true;
                return \App\Exceptions\Handler::messageError("405","Falta algún parametro de configuración del WIFI:[ssid,password,wificanal]");
            }
        }
        else
            $wifi=0;
        
        if($errores==false)
        {
            $perfil=new PerfilInstalacion();
            
            $perfil->user_id=$usuario->id;
            $perfil->nombre=$request->nombre;
            $perfil->paquetecanal_id=$paquete->id;
            $perfil->background_id=$background->id;
            $perfil->wifienabled=$wifi;
            if($wifi==1)
            {
                $perfil->ssid=$request->ssid;
                $perfil->password=$request->password;
                $perfil->wificanal=$request->wificanal;
            }
            $perfil->save();
            
            return $perfil;
        }
    }
    
    public static function actualizar($request)
    {
        $perfil= PerfilInstalacion::where('id', $request->id)->first();
        $error=false;
      
        if(empty($perfil))
             return \App\Exceptions\Handler::messageError("405","El perfil buscado no existe");
        else
        {
             $usuario= \App\User::where('id', $request->user_id)->first();
             
             if(empty($usuario) && !empty($request->user_id))
             {
                  $error=true;
                  return \App\Exceptions\Handler::messageError("405","El usuario pasado no existe.");
             }
             else
             {
                 if(!empty($request->user_id))
                    $perfil->user_id=$usuario->id;
             }
             
             $paquete= \App\PaqueteCanal::where('id', $request->paquetecanal_id)->first();
             
             if(empty($paquete) && !empty($request->paquetecanal_id))
             {
                   $error=true;
                  return \App\Exceptions\Handler::messageError("405","El paquete pasado no existe.");
             }
             else
             {
                 if(!empty($request->paquetecanal_id))
                    $perfil->paquetecanal_id=$paquete->id;
             }
             
             $background= \App\Background::where('id', $request->background_id)->first();
                          
             if(empty($background) && !empty($request->background_id))
             {
                   $error=true;
                  return \App\Exceptions\Handler::messageError("405","El background pasado no existe.");
             }
             else
             {
                 if(!empty($request->background_id))
                    $perfil->background_id=$background->id;
             }
             
             if(!empty($request->nombre))
                 $perfil->nombre=$request->nombre;
             

  
             if(!empty($request->wifienabled))
             {
                 echo "WOO";
                 //Si se desactiva ponemos los parámetros del wifi a null/blanco.
                 
                 if($request->wifienabled==0)
                 {
                     echo "ENTRAMOS";
                     $perfil->wifienabled=0;
                     $perfil->password="";
                     $perfil->wificanal="";
                 }
                 else
                 {
                    if(empty($request->ssid) || empty($request->password) || empty($request->wificanal) )
                    {
                        $errores=true;
                        return \App\Exceptions\Handler::messageError("405","Falta algún parametro de configuración del WIFI:[ssid,password,wificanal]");
                    }
                    else
                    {
                        $perfil->ssid=$request->ssid;
                        $perfil->password=$request->password;
                        $perfil->wificanal=$request->wificanal;
                    }
                 }
             }
             else 
                 echo "VACIO";
             
             //Si no hay errores se guarda el perfil
             if($error==false)
             {
                 $perfil->save();
                 return $perfil;
             }
             
             
        }
    }

}
