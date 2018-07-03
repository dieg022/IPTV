<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Cliente extends Model
{

    protected $table="clientes";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cif','user_id','nombrecompleto','direccion','poblacion',
    ];

    public $timestamps = true;


    /**
     * devuelve el usuario dueño de este cliente
     */
    public function usuario(){
        //return $this->belongsTo('App\User');
        return $this->hasOne('App\User');
    }
    
    public  function buscarPorId($id)
    {
         $cliente=Cliente::where('id', $id)->first();
         if(empty($cliente))
         {
             $cliente=Cliente::where('cif', $id)->first();
             if(empty($cliente))
                 $cliente=new Cliente();
             
         }
         return $cliente;
       // return $this->find($id);
    }
    /**
     * Devuelve los iptvs asociados a este cliente, indico una serie de atributos en la tabla pivote
     */
   
    public function iptvs(){
        return $this->belongsToMany('App\Iptv', 'clientes_iptvs')
            ->withPivot('paquetecanal_id','background_id','wifienabled','ssid','password','wificanal','ipactual','flagwifiact','flagbackgroundact','flagpaquetecanalact','switchpoe_id','switchpoepuerto','observaciones');
    }

    public function crear(array $attributes=[])
    {
        return static::query()->create($attributes);
    }
    
    public function actualizar(array $attributes=[])
    {
        //Comprobamos que el usuario nos envía el id;
        if(!empty($attributes['id']))
        {
           $co= Cliente::find($attributes['id']);
           
           //Si el cliente existe se modifica
           if($co!=null)
           {
               
            DB::table('clientes')
            ->where('id', $co->id)
            ->update($attributes);
            return $attributes;
           }
           else
               return \App\Exceptions\Handler::messageError("400","El cliente con este id no existe.");
           
        }
        else
          return \App\Exceptions\Handler::messageError("400","Para actualizar es necesario el ID del cliente.");
          
    }
    
    public function buscarPorDNI($dni)
    {
        if(!Auth::user()->clientes->contains('cif',$dni)) {
            abort(301, '', ['Location' => url('cliente')]);
        }
    }
    /**
     *Devuelve las motos de este user, defino la relacion apuntando directamente a la clase
     */
/*    public function ubicaciones(){

        //return $this->belongsToMany('App\Ubicacion', 'moto_user');
        return $this->belongsToMany('App\Ubicacion');
    }*/
}
