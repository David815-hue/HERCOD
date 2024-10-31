<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BitacoraResource\Pages;
use App\Filament\Resources\BitacoraResource\RelationManagers;
use App\Models\Bitacora;
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
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;

class BitacoraResource extends Resource
{
    protected static ?string $model = Bitacora::class;

    protected static ?string $navigationGroup = 'Seguridad';

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //Forms\Components\TextInput::make('ID_Bitacora')->required(),
                //Forms\Components\TextInput::make('Fecha')->required(),
                //TextInput::make('ID_Usuario')->required(),
                TextInput::make('ID_Objeto')->required(),
                Select::make('Accion')
                ->options([
                    'Insertar' => 'Insertar',
                    'Modificar' => 'Modificar',
                    'Eliminar' => 'Eliminar',
                ])
                ->label('Accion')
                ->required(),
                //TextInput::make('Accion')->required(),
                TextInput::make('Descripcion')->required()
            ]);
    }

    public static function table(Table $table): Table

    {
        return $table
            ->columns([
                TextColumn::make('ID_Bitacora')->label('#')->searchable()->sortable(),
                TextColumn::make('Fecha')->label('Fecha')->default(now()),
                TextColumn::make('ID_Usuario')->label('Usuario')->default(Auth::user()->name)->searchable()->sortable(),
                TextColumn::make('ID_Objeto')->label('Objeto'),
                TextColumn::make('Accion')->label('Accion')->searchable()->sortable(),
                TextColumn::make('Descripcion')->label('Descripcion'),
            ])
            ->filters([
                Filter::make('fecha')
                ->form([
                    DatePicker::make('from')->label('Desde')->placeholder('Selecciona la fecha de inicio'),
                    DatePicker::make('until')->label('Hasta')->placeholder('Selecciona la fecha de fin'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['from'],
                            fn (Builder $query, $date): Builder => $query->whereDate('Fecha', '>=', $date),
                        )
                        ->when(
                            $data['until'],
                            fn (Builder $query, $date): Builder => $query->whereDate('Fecha', '<=', $date),
                        );
                }),
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListBitacoras::route('/'),
            'create' => Pages\CreateBitacora::route('/create'),
            'edit' => Pages\EditBitacora::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): string
    {
        return 'Bitacora'; // Nombre singular
    }

    public static function getPluralLabel(): string
    {
        return 'Bitacora'; // Nombre plural si es necesario
    }
}

