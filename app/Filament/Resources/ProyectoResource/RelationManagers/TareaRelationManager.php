<?php

namespace App\Filament\Resources\ProyectoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\ToggleColumn;



class TareaRelationManager extends RelationManager
{
    protected static string $relationship = 'Tarea';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('Descripcion')
                    ->label('Tarea')
                    ->required(),
                Forms\Components\DatePicker::make('Fecha_Inicio')
                    ->label('Fecha de Inicio')
                    ->required(),
                Forms\Components\Select::make('Responsable')
                    ->relationship(
                        'persona',
                        'ID_Persona',
                        fn ($query) => $query
                            ->select(['ID_Persona', 'Nombres', 'Apellidos'])
                            ->where('Estado', 'Activo')
                        )
                        ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->Nombres} {$record->Apellidos}")
                        ->label('Responsable')
                        ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Descripcion')
            ->columns([
                Tables\Columns\TextColumn::make('Descripcion'),
                Tables\Columns\TextColumn::make('Fecha_Inicio')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('persona')
                    ->label('Encargado')
                    ->formatStateUsing(fn ($state) => "{$state->Nombres} {$state->Apellidos}")
                    ->toggleable()
                    ->searchable(),
                CheckboxColumn::make('Estado'),
                Tables\Columns\TextColumn::make('Fecha_Completado')
                ->date()
                ->sortable(),
                Tables\Columns\TextColumn::make('Creado_Por')
                ->searchable(),

            ])
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                
            ]);
    }
}

