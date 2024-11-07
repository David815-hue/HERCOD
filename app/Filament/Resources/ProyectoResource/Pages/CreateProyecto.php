<?php

namespace App\Filament\Resources\ProyectoResource\Pages;

use App\Filament\Resources\ProyectoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Municipio;
use App\Models\Departamentos;
use App\Models\Empresa;

class CreateProyecto extends CreateRecord
{
    protected static string $resource = ProyectoResource::class;

    protected function afterCreate(): void
    {
        // Obtener el ID del proyecto recién creado
        $proyecto = $this->record;

        // Crear el departamento si no existe
        $departamentoName = $this->data['municipio']['departamento']['Nom_Departamento'];
        $departamento = Departamentos::firstOrCreate(
            ['Nom_Departamento' => $departamentoName]
        );

        // Crear el municipio
        $municipioName = $this->data['municipio']['Nom_Municipio'];
        $municipio = Municipio::firstOrCreate([
            'Nom_Municipio' => $municipioName,
            'ID_Departamento' => $departamento->ID_Departamento,
        ]);

        // Asignar el ID del municipio al proyecto
        $proyecto->update(['ID_Municipio' => $municipio->ID_Municipio]);
    }
}
