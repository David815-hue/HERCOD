<?php

namespace App\Filament\Resources\BitacoraResource\Pages;

use App\Filament\Resources\BitacoraResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use ArielMejiaDev\FilamentPrintable\Actions\PrintAction;

class ListBitacoras extends ListRecords
{
    protected static string $resource = BitacoraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
            PrintAction::make()->label('Exportar')->icon('heroicon-o-arrow-down-tray')->color('danger'),
        ];
    }
}
