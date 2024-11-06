<?php

namespace App\Filament\Resources\EmpresaResource\Pages;

use App\Filament\Resources\EmpresaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Pages\Actions\ButtonAction;

class ViewEmpresa extends ViewRecord
{
    protected static string $resource = EmpresaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
            ButtonAction::make('Regresar')
                ->label('Regresar al índice')
                ->url($this->getResource()::getUrl('index'))
                ->color('success')
                ->icon('heroicon-o-arrow-left'),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
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
                    'Nom_Municipio' => $empresa->direcciones->municipio->Nom_Municipio ?? null,
                    'departamento' => [
                        'Nom_Departamento' => $empresa->direcciones->municipio->departamento->Nom_Departamento ?? null,
                    ],
                ],
            ],
        ];

        // Agregar otros campos específicos de la empresa, si existen
        if ($empresa->Creado_Por) {
            $data['Creado_Por'] = $empresa->Creado_Por;
        }

        return $data;
    }
}