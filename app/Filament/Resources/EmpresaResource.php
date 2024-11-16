<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmpresaResource\Pages;
use App\Filament\Resources\EmpresaResource\RelationManagers;
use App\Models\Empresa;
use App\Models\Municipio;
use App\Models\Departamentos;
use App\Models\Direcciones;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
class EmpresaResource extends Resource
{
    protected static ?string $model = Empresa::class;
   


    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';


    protected static ?string $navigationGroup = 'Entidades';


    public static function form(Form $form): Form
    {
        return $form
            
            ->schema([
                Section::make('Datos Empresariales')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('RTN')->required()->label('RTN')
                        ->numeric()
                        ->rules(['digits:14']),
                        Forms\Components\TextInput::make('Nombre_Empresa')->required()->label('Nombre de la Empresa'),

                        Forms\Components\TextInput::make('telefono.Telefono')->label('Teléfono'),  // Nota el cambio aquí
                        Forms\Components\TextInput::make('correo.Correo')->label('Correo'), 

                        Select::make('direcciones.municipio.departamento.Nom_Departamento')
                        ->label('Departamento')
                        ->options(self::getDepartamentos())
                       ->reactive()
                       ->afterStateUpdated(fn (callable $set) => $set('direcciones.municipio.Nom_Municipio', null)),

                        Select::make('direcciones.municipio.Nom_Municipio')
                       ->label('Municipio')
                       ->options(function (callable $get) {
                       $departamento = $get('direcciones.municipio.departamento.Nom_Departamento');
                       return $departamento ? array_combine(self::getMunicipios($departamento), self::getMunicipios($departamento)) : [];
    })
    ->required(),

                        Forms\Components\Textarea::make('direcciones.Descripcion')->label('Direccion exacta'),
            
                    ]),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('RTN')
                    ->label('RTN')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('Nombre_Empresa')
                    ->label('Nombre Empresa')
                    ->searchable(),
                TextColumn::make('Fecha_Creacion')
                    ->label('Fecha de Creación')
                    ->date(),
                TextColumn::make('direcciones.municipio.departamento.Nom_Departamento')
                    ->label('Departamento')
                    ->searchable(),
                TextColumn::make('direcciones.municipio.Nom_Municipio')
                    ->label('Municipio')
                    ->searchable(),

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                ->before(function (Empresa $record) {
                    // Primero eliminamos todas las direcciones asociadas
                    $record->direcciones()->delete();
                })

            ]);
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


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmpresa::route('/'),
            'create' => Pages\CreateEmpresa::route('/create'),
            'edit' => Pages\EditEmpresa::route('/{record}/edit'),
            'view' => Pages\ViewEmpresa::route('/{record}'),
        ];
    }
}