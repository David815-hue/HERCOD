<?php
// app/Models/Correo.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Correo extends Model
{
    use LogsActivity;
    protected $table = 'TBL_Correo';
    protected $primaryKey = 'ID_Correo';
    public $timestamps = false;

    protected $fillable = [
        'ID_Persona',
        'ID_Empresa',
        'Correo'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($telefono) {
            activity()->disableLogging();  
        });
    }

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'ID_Persona', 'ID_Persona');
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'ID_Empresa', 'ID_Empresa');
    }

    public function getActivitylogOptions(): LogOptions { return LogOptions::defaults() 
        ->useLogName(logName: 'Cambio Correo')
        ->logOnly(['Correo'])
        ->logOnlyDirty(); 
    }
    
}