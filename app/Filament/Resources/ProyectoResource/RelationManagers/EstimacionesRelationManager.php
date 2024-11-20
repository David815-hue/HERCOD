<?php

namespace App\Filament\Resources\ProyectoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Pelmered\FilamentMoneyField\Tables\Columns\MoneyColumn;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use Carbon\Carbon;

class EstimacionesRelationManager extends RelationManager
{
    protected static string $relationship = 'Estimaciones';

    protected static bool $shouldRegisterNavigation = true;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                    Forms\Components\TextInput::make('Estimacion')
                        ->required()
                        ->numeric()
                        ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2),
                    Forms\Components\TextInput::make('Descripcion')
                        ->label('Descripcion'),
                    Forms\Components\DatePicker::make('Fecha_Estimacion')
                        ->required(),
                    Forms\Components\DatePicker::make('Fecha_Subsanacion')
                        ->nullable(),
                ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Estimacion')
            ->columns([
                    Tables\Columns\TextColumn::make('Estimacion')
                        ->money('hnl')
                        ->sortable()
                        ->searchable(),
                    Tables\Columns\TextColumn::make('Descripcion')
                        ->label('Descripcion')
                        ->toggleable(),
                    Tables\Columns\TextColumn::make('Fecha_Estimacion')
                        ->date()
                        ->sortable(),
                    Tables\Columns\TextColumn::make('Fecha_Subsanacion')
                        ->date()
                        ->sortable()
                        ->toggleable(),
                    Tables\Columns\TextColumn::make('Creado_Por')
                        ->searchable()
                        ->toggleable(),
                    Tables\Columns\TextColumn::make('Fecha_Creacion')
                        ->date()
                        ->sortable()
                        ->toggleable(),
                ])

            ->filters([
                DateRangeFilter::make('Fecha_Creacion')
                ->timezone('UTC')
                ->minDate(Carbon::now()->subMonth())->maxDate(Carbon::now()->addMonth())
                ->alwaysShowCalendar(),
            ])
            ->headerActions([
                    Tables\Actions\CreateAction::make(),
                ])
            ->actions([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->requiresConfirmation() // Agrega un diálogo de confirmación
                        ->modalHeading('Eliminar Estimación')
                        ->modalDescription('¿Está seguro que desea eliminar esta estimación? Esta acción no se puede deshacer.')
                        ->modalSubmitActionLabel('Sí, eliminar')
                        ->modalCancelActionLabel('Cancelar'),
                    Tables\Actions\Action::make('Exportar PDF')
                        ->url(fn($record) => route('pdf.estimacion', ['estimacion' => $record->ID_Estimacion])) // Llama a la ruta con el ID del usuario
                        ->label('PDF')
                        ->icon('heroicon-o-document-text')
                        ->color('danger'),
                ])
            ->bulkActions([
                    Tables\Actions\BulkActionGroup::make([
                        Tables\Actions\DeleteBulkAction::make(),
                    ]),
                ]);
    }
}
