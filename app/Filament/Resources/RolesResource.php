<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RolesResource\Pages;
use App\Filament\Resources\RolesResource\RelationManagers;
use App\Models\Roles;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Auth;


class RolesResource extends Resource
{
    protected static ?string $model = Roles::class;

    protected static ?string $navigationGroup = 'Seguridad';

    protected static ?string $navigationIcon = 'heroicon-o-star';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //TextInput::make('ID_Rol')->required(),
                TextInput::make('Rol')->required(),
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
                TextColumn::make('ID_Rol')->label('#')->searchable()->sortable(),
                TextColumn::make('Rol')->searchable()->sortable(),
                TextColumn::make('Creado_Por')->label('Creado Por')->default(Auth::user()->name),
                TextColumn::make('Fecha_Creacion')->label('Fecha Creacion')->default(now()),
                TextColumn::make('Modificado_Por')->label('Modificado Por'),
                TextColumn::make('Fecha_Modificacion')->label('Fecha Modificacion'),
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRoles::route('/create'),
            'edit' => Pages\EditRoles::route('/{record}/edit'),
        ];
    }
}