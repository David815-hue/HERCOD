<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BackupResource\Pages;
use App\Models\Backup;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class BackupResource extends Resource
{
    protected static ?string $model = Backup::class;

    protected static ?string $navigationGroup = 'Seguridad';

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // 
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')->label('Nombre')->default('backup_'),
                TextColumn::make('creado_por')->label('Creado por')->default(auth()->user()->name),
                //TextColumn::make('fecha')->label('Fecha')->default(now()),
                TextColumn::make('tamanio')->label('Tamaño')->default('20 MB'),
            ])
            ->filters([
               //
            ])
            ->actions([
                Tables\Actions\Action::make('restore')
                    ->label('Restaurar')
                    ->action(fn(Backup $record) => $record->restoreDatabase())
                    ->requiresConfirmation()
                    ->color('danger'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // 
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBackups::route('/'),
            //'create' => Pages\CreateBackup::route('/create'),
            //'edit' => Pages\EditBackup::route('/{record}/edit'),
        ];
    }
}
