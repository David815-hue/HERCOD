<?php

namespace App\Filament\Pages;

use ShuvroRoy\FilamentSpatieLaravelBackup\Pages\Backups as OriginalBackups;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use ShuvroRoy\FilamentSpatieLaravelBackup\Enums\Option;
use ShuvroRoy\FilamentSpatieLaravelBackup\Jobs\CreateBackupJob;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class Backups extends OriginalBackups
{
  use HasPageShield;
  // Aquí puedes sobrescribir cualquier método
  public function create(string $option = ''): void
  {
    // Personalizar el comportamiento del método create
    $command = "cd " . base_path() . " && php artisan backup:run";
    $command .= !empty($option) ? " --{$option}" : "";

    $output = shell_exec($command);

    // Puedes agregar una notificación para confirmar la ejecución
    Notification::make()
      ->title('Backup realizado con éxito')
      ->success()
      ->send();
  }
}
