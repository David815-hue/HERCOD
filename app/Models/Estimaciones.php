<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Estimaciones extends Model
{
    use HasFactory,LogsActivity;

    protected $table = 'TBL_Estimaciones'; //El nombre de la tabla ya creada

    public $timestamps = false; // Esto siempre ira
 
    protected $primaryKey = 'ID_Estimacion'; //Poner la PK

    protected $casts = [
        'Estimacion' => 'float',
    ];

    public function proyecto() //Relacion hacia proyecto
    {
        return $this->belongsTo(Proyecto::class, 'ID_Proyecto', 'ID_Proyecto');
    }


    protected static function boot()  // Esto es para agregar el Creado por
    {
        parent::boot();


        // Antes de crear un registro guardar  el usuario
        static::creating(function ($estimacion) {
            $estimacion->Creado_Por = Auth::user()->username;
            $estimacion->Fecha_Creacion = now();

        });
    }

    //Esto son los datos que se van a ingresar
    //Son todos los datos de la tabla solo que sin la PK
    protected $fillable = [
        'Estimacion',
        'Fecha_Estimacion',
        'Fecha_Subsanacion',
        'ID_Proyecto',
        'Fecha_Creacion',
        'Descripcion'
    ];

    public function getActivitylogOptions(): LogOptions { return LogOptions::defaults() 
        ->logAll()
        ->useLogName('Actividad Estimaciones')
        ->logOnlyDirty();
    }

  
}
