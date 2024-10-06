<?php

namespace App\Filament\Resources\ObjetosResource\Pages;

use App\Filament\Resources\ObjetosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use ArielMejiaDev\FilamentPrintable\Actions\PrintAction;


class ListObjetos extends ListRecords
{
    protected static string $resource = ObjetosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Crear'),
            PrintAction::make()->label('Exportar')->icon('heroicon-o-arrow-down-tray')->color('danger'),
        ];
    }
}
