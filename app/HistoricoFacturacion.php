<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoricoFacturacion extends Model
{
    protected $table="historico_facturacion";

    protected $fillable = [
        'fecha_facturacion','importe'
    ];

    public $timestamps=false;
}
