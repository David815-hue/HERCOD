<?php

namespace App\Filament\Resources\ParametrosResource\Pages;

use App\Filament\Resources\ParametrosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use ArielMejiaDev\FilamentPrintable\Actions\PrintAction;

class ListParametros extends ListRecords
{
    protected static string $resource = ParametrosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Crear'),
            PrintAction::make()->label('Exportar')->icon('heroicon-o-arrow-down-tray')->color('danger'),
        ];
    }
}
