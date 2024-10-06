<?php

namespace App\Filament\Resources\ObjetosResource\Pages;

use App\Filament\Resources\ObjetosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditObjetos extends EditRecord
{
    protected static string $resource = ObjetosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
