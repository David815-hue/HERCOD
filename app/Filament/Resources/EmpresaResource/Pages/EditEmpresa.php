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
            Actions\DeleteAction::make()
                ->before(function (Model $record) {
                    // Eliminar la dirección específica antes de eliminar la empresa
                    $direccion = $record->direcciones()->first(); // Aquí selecciona la dirección específica que quieres eliminar
                    if ($direccion) {
                        $direccion->delete();
                    }
                }),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Obtener la empresa con sus relaciones
        $empresa = $this->record;

        $data = [
            'RTN' => $empresa->RTN,
            'Nombre_Empresa' => $empresa->Nombre_Empresa,
            'Fecha_Creacion' => $empresa->Fecha_Creacion,
            'direcciones' => [
                'Nom_Direccion' => $empresa->direcciones->Nom_Direccion,
                'Tip_Direccion' => $empresa->direcciones->Tip_Direccion,
                'Descripcion' => $empresa->direcciones->Descripcion,
                'municipio' => [
                    'departamento' => [
                        'Nom_Departamento' => $empresa->direcciones->municipio->departamento->Nom_Departamento ?? null,
                    ],
                    'Nom_Municipio' => $empresa->direcciones->municipio->Nom_Municipio ?? null,
                ],
            ],
        ];

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Actualiza los campos específicos de la empresa
        $record->update([
            'RTN' => $data['RTN'],
            'Nombre_Empresa' => $data['Nombre_Empresa'],
        ]);

        // Obtener o crear el departamento
        $departamentoName = $data['direcciones']['municipio']['departamento']['Nom_Departamento'] ?? null;
        $departamento = Departamentos::firstOrCreate(['Nom_Departamento' => $departamentoName]);

        // Obtener o crear el municipio
        $municipioName = $data['direcciones']['municipio']['Nom_Municipio'] ?? null;
        $municipio = Municipio::firstOrCreate([
            'Nom_Municipio' => $municipioName,
            'ID_Departamento' => $departamento->ID_Departamento,
        ]);

        // Actualizar o crear la dirección asociada
        $direccionData = [
            'Nom_Direccion' => $data['direcciones']['Nom_Direccion'],
            'Tip_Direccion' => $data['direcciones']['Tip_Direccion'],
            'Descripcion' => $data['direcciones']['Descripcion'],
            'ID_Municipio' => $municipio->ID_Municipio, // Aquí utilizas el ID del municipio creado
        ];

        // Actualiza la dirección asociada (suponiendo que ya tienes una dirección relacionada con la empresa)
        // Si la dirección no existe, necesitarás crearla.
        $record->direcciones()->updateOrCreate(
            ['ID_Empresa' => $record->ID_Empresa], // Cambia esto según tu lógica
            $direccionData
        );

        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
