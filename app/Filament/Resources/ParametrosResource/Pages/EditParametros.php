<?php

namespace App\Filament\Resources\ParametrosResource\Pages;

use App\Filament\Resources\ParametrosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditParametros extends EditRecord
{
    protected static string $resource = ParametrosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
