<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Estimaciones extends Model
{
    use HasFactory;

    protected $table = 'TBL_Estimaciones'; //El nombre de la tabla ya creada

    public $timestamps = false; // Esto siempre ira
 
    protected $primaryKey = 'ID_Estimacion'; //Poner la PK

    //Funcion para relaciones.
    public function proyecto() //Relacion hacia proyecto
    {
        return $this->belongsTo(Proyecto::class, 'ID_Proyecto', 'Id_Proyecto');
    }


    protected static function boot()  // Esto es para agregar el Creado por
    {
        parent::boot();

        // Antes de crear un registro guardar  el usuario
        static::creating(function ($estimacion) {
            $estimacion->Creado_Por = Auth::user()->name;
        });
    }

    //Esto son los datos que se van a ingresar
    //Son todos los datos de la tabla solo que sin la PK
    protected $fillable = [
        'Estimacion',
        'Fecha_Estimacion',
        'Fecha_Subsanacion',
        'ID_Proyecto',
    ];


  
}
