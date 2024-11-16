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
                ->icon('heroicon-o-arrow-left')
                ->color('success'),

                Action::make('refresh')
                ->label('Refrescar')
                ->color('primary')
                ->button()
                ->icon('heroicon-o-arrow-path')
                ->action('refreshPage'),
        ];
    }

    public function refreshPage()
    {
        $this->redirect($this->getResource()::getUrl('view', ['record' => $this->record->getKey()]));
    }

    
}
