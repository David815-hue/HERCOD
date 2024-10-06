<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProyectoResource\Pages;
use App\Filament\Resources\ProyectoResource\RelationManagers;
use App\Models\Proyecto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Card;
use Filament\Tables\Actions\ButtonAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\Modal;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Tables\Actions\HeaderActions\ButtonHeaderAction;
use \PDF;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use RyanChandler\FilamentProgressColumn\ProgressColumn;


class ProyectoResource extends Resource
{
    protected static ?string $model = Proyecto::class;

    protected static ?string $navigationGroup = 'Proyectos';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';


    public static function form(Form $form): Form
    {


        return $form
            
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Wizard::make([
                            Forms\Components\Wizard\Step::make('Detalles Proyecto')
                                ->columns(2)
                                ->schema([
                                    Forms\Components\TextInput::make('Numero de Contrato'),
                                    Forms\Components\TextInput::make('Numero de Licitacion'),
                                    Forms\Components\TextInput::make('Nombre del Proyecto'),
                                    Forms\Components\TextInput::make('Descripcion'),
                                    Forms\Components\TextInput::make('Anticipo'),
                                    Forms\Components\DatePicker::make('Fecha Firma Contrato'),
                                    Forms\Components\DatePicker::make('Fecha Orden Inicio'),
                                    Forms\Components\TextInput::make('Direccion'),
                                    Forms\Components\TextInput::make('Monto Contraactual')
                                        ->numeric()
                                        ->inputMode('decimal'),
                                    Select::make('Departamento')
                                        ->options([
                                            'Atlántida' => 'Atlántida',
                                            'Colón' => 'Colón',
                                            'Comayagua' => 'Comayagua',
                                            'Copán' => 'Copán A',
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
                                        ->searchable(),  // Activa la búsqueda en el select  // Hace que el campo sea obligatorio
                                    Forms\Components\TextInput::make('Municipio')
                                        ->datalist([
                                            'Atlántida',
                                            'Colón',
                                        ]),
                                    Select::make('empresa')
                                        ->label('Empresa')
                                        ->searchable()
                                        ->options([
                                            'empresa1' => 'Empresa 1',
                                            'empresa2' => 'Empresa 2',
                                            'empresa3' => 'Empresa 3',
                                        ]),
                                ]),
                                Wizard\Step::make('Personal')
                                ->schema([
                                    
                                    Repeater::make('personal')
                                    ->label('Personal')
                                    ->columns(2)
                                    ->schema([
                                        Select::make('persona')
                                            ->label('Persona')
                                            ->options([
                                                '1' => 'Juan Pérez',
                                                '2' => 'María García',
                                                '3' => 'Luis Martínez',
                                                '4' => 'Ana López',
                                            ])
                                            ->placeholder('Seleccionar Persona'),
                                        Select::make('rol')
                                            ->label('Rol')
                                            ->options([
                                                '1' => 'Rol 1',
                                                '2' => 'Rol 2',
                                                '3' => 'Rol 3',
                                            ])
                                            ->placeholder('Seleccionar Rol'),

                                    ])  
                                    ->collapsed(false)
                                    ->default(fn () => [
                                        [
                                            'persona' => '1',
                                            'rol' => '1',
                                            ],
                                        
                                        ]),
                                ]),
                        ]),
                    ]),
            ]);
    }


    public static function infolist(Infolist $infolist): infolist
    {
        return $infolist
        ->schema([
            Section::make('Informacion General')
            ->columns(3)
            ->schema([
                TextEntry::make('ID_Proyecto')
                ->label('ID Proyecto'),
                TextEntry::make('NumeroContrato')
                ->label('Numero de Contrato'),
                TextEntry::make('NumeroLicitacion')
                ->label('Numero de Licitacion'),
                TextEntry::make('Cliente')
                ->label('ID_Empresa'),
                TextEntry::make('Descripcion')
                ->label('Descripcion'),
                TextEntry::make('Fecha_FirmaContrato')
                ->label('Fecha Firma de Contrato'),
                TextEntry::make('Fecha_OrdenInicio')
                ->label('Fecha Orden de Inicio'),
                TextEntry::make('Fecha_Fin')
                ->label('Fecha Finalizacion'),
                TextEntry::make('ID_Estado')
                ->label('Estado'),
                TextEntry::make('ID_Tipo')
                ->label('Tipo de Proyecto'),
                TextEntry::make('Creado_Por')
                ->label('Usuario Creacion'),
                TextEntry::make('created_at')
                ->label('Fecha Creacion'),
                TextEntry::make('Modificado_Por')
                ->label('Usuario Modificacion'),
                TextEntry::make('updated_at')
                ->label('Fecha Modificacion'),

            ]),
        
            Section::make('Ubicacion Proyecto')
            ->columns(3)
            ->schema([
                TextEntry::make('Direccion')
                ->label('Direccion'),
                TextEntry::make('ID_Departamento')
                ->label('Departamento'),
                TextEntry::make('ID_Municipio')
                ->label('Municipio'),

                
            ]),
            Section::make('Personal')
            ->columns(3)
            ->schema([
                TextEntry::make('#Personal')
                ->label('#'),
                TextEntry::make('Nombre Personal')
                ->label('Nombre'),
                TextEntry::make('Rol Proyecto')
                ->label('Rol Proyecto'),
            ]),
            Section::make('Montos')
            ->columns(3)
            ->schema([
                TextEntry::make('Monto_Contractual')
                ->label('Anticipo'),
                TextEntry::make('Monto_Final')
                ->label('Monto Contractual'),
                TextEntry::make('ID_Departamento')
                ->label('Monto Final'),
                
                
            ]),
            Section::make('Estimaciones')
                    ->columns(1)
                    ->schema([
                        Tabs::make('Estimaciones Tabs')
                            ->tabs([
                                Tab::make('Estimacion #1')
                                    ->schema([
                                        TextEntry::make('ID')
                                            ->label('Estimacion #'),
                                        TextEntry::make('Monto')
                                            ->label('Monto Estimacion'),
                                        TextEntry::make('Fecha Estimacion')
                                            ->label('Fecha Estimacion'),
                                        TextEntry::make('Fecha Subsanacion')
                                            ->label('Fecha Subsanacion'),
                                    ]),
                                Tab::make('Estimacion #2')
                                    ->schema([
                                        TextEntry::make('ID')
                                            ->label('Estimacion #'),
                                        TextEntry::make('Monto')
                                            ->label('Monto Estimacion'),
                                        TextEntry::make('Fecha Estimacion')
                                            ->label('Fecha Estimacion'),
                                        TextEntry::make('Fecha Subsanacion')
                                            ->label('Fecha Subsanacion'),
                                    ]),
                                // Agrega más pestañas según sea necesario
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
            Tables\Columns\TextColumn::make('NumeroContrato')
                ->label('Numero de Contrato'),
            /*Tables\Columns\TextColumn::make('NumeroLicitacion')
                ->label('Numero de Licitacion')
                ->toggleable(), */
            Tables\Columns\TextColumn::make('Nombre_Proyecto')
                ->label('Nombre del Proyecto'),
            ProgressColumn::make('progress')
                ->color('success')
                ->label('Progreso')
                ->progress(function ($record) {
                    // Aquí se define un valor ficticio para el progreso
                    return 75; // Genera un número aleatorio entre 0 y 100
                }),
            Tables\Columns\TextColumn::make('Fecha_OrdenInicio')
                ->label('Fecha Orden de Inicio')
                ->toggleable(),
            Tables\Columns\TextColumn::make('Fecha_Fin')
                ->label('Fecha Fin')
                ->toggleable(),
        ])
        ->headerActions([
                Action::make('maintenance_rol_proyecto')
                    ->label('Mantenimiento Rol Proyecto')
                    ->color('gray')
                    ->icon('heroicon-o-cog')
                    ->modalHeading('Mantenimiento Rol Proyecto')
                    ->form([
                        Forms\Components\Section::make('Roles de Proyecto Existentes')
                            ->schema([
                                Forms\Components\View::make('existing-roles-table')
                            ]),
                        Forms\Components\Section::make('Agregar Nuevo Rol')
                            ->schema([
                                Forms\Components\TextInput::make('nombre_rol')
                                    ->label('Nombre Rol Proyecto')
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
                    Action::make('maintenance_tipo_proyecto')
                    ->label('Mantenimiento Tipo de Proyecto')
                    ->color('gray')
                    ->icon('heroicon-o-cog')
                    ->modalHeading('Mantenimiento Tipo de Proyecto')
                    ->form([
                        Forms\Components\Section::make('Tipos de Proyecto Existentes')
                            ->schema([
                                Forms\Components\View::make('existing-tipo-proyecto-table')
                            ]),
                        Forms\Components\Section::make('Agregar Nuevo Tipo de Proyecto')
                            ->schema([
                                Forms\Components\TextInput::make('nombre_tipo_proyecto')
                                    ->label('Nombre Tipo de Proyecto')
                                    ->required(),
                                // Otros campos necesarios
                            ]),
                    ])
                    ->action(function (array $data) {
                        // Lógica para manejar el mantenimiento del tipo de proyecto
                        Notification::make()
                            ->title('Mantenimiento de Tipo de Proyecto realizado')
                            ->success()
                            ->send();
                    }),
                    Action::make('maintenance_estado')
                    ->label('Mantenimiento Estado')
                    ->color('gray')
                    ->icon('heroicon-o-cog')
                    ->modalHeading('Mantenimiento Estado')
                    ->form([
                        Forms\Components\Section::make('Estados Existentes')
                            ->schema([
                            ]),
                        Forms\Components\Section::make('Agregar Nuevo Estado')
                            ->schema([
                                Forms\Components\TextInput::make('nombre_estado')
                                    ->label('Nombre Estado')
                                    ->required(),
                                // Otros campos necesarios
                            ]),
                    ])
                    ->action(function (array $data) {
                        // Lógica para manejar el mantenimiento del estado
                        Notification::make()
                            ->title('Mantenimiento de Estado realizado')
                            ->success()
                            ->send();
                    })

                    
            ])

            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->color('primary'),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('create_estimations')
                        ->label('Estimaciones')
                        ->icon('heroicon-o-plus')
                        ->color('info')
                        ->form([
                            Forms\Components\TextInput::make('monto')
                                ->label('Monto')
                                ->required()
                                ->numeric(),
                            Forms\Components\DatePicker::make('fecha')
                                ->label('Fecha')
                                ->required(),
                            Forms\Components\DatePicker::make('fecha_subsanacion')
                                ->label('Fecha de Subsanación'),
                        ])
                        ->action(function (array $data, $record) {
                            $idProyecto = $record->ID_Proyecto;
                            Notification::make()
                                ->title('Estimación creada para el proyecto ' . $idProyecto)
                                ->success()
                                ->send();
                        }),

                        Action::make('create_task')
                        ->label('Tarea')
                        ->icon('heroicon-o-plus')
                        ->color('success')
                        ->form([
                            Forms\Components\TextInput::make('Tarea')
                                ->label('Tarea')
                                ->required(),
                            Forms\Components\DatePicker::make('Fecha_Inicio')
                                ->label('Fecha de Inicio')
                                ->required(),
                            Forms\Components\DatePicker::make('Fecha_Fin')
                                ->label('Fecha de Finalizacion')
                                ->required(),
                            Forms\Components\Select::make('ID_Responsable')
                                ->options([
                                    'Moises Madrid' => 'Moises Madrid',
                                    'Daniel Medina' => 'Daniel Medina',
                                    'Fanny Rosales' => 'Fanny Rosales',
                                ])->label('Responsable')
                                ->required(),
                        ])
                        ->action(function (array $data, $record) {
                            $idProyecto = $record->ID_Proyecto;
                
                            // Aquí puedes manejar la lógica para guardar los datos de la tarea
                            // En este ejemplo, simplemente mostramos una notificación.
                
                            Notification::make()
                                ->title('Tarea creada para el proyecto ' . $idProyecto)
                                ->success()
                                ->send();
                        }),
            
                    Tables\Actions\Action::make('download_pdf')
                        ->label('PDF')
                        ->icon('heroicon-o-document-text')
                        ->color('danger')
                        ->action(function ($record) {
                            // Aquí solo se manejará lo visual
                            Notification::make()
                                ->title('Generando PDF...')
                                ->success()
                                ->send();
                        }),
            
                    Tables\Actions\Action::make('Mostrar_Tareas')
                        ->label('Tareas Ingresadas')
                        ->color('success')
                        ->icon('heroicon-o-clipboard-document-list')
                        ->modalHeading('Lista de Tareas')
                        ->modalWidth('3xl')
                        ->modalContent(function ($record) {
                            // Datos ficticios para la visualización
                            $tareas = [
                                (object) ['#' => 'Revisión de planos', 'Fecha_Inicio' => '2024-08-01', 'Responsable' => 'Juan Pérez', 'Estado' => 'Pendiente'],
                                (object) ['#' => 'Compra de materiales', 'Fecha_Inicio' => '2024-08-05', 'Responsable' => 'Ana Gómez', 'Estado' => 'Completada'],
                                (object) ['#' => 'Contratación de subcontratistas', 'Fecha_Inicio' => '2024-08-10', 'Responsable' => 'Luis Fernández', 'Estado' => 'Pendiente'],
                            ];
            
                            return view('tareas-modal', compact('tareas'));
                        }),
                    Tables\Actions\DeleteAction::make(),

                   
                ])
                ->label('Acciones') 
                ->dropdownPlacement('top-start')
                ->button()
                ->color('gray')


                
                
            ])
            ->bulkActions([
                
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
            'index' => Pages\ListProyectos::route('/'),
            'create' => Pages\CreateProyecto::route('/create'),
            'edit' => Pages\EditProyecto::route('/{record}/edit'),
        ];
    }
}
