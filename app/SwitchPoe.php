<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SwitchPoe extends Model
{
    protected $table="switchpoes";

    protected $fillable = [
        'nombre','ubicacion','user_acceso','pass_acceso','direccionip'
    ];

    public $timestamps=false;

}
