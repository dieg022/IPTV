<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Canal extends Model
{

    protected $table="canales";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pais','canal','urlcanal','urllogo','acronimopais','precio'
    ];

    public $timestamps = false;


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * Un canal puede pertenecer a muchos paquetes de mucha gente
     */
    public function paquetescanales(){
        return $this->belongsToMany('App\PaqueteCanal', 'canales_paquetes','canal_id');
    }
    
    public function crear($request)
    {
        
        $array=array("pais"=>$request->pais,"canal"=>$request->canal,"urlcanal"=>$request->urlCanal);
       // if(isset($request->canal))
      
            $model = static::query()->create($array);
        
        
        return $model;
    }
    
    public function actualizar($request)
    {

        $array=array("id"=>$request->id ,"pais"=>$request->pais,"canal"=>$request->canal,"urlcanal"=>$request->urlCanal);

            $co=Canal::find($array['id']);
            if($co!=null)
            {
           
            $co->fill($array);
            $co->pais=$request->pais;
            
            DB::table('canales')
            ->where('id', $co->id)
            ->update($array);
            return $co;
            
            }
            else
            {
               return \App\Exceptions\Handler::messageError("400","El recurso buscado no esta disponible, ID.");
            }

    }
    
    public function getCanalesUsuario($idUsuario)
    {
        $s= $this->belongsToMany('App\Canal','canales_users');
    }
    
   

}
