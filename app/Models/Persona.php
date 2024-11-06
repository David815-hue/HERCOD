<?php
// app/Models/Persona.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Persona extends Model
{
    protected $table = 'TBL_Persona';
    protected $primaryKey = 'ID_Persona';
    public $timestamps = false;

    protected $fillable = [
        'DNI',
        'Nombres',
        'Apellidos',
        'Genero',
        'Creado_Por',
        'Fecha_Creacion',
        'Estado'

    ];

    public function empleado(): HasOne
    {
        return $this->hasOne(Empleados::class, 'ID_Persona', 'ID_Persona');
    }
    
    public function telefono()
    {
        return $this->hasOne(Telefono::class, 'ID_Persona', 'ID_Persona');
    }
    
    public function correo()
    {
        return $this->hasOne(Correo::class, 'ID_Persona', 'ID_Persona');
    }
    
    
    public function proyecto() //Relacion hacia proyecto
    {
        return $this->belongsTo(Proyecto::class, 'ID_Persona', 'Encargado');
    }

    public function tarea() //Relacion hacia proyecto
    {
        return $this->belongsTo(Tarea::class, 'ID_Persona', 'Responsable');
    }

    protected static function boot()  // Esto es para agregar el Creado por
    {
        parent::boot();

        // Antes de crear un registro guardar  el usuario
        static::creating(function ($estimacion) {
            $estimacion->Creado_Por = Auth::user()->name;
        });
    }


  
}