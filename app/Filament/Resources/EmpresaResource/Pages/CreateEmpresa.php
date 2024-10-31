<?php

namespace App\Filament\Resources\EmpresaResource\Pages;

use App\Filament\Resources\EmpresaResource;
use App\Models\Direcciones;
use App\Models\Municipio;
use App\Models\Departamentos;
use App\Models\Empresa; // Asegúrate de que esta línea esté presente

use Filament\Resources\Pages\CreateRecord;

class CreateEmpresa extends CreateRecord
{
    protected static string $resource = EmpresaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Crear la empresa y obtener el ID
        $empresa = Empresa::create([
            'RTN' => $data['RTN'],
            'Nombre_Empresa' => $data['Nombre_Empresa'],
            'Fecha_Creacion' => $data['Fecha_Creacion'],
        ]);
    
        // Asegúrate de que $data contenga el ID de la empresa
        $data['ID_Empresa'] = $empresa->ID_Empresa;
    
        // Crear el departamento
        $departamento = Departamentos::create([
            'Nom_Departamento' => $data['direcciones']['municipio']['departamento']['Nom_Departamento'],
        ]);
    
        // Crear el municipio
        $municipio = Municipio::create([
            'Nom_Municipio' => $data['direcciones']['municipio']['Nom_Municipio'],
            'ID_Departamento' => $departamento->ID_Departamento,
        ]);
    
        // Crear la dirección, enlazando con el ID de la empresa y el ID del municipio
        $direccion = Direcciones::create([
            'ID_Empresa' => $data['ID_Empresa'], // Ahora existe en $data
            'Nom_Direccion' => $data['direcciones']['Nom_Direccion'],
            'Tip_Direccion' => $data['direcciones']['Tip_Direccion'],
            'Descripcion' => $data['direcciones']['Descripcion'],
            'ID_Municipio' => $municipio->ID_Municipio,
        ]);
    
        return $data;
    }
    
}
