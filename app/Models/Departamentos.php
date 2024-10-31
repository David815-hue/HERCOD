<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departamentos extends Model
{
    protected $table = 'TBL_Departamento';
    protected $primaryKey = 'ID_Departamento';
    public $timestamps = false;

    protected $fillable = [
        'Nom_Departamento',
    ];

    public function municipios(): HasMany
    {
        return $this->hasMany(Municipio::class, 'ID_Departamento', 'ID_Departamento');
    }
}