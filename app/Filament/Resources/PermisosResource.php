<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermisosResource\Pages;
use App\Filament\Resources\PermisosResource\RelationManagers;
use App\Models\Permisos;
use ArielMejiaDev\FilamentPrintable\Actions\PrintBulkAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Auth;


class PermisosResource extends Resource
{
    protected static ?string $model = Permisos::class;

    protected static ?string $navigationGroup = 'Seguridad';

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('ID_Rol')
            ->options([
                'INVITADO' => 'INVITADO',
                'EMPLEADO' => 'EMPLEADO',
                'CLIENTE' => 'CLIENTE',
                'ADMIN' => 'ADMIN',
            ])
            ->label('Rol')
            ->searchable()
            ->required(), 
                Select::make('Objeto')->required()->searchable()
                ->options([
                    'Objeto 1' => 'Objeto 1',
                    'Objeto 2' => 'Objeto 2',
                ]),
                Select::make('Insertar')
                ->options([
                    'Activo' => 'Activo',
                    'Inactivo' => 'Inactivo',
                ])
                ->label('Insertar')
                ->required(),
                Select::make('Modificar')
                ->options([
                   'Activo' => 'Activo',
                   'Inactivo' => 'Inactivo',
                ])
                 ->label('Modificar')
                 ->required(),
                Select::make('Consultar')
                ->options([
                    'Activo' => 'Activo',
                    'Inactivo' => 'Inactivo',
                ])
                ->label('Consultar')
                ->required(),
                Select::make('Eliminar')
                ->options([
                    'Activo' => 'Activo',
                    'Inactivo' => 'Inactivo',
                ])
                ->label('Eliminar')
                ->required(),
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
                TextColumn::make('ID_Objeto')->label('ID_Objeto'),
                TextColumn::make('Insertar')->searchable()->sortable(),
                TextColumn::make('Eliminar')->searchable()->sortable(),
                TextColumn::make('Modificar')->searchable()->sortable(),
                TextColumn::make('Consultar')->searchable()->sortable(),
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
                    PrintBulkAction::make(),
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
            'index' => Pages\ListPermisos::route('/'),
            'create' => Pages\CreatePermisos::route('/create'),
            'edit' => Pages\EditPermisos::route('/{record}/edit'),
        ];
    }
}

