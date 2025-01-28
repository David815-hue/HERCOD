<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmpleadosResource\Pages;
use App\Filament\Resources\EmpleadosResource\RelationManagers;
use App\Models\Empleados;
use App\Models\DepartamentoTrabajo;
use Filament\Forms\Components\TextInput;
use Carbon\Carbon; // Asegúrate de importar Carbon
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\Action;
use ArielMejiaDev\FilamentPrintable\Actions\PrintBulkAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction; //Para generar Excel
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
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
                        TextInput::make('persona.ID_Persona')
                        ->label('ID Persona')
                        ->visible(fn ($livewire) => $livewire instanceof Pages\ViewEmpleados)
                        ->disabled(),
                        TextInput::make('persona.DNI')
                            ->required()
                            ->label('DNI')
                            ->numeric()
                            ->minLength(13)
                            ->maxLength(13)
                            ->rules(rules: ['digits:13']),

                        TextInput::make('persona.Nombres')
                            ->required()
                            ->rules(['regex:/^[\pL\s]+$/u'])
                            ->label('Nombres'),

                        TextInput::make('persona.Apellidos')
                            ->required()
                            ->rules(['regex:/^[\pL\s]+$/u'])
                            ->label('Apellidos'),

                        Forms\Components\Select::make('persona.Genero')
                            ->options([
                                'Masculino' => 'Masculino',
                                'Femenino' => 'Femenino',


                            ])
                            ->required(),

                        TextInput::make('persona.telefono.Telefono')
                            ->required()
                            ->tel()
                            ->label('Teléfono')
                            ->numeric()
                            ->minLength(8)
                            ->maxLength(8)
                            ->rules(['digits:8']),

                        TextInput::make('persona.correo.Correo')
                            ->required()
                            ->email()
                            ->label('Correo'),

                        Forms\Components\Select::make('persona.Estado')
                            ->options([
                                'Activo' => 'Activo',
                                'Inactivo' => 'Inactivo',
                            ])
                            ->default('Activo')
                            ->required(),

                    ])->columns(2),


                Section::make('Datos Laborales')
                    ->schema([
                        Select::make('departamentoTrabajo.Dep_Trabajo')
                            ->label('Departamento de Trabajo')
                            ->options(fn() => \App\Models\DepartamentoTrabajo::pluck('Dep_Trabajo', 'Dep_Trabajo'))
                            ->required()
                            ->createOptionForm([
                                TextInput::make('Dep_Trabajo')
                                    ->required()
                                    ->label('Nuevo Departamento')
                                    ->minLength(3)
                                    ->maxLength(50)
                                    ->unique('TBL_Departamento_Trabajo', 'Dep_Trabajo')
                            ])
                            ->createOptionUsing(function (array $data) {
                                $departamento = \App\Models\DepartamentoTrabajo::create([
                                    'Dep_Trabajo' => $data['Dep_Trabajo']
                                ]);

                                return $departamento->Dep_Trabajo;
                            })
                            ->createOptionAction(function (Forms\Components\Actions\Action $action) {
                                return $action
                                    ->modalHeading('Crear Nuevo Departamento')
                                    ->modalButton('Crear Departamento')
                                    ->modalWidth('md');
                            }),
                        Forms\Components\TextInput::make('Cargo')
                            ->required(),

                        Forms\Components\TextInput::make('Sueldo')
                            ->required()
                            ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                            ->numeric(),

                            Forms\Components\DatePicker::make('Fecha_Ingreso')
                             ->required()
                             ->disabled(fn ($livewire) => $livewire instanceof Pages\EditEmpleados)

                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('persona.DNI')
                    ->label('DNI')
                    ->toggleable()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('persona.Nombres')
                    ->label('Nombres')
                    ->toggleable()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('persona.Apellidos')
                    ->label('Apellidos')
                    ->toggleable()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('departamentoTrabajo.Dep_Trabajo')
                    ->toggleable()
                    ->label('Departamento Trabajo')
                    ->searchable()
                    ->sortable(),
                BadgeColumn::make('persona.Estado')
                    ->colors([
                        'success' => 'Activo',
                        'danger' => 'Inactivo',
                    ])
                    ->label('Estado')
                    ->toggleable()
                    ->sortable(),
            ])
            ->defaultSort('Fecha_Ingreso', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Exportar PDF')
                    ->label('PDF')
                    ->url(fn($record) => route('pdf.empleado', ['empleado' => $record->ID_Empleado] ))
                    ->color('danger')
                    ->icon('heroicon-o-document-text'),

                Action::make('toggleStatus')
                    ->label(fn($record) => $record->persona->Estado === 'Activo' ? 'Desactivar' : 'Activar')
                    ->icon(fn($record) => $record->persona->Estado === 'Activo' ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn($record) => $record->persona->Estado === 'Activo' ? 'danger' : 'success')
                    ->action(function (Empleados $record) {
                        $newStatus = $record->persona->Estado === 'Activo' ? 'Inactivo' : 'Activo';
                        $record->persona->update(['Estado' => $newStatus]);
                    })
                    ->requiresConfirmation()
                    ->modalHeading(fn($record) => $record->persona->Estado === 'Activo' ?
                        '¿Desactivar empleado?' : '¿Activar empleado?')
                    ->modalDescription(fn($record) => $record->persona->Estado === 'Activo' ?
                        'El empleado será marcado como inactivo.' : 'El empleado será marcado como activo.')
                    ->modalSubmitActionLabel(fn($record) => $record->persona->Estado === 'Activo' ?
                        'Sí, desactivar' : 'Sí, activar')
            ])
            ->filters([
                DateRangeFilter::make('Fecha_Ingreso')
                    ->timezone('UTC')
                    ->minDate(Carbon::now()->subMonth())
                    ->maxDate(Carbon::now()->addMonth())
                    ->alwaysShowCalendar(),

                Tables\Filters\SelectFilter::make('Estado')
                    ->options([
                        'Activo' => 'Activo',
                        'Inactivo' => 'Inactivo',
                    ])
                    ->query(function ($query, $data) {
                        if (isset($data['value'])) {
                            return $query->whereHas('persona', function ($query) use ($data) {
                                $query->whereRaw('TBL_Persona.Estado = ?', [$data['value']]);
                            });
                        }
                        return $query;
                    })
                    ->label('Estado'),
            ])
            ->headerActions([
                Tables\Actions\Action::make('Exportar PDF')
                    ->label('PDF')
                    ->action('exportarPDF') 
                    ->color('danger')
                    ->icon('heroicon-o-document-text'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()->exports([
                        ExcelExport::make('table')->fromTable()
                            ->askForFilename() 
                            ->askForWriterType() // Tipos de Formatos automaticos
                            ->withColumns([
                                Column::make('name')->heading('User name'),
                                Column::make('email')->heading('Email address'),
                                Column::make('created_at')->heading('Creation date'),
                                Column::make('deleted_at')->heading(('Delete date')),
                            ]),

                        ExcelExport::make('form')->fromForm()
                            ->askForFilename()
                            ->askForWriterType()
                            ->withColumns([
                                Column::make('name')->heading('User name'),
                                Column::make('email')->heading('Email address'),
                                Column::make('created_at')->heading('Creation date'),
                                Column::make('deleted_at')->heading(('Delete date')),
                            ]),

                    ])
                        ->label('Excel')
                        ->color('success')
                        ->icon('heroicon-o-document-text'),
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
