<?php

namespace App\Filament\Resources\EmpleadosResource\Pages;

use App\Filament\Resources\EmpleadosResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Pages\Actions\ButtonAction;

class ViewEmpleados extends ViewRecord
{
    protected static string $resource = EmpleadosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
            ButtonAction::make('Regresar')
                ->label('Regresar al índice')
                ->url($this->getResource()::getUrl('index'))
                ->color('secondary')
                ->icon('heroicon-o-arrow-left'),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $empleado = $this->record;

        $data['persona'] = [
            'DNI' => $empleado->persona->DNI,
            'Nombres' => $empleado->persona->Nombres,
            'Apellidos' => $empleado->persona->Apellidos,
            'Genero' => $empleado->persona->Genero,
            'telefono' => [
                'Telefono' => $empleado->persona->telefono->Telefono
            ],
            'correo' => [
                'Correo' => $empleado->persona->correo->Correo
            ]
        ];

        if ($empleado->departamentoTrabajo) {
            $data['departamentoTrabajo'] = [
                'Dep_Trabajo' => $empleado->departamentoTrabajo->Dep_Trabajo
            ];
        }

        // Agregamos el campo Creado_Por
        $data['Creado_Por'] = $empleado->Creado_Por;
        
        // También incluimos los otros campos laborales para mantener la consistencia
        $data['Cargo'] = $empleado->Cargo;
        $data['Sueldo'] = $empleado->Sueldo;
        $data['Fecha_Ingreso'] = $empleado->Fecha_Ingreso;

        return $data;
    }
}
