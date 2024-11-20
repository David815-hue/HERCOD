<?php
// app/Models/Telefono.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class Telefono extends Model
{

    use LogsActivity;
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
   

    protected static function boot()
{
    parent::boot();

    static::creating(function ($telefono) {
        
        activity()->disableLogging();  
    });
}
    
    public function getActivitylogOptions(): LogOptions { return LogOptions::defaults() 
        ->useLogName(logName: 'Cambio Telefono')
        ->logOnly(['Telefono'])
        ->logOnlyDirty();
    }
     


}



