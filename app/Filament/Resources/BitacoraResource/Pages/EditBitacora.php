<?php

namespace App\Filament\Resources\BitacoraResource\Pages;

use App\Filament\Resources\BitacoraResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBitacora extends EditRecord
{
    protected static string $resource = BitacoraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
