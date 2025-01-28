<?php

namespace Rmsramos\Activitylog\Resources;

use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Livewire\Component as Livewire;
use Rmsramos\Activitylog\Actions\Concerns\ActionContent;
use Rmsramos\Activitylog\ActivitylogPlugin;
use Rmsramos\Activitylog\RelationManagers\ActivitylogRelationManager;
use Rmsramos\Activitylog\Resources\ActivitylogResource\Pages\ListActivitylog;
use Rmsramos\Activitylog\Resources\ActivitylogResource\Pages\ViewActivitylog;
use Spatie\Activitylog\Models\Activity;

class ActivitylogResource extends Resource
{
    use ActionContent;

    public static function getModel(): string
    {
        return Activity::class;
    }

    public static function getModelLabel(): string
    {
        return ActivitylogPlugin::get()->getLabel();
    }

    public static function getPluralModelLabel(): string
    {
        return ActivitylogPlugin::get()->getPluralLabel();
    }

    public static function getNavigationIcon(): string
    {
        return ActivitylogPlugin::get()->getNavigationIcon();
    }

    public static function getNavigationLabel(): string
    {
        return Str::title(static::getPluralModelLabel()) ?? Str::title(static::getModelLabel());
    }

    public static function getNavigationSort(): ?int
    {
        return ActivitylogPlugin::get()->getNavigationSort();
    }

    public static function getNavigationGroup(): ?string
    {
        return ActivitylogPlugin::get()->getNavigationGroup();
    }

    public static function getNavigationBadge(): ?string
    {
        return ActivitylogPlugin::get()->getNavigationCountBadge() ?
            number_format(static::getModel()::count()) : null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Split::make([
                    Section::make([
                        TextInput::make('causer_id')
                            ->afterStateHydrated(function ($component, ?Model $record) {
                                /** @phpstan-ignore-next-line */
                                return $component->state($record->causer?->username);
                            })
                            ->label(__('Realizado por')),
                        Textarea::make('description')
                            ->label(__('activitylog::forms.fields.description.label'))
                            ->rows(2)
                            ->columnSpan('full'),
                        TextInput::make('subject_type')
                            ->afterStateHydrated(function ($component, ?Model $record, $state) {
                                /** @var Activity&ActivityModel $record */
                                if (! $state || ! $record->subject) {
                                    return '-';
                                }
                        
                                $subject = $record->subject;
                                $recursoNombre = '-'; // Valor por defecto
                        
                                // Determinar el nombre del recurso afectado en base a la clase del subject
                                switch (get_class($subject)) {
                                    case \App\Models\Empresa::class:
                                        $recursoNombre = $subject->Nombre_Empresa ?? 'Sin Nombre';
                                        break;
                        
                                    case \App\Models\Tarea::class:
                                        $recursoNombre = $subject->Descripcion ?? 'Sin Nombre';
                                        break;
                        
                                    case \App\Models\Proyecto::class:
                                        $recursoNombre = $subject->Nombre_Proyecto ?? $subject['Nombre_Proyecto'] ?? 'Sin Nombre';
                                        break;
                        
                                    case \App\Models\Estimaciones::class:
                                        $recursoNombre = $subject->Descripcion ?? 'Sin Nombre';
                                        break;
                        
                                    case \App\Models\User::class:
                                        $recursoNombre = $subject->username ?? 'Sin Nombre';
                                        break;
                                    /*case \App\Filament\Pages\Backups::class:
                                        $recursoNombre = 'Backup';
                                        break;     */
                                        
                                    case \App\Models\Empleados::class:
                                        $recursoNombre = $subject->persona?->Nombres . ' ' . $subject->persona?->Apellidos ?? 'Sin Nombre';
                                        break;
                        
                                    case \App\Models\Persona::class:
                                        $recursoNombre = ($subject->Nombres ?? 'Sin Nombre') . ' ' . ($subject->Apellidos ?? '');
                                        break;
                                        
                                    case \App\Models\Telefono::class:
                                        // Si el subject es un Telefono, obtenemos el nombre de la empresa o la persona asociada
                                        $recursoNombre = $subject->empresa?->Nombre_Empresa // Si tiene empresa
                                            ?? ($subject->persona ? $subject->persona->Nombres . ' ' . $subject->persona->Apellidos : 'Sin Nombre');
                                        break;
                                    
                                    case \App\Models\Correo::class:
                                           
                                        $recursoNombre = $subject->empresa?->Nombre_Empresa // Si tiene empresa
                                            ?? ($subject->persona ? $subject->persona->Nombres . ' ' . $subject->persona->Apellidos : 'Sin Nombre');
                                        break;
                        
                                    default:
                                        // Para cualquier otro caso, usamos el estado y el ID del recurso
                                        $recursoNombre = Str::of($state)->afterLast('\\')->headline() . ' # ' . $record->subject_id;
                                }
                        
                                // Retornar el nombre del recurso afectado
                                return $component->state($recursoNombre);
                            })
                            ->label("Recurso Afectado"),
                    ]),
                    Section::make([
                        Placeholder::make('log_name')
                            ->content(function (?Model $record): string {
                                /** @var Activity&ActivityModel $record */
                                return $record->log_name ? ucwords($record->log_name) : '-';
                            })
                            ->label(__('activitylog::forms.fields.log_name.label')),

                        Placeholder::make('event')
                            ->content(function (?Model $record): string {
                                /** @phpstan-ignore-next-line */
                                return $record?->event ? ucwords($record?->event) : '-';
                            })
                            ->label(__('activitylog::forms.fields.event.label')),

                        Placeholder::make('created_at')
                            ->label(__('activitylog::forms.fields.created_at.label'))
                            ->content(function (?Model $record): string {
                                /** @var Activity&ActivityModel $record */
                                return $record->created_at ? "{$record->created_at->format(config('filament-activitylog.datetime_format', 'd/m/Y H:i:s'))}" : '-';
                            }),
                    ])->grow(false),
                ])->from('md'),

                Section::make()
                    ->columns()
                    ->visible(fn ($record) => $record->properties?->count() > 0)
                    ->schema(function (?Model $record) {
                        /** @var Activity&ActivityModel $record */
                        $properties = $record->properties->except(['attributes', 'old']);

                        $schema = [];

                        if ($properties->count()) {
                            $schema[] = KeyValue::make('properties')
                                ->label(__('activitylog::forms.fields.properties.label'))
                                ->columnSpan('full');
                        }

                        if ($old = $record->properties->get('old')) {
                            $schema[] = KeyValue::make('old')
                                ->formatStateUsing(fn () => self::formatDateValues($old))
                                ->label(__('activitylog::forms.fields.old.label'));
                        }

                        if ($attributes = $record->properties->get('attributes')) {
                            $schema[] = KeyValue::make('attributes')
                                ->formatStateUsing(fn () => self::formatDateValues($attributes))
                                ->label(__('activitylog::forms.fields.attributes.label'));
                        }

                        return $schema;
                    }),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                static::getLogNameColumnCompoment(),
                static::getEventColumnCompoment(),
                static::getSubjectTypeColumnCompoment(),
                static::getCauserNameColumnCompoment(),
                static::getPropertiesColumnCompoment(),
                static::getCreatedAtColumnCompoment(),
            ])
            ->defaultSort(config('filament-activitylog.resources.default_sort_column', 'created_at'), config('filament-activitylog.resources.default_sort_direction', 'asc'))
            ->filters([
                static::getDateFilterComponent(),
                static::getEventFilterCompoment(),
            ]);
    }

    public static function getLogNameColumnCompoment(): Column
    {
        return TextColumn::make('log_name')
            ->label(__('activitylog::tables.columns.log_name.label'))
            ->badge()
            ->searchable()
            ->formatStateUsing(fn ($state) => ucwords($state))
            ->sortable();
    }

    public static function getEventColumnCompoment(): Column
    {
        return TextColumn::make('event')
            ->label(__('Acción')) // Traducción del encabezado
            ->formatStateUsing(fn ($state) => match ($state) {
                'created' => __('Creado'),
                'updated' => __('Actualizado'),
                'deleted' => __('Eliminado'),
                default => ucwords($state), // Por si hay otros eventos no previstos
            })
            ->badge()
            ->color(fn (string $state): string => match ($state) {
                'draft'   => 'gray',
                'updated' => 'warning',
                'created' => 'success',
                'deleted' => 'danger',
                default   => 'primary',
            })
            ->sortable();
    }
    
    public static function getSubjectTypeColumnCompoment(): Column
{
    return TextColumn::make('subject_type')
        ->label(__('Recurso Afectado'))
        ->formatStateUsing(function ($state, Model $record) {
            /** @var Activity $record */
            if (! $state || ! $record->subject) {
                return '-';
            }

            $subject = $record->subject;

            return match (get_class($subject)) {
                \App\Models\Empresa::class => $subject->Nombre_Empresa ?? 'Sin Nombre', 
                \App\Models\Tarea::class => $subject->Descripcion ?? 'Sin Nombre', 
                \App\Models\Proyecto::class => $subject->Nombre_Proyecto ?? $subject['Nombre_Proyecto'] ?? 'Sin Nombre', // Intenta ambas formas de acceso                \App\Models\Estimaciones::class => $subject->Descripcion ?? 'Sin Nombre', 
                \App\Models\Estimaciones::class => $subject->Descripcion ?? 'Sin Nombre',
                
                \App\Models\User::class => $subject->username ?? 'Sin Nombre', 
                //\App\Filament\Pages\Backups::class => $subject->tipo_accion ?? 'Sin Nombre', 

                \App\Models\Empleados::class => $subject->persona?->Nombres . ' ' . $subject->persona?->Apellidos ?? 'Sin Nombre',
                \App\Models\Persona::class => $recursoNombre = ($subject->Nombres ?? 'Sin Nombre') . ' ' . ($subject->Apellidos ?? ''),

                \App\Models\Telefono::class => $subject->empresa?->Nombre_Empresa // Si tiene empresa
                    ?? $subject->persona?->Nombres . ' ' . $subject->persona?->Apellidos // Si tiene empleado asociado
                    ?? 'Sin Nombre', // Si ninguno está asociado

                \App\Models\Correo::class => $subject->empresa?->Nombre_Empresa // Si tiene empresa
                    ?? $subject->persona?->Nombres . ' ' . $subject->persona?->Apellidos // Si tiene empleado asociado
                    ?? 'Sin Nombre', // Si ninguno está asociado

                default => Str::of($state)->afterLast('\\')->headline() . ' # ' . $record->subject_id,
            };
        })
        ->hidden(fn (Livewire $livewire) => $livewire instanceof ActivitylogRelationManager);
}

   public static function getCauserNameColumnCompoment(): Column
    {
        return TextColumn::make('causer.username')
        ->label('Realizado por')
        ->getStateUsing(function (Model $record) {
            return $record->causer ? $record-> causer->username : new HtmlString('&mdash;');
        })
        ->searchable();
    }

    public static function getPropertiesColumnCompoment(): Column
    {
        return ViewColumn::make('properties')
            ->label(__('activitylog::tables.columns.properties.label'))
            ->view('activitylog::filament.tables.columns.activity-logs-properties')
            ->toggleable(isToggledHiddenByDefault: true);
    }

    public static function getCreatedAtColumnCompoment(): Column
    {
        return TextColumn::make('created_at')
            ->label(__('Fecha Registrado')) // Cambiar el label a "Fecha Registrado"
            ->dateTime(config('filament-activitylog.datetime_format', 'd/m/Y H:i:s'))
            ->sortable();
    }
    public static function getDateFilterComponent(): Filter
    {
        return Filter::make('created_at')
            ->label(__('activitylog::tables.filters.created_at.label'))
            ->indicateUsing(function (array $data): array {
                $indicators = [];

                if ($data['created_from'] ?? null) {
                    $indicators['created_from'] = __('activitylog::tables.filters.created_at.created_from') . Carbon::parse($data['created_from'])->toFormattedDateString();
                }

                if ($data['created_until'] ?? null) {
                    $indicators['created_until'] = __('activitylog::tables.filters.created_at.created_until') . Carbon::parse($data['created_until'])->toFormattedDateString();
                }

                return $indicators;
            })
            ->form([
                DatePicker::make('created_from')
                    ->label(__('activitylog::tables.filters.created_at.created_from')),
                DatePicker::make('created_until')
                    ->label(__('activitylog::tables.filters.created_at.created_until')),
            ])
            ->query(function (Builder $query, array $data): Builder {
                return $query
                    ->when(
                        $data['created_from'],
                        fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                    )
                    ->when(
                        $data['created_until'],
                        fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                    );
            });
    }

    public static function getEventFilterCompoment(): SelectFilter
    {
        return SelectFilter::make('event')
            ->label(__('activitylog::tables.filters.event.label'))
            ->options(
                ['created' => 'Creado',
                'updated' => 'Actualizado',
                'deleted' => 'Eliminado']);
    }


    
    public static function getPages(): array
    {
        return [
            'index' => ListActivitylog::route('/'),
            'view'  => ViewActivitylog::route('/{record}'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        $plugin = Filament::getCurrentPanel()?->getPlugin('rmsramos/activitylog');

        return $plugin->getNavigationItem();
    }

    public static function canAccess(): bool
    {
        $policy = Gate::getPolicyFor(static::getModel());

        if ($policy && method_exists($policy, 'viewAny')) {
            return static::canViewAny();
        } else {
            return ActivitylogPlugin::get()->isAuthorized();
        }
    }

    
}
