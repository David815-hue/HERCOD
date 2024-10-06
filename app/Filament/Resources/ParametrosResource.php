<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ParametrosResource\Pages;
use App\Filament\Resources\ParametrosResource\RelationManagers;
use App\Models\Parametros;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;



class ParametrosResource extends Resource
{
    protected static ?string $model = Parametros::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static ?string $navigationGroup = 'Seguridad';


    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            TextInput::make('Parametro')
                ->label('Parámetro')
                ->required(),
            TextInput::make('Valor')
                ->label('Valor')
                ->required(),
            // Información Adicional
            Select::make('Id_Usuario')
                ->label('Usuario')
                ->options([
                    'john_doe' => 'Juan Carlos',
                    'jane_smith' => 'Jane Smith',
                    'michael_brown' => 'Maicol Josue',
                    'emily_jones' => 'Emily Wilson',
                    'david_wilson' => 'David Ochoa',
                ])
                ->searchable()
                ->required(),
            
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table

        ->columns([
            
            Tables\Columns\TextColumn::make('ID_Parametro')
                ->label('#')
                ->searchable()
                ->sortable()
                ->toggleable(),
            Tables\Columns\TextColumn::make('Parametro')
                ->label('Parámetro')
                ->searchable()
                ->sortable()
                ->toggleable(),
            Tables\Columns\TextColumn::make('Valor')
                ->label('Valor')
                ->searchable()
                ->sortable()
                ->toggleable(),
            Tables\Columns\TextColumn::make('Id_Usuario')
                ->label('Usuario')
                ->searchable()
                ->sortable()
                ->toggleable(),
            Tables\Columns\TextColumn::make('Fecha_creacion')
                ->label('Fecha de Creación')
                ->searchable()
                ->sortable()
                ->toggleable(),
            Tables\Columns\TextColumn::make('Fecha_modificacion')
                ->label('Fecha de Modificación')
                ->searchable()
                ->sortable()
                ->toggleable(),
                ])

                ->filters([
                    // 
                ])

            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

            ])

         ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
            Tables\Actions\DeleteBulkAction::make(),
            ]),
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
            'index' => Pages\ListParametros::route('/'),
            'create' => Pages\CreateParametros::route('/create'),
            'edit' => Pages\EditParametros::route('/{record}/edit'),
        ];
    }
}

