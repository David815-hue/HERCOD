<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class DepartamentoTrabajo extends Model
{

    protected $table = 'TBL_Departamento_Trabajo'; // Nombre correcto de la tabla
    protected $primaryKey = 'ID_Departamento_trabajo';
    public $timestamps = false;

    protected $fillable = [
        'Dep_Trabajo', // Añade tu propiedad aquí
        // Otras propiedades que deseas permitir
    ];


    public function empleados()
    {
        return $this->hasMany(Empleados::class, 'ID_Departamento_trabajo', 'ID_Departamento_trabajo');
    }

}