<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Estimaciones extends Model
{
    use HasFactory;

    protected $table = 'TBL_Estimaciones';

    public $timestamps = false;

    protected $primaryKey = 'ID_Estimacion';

    public function proyecto() //Relacion hacia proyecto
    {
        return $this->belongsTo(Proyecto::class, 'ID_Proyecto', 'Id_Proyecto');
    }

    protected static function boot()
    {
        parent::boot();

        // Antes de crear un registro registrar el usuario
        static::creating(function ($estimacion) {
            $estimacion->Creado_Por = Auth::user()->name;
        });
    }

    protected $fillable = [
        'Estimacion',
        'Fecha_Estimacion',
        'Fecha_Subsanacion',
        'ID_Proyecto',
    ];


  
}
