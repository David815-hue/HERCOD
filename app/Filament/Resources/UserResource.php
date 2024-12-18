<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use ArielMejiaDev\FilamentPrintable\Actions\PrintBulkAction;
use Filament\Notifications\Notification;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction; //Para generar Excel
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use Carbon\Carbon;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'Seguridad';

    protected static ?string $label = 'Usuario';
    protected static ?string $pluralLabel = 'Usuarios';

    protected static ?int $sort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('username')
                    ->label('Usuario')
                    ->required()
                    ->maxLength(255)
                    ->maxLength(20)
                    ->regex('/^[A-Za-z]+$/'),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(User::class, 'email', ignoreRecord: true),

                TextInput::make('password')
                    ->label('Contraseña')
                    ->password()
                    ->required()
                    ->maxLength(255)
                    ->rules([
                        Password::min(8)
                            ->max(12)
                            ->mixedCase()
                            ->letters()
                            ->numbers()
                            ->symbols()
                            ->uncompromised(),
                        'regex:/^(?!\d+$).+$/',
                    ])
                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                    ->revealable(filament()->arePasswordsRevealable())
                    ->visibleOn('create'),

                TextInput::make('passwordConfirmation')
                    ->label('Confirmar Contraseña')
                    ->password()
                    ->same('password') // valida las contraseñas
                    ->required()
                    ->dehydrated(false)
                    ->revealable(filament()->arePasswordsRevealable())
                    ->visibleOn('create'),

                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->preload()
                    ->required()
                    ->searchable()
                    ->disabled(!auth()->user()->hasRole('super_admin')),

                TextInput::make('creado_por')
                    ->label('Creado Por')
                    ->disabled()
                    ->default(Auth::user()->username),

                DateTimePicker::make('fecha_creacion')
                    ->label('Fecha de Creación')
                    ->default(now())
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_usuario')
                    ->label('#')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('username')
                    ->label('Usuario')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('roles.name')
                    ->label('Roles'),
                TextColumn::make('creado_por')
                    ->label('Creado Por'),
                TextColumn::make('fecha_creacion')
                    ->label('Creación'),
            ])
            ->filters([
                DateRangeFilter::make('fecha_creacion')
                    ->timezone('UTC')
                    ->minDate(Carbon::now()->subMonth())->maxDate(Carbon::now()->addMonth())
                    ->alwaysShowCalendar(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('Exportar PDF')
                    ->label('PDF')
                    ->action('exportarPDF') // Función está en ListUsers.php
                    ->color('danger')
                    ->icon('heroicon-o-document-text'),
            ])

            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Exportar PDF')
                    ->label('PDF')
                    ->url(fn($record) => route('pdf.usuario', ['user' => $record->id_usuario]))
                    ->color('danger')
                    ->icon('heroicon-o-document-text'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()->exports([
                        ExcelExport::make('table')->fromTable()
                            ->askForFilename() 
                            ->askForWriterType() 
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
