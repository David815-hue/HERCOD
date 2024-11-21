<?php

namespace App\Filament\Resources\ProyectoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\ToggleColumn;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use Carbon\Carbon;

class TareaRelationManager extends RelationManager
{
    protected static string $relationship = 'Tarea';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('Descripcion')
                    ->label('Tarea')
                    ->required(),
                Forms\Components\DatePicker::make('Fecha_Inicio')
                    ->label('Fecha de Inicio')
                    ->required(),
                Forms\Components\Select::make('Responsable')
                    ->relationship(
                        'persona',  // Relación
                        'ID_Persona', // ID que representa la relación
                        fn($query) => $query
                            ->select(['ID_Persona', 'Nombres', 'Apellidos'])
                            ->where('Estado', 'Activo')
                    )
                    ->getSearchResultsUsing(fn(string $search): array =>
                        \App\Models\Persona::where('Estado', 'Activo')
                            ->where(function($query) use ($search) {
                                $query->where('Nombres', 'like', "%{$search}%")
                                    ->orWhere('Apellidos', 'like', "%{$search}%");
                            })
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(fn($persona) => [
                                $persona->ID_Persona => "{$persona->Nombres} {$persona->Apellidos}"
                            ])
                            ->toArray()
                    )
                    ->getOptionLabelUsing(fn($value): ?string =>
                        optional(\App\Models\Persona::find($value))->Nombres . ' ' . optional(\App\Models\Persona::find($value))->Apellidos
                    )
                    ->label('Encargado')
                    ->required()
                    ->searchable(),
                ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Descripcion')
            ->columns([
                Tables\Columns\TextColumn::make('Descripcion'),
                Tables\Columns\TextColumn::make('Fecha_Inicio')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('persona')
                    ->label('Encargado')
                    ->formatStateUsing(fn ($state) => "{$state->Nombres} {$state->Apellidos}")
                    ->toggleable()
                    ->searchable(),
                CheckboxColumn::make('Estado'),
                Tables\Columns\TextColumn::make('Fecha_Completado')
                ->date()
                ->sortable(),
                Tables\Columns\TextColumn::make('Creado_Por')
                ->searchable(),

            ])
            ->filters([
                DateRangeFilter::make('Fecha_Inicio')
                ->timezone('UTC')
                ->minDate(Carbon::now()->subMonth())->maxDate(Carbon::now()->addMonth())
                ->alwaysShowCalendar(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('Exportar PDF')
                        ->url(fn($record) => route('pdf.tarea', ['tarea' => $record->ID_Tarea])) // Llama a la ruta con el ID del usuario
                        ->label('PDF')
                        ->icon('heroicon-o-document-text')
                        ->color('danger'),
            ])
            ->bulkActions([
                
            ]);
    }

}

