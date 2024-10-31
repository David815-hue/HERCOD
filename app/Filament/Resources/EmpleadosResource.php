<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmpleadosResource\Pages;
use App\Filament\Resources\EmpleadosResource\RelationManagers;
use App\Models\Empleados;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;

class EmpleadosResource extends Resource
{
    protected static ?string $model = Empleados::class;

    protected static ?string $navigationGroup = 'Entidades';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';


    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Datos Personales')
                    ->schema([
                        Forms\Components\TextInput::make('persona.DNI')
                            ->required()
                            ->label('DNI'),

                        Forms\Components\TextInput::make('persona.Nombres')
                            ->required()
                            ->label('Nombres'),

                        Forms\Components\TextInput::make('persona.Apellidos')
                            ->required()
                            ->label('Apellidos'),

                        Forms\Components\Select::make('persona.Genero')
                            ->options([
                                'Masculino' => 'Masculino',
                                'Femenino' => 'Femenino',

                            ])
                            ->required(),

                        Forms\Components\TextInput::make('persona.telefono.Telefono')
                            ->required()
                            ->tel()
                            ->label('Teléfono'),

                        Forms\Components\TextInput::make('persona.correo.Correo')
                            ->required()
                            ->email()
                            ->label('Correo'),


                    ])->columns(2),

                    
                Section::make('Datos Laborales')
                    ->schema([
                        Forms\Components\TextInput::make('departamentoTrabajo.Dep_Trabajo')
                        ->label('Dep_Trabajo'),
                        Forms\Components\TextInput::make('Cargo')
                            ->required(),

                        Forms\Components\TextInput::make('Sueldo')
                            ->required()
                            ->numeric(),

                        Forms\Components\DateTimePicker::make('Fecha_Ingreso')
                            ->required(),


                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('persona.DNI')->label('DNI') ->toggleable()->searchable()->sortable(),
                TextColumn::make('persona.Nombres')->label('Nombres') ->toggleable()->searchable()->sortable(),
                TextColumn::make('persona.Apellidos')->label('Apellidos') ->toggleable()->searchable()->sortable(),
                TextColumn::make('departamentoTrabajo.Dep_Trabajo') ->toggleable()->label('Dep_Trabajo')->searchable()->sortable(),

            ])
            ->defaultSort('Fecha_Ingreso', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmpleados::route('/'),
            'create' => Pages\CreateEmpleados::route('/create'),
            'edit' => Pages\EditEmpleados::route('/{record}/edit'),
            'view' => Pages\ViewEmpleados::route('/{record}'),
        ];
    }
    
}