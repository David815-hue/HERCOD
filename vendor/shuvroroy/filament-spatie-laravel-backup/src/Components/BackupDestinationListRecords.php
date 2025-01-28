<?php

namespace ShuvroRoy\FilamentSpatieLaravelBackup\Components;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Component;
use ShuvroRoy\FilamentSpatieLaravelBackup\FilamentSpatieLaravelBackup;
use ShuvroRoy\FilamentSpatieLaravelBackup\FilamentSpatieLaravelBackupPlugin;
use ShuvroRoy\FilamentSpatieLaravelBackup\Models\BackupDestination;
use Spatie\Backup\BackupDestination\Backup;
use Spatie\Backup\BackupDestination\BackupDestination as SpatieBackupDestination;

class BackupDestinationListRecords extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    /**
     * @var array<int|string, array<string, string>|string>
     */
    protected $queryString = [
        'tableSortColumn',
        'tableSortDirection',
        'tableSearchQuery' => ['except' => ''],
    ];

    public function render(): View
    {
        return view('filament-spatie-backup::components.backup-destination-list-records');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(BackupDestination::query())
            ->columns([
                Tables\Columns\TextColumn::make('path')
                    ->label(__('filament-spatie-backup::backup.components.backup_destination_list.table.fields.path'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('disk')
                    ->label(__('filament-spatie-backup::backup.components.backup_destination_list.table.fields.disk'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->label(__('filament-spatie-backup::backup.components.backup_destination_list.table.fields.date'))
                    ->dateTime()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('size')
                    ->label(__('filament-spatie-backup::backup.components.backup_destination_list.table.fields.size'))
                    ->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('disk')
                    ->label(__('filament-spatie-backup::backup.components.backup_destination_list.table.filters.disk'))
                    ->options(FilamentSpatieLaravelBackup::getFilterDisks()),
            ])
            ->actions([
                Tables\Actions\Action::make('download')
                    ->label(__('filament-spatie-backup::backup.components.backup_destination_list.table.actions.download'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn (BackupDestination $record) => Storage::disk($record->disk)->download($record->path)),

                Tables\Actions\Action::make('delete')
                    ->label(__('filament-spatie-backup::backup.components.backup_destination_list.table.actions.delete'))
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->modalIcon('heroicon-o-trash')
                    ->action(function (BackupDestination $record) {
                        // Encuentra el backup relacionado
                        $backup = SpatieBackupDestination::create($record->disk, config('backup.backup.name'))
                            ->backups()
                            ->first(function (Backup $backup) use ($record) {
                            return $backup->path() === $record->path;
                        });

                        
                        if ($backup) {
                            
                            $backup->delete();

                            activity('Actividad Backup') 
                                ->causedBy(auth()->user()) 
                                ->event('deleted') 
                                ->withProperties([
                                    'tipo_accion' => 'Eliminar Backup',
                                    'ruta' => $backup->path(),
                                ])
                                ->log('Se ha eliminado el backup: ' . $record->name); // 
                            Notification::make()
                                ->title('Backup eliminado con éxito')
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Error al eliminar el backup')
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([
                // ...
            ]);
    }

    #[Computed]
    public function interval(): string
    {
        /** @var FilamentSpatieLaravelBackupPlugin $plugin */
        $plugin = filament()->getPlugin('filament-spatie-backup');

        return $plugin->getPolingInterval();
    }
}
