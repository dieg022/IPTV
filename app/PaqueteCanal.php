<?php

namespace App;

use DB;

use Illuminate\Database\Eloquent\Model;

class PaqueteCanal extends Model
{
    protected $table="paquetescanales";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombrepaquete','user_id',
    ];

    public $timestamps = false;

    /**
     * devuelve el usuario dueño de este paquete de canales
     */
    public function usuario(){
        //return $this->belongsTo('App\User');
        return $this->hasOne('App\User');
    }
    
    function crear(array $attributes=[])
    {
        $existe= PaqueteCanal::where('nombrepaquete', $attributes['nombrepaquete'])->first();
        if($existe==null)
            return static::query()->create($attributes);
        else 
            return \App\Exceptions\Handler::messageError("500","Ya existe un paquete con el nombre dado.");
    }
    
    public static function actualizar(array $attributes=[])
    {
     
        //si el paquete con el id pasado existe
        $paqueteObject= PaqueteCanal::find($attributes['id']);
       
        
            if($paqueteObject!=null)
            {
           
       
                    $paqueteObject->fill($attributes);
                    DB::table('users')
                    ->where('id',$paqueteObject->id)
                    ->update($attributes);
                    return  $paqueteObject;
                
            }
            else
            {
               return \App\Exceptions\Handler::messageError("400","El recurso buscado no esta disponible, ID.");
            }
        
    }
    
    public  function getPaquetesUsuario($idUsuario)
    {
        
                     return DB::table('paquetescanales')
                    ->where('user_id',$idUsuario)->get();

    }
    
    public static function setCanal(array $attributes=[])
    {
     
        for($i=0;$i<count($attributes);$i++)
        {
            
   
            $paqueteObject= PaqueteCanal::find($attributes[$i]['paquetecanal_id']);
            $canalObject=Canal::find($attributes[$i]['canal_id']);
            
            if($paqueteObject!=null && $canalObject!=null)
            {
                    $paqueteObject->fill($attributes);
                    DB::table('canales_paquetes')->insertGetId($attributes[$i]);
            }
            else
            {
               return \App\Exceptions\Handler::messageError("400","El canal o paquete seleccionado no existe.");
            }
        }
        return  $attributes;
       
       
            
        
    }
    
    public static function deleteCanal(array $attributes=[])
    {
         for($i=0;$i<count($attributes);$i++)
        {
            
   
            $paqueteObject= PaqueteCanal::find($attributes[$i]['paquetecanal_id']);
            $canalObject=Canal::find($attributes[$i]['canal_id']);
            
            if($paqueteObject!=null && $canalObject!=null)
            {
                   DB::table('canales_paquetes')
                           ->where('paquetecanal_id', '=',$attributes[$i]['paquetecanal_id'])
                           ->where('canal_id','=',$attributes[$i]['canal_id'])
                           ->delete();
            }
            else
            {
               return \App\Exceptions\Handler::messageError("400","El canal o paquete seleccionado no existe.");
            }
        }
        return  $attributes;
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * Devuelve los canales de este paquete
     */
    public function canales(){
        //return $this->belongsToMany('App\Canal', 'canales_paquetes','paquetecanal_id');
        
        return $this->belongsToMany('App\Canal', 'canales_paquetes','paquetecanal_id')->withPivot('ordencanal');
    }


}
