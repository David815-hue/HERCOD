<?php

namespace App\Filament\Resources\EmpleadosResource\Pages;

use App\Filament\Resources\EmpleadosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model; 
use App\Models\Persona;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; 


class EditEmpleados extends EditRecord
{
    protected static string $resource = EmpleadosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Obtener el empleado con sus relaciones
        $empleado = $this->record;
        
        // Estructurar los datos anidados
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
        
        // Agregar el departamento de trabajo si existe
        if ($empleado->departamentoTrabajo) {
            $data['departamentoTrabajo'] = [
                'Dep_Trabajo' => $empleado->departamentoTrabajo->Dep_Trabajo
            ];
        }

        return $data;
    }

    protected function mutateRecordDataUsing(array $data): array
    {
        // Validación del DNI
        if (isset($data['persona']['DNI']) && $data['persona']['DNI'] !== $this->record->persona->DNI) {
            if (Persona::where('DNI', $data['persona']['DNI'])->exists()) {
                throw new \Exception('El DNI ya ha sido registrado.'); 
            }
        }


        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Actualización de los datos de las relaciones
        $record->persona->update($data['persona']);
        $record->persona->telefono->update(['Telefono' => $data['persona']['telefono']['Telefono']]);
        $record->persona->correo->update(['Correo' => $data['persona']['correo']['Correo']]);
        
        // Actualizar los campos específicos del empleado
        $record->update([
            'Cargo' => $data['Cargo'],
            'Sueldo' => $data['Sueldo'],
        
        ]);

        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
