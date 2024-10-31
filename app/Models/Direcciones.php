<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Direcciones extends Model
{
    protected $table = 'TBL_Direcciones';
    protected $primaryKey = 'ID_Direccion';
    public $timestamps = false;

    protected $fillable = [
        'ID_Empresa',
        'Nom_Direccion',
        'Tip_Direccion',       
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
}