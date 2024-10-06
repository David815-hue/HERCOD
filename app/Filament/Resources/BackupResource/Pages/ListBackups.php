<?php

namespace App\Filament\Resources\BackupResource\Pages;

use App\Filament\Resources\BackupResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action; 
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Notification;
use App\Models\Backup;


class ListBackups extends ListRecords
{
    protected static string $resource = BackupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('createBackup')
                ->label('Crear Respaldo')
                ->color('success')
                ->action('crearBackup') 
                ->requiresConfirmation()
                ->icon('heroicon-o-document'),
        ];
    }

    public function crearBackup()
    {
        try {
            Artisan::call('db:backup');
            Notification::make()
                ->title('Respaldo creado')
                ->success()
                ->send();
            //Log::info('Respaldo creado exitosamente');

            $fecha = now()->format('Y_m_d');

            $nombreBackup = 'backup_' . $fecha;

            $nuevoBackup = Backup::create([
                'nombre' => $nombreBackup,
                'tamanio' => '20 MB',
                //'fecha' => now(), 
                'creado_por' => auth()->user()->name,
            ]);

            $this->emit('refresh');// para regrescar la pagina
        } catch (\Exception $e) {
            /*Notification::make()
                ->title('Error al crear respaldo')
                ->body($e->getMessage())
                ->danger()
                ->send();*/
            //Log::error('Error creando respaldo: ' . $e->getMessage());
        }
    }
}