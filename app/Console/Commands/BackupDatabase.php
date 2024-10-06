<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup';

    protected $description = 'Crear un Respaldo de la base de datos';

    public function handle()
    {
        $filename = 'backups/backup_' . Carbon::now()->format('Y_m_d_His') . '.sql';
        $command = 'mysqldump --user=' . env('DB_USERNAME') .
            ' --password=' . env('DB_PASSWORD') .
            ' --host=' . env('DB_HOST') .
            ' ' . env('DB_DATABASE') . ' > ' . storage_path('app/' . $filename);

        $returnVar = NULL;
        $output = NULL;
        exec($command, $output, $returnVar);

        if ($returnVar == 0) {
            $this->info('Respaldo completado: ' . $filename);
        } else {
            $this->error('Respaldo fallo!');
        }
    }
}
