<?php
namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Direcciones extends Model
{
    use LogsActivity;
    protected $table = 'TBL_Direcciones';
    protected $primaryKey = 'ID_Direccion';
    public $timestamps = false;

    protected $fillable = [
        'ID_Empresa',   
        'Descripcion',
        'ID_Municipio',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'ID_Empresa', 'ID_Empresa');
    }

    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class, 'ID_Municipio', 'ID_Municipio');
    }

    public function getActivitylogOptions(): LogOptions { return LogOptions::defaults() 
        ->logAll()
        ->useLogName('Actividad');
    }
}