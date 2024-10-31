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
                ->color('secondary')
                ->icon('heroicon-o-arrow-left'),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $empresa = $this->record;

        $data['RTN'] = $empresa->RTN;
        $data['Nombre_Empresa'] = $empresa->Nombre_Empresa;
        $data['ID_Departamento'] = $empresa->ID_Departamento;
        $data['ID_Municipio'] = $empresa->ID_Municipio;

        return $data;
    }
}