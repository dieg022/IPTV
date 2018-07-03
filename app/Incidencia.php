<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    protected $table="historico_iptvs";

    protected $fillable = [
        'fecha_apertura_incidencia','fecha_cierre_incidencia','iptv_id','problema','resolucion','tipo','urlfoto','estado','user_id'
    ];

    public $timestamps=false;
}
