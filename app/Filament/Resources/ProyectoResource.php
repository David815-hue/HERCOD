<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProyectoResource\Pages;
use App\Filament\Resources\ProyectoResource\RelationManagers;
use App\Models\Proyecto;
use ArielMejiaDev\FilamentPrintable\Actions\PrintBulkAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Support\Facades\DB;
use Filament\Infolists\Components\ViewEntry;
use App\Models\Estimaciones; // Modelo de Estimaciones
use App\Models\Tarea;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Pelmered\FilamentMoneyField\Forms\Components\MoneyInput;
use Pelmered\FilamentMoneyField\Infolists\Components\MoneyEntry;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Hidden;
use App\Filament\Resources\ProyectoResource\RelationManagers\TareaRelationManager;
use App\Models\Municipio;
use App\Models\Departamentos;
use Filament\Forms\Components\TextInput;
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
            Forms\Components\TextInput::make('NumeroContrato')
                ->label('Número de Contrato')
                ->required()
                ->integer()
                ->numeric(),
                
            Forms\Components\TextInput::make('NumeroLicitacion')
                ->label('Número de Licitación')
                ->required(),

            Forms\Components\TextInput::make('Nombre_Proyecto')
                ->label('Nombre del Proyecto')
                ->required(),

            Forms\Components\Textarea::make('Descripcion')
                ->label('Descripción')
                ->required(),

            Forms\Components\TextInput::make('Anticipo')
                ->label('Anticipo')
                ->currencyMask(thousandSeparator: ',',decimalSeparator: '.',precision: 2)
                ->required()
                ->step(1)
                ->numeric(),

            

            Forms\Components\DatePicker::make('Fecha_FirmaContrato')
                ->label('Fecha Firma de Contrato')
                ->required(),

            Forms\Components\DatePicker::make('Fecha_OrdenInicio')
                ->label('Fecha Orden de Inicio')
                ->required(),

                Forms\Components\TextInput::make('Monto_Contractual')
                ->label('Monto Contractual')
                ->currencyMask(thousandSeparator: ',',decimalSeparator: '.',precision: 2)
                ->required()
                ->step(1)
                ->numeric(),

                Forms\Components\TextInput::make('Monto_Final')
                ->label('Monto Final')
                ->currencyMask(thousandSeparator: ',',decimalSeparator: '.',precision: 2)
                ->numeric()
                ->step(1),


            
            Select::make('Estado')
                ->label('Estado')
                ->options([
                    'Completado' => 'Completado',
                    'En Progreso' => 'En Progreso',
                ])
            ->visible(fn ($record) => $record !== null) // Solo visible al editar
            ->required(fn ($record) => $record !== null), // Requerido solo al editar

            Forms\Components\DatePicker::make('Fecha_Fin')
                ->label('Fecha de Finalización')
                ->nullable(),


            Forms\Components\Select::make('ID_Empresa')
                ->relationship('empresa', 'Nombre_Empresa')
                ->label('Empresa')
                ->required(),

            
            Select::make('municipio.departamento.Nom_Departamento')
                ->label('Departamento')
                ->options(self::getDepartamentos())
                ->reactive()
                ->afterStateUpdated(fn (callable $set) => $set('municipio.Nom_Municipio', null)),
            
            Select::make('municipio.Nom_Municipio')
                ->label('Municipio')
                ->required()
                ->options(function (callable $get) {
                    $departamento = $get('municipio.departamento.Nom_Departamento');
                    return $departamento ? array_combine(self::getMunicipios($departamento), self::getMunicipios($departamento)) : [];
                }),
            
            Forms\Components\TextInput::make('Direccion')
                ->label('Dirección')
                ->required(),
            
            Forms\Components\Select::make('Encargado')
                ->relationship(
                    'persona',
                    'ID_Persona',
                    fn ($query) => $query
                        ->select(['ID_Persona', 'Nombres', 'Apellidos'])
                        ->where('Estado', 'Activo')
                )
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->Nombres} {$record->Apellidos}")
                ->label('Encargado')
                ->required(),

            
           /* Forms\Components\DatePicker::make('Fecha_Creacion')
                ->label('Fecha de Creación')
                ->default(now())
                ->disabled(), */

        ]);
    }



    public static function infolist(Infolist $infolist): infolist
    {
        return $infolist
        ->schema([
            Section::make('Informacion General')
            ->collapsible()
            ->columns(3)
            ->schema([
                TextEntry::make('Nombre_Proyecto')
                ->label('Nombre Proyecto')
                ->color('warning'),
                TextEntry::make('NumeroContrato')
                ->label('Numero de Contrato'),
                TextEntry::make('NumeroLicitacion')
                ->label('Numero de Licitacion'),
                TextEntry::make('empresa.Nombre_Empresa')
                ->label('Empresa'),
                TextEntry::make('Descripcion')
                ->label('Descripcion'),
                TextEntry::make('Fecha_FirmaContrato')
                ->label('Fecha Firma de Contrato'),
                TextEntry::make('Fecha_OrdenInicio')
                ->label('Fecha Orden de Inicio'),
                TextEntry::make('Fecha_Fin')
                ->label('Fecha Finalizacion'),
                
                TextEntry::make('Estado')
                ->label('Estado')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'En Progreso' => 'warning',
                    'Completado' => 'success',
                    default => 'gray'
                }),
                TextEntry::make('persona')
                ->label('Encargado')
                ->formatStateUsing(fn ($state) => "{$state->Nombres} {$state->Apellidos}"),
                TextEntry::make('Creado_Por')
                ->label('Usuario Creacion'),
                TextEntry::make('Fecha_Creacion')
                ->label('Fecha Creacion'),
                TextEntry::make('Modificado_Por')
                ->label('Usuario Modificacion'),
                TextEntry::make('Fecha_Modificacion')
                ->label('Fecha Modificacion'),

            ]),
        
            Section::make('Ubicacion Proyecto')
            ->collapsible()
            ->columns(3)
            ->schema([
                TextEntry::make('Direccion')
                ->label('Direccion'),
                TextEntry::make('municipio.departamento.Nom_Departamento')
                ->label('Departamento'),
                TextEntry::make('municipio.Nom_Municipio')
                ->label('Municipio'),

            ]),

            Section::make('Montos')
            ->collapsible()
            ->columns(3)
            ->schema([
                TextEntry::make('Anticipo')
                ->money('hnl')
                ->label('Anticipo'),
                TextEntry::make('Monto_Contractual')
                ->money('hnl')
                ->label('Monto Contractual'),
                TextEntry::make('Monto_Final')
                ->money('hnl')
                ->label('Monto Final'),
            ]),

            Section::make('Historial de Montos')
            ->description('Historial de Cambios en los Montos')
            ->collapsed()
            ->schema([
                ViewEntry::make('view_historial_montos')
                    ->view('hist_monto') // La vista que mostrarás
                    ->extraAttributes(['class' => 'col-span-full'])
            ]),
                         
        ]);
    }



    
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('NumeroContrato')
                    ->label('Numero Contrato')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('Nombre_Proyecto')
                    ->searchable()
                    ->label('Nombre del Proyecto')
                    ->sortable(),
                
                BadgeColumn::make('Estado')
                    ->colors([
                        'danger' => 'Cancelado',
                        'warning' => 'En Progreso',
                        'success' => 'Completado'
                    ]),
                    
                TextColumn::make('persona')
                    ->label('Encargado')
                    ->formatStateUsing(fn ($state) => "{$state->Nombres} {$state->Apellidos}")
                    ->toggleable(),
                TextColumn::make('municipio.departamento.Nom_Departamento')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->label('Departamento'),
                
                    
            ])
            ->defaultSort('Fecha_Creacion', 'desc')
            ->filters([
                SelectFilter::make('Estado')
                    ->label('Estado')
                    ->options([
                        'En progreso' => 'En progreso',
                        'Completado' => 'Completado',
                    ]),
                    
               
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('create_estimations')
                        ->label('Estimaciones')
                        ->icon('heroicon-o-plus')
                        ->color('info')
                        ->form([
                            TextInput::make('monto')
                                ->label('Monto')
                                ->currencyMask(thousandSeparator: ',',decimalSeparator: '.',precision: 2)
                                ->required(),
                            Forms\Components\TextInput::make('Descripcion')
                                ->label('Descripcion'),
                            Forms\Components\DatePicker::make('fecha')
                                ->label('Fecha')
                                ->required(),
                            Forms\Components\DatePicker::make('fecha_subsanacion')
                                ->label('Fecha de Subsanación'),
                        ])
                        ->action(function (array $data, $record) {

                            $ID_Proyecto = $record->ID_Proyecto;

                            Estimaciones::create([
                                'Estimacion' => $data['monto'], 
                                'Descripcion' => $data['Descripcion'],
                                'Fecha_Estimacion' => $data['fecha'],
                                'Fecha_Subsanacion' => $data['fecha_subsanacion'] ?? null, 
                                'ID_Proyecto' => $ID_Proyecto, // Usando el ID del proyecto obtenido
                            ]);

                            Notification::make()
                            ->title('Estimación creada con éxito') 
                            ->success() 
                            ->send();
                        }),

                        
                       
            
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //RelationManagers\TareaRelationManager::class,
            RelationManagers\EstimacionesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProyectos::route('/'),
            'create' => Pages\CreateProyecto::route('/create'),
            'edit' => Pages\EditProyecto::route('/{record}/edit'),
            'view' => Pages\ViewProyecto::route('/{record}'),
            
        ];
    }



    public static function getDepartamentos(): array
    {
        return [
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
        ];
    }

    public static function getMunicipios(string $departamento): array
    {
        $municipios = [
            'Atlántida' => [
                'La Ceiba', 'El Porvenir', 'Esparta', 'Jutiapa', 'La Masica', 'San Francisco', 'Tela', 'Arizona'
            ],
            'Colón' => [
                'Trujillo', 'Balfate', 'Iriona', 'Limón', 'Sabá', 'Santa Fe', 'Santa Rosa de Aguán', 'Sonaguera', 'Tocoa', 'Bonito Oriental'
            ],
            'Comayagua' => [
                'Comayagua', 'Ajuterique', 'El Rosario', 'Esquías', 'Humuya', 'La Libertad', 'Lamaní', 'La Trinidad', 
                'Lejamani', 'Meámbar', 'Minas de Oro', 'Ojos de Agua', 'San Jerónimo', 'San José de Comayagua', 
                'San José del Potrero', 'Siguatepeque', 'Villa de San Antonio', 'Las Lajas', 'Taulabé'
            ],
            'Copán' => [
                'Santa Rosa de Copán', 'Cabañas', 'Concepción', 'Copán Ruinas', 'Corquín', 'Cucuyagua', 'Dolores', 
                'Dulce Nombre', 'El Paraíso', 'Florida', 'La Jigua', 'La Unión', 'Nueva Arcadia', 'San Agustín', 
                'San Antonio', 'San Jerónimo', 'San José', 'San Juan de Opoa', 'San Nicolás', 'San Pedro', 
                'Santa Rita', 'Trinidad de Copán', 'Veracruz'
            ],
            'Cortés' => [
                'San Pedro Sula', 'Choloma', 'Omoa', 'Pimienta', 'Potrerillos', 'Puerto Cortés', 'San Antonio de Cortés', 
                'San Francisco de Yojoa', 'San Manuel', 'Santa Cruz de Yojoa', 'Villanueva', 'La Lima'
            ],
            'Choluteca' => [
                'Choluteca', 'Apacilagua', 'Concepción de María', 'Duyure', 'El Corpus', 'El Triunfo', 'Marcovia', 
                'Morolica', 'Namasigüe', 'Orocuina', 'Pespire', 'San Antonio de Flores', 'San Isidro', 'San José', 
                'San Marcos de Colón', 'Santa Ana de Yusguare'
            ],
            'El Paraíso' => [
                'Yuscarán', 'Alauca', 'Danlí', 'El Paraíso', 'Güinope', 'Jacaleapa', 'Liure', 'Morocelí', 'Oropolí', 
                'Potrerillos', 'San Antonio de Flores', 'San Lucas', 'San Matías', 'Soledad', 'Teupasenti', 'Texiguat', 
                'Vado Ancho', 'Yauyupe', 'Trojes'
            ],
            'Francisco Morazán' => [
                'Tegucigalpa', 'Alubarén', 'Cedros', 'Curarén', 'El Porvenir', 'Guaimaca', 'La Libertad', 
                'La Venta', 'Lepaterique', 'Maraita', 'Marale', 'Nueva Armenia', 'Ojojona', 'Orica', 'Reitoca', 
                'Sabana Grande', 'San Antonio de Oriente', 'San Buenaventura', 'San Ignacio', 'San Juan de Flores', 
                'San Miguelito', 'Santa Ana', 'Santa Lucía', 'Talanga', 'Tatumbla', 'Valle de Ángeles', 'Villa de San Francisco', 'Vallecillo'
            ],
            'Gracias a Dios' => [
                'Puerto Lempira', 'Brus Laguna', 'Ahuas', 'Juan Francisco Bulnes', 'Ramón Villeda Morales', 'Wampusirpi'
            ],
            'Intibucá' => [
                'La Esperanza', 'Camasca', 'Colomoncagua', 'Concepción', 'Dolores', 'Intibucá', 'Jesús de Otoro', 
                'Magdalena', 'Masaguara', 'San Antonio', 'San Isidro', 'San Juan', 'San Marcos de la Sierra', 
                'San Miguel Guancapla', 'Santa Lucía', 'Yamaranguila'
            ],
            'Islas de la Bahía' => [
                'Roatán', 'Guanaja', 'José Santos Guardiola', 'Utila'
            ],
            'La Paz' => [
                'La Paz', 'Aguanqueterique', 'Cabañas', 'Cane', 'Chinacla', 'Guajiquiro', 'Lauterique', 'Marcala', 
                'Mercedes de Oriente', 'Opatoro', 'San Antonio del Norte', 'San José', 'San Juan', 'San Pedro de Tutule', 
                'Santa Ana', 'Santa Elena', 'Santa María', 'Santiago de Puringla', 'Yarula'
            ],
            'Lempira' => [
                'Gracias', 'Belén', 'Candelaria', 'Cololaca', 'Erandique', 'Gualcince', 'Guarita', 'La Campa', 
                'La Iguala', 'Las Flores', 'La Unión', 'La Virtud', 'Lepaera', 'Mapulaca', 'Piraera', 'San Andrés', 
                'San Francisco', 'San Juan Guarita', 'San Manuel Colohete', 'San Rafael', 'San Sebastián', 
                'Santa Cruz', 'Talgua', 'Tambla', 'Tomalá', 'Valladolid', 'Virginia', 'San Marcos de Caiquín'
            ],
            'Ocotepeque' => [
                'Nueva Ocotepeque', 'Belén Gualcho', 'Concepción', 'Dolores Merendón', 'Fraternidad', 'La Encarnación', 
                'La Labor', 'Lucerna', 'Mercedes', 'San Fernando', 'San Francisco del Valle', 'San Jorge', 
                'San Marcos', 'Santa Fe', 'Sensenti', 'Sinuapa'
            ],
            'Olancho' => [
                'Juticalpa', 'Campamento', 'Catacamas', 'Concordia', 'Dulce Nombre de Culmí', 'El Rosario', 
                'Esquipulas del Norte', 'Gualaco', 'Guarizama', 'Guata', 'Jano', 'La Unión', 'Mangulile', 
                'Manto', 'Salamá', 'San Esteban', 'San Francisco de Becerra', 'San Francisco de La Paz', 
                'Santa María del Real', 'Silca', 'Yocón', 'Patuca'
            ],
            'Santa Bárbara' => [
                'Santa Bárbara', 'Arada', 'Atima', 'Azacualpa', 'Ceguaca', 'Concepción del Norte', 'Concepción del Sur', 
                'Chinda', 'El Níspero', 'Gualala', 'Ilama', 'Las Vegas', 'Macuelizo', 'Naranjito', 'Nuevo Celilac', 
                'Petoa', 'Protección', 'Quimistán', 'San Francisco de Ojuera', 'San José de Colinas', 'San Luis', 
                'San Marcos', 'San Nicolás', 'San Pedro Zacapa', 'Santa Rita', 'Trinidad'
            ],
            'Valle' => [
                'Nacaome', 'Alianza', 'Amapala', 'Aramecina', 'Caridad', 'Goascorán', 'Langue', 'San Francisco de Coray', 'San Lorenzo'
            ],
            'Yoro' => [
                'Yoro', 'Arenal', 'El Negrito', 'El Progreso', 'Jocón', 'Morazán', 'Olanchito', 'Santa Rita', 
                'Sulaco', 'Victoria', 'Yorito'
            ],
            
        ];
        

        return $municipios[$departamento] ?? [];
    }
    
}
