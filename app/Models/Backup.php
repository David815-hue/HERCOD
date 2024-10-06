<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class Backup extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'tamanio',
        'fecha',
        'creado_en',
    ];

    public $timestamps = false;
    protected $table = 'TBL_Backup';
    protected $primaryKey = 'ID_Backup';

    public static function getEloquentQuery(): Builder
    {
        return parent::newQuery();
    }


    public static function todosArchivos()
    {
        $files = Storage::disk('local')->files('backups');
        Log::info('Archivos encontrados: ', $files);

        return collect($files)->map(function ($file) {
            return new static([
                'nombre' => basename($file),
                'ruta' => $file,
                'tamanio' => Storage::disk('local')->size($file),
                'creado_en' => Storage::disk('local')->lastModified($file),
            ]);
        });
    }

    public function restoreDatabase()
    {
        $filePath = storage_path('app/' . $this->ruta);
        \Illuminate\Support\Facades\DB::unprepared(file_get_contents($filePath));
    }
}