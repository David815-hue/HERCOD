<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empresa extends Model
{
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

    public function telefono(): HasOne
    {
        return $this->hasOne(Telefono::class, 'ID_Persona', 'ID_Persona');
    }

    public function correo(): HasOne
    {
        return $this->hasOne(Correo::class, 'ID_Persona', 'ID_Persona');
    }

    public function direcciones(): HasMany
    {
        return $this->hasMany(Direcciones::class, 'ID_Empresa', 'ID_Empresa');
    }

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamentos::class, 'ID_Departamento', 'ID_Departamento_trabajo');
    }

    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class, 'ID_Municipio', 'ID_Municipio');
    }
    protected static function boot()  // Esto es para agregar el Creado por
    {
        parent::boot();

        // Antes de crear un registro guardar  el usuario
        static::creating(function ($empresa) {
            $empresa->Creado_Por = Auth::user()->name;
        });
    }
}

