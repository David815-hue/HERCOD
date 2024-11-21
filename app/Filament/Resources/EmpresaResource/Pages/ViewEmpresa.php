<?php

namespace App\Filament\Resources\EmpresaResource\Pages;

use App\Filament\Resources\EmpresaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\Action;

class ViewEmpresa extends ViewRecord
{
    protected static string $resource = EmpresaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make()
                ->before(function ($record) {
                    // Eliminar registros relacionados
                    $record->telefono()->delete();
                    $record->correo()->delete();
                    $record->direcciones()->delete();
                }),
            Action::make('back')
                ->label('Regresar')
                ->url($this->getResource()::getUrl('index'))
                ->color('secondary')
                ->icon('heroicon-o-arrow-left'),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $empresa = $this->record;

        // Verificar que las relaciones existan antes de acceder a ellas
        $telefono = $empresa->telefono;
        $correo = $empresa->correo;
        $direcciones = $empresa->direcciones;
        
        
        $data = [
            'ID_Empresa' => $empresa->ID_Empresa,
            'RTN' => $empresa->RTN,
            'Nombre_Empresa' => $empresa->Nombre_Empresa,

            'telefono' => [
                'Telefono' => $telefono ? $telefono->Telefono : null
            ],
            'correo' => [
                'Correo' => $correo ? $correo->Correo : null
            ],
        ];


        // Agregar datos de dirección si existe
        if ($direcciones) {
            $data['direcciones'] = [
                'Nom_Direccion' => $direcciones->Nom_Direccion,
                'Tip_Direccion' => $direcciones->Tip_Direccion,
                'Descripcion' => $direcciones->Descripcion,
            ];
            // Agregar datos de municipio si existe
            if ($direcciones->municipio) {
                $data['direcciones']['municipio'] = [
                    'Nom_Municipio' => $direcciones->municipio->Nom_Municipio,
                ];

                // Agregar datos de departamento si existe
                if ($direcciones->municipio->departamento) {
                    $data['direcciones']['municipio']['departamento'] = [
                        'Nom_Departamento' => $direcciones->municipio->departamento->Nom_Departamento,
                    ];
                }
            }
        }

        // Agregar campos adicionales si existen
        if ($empresa->Creado_Por) {
            $data['Creado_Por'] = $empresa->Creado_Por;
        }
        $data['Fecha_Creacion'] = $empresa->Fecha_Creacion;



        return $data;
    }
}