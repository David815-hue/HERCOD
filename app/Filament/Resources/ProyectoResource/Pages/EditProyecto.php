<?php

namespace App\Filament\Resources\ProyectoResource\Pages;

use App\Filament\Resources\ProyectoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Departamentos;
use App\Models\Municipio;
use Illuminate\Database\Eloquent\Model;



class EditProyecto extends EditRecord
{
    protected static string $resource = ProyectoResource::class;

  

    protected function getActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Atrás')
                ->url($this->getResource()::getUrl('index')) 
                ->icon('heroicon-o-arrow-left')
                ->color('success'),
    
        ];
    }

    protected function getRedirectUrl(): string
    {
    return $this->getResource()::getUrl('index');
    }


    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Obtener el proyecto con sus relaciones
        $proyecto = $this->record;

        $data = [
            'NumeroContrato' => $proyecto->NumeroContrato,
            'NumeroLicitacion' => $proyecto->NumeroLicitacion,
            'Nombre_Proyecto' => $proyecto->Nombre_Proyecto,
            'Descripcion' => $proyecto->Descripcion,
            'Anticipo' => $proyecto->Anticipo,
            'Fecha_FirmaContrato' => $proyecto->Fecha_FirmaContrato,
            'Fecha_OrdenInicio' => $proyecto->Fecha_OrdenInicio,
            'Monto_Contractual' => $proyecto->Monto_Contractual,
            'Monto_Final' => $proyecto->Monto_Final,
            'Estado' => $proyecto->Estado,
            'Fecha_Fin' => $proyecto->Fecha_Fin,
            'Direccion' => $proyecto->Direccion,
            'ID_Empresa' => $proyecto->ID_Empresa,
            'municipio' => [
                'departamento' => [
                    'Nom_Departamento' => $proyecto->municipio->departamento->Nom_Departamento ?? null
                ],
                'Nom_Municipio' => $proyecto->municipio->Nom_Municipio ?? null
            ]
        ];

        return $data;
    }

   
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Actualizar los campos específicos del proyecto
        $record->update([
            'NumeroContrato' => $data['NumeroContrato'],
            'NumeroLicitacion' => $data['NumeroLicitacion'],
            'Nombre_Proyecto' => $data['Nombre_Proyecto'],
            'Descripcion' => $data['Descripcion'],
            'Anticipo' => $data['Anticipo'],
            'Fecha_FirmaContrato' => $data['Fecha_FirmaContrato'],
            'Fecha_OrdenInicio' => $data['Fecha_OrdenInicio'],
            'Monto_Contractual' => $data['Monto_Contractual'],
            'Monto_Final' => $data['Monto_Final'],
            'Estado' => $data['Estado'],
            'Fecha_Fin' => $data['Fecha_Fin'],
            'Direccion' => $data['Direccion'],
            'ID_Empresa' => $data['ID_Empresa'],
        ]);

        // Actualizar el municipio y departamento si es necesario
        $departamentoName = $data['municipio']['departamento']['Nom_Departamento'];
        $departamento = Departamentos::firstOrCreate(
            ['Nom_Departamento' => $departamentoName]
        );

        // Actualizar o crear el municipio
        $municipioName = $data['municipio']['Nom_Municipio'];
        $municipio = Municipio::firstOrCreate([
            'Nom_Municipio' => $municipioName,
            'ID_Departamento' => $departamento->ID_Departamento,
        ]);

        // Asignar el ID del municipio al proyecto
        $record->update(['ID_Municipio' => $municipio->ID_Municipio]);

        return $record;
    }

    
}
