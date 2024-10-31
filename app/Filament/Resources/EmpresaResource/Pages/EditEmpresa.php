<?php

namespace App\Filament\Resources\EmpresaResource\Pages;

use App\Filament\Resources\EmpresaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use App\Models\Empresa;
use App\Models\Departamentos;
use App\Models\Municipio;

class EditEmpresa extends EditRecord
{
    protected static string $resource = EmpresaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Obtener la empresa con sus relaciones
        $empresa = $this->record;

        // Estructurar los datos anidados, verificando si las relaciones existen
        $data['RTN'] = $empresa->RTN;
        $data['Nombre_Empresa'] = $empresa->Nombre_Empresa;

        $data['departamento'] = $empresa->departamento ? [
            'ID_Departamento' => $empresa->departamento->ID_Departamento,
            'Nom_Departamento' => $empresa->departamento->Nom_Departamento,
        ] : null; // o asignar valores predeterminados si es necesario

        $data['municipio'] = $empresa->municipio ? [
            'ID_Municipio' => $empresa->municipio->ID_Municipio,
            'Nom_Municipio' => $empresa->municipio->Nom_Municipio,
        ] : null; // o asignar valores predeterminados si es necesario

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Actualizar los campos específicos de la empresa
        $record->update([
            'RTN' => $data['RTN'],
            'Nombre_Empresa' => $data['Nombre_Empresa'],
            'ID_Departamento' => $data['departamento']['ID_Departamento'] ?? null, // Usar null si no hay departamento
            'ID_Municipio' => $data['municipio']['ID_Municipio'] ?? null, // Usar null si no hay municipio
        ]);

        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
