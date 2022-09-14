<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuario';
    public $timestamps = false;
    protected $fillable = ['nombres','apellidos','pais','email','cedula','direccion','celular','categoria_id'];

    
}
