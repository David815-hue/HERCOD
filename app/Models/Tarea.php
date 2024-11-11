<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Tarea extends Model
{
    use HasFactory;

    protected $table = 'TBL_Tareas';

    protected $primaryKey = 'ID_Tarea';

    public $timestamps = false; // Esto siempre ira
 

    public function proyecto() //Relacion hacia proyecto
    {
        return $this->belongsTo(Proyecto::class, 'ID_Proyecto', 'ID_Proyecto');
    }

    protected static function boot()
    {
        parent::boot();

        // Asignar el usuario creador antes de crear una tarea
        static::creating(function ($tarea) {
            $tarea->Creado_Por = Auth::user()->username;

            // Marcar el proyecto como "En Progreso" si se agrega una nueva tarea
            $proyecto = $tarea->proyecto;
            if ($proyecto && $proyecto->Estado === 'Completado') {
                $proyecto->Estado = 'En Progreso';
                $proyecto->save();
            }
        });

        static::updating(function ($tarea) {
            // Si el estado se está marcando como verdadero
            if ($tarea->isDirty('Estado') && $tarea->Estado) {
                $tarea->Fecha_Completado = now(); // Llenar con la fecha actual
            }   else {
                // Si se desmarca, borrar la fecha completada
                $tarea->Fecha_Completado = null;
            }
        });

        
    }
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'Responsable', 'ID_Persona');
    }
    protected $fillable = [
        'Descripcion',
        'Fecha_Completado',
        'Fecha_Inicio',
        'ID_Proyecto',
        'Estado',
        'Responsable',
        'Creado_Por'
    ];
    
}
