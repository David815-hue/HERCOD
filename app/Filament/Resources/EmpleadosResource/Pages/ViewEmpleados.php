<?php
namespace App\Filament\Resources\EmpleadosResource\Pages;

use App\Filament\Resources\EmpleadosResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEmpleados extends ViewRecord
{
    protected static string $resource = EmpleadosResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    protected function getViewData(): array
    {
        $this->record->load(['persona', 'persona.telefono', 'persona.correo', 'departamentoTrabajo']);
    
        return [
            'empleado' => $this->record,
            'persona' => $this->record->persona,
            'telefono' => $this->record->persona->telefono,
            'correo' => $this->record->persona->correo,
            'departamentoTrabajo' => $this->record->departamentoTrabajo,
        ];
    }
}