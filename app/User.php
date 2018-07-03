<?php

namespace App;

use Exception;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Hash;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','tipocliente','nombre_completo','cif','direccion','poblacion','intentoslogin','ipwowzalocal','preciopoblacion',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * Override create method, para hacer comprobaciones adicionales
     * @param array $attributes
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    
  
    public static function create(array $attributes = []){

       $name=$attributes['name'];
       $attributes['password']=Hash::make($attributes['password']);
           //chequeo si ya existe el email o el nombre de usuario
        $existe=User::where('name', $name)->first();

        if($existe)
        {
           // throw new Exception("Ya existe un usuario con el name".$name);
            return \App\Exceptions\Handler::messageError("400","El usuario $name ya existe.");
        }
        else
        {
  
        $model = static::query()->create($attributes);
        return $model;
        }


    }
    
  
    public static function actualizar(array $attributes=[])
    {
     
        
        $userObject=User::find($attributes['id']);
        $attributes['password']=Hash::make($attributes['password']);
        
            if($userObject!=null)
            {
           
            $existe=User::where('name', $attributes['name'])->first();
                if(!$existe)
                {
                    $userObject->fill($attributes);
                    
                    $userObject->save();
                    
                    /*DB::table('users')
                    ->where('id', $userObject->id)
                    ->update($attributes);*/
                    
                    return $userObject;
                }
                else
                   return \App\Exceptions\Handler::messageError("400","El name pasado ya existe en el sistema.");
            }
            else
            {
               return \App\Exceptions\Handler::messageError("400","El recurso buscado no esta disponible, ID.");
            }
        
    }


    /**
     * @param $email
     * @throws Exception
     */
    public function getPoblacion(){

        return $this->poblacion;

    }


    /**
     * @param $email
     * @throws Exception
     */
    public function setEmail($email){

        //chequeo si ya existe el email,si no, lo asigno
        $existe=User::where('email', $email)->first();

        if($existe){
            throw new Exception("Ya existe un usuario con email $email");
        }
        else
            $this->email=$email;

    }

    /**
     * @param $name
     * @throws Exception
     */
    public function setName($name1){

        //chequeo si ya existe el email,si no, lo asigno
        $existe=User::where('name', $name1)->first();

        if($existe){
            throw new Exception("Ya existe un usuario con ese nombre $name1");
        }
        else
            $this->name=$name1;

    }
    
    public function getCanalesUsuario($idUsuario)
    {
        $canalObject=new \App\Canal();
        return $canalObject->getCanalesUsuario($idUsuario);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * Devuelve los clients asociados a este usuarios
     */
    public function clientes(){
       
        return $this->hasMany('App\Cliente');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function backgrounds(){

        return $this->hasMany('App\Background');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function perfilesinstalacion(){

        return $this->hasMany('App\PerfilInstalacion');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * Devuelve los iptvs asociados a este usuario, todos!! stock e instalados
     * IMPORTANTE, PARA PODER USAR EL ATACH Y DETACH SE TIENE QUE LLAMAR ASI!!!
     */
    public function iptvs(){

        //return $this->belongsToMany('App\Iptv', 'user_iptvs');
        return $this->hasMany('App\Iptv');
        //return $this->hasMany('App\Iptv', 'user_iptvs','user_id','iptv_id');
        //return $this->belongsToMany('App\Ubicacion');
    }


    /**Devuelve los iptvs en stock para este cliente
     * @return mixed
     */
    public function iptvsStock()
    {

            if($this->tipocliente=='ALMACEN')
                return Iptv::all()->where('estado','STOCK');
            
            else
                return $this->iptvs->where('estado','STOCK');
    }

    /**
     * Devuelve los iptvs en estado Retirado
     * @return static
     */
    public function iptvsRetirados()
    {
        if($this->tipocliente=='ALMACEN')
            return Iptv::all()->where('estado','RETIRADO');
        else
            return $this->iptvs->where('estado','RETIRADO');
    }

    /**Devuelve los iptvs en RMA para este cliente
     * @return mixed
     */
    public function iptvsRMA(){

        return $this->iptvs->where('estado','RMA');
    }


    /**
     * Devuelve los iptvs instalados para este usuario, es decir, de sus clientes
     * los iptvs que tienen cada uno
     * @return \Illuminate\Support\Collection
     */
    public function iptvsInstalados(){

        //creo una collection vacia
        $collection_obj = collect();

        //obtengo una coleccion de clientes de este usuario
        $clientes = $this->clientes;
        $i=0;
        //para cada cliente obtengo sus iptvs instalados
        foreach($clientes as $cliente) {
            $iptvs = $cliente->iptvs;

            //cojo cada iptv y lo meto en la collection vacia
            foreach ($iptvs as $iptv) {
                $collection_obj->put($i, $iptv);
                $i++;
            }
        }
        //dd($clientes);
        //dd($collection_obj);

        return $collection_obj;
    }

    /**
     * Devuelve los canales de este usuario
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function canals(){

        return $this->belongsToMany('App\Canal', 'canales_users');
        //return $this->belongsToMany('App\Ubicacion');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * Devuelve los paquetescanales asociados a este usuarios
     */
    public function paquetescanales(){
         
        return $this->hasMany('App\PaqueteCanal');
    }



    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * Devuelve los switchpoes asociados a este usuario
     */
    public function switchpoes(){

        return $this->hasMany('App\SwitchPoe');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * Devuelve los clients asociados a este usuarios
     */
    public function incidencias(){

        return $this->hasMany('App\Incidencia');
    }

    /**
     * Devuelve el tipo de cliente de un usuario
     * @return string
     */
    public function tipoClienteDestino(){
        if($this->tipocliente=='hotel')
            return "habitacion";
        else
            return "cliente";
    }


    /*facturacion*/
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * Devuelve los clients asociados a este usuarios
     */
    public function getFacturacionProductor(){

        return $this->hasMany('App\HistoricoFacturacion');
    }

}
