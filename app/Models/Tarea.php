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

    
}
