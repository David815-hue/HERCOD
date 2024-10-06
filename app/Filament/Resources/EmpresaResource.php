<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmpresaResource\Pages;
use App\Filament\Resources\EmpresaResource\RelationManagers;
use App\Models\Empresa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Card;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;


class EmpresaResource extends Resource
{
    protected static ?string $model = Empresa::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';    

    protected static ?string $navigationGroup = 'Entidades';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Wizard::make([
                            Wizard\Step::make('Detalles de la Empresa')
                                ->columns(2)
                                ->schema([
                                    TextInput::make('RTN')
                                        ->label('RTN'),
                                    TextInput::make('Nombre_Empresa')
                                        ->label('Nombre de la Empresa'),
                                ]),
                                Wizard\Step::make('Representante')
                                ->columns(2)
                                ->schema([
                                    TextInput::make('Nombre_Representante')
                                        ->label('Nombre del Representante')
                                        ->required(),
                                    TextInput::make('Cargo')
                                        ->label('Cargo del Representante'),
                                    TextInput::make('DNI')
                                        ->label('DNI'),
                                    TextInput::make('Nombres')
                                        ->label('Nombres'),
                                    TextInput::make('Apellidos')
                                        ->label('Apellidos'),
                                    TextInput::make('Correo')
                                        ->label('Correo'),
                                    TextInput::make('Direccion')
                                        ->label('Dirección')
                                        ->required(),
                                    TextInput::make('Telefono')
                                        ->label('Teléfono')
                                        ->required(),
                                    TextInput::make('ID_Genero')
                                        ->label('ID Género')
                                        ->required(),
                                    TextInput::make('ID_Departamento')
                                        ->label('Departamento')
                                        ->required(),
                                    TextInput::make('ID_Municipio')
                                        ->label('Municipio')
                                        ->required(),
                                ]),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ID_Empresa')
                    ->label('#')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('RTN')
                    ->label('RTN')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('Nombre_Empresa')
                    ->label('Nombre de la Empresa')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('ID_Representante')
                    ->label('Representante')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('Fecha_registro')
                    ->label('Fecha de Registro')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                // Campos de la tabla TBL_Representante
                Tables\Columns\TextColumn::make('representante.Nombre_Representante')
                    ->label('Nombre del Representante')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('representante.Cargo')
                    ->label('Cargo del Representante')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                    Tables\Columns\TextColumn::make('persona.DNI')
                    ->label('DNI')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('persona.Nombres')
                    ->label('Nombres')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('persona.Apellidos')
                    ->label('Apellidos')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('persona.Correo')
                    ->label('Correo')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('persona.Direccion')
                    ->label('Dirección')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('persona.Telefono')
                    ->label('Teléfono')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('persona.ID_Genero')
                    ->label('ID Género')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('persona.ID_Departamento')
                    ->label('Departamento')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('Municipio')
                    ->label('ID Municipio')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                

            ])
            ->headerActions([
                Action::make('maintenance_rol_genero')
                    ->label('Mantenimiento Genero')
                    ->color('gray')
                    ->icon('heroicon-o-cog')
                    ->modalHeading('Mantenimiento Genero')
                    ->form([
                        Forms\Components\Section::make('Generos Existentes')
                            ->schema([
                                Forms\Components\View::make('existing-generos-table')
                            ]),
                        Forms\Components\Section::make('Agregar Nuevo Genero')
                            ->schema([
                                Forms\Components\TextInput::make('nombre_genero')
                                    ->label('Genero')
                                    ->required(),
                                // Otros campos necesarios
                            ]),
                    ])
                    ->action(function (array $data) {
                        // Lógica para manejar el mantenimiento del rol del proyecto
                        Notification::make()
                            ->title('Mantenimiento de Rol Proyecto realizado')
                            ->success()
                            ->send();
                    }),
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
            'index' => Pages\ListEmpresas::route('/'),
            'create' => Pages\CreateEmpresa::route('/create'),
            'edit' => Pages\EditEmpresa::route('/{record}/edit'),
        ];
    }
}
