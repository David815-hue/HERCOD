<?php

namespace App\Filament\Resources\ProyectoResource\Pages;

use App\Filament\Resources\ProyectoResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\Action;


class ViewProyecto extends ViewRecord
{
    protected static string $resource = ProyectoResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->url(static::getResource()::getUrl())
                ->button()
                ->label('Atras')
                ->color('success'),
        ];
    }
}
