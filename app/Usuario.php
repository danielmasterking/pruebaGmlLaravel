<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuario';
    public $timestamps = false;
    protected $fillable = ['nombres','apellidos','pais','email','cedula','direccion','celular','categoria_id'];


    /*public function categorias()
    {
        return $this->hasMany(Categoria::class);
    }*/

    public function categorias() {
        return $this->hasOne('App\Categoria', 'id', 'categoria_id');
    }
}
