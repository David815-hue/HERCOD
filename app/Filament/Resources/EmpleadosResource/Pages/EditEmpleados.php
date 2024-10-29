<?php

namespace App\Filament\Resources\EmpleadosResource\Pages;

use App\Filament\Resources\EmpleadosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model; 
use App\Models\Persona;

class EditEmpleados extends EditRecord
{
    protected static string $resource = EmpleadosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function fillForm(): void
    {
        $this->record->load(['persona', 'departamentoTrabajo']);
    
        $this->form->fill([
            'persona.DNI' => $this->record->persona->DNI,
            'persona.Nombres' => $this->record->persona->Nombres,
            'persona.Apellidos' => $this->record->persona->Apellidos,
            'persona.Genero' => $this->record->persona->Genero,
            'persona.telefono.Telefono' => $this->record->persona->telefono->Telefono ?? '',
            'persona.correo.Correo' => $this->record->persona->correo->Correo ?? '',
            'Cargo' => $this->record->Cargo,
            'departamentoTrabajo.Dep_Trabajo' => $this->record->departamentoTrabajo->Dep_Trabajo,
            'Sueldo' => $this->record->Sueldo,
            'Fecha_Ingreso' => $this->record->Fecha_Ingreso,
        ]);
    }

    protected function mutateRecordDataUsing(array $data): array
    {
        // Validación del DNI
        if (isset($data['persona']['DNI']) && $data['persona']['DNI'] !== $this->record->persona->DNI) {
            if (Persona::where('DNI', $data['persona']['DNI'])->exists()) {
                throw new \Exception('El DNI ya ha sido registrado.'); 
            }
        }

        // Agregar user_id al array de datos
        $data['user_id'] = auth()->id();

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
            'Fecha_Ingreso' => $data['Fecha_Ingreso'],
            'user_id' => $data['user_id'], // Guardar el user_id
        ]);

        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
