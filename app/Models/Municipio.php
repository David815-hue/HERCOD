<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Municipio extends Model
{
    protected $table = 'TBL_Municipio';
    protected $primaryKey = 'ID_Municipio';
    public $timestamps = false;

    protected $fillable = [
        'Nom_Municipio',
        'ID_Departamento',
    ];

    public function direcciones(): HasMany
    {
        return $this->hasMany(Direcciones::class, 'ID_Municipio', 'ID_Municipio');
    }
    
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamentos::class, 'ID_Departamento', 'ID_Departamento');
    }
}
