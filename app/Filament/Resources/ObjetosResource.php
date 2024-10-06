<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ObjetosResource\Pages;
use App\Filament\Resources\ObjetosResource\RelationManagers;
use App\Models\Objetos;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;

class ObjetosResource extends Resource
{
    protected static ?string $model = Objetos::class;

    protected static ?string $navigationGroup = 'Seguridad';

    protected static ?int $sort = 2;




    protected static ?string $navigationIcon = 'heroicon-o-cube';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //TextInput::make('ID_Rol')->required(),
                TextInput::make('Objeto')->required(),
                //TextInput::make('Creado_Por'),
                //TextInput::make('Fecha_Creacion'),
                //TextInput::make('Modificado_Por'),
                //TextInput::make('Fecha_Modificacion'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                ->label('#')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('Objeto')
                ->label('Objeto'),
            Tables\Columns\TextColumn::make('Creado_Por')
                ->label('Usuario Creacion')
                ->toggleable(),
                Tables\Columns\TextColumn::make('Creado_Por')
                ->label('Usuario Creacion')
                ->toggleable(),
            Tables\Columns\TextColumn::make('Mpdificado_Por')
                ->label('Fecha Creacion')
                ->toggleable(),
            Tables\Columns\TextColumn::make('Modificado_Por')
                ->label('Usuario Modificacion')
                ->toggleable(), 
            Tables\Columns\TextColumn::make('Fecha_Modificacion')
                ->label('Fecha Modificacion')
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
            'index' => Pages\ListObjetos::route('/'),
            'create' => Pages\CreateObjetos::route('/create'),
            'edit' => Pages\EditObjetos::route('/{record}/edit'),
        ];
    }
}
