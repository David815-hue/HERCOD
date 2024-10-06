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
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Card;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Select;


class EmpleadosResource extends Resource
{
    protected static ?string $model = Empleados::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';    
    
    protected static ?string $navigationGroup = 'Entidades';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Wizard::make([
                            Forms\Components\Wizard\Step::make('Detalles Personales')
                                ->columns(2)
                                ->schema([
                                    Forms\Components\TextInput::make('DNI')
                                        ->label('DNI')
                                        ->required(),
                                    Forms\Components\TextInput::make('Nombres')
                                        ->label('Nombres')
                                        ->required(),
                                    Forms\Components\TextInput::make('Apellidos')
                                        ->label('Apellidos')
                                        ->required(),
                                    Forms\Components\TextInput::make('Correo')
                                        ->label('Correo'),
                                    Forms\Components\TextInput::make('Direccion')
                                        ->label('Dirección')
                                        ->required(),
                                    Forms\Components\TextInput::make('Telefono')
                                        ->label('Teléfono')
                                        ->required(),
                                    Select::Make('persona.ID_Genero')
                                        ->label('Género')
                                        ->searchable()
                                        ->options([
                                            'Masculino' => 'Masculinio',
                                            'Femenino' => 'Femenino',
                                        ]),
                                        Select::make('Departamento')
                                        ->options([
                                            'Atlántida' => 'Atlántida',
                                            'Colón' => 'Colón',
                                            'Comayagua' => 'Comayagua',
                                            'Copán' => 'Copán',
                                            'Cortés' => 'Cortés',
                                            'Choluteca' => 'Choluteca',
                                            'El Paraíso' => 'El Paraíso',
                                            'Francisco Morazán' => 'Francisco Morazán',
                                            'Gracias a Dios' => 'Gracias a Dios',
                                            'Intibucá' => 'Intibucá',
                                            'Islas de la Bahía' => 'Islas de la Bahía',
                                            'La Paz' => 'La Paz',
                                            'Lempira' => 'Lempira',
                                            'Ocotepeque' => 'Ocotepeque',
                                            'Olancho' => 'Olancho',
                                            'Santa Bárbara' => 'Santa Bárbara',
                                            'Valle' => 'Valle',
                                            'Yoro' => 'Yoro',
                                        ])
                                        ->searchable()  // Activa la búsqueda en el select
                                        ->required(),  // Hace que el campo sea obligatorio
                                    Forms\Components\TextInput::make('ID_Municipio')
                                        ->label('Municipio')
                                        ->required(),
                                ]),
                            Forms\Components\Wizard\Step::make('Información Laboral')
                                ->columns(2)
                                ->schema([
                                    Select::Make('Cargo')
                                        ->label('Cargo')
                                        ->searchable()
                                        ->options([
                                            'Administrador' => 'Administrador',
                                            'Licitaciones' => 'Licitaciones',
                                        ]),
                                    Forms\Components\TextInput::make('Sueldo')
                                        ->label('Sueldo')
                                        ->required(),
                                    Forms\Components\DatePicker::make('Fecha_Ingreso')
                                        ->label('Fecha de Ingreso')
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
                Tables\Columns\TextColumn::make('ID_Proyecto')
                    ->label('#')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('persona.DNI')
                    ->label('DNI')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('persona.Nombres')
                    ->label('Nombres')
                    ->searchable()
                    ->sortable(),
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
                    ->label('Género')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('Cargo')
                    ->label('Cargo')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('Sueldo')
                    ->label('Sueldo')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('Fecha_Ingreso')
                    ->label('Fecha De Ingreso')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('ID')
                    ->label('Departamento')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                // Campos de la tabla TBL_Persona
                
                Tables\Columns\TextColumn::make('persona.ID_Departamento')
                    ->label('Departamento')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('persona.ID_Municipio')
                    ->label('Municipio')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
            ])
            ->headerActions([
                Action::make('maintenance_rol_proyecto')
                    ->label('Mantenimiento Departamento de Trabajo')
                    ->color('gray')
                    ->icon('heroicon-o-cog')
                    ->modalHeading('Mantenimiento Departamento de Trabajo')
                    ->form([
                        Forms\Components\Section::make('Departamentos de Trabajo Existentes')
                            ->schema([
                                Forms\Components\View::make('existing-departamentos-table')
                            ]),
                        Forms\Components\Section::make('Agregar Nuevo Departamento')
                            ->schema([
                                Forms\Components\TextInput::make('nombre_departamento')
                                    ->label('Nombre Departamento Trabajo')
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
            'index' => Pages\ListEmpleados::route('/'),
            'create' => Pages\CreateEmpleados::route('/create'),
            'edit' => Pages\EditEmpleados::route('/{record}/edit'),
        ];
    }
}
