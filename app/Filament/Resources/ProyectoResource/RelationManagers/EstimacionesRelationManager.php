<?php

namespace App\Filament\Resources\ProyectoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EstimacionesRelationManager extends RelationManager


{
    protected static string $relationship = 'Estimaciones';

    protected static bool $shouldRegisterNavigation = true;

    public function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\TextInput::make('Estimacion')
                ->required()
                ->maxLength(255),
            Forms\Components\DatePicker::make('Fecha_Estimacion')
                ->required(),
            Forms\Components\DatePicker::make('Fecha_Subsanacion')
                ->nullable(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Estimacion')
            ->columns([
                Tables\Columns\TextColumn::make('Estimacion')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('Fecha_Estimacion')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Fecha_Subsanacion')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('Creado_Por')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('Fecha_Creacion')
                    ->date()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation() // Agrega un diálogo de confirmación
                    ->modalHeading('Eliminar Estimación')
                    ->modalDescription('¿Está seguro que desea eliminar esta estimación? Esta acción no se puede deshacer.')
                    ->modalSubmitActionLabel('Sí, eliminar')
                    ->modalCancelActionLabel('Cancelar'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
