<?php  

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Empleados extends Model
{
    use HasFactory,LogsActivity;

    protected $table = 'TBL_Empleados';
    protected $primaryKey = 'ID_Empleado';
    public $timestamps = false;

    protected $fillable = [
        'Cargo',
        'Sueldo',
        'Fecha_Ingreso',
        'ID_Persona',
        'ID_Departamento_trabajo',
        'Creado_Por'
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'ID_Persona', 'ID_Persona');
    }
    
    public function departamentoTrabajo()
    {
        return $this->belongsTo(DepartamentoTrabajo::class, 'ID_Departamento_trabajo', 'ID_Departamento_trabajo');
    }

    
    
    protected static function boot()  // Esto es para agregar el Creado por


    {
        parent::boot();

        // Antes de crear un registro guardar  el usuario
        static::creating(function ($empleado) {
            $empleado->Creado_Por = Auth::user()->name;
        });
    }

    public function getActivitylogOptions(): LogOptions { return LogOptions::defaults() 
        ->logAll()
        ->useLogName('Actividad');
    }
}