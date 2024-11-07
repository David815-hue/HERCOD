<?php

namespace App\Filament\Resources\EmpresaResource\Pages;

use App\Filament\Resources\EmpresaResource;
use App\Models\Direcciones;
use App\Models\Municipio;
use App\Models\Departamentos;
use App\Models\Empresa;

use Filament\Resources\Pages\CreateRecord;

class CreateEmpresa extends CreateRecord
{
    protected static string $resource = EmpresaResource::class;

    protected function afterCreate(): void
    {
        // Obtener el ID de la empresa recién creada por Filament
        $empresa = $this->record;

        // Crear el departamento
        $departamento = Departamentos::create([
            'Nom_Departamento' => $this->data['direcciones']['municipio']['departamento']['Nom_Departamento'],
        ]);

        // Crear el municipio
        $municipio = Municipio::create([
            'Nom_Municipio' => $this->data['direcciones']['municipio']['Nom_Municipio'],
            'ID_Departamento' => $departamento->ID_Departamento,
        ]);

        // Crear la dirección, enlazando con el ID de la empresa y el ID del municipio
        Direcciones::create([
            'ID_Empresa' => $empresa->ID_Empresa,
            'Nom_Direccion' => $this->data['direcciones']['Nom_Direccion'],
            'Tip_Direccion' => $this->data['direcciones']['Tip_Direccion'],
            'Descripcion' => $this->data['direcciones']['Descripcion'],
            'ID_Municipio' => $municipio->ID_Municipio,
        ]);
    }
}