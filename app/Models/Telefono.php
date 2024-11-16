<?php
// app/Models/Telefono.php
namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Telefono extends Model
{
    protected $table = 'TBL_Telefono';
    protected $primaryKey = 'ID_Telefono';
    public $timestamps = false;

    protected $fillable = [
        'ID_Persona',
        'ID_Empresa',
        'Telefono'
    ];

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'ID_Persona', 'ID_Persona');
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'ID_Empresa', 'ID_Empresa');
    }

    public function getActivitylogOptions(): LogOptions { return LogOptions::defaults() 
        ->logAll()
        ->useLogName('Actividad');
    }
    
}