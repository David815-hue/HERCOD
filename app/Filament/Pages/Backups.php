<?php

namespace App\Filament\Pages;

use ShuvroRoy\FilamentSpatieLaravelBackup\Pages\Backups as OriginalBackups;
use Filament\Notifications\Notification;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class Backups extends OriginalBackups
{
  use HasPageShield;

  public function create(string $option = ''): void
  {
    $command = "cd " . base_path() . " && php artisan backup:run";
    $command .= !empty($option) ? " --{$option}" : "";

    // Ejecutar el comando
    $output = shell_exec($command);

    // Registra la actividad en el Activity Log
    activity('Actividad Backup') 
      ->causedBy(auth()->user()) 
      ->event('created') 
      ->withProperties([
        'tipo_accion' => 'Crear Backup',
        'opcion' => $option ?: 'Sin opciones',
      ])
      ->log('Se ha creado un nuevo backup exitosamente.'); 
 


    // Notificar al usuario
    Notification::make()
      ->title('Backup realizado con éxito')
      ->success()
      ->send();
  }
}
