<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Proyecto extends Model
{
    use HasFactory;

    protected $table = 'tbl_proyecto';

    public $timestamps = false;

    protected $primaryKey = 'Id_Proyecto';



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
            $proyecto->Creado_Por = Auth::user()->name;
        });

        //Antes de actualizar
        static::updating(function ($proyecto) {
            $proyecto->Modificado_Por = Auth::user()->name;
            $proyecto->Fecha_Modificacion = now();
        });

       
    }

    public function historialMontos() //Relacion hacia HistorialMonto
    {
        return $this->hasMany(HistMonto::class, 'ID_Proyecto', 'Id_Proyecto');
    }

    public function Estimaciones() //Relacion hacia HistorialMonto
    {
        return $this->hasMany(Estimaciones::class, 'ID_Proyecto', 'Id_Proyecto');
    }

}
