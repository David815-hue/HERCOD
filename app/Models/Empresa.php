<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;


class Empresa extends Model
{
    use LogsActivity;
    protected $table = 'TBL_Empresa';
    protected $primaryKey = 'ID_Empresa';
    public $timestamps = false;

    protected $fillable = [
        'RTN',
        'Nombre_Empresa',
        'Creado_Por',
        'Fecha_Creacion',
        'ID_Departamento',
        'ID_Municipio',
    ];

   
 
    public function telefono()
{
    return $this->hasOne(Telefono::class, 'ID_Empresa', 'ID_Empresa');
}

    public function correo()
    {
        return $this->hasOne(Correo::class, 'ID_Empresa', 'ID_Empresa');
    }


   /* public function direcciones(): HasMany
    {
        return $this->hasMany(Direcciones::class, 'ID_Empresa', 'ID_Empresa');
    } */


    public function direcciones(): BelongsTo
    {
        return $this->belongsTo(Direcciones::class, 'ID_Empresa', 'ID_Empresa');
    }


    /*
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamentos::class, 'ID_Departamento', 'ID_Departamento_trabajo');
    } */

    /*public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class, 'ID_Municipio', 'ID_Municipio');
    }*/

    protected static function boot()  // Esto es para agregar el Creado por
    {
        parent::boot();

        // Antes de crear un registro guardar  el usuario
        static::creating(function ($empresa) {
            $empresa->Creado_Por = Auth::user()->username;
            $empresa->Fecha_Creacion = now();
        });
    }

    public function proyecto() //Relacion hacia proyecto
    {
        return $this->belongsTo(Proyecto::class, 'ID_Empresa', 'ID_Empresa');
    }

    public function getActivitylogOptions(): LogOptions { return LogOptions::defaults() 
        ->logAll()
        ->useLogName('Actividad Empresa')
        ->logOnlyDirty();
    }
}



