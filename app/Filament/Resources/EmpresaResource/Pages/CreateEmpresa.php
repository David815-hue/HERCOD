<?php

namespace App\Filament\Resources\EmpresaResource\Pages;

use App\Filament\Resources\EmpresaResource;
use App\Models\Direcciones;
use App\Models\Municipio;
use App\Models\Departamentos;
use App\Models\Empresa;
use App\Models\Telefono;
use Carbon\Carbon;

use App\Models\Correo;
use Filament\Resources\Pages\CreateRecord;

class CreateEmpresa extends CreateRecord
{
    protected static string $resource = EmpresaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Asigna la fecha actual a Fecha_Creacion
        $data['Fecha_Creacion'] = Carbon::now();

        return $data;
    }


    protected function afterCreate(): void
    {
        // Obtener el ID de la empresa recién creada por Filament
        $empresa = $this->record;

        // Crear el registro de teléfono
        Telefono::create([
            'ID_Empresa' => $empresa->ID_Empresa,
            'Telefono' => $this->data['telefono']['Telefono']
        ]);

        // Crear el registro de correo
        Correo::create([
            'ID_Empresa' => $empresa->ID_Empresa,
            'Correo' => $this->data['correo']['Correo']
        ]);

        // Verificar si el departamento ya existe
        $departamento = Departamentos::firstOrCreate(
            ['Nom_Departamento' => $this->data['direcciones']['municipio']['departamento']['Nom_Departamento']]
        );

        // Verificar si el municipio ya existe y está vinculado al departamento correcto
        $municipio = Municipio::firstOrCreate(
            [
                'Nom_Municipio' => $this->data['direcciones']['municipio']['Nom_Municipio'],
                'ID_Departamento' => $departamento->ID_Departamento,
            ]
        );

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
