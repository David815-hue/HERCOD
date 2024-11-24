<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Proyecto extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'TBL_Proyectos';

    public $timestamps = false;

    protected $primaryKey = 'ID_Proyecto';

    protected $casts = [

        'Monto_Final' => 'float',
        'Monto_Contractual' => 'float',
        'Anticipo' => 'float',
    ];



    protected $fillable = [
        'NumeroContrato',
        'NumeroLicitacion',
        'Nombre_Proyecto',
        'Descripcion',
        'Anticipo',
        'Estado',
        'Fecha_FirmaContrato',
        'Fecha_OrdenInicio',
        'Monto_Contractual',
        'Monto_Final',
        'Fecha_Fin',
        'Direccion',
        'Fecha_Creacion',
        'ID_Empresa',
        'ID_Municipio',
        'Encargado',
        'Creado_Por',
        'Modificado_Por',
        'Fecha_Modificacion',
    ];
    //Estp es para agregar el creado_por
    protected static function boot()
    {
        parent::boot();

        // Antes de crear un registro
        static::creating(function ($proyecto) {
            $proyecto->Creado_Por = Auth::user()->username;
            $proyecto->Fecha_Creacion = now();

        });

        //Antes de actualizar
        static::updating(function ($proyecto) {
            $proyecto->Modificado_Por = Auth::user()->username;
            $proyecto->Fecha_Modificacion = now();
        });

        static::deleting(function ($proyecto) {
            $proyecto->historialMontos()->delete(); // Elimina los registros en tbl_hist_monto
            $proyecto->Estimaciones()->delete();
        });


    }

    public function historialMontos() //Relacion hacia HistorialMonto
    {
        return $this->hasMany(HistMonto::class, 'ID_Proyecto', 'ID_Proyecto');
    }

    public function Estimaciones() //Relacion hacia HistorialMonto
    {
        return $this->hasMany(Estimaciones::class, 'ID_Proyecto', 'ID_Proyecto');
    }

    public function Tarea() //Relacion hacia HistorialMonto
    {
        return $this->hasMany(Tarea::class, 'ID_Proyecto', 'ID_Proyecto');
    }



    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class, 'ID_Municipio', 'ID_Municipio');
    }


    public function persona()
    {
        return $this->belongsTo(Persona::class, 'Encargado', 'ID_Persona');
    }

    public function empresas()
    {
        return $this->hasMany(Empresa::class, 'ID_Empresa', 'ID_Empresa');
    }

    //ACCESOR
    public function getNombreEncargadoAttribute()
    {
        $persona = $this->persona; //Usando la relaciOn existente con Persona
        return $persona ? "{$persona->Nombres} {$persona->Apellidos}" : 'Desconocido';
    }

    public function getNombresEmpresasAttribute()
    {
        $empresas = $this->empresas;
        if ($empresas->isEmpty()) {
            return 'Sin empresas asociadas';
        }

        return $empresas->pluck('Nombre_Empresa')->join(', ');
    }




    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'Nombre_Proyecto',
                'Descripcion',
                'Anticipo',
                'Estado',
                'Fecha_FirmaContrato',
                'Fecha_OrdenInicio',
                'Monto_Contractual',
                'Monto_Final',
                'Fecha_Fin',
                'Direccion',
                'Fecha_Creacion',
                'NombresEmpresas',
                'ID_Municipio',
                'NombreEncargado', 
                'Creado_Por',
                'Modificado_Por',
                'Fecha_Modificacion',
            ])
            ->useLogName('Actividad Proyecto')
            ->logOnlyDirty();
    }

}
