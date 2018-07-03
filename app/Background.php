<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Concerns;
use Illuminate\Http\UploadedFile;
use Storage;

class Background extends Model
{
    protected $table="backgrounds";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'background','urlbackground',
    ];

    public $timestamps = false;

    /**
     * devuelve el usuario dueño de este background
     */
    public function usuario(){
        //return $this->belongsTo('App\User');
        return $this->hasOne('App\User');
    }
    
    public function crear($request)
    {
        
        $Base64Img = base64_decode($request->background);
        Storage::disk('images')->put($request->urlbackground,base64_decode($request->background));
        
        $background=new Background();
        $background->user_id=$request->user_id;
        $background->background=$request->urlbackground;
        $background->urlbackground=$request->urlbackground;
        $background->save();
        
        return $background;
      

    }
    
      public function actualizar(array $attributes=[])
    {
        if(!empty($attributes['id']))
        {
           $co= Background::find($attributes['id']);
           if($co!=null)
           {
            DB::table('backgrounds')
            ->where('id', $co->id)
            ->update($attributes);
            return $attributes;
           }
           else
               return \App\Exceptions\Handler::messageError("400","El background con este id no existe.");
           
        }
        else
          return \App\Exceptions\Handler::messageError("400","Para actualizar es necesario el ID del background.");
    }
    public  function getBackgroundUsuario($idUsuario)
    {
                    return DB::table('backgrounds')
                    ->where('user_id',$idUsuario)->get();
    }
}
