<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistMonto extends Model
{

    use LogsActivity;

    protected $table = 'TBL_Hist_Monto';

    protected $primaryKey = 'ID_Historial'; //Poner la PK

    public $timestamps = false; // Esto siempre ira


    protected $fillable = [
        'Monto_Anterior',
        'Monto_Nuevo',
        'Fecha_Modificacion',
        'Modificado_Por',
        'ID_Proyecto',
        'TipoMonto',
    ];

    public function proyecto() //Relacion hacia proyecto
    {
        return $this->belongsTo(Proyecto::class, 'ID_Proyecto', 'ID_Proyecto');
    }

    public function getActivitylogOptions(): LogOptions { return LogOptions::defaults() 
        ->logAll()
        ->useLogName('Actividad');
    }
    
}
