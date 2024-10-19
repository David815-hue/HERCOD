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
use Filament\Notifications\Notification;



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
                ->integer(),
                
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
                ->numeric()
                ->required(),

            Forms\Components\TextInput::make('Estado')
                ->label('Estado')
                ->required(),

            Forms\Components\DatePicker::make('Fecha_FirmaContrato')
                ->label('Fecha Firma de Contrato')
                ->required(),

            Forms\Components\DatePicker::make('Fecha_OrdenInicio')
                ->label('Fecha Orden de Inicio')
                ->required(),

            Forms\Components\TextInput::make('Monto_Contractual')
                ->label('Monto Contractual')
                ->numeric()
                ->required(),

            Forms\Components\TextInput::make('Monto_Final')
                ->label('Monto Final')
                ->numeric()
                ->nullable(),

            Forms\Components\DatePicker::make('Fecha_Fin')
                ->label('Fecha de Finalización')
                ->nullable(),

            Forms\Components\TextInput::make('Direccion')
                ->label('Dirección')
                ->required(),

            Forms\Components\DatePicker::make('Fecha_Creacion')
                ->label('Fecha de Creación')
                ->default(now())
                ->disabled(),

            Forms\Components\TextInput::make('ID_Empresa')
                ->label('ID Empresa')
                ->required(),

            Forms\Components\TextInput::make('ID_Municipio')
                ->label('ID Municipio')
                ->required(),

            Forms\Components\TextInput::make('Encargado')
                ->label('Encargado')
                ->required(),

            Forms\Components\TextInput::make('Creado_Por')
                ->disabled()
                ->dehydrated(false)
                ->visible(fn ($record) => $record !== null),
        ]);
    }



    public static function infolist(Infolist $infolist): infolist
    {
        return $infolist
        ->schema([
            Section::make('Informacion General')
            ->columns(3)
            ->schema([
                TextEntry::make('Id_Proyecto')
                ->label('ID Proyecto'),
                TextEntry::make('NumeroContrato')
                ->label('Numero de Contrato'),
                TextEntry::make('NumeroLicitacion')
                ->label('Numero de Licitacion'),
                TextEntry::make('ID_Empresa')
                ->label('ID_Empresa'),
                TextEntry::make('Descripcion')
                ->label('Descripcion'),
                TextEntry::make('Fecha_FirmaContrato')
                ->label('Fecha Firma de Contrato'),
                TextEntry::make('Fecha_OrdenInicio')
                ->label('Fecha Orden de Inicio'),
                TextEntry::make('Fecha_Fin')
                ->label('Fecha Finalizacion'),
                TextEntry::make('Estado')
                ->label('Estado'),
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
            ->columns(3)
            ->schema([
                TextEntry::make('Direccion')
                ->label('Direccion'),
                TextEntry::make('ID_Departamento')
                ->label('Departamento'),
                TextEntry::make('ID_Municipio')
                ->label('Municipio'),

                
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

            Section::make('Historial de Montos')
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
                TextColumn::make('Id_Proyecto')
                    ->label('#')
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('NumeroContrato')
                    ->label('Numero Contrato')
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('Nombre_Proyecto')
                    ->toggleable()
                    ->label('Nombre del Proyecto')
                    ->sortable(),
                BadgeColumn::make('Estado')
                    ->colors([
                        'danger' => 'Cancelado',
                        'warning' => 'En Progreso',
                    ]),
                TextColumn::make('Fecha_OrdenInicio')
                    ->toggleable()
                    ->label('Fecha Orden de Inicio')
                    ->date(),
            ])
            ->filters([
                //
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

                            $Id_Proyecto = $record->Id_Proyecto;

                            Estimaciones::create([
                                'Estimacion' => $data['monto'], 
                                'Fecha_Estimacion' => $data['fecha'],
                                'Fecha_Subsanacion' => $data['fecha_subsanacion'] ?? null, 
                                'ID_Proyecto' => $Id_Proyecto, // Usando el ID del proyecto obtenido
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
}
