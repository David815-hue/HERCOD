<?php

namespace App\Providers\Filament;

use Rmsramos\Activitylog\ActivitylogPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use Yebor974\Filament\RenewPassword\RenewPasswordPlugin;
use Awcodes\LightSwitch\LightSwitchPlugin;
use Swis\Filament\Backgrounds\FilamentBackgroundsPlugin;
use Swis\Filament\Backgrounds\ImageProviders\MyImages;
use ShuvroRoy\FilamentSpatieLaravelBackup\FilamentSpatieLaravelBackupPlugin;
use App\Filament\Pages\Backups;
use App\Filament\Widgets\ProyectosPorDepartamentoChart;



class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->sidebarCollapsibleOnDesktop()
            ->default()
            ->id('')  //admin
            ->path('')  //admin
            ->login()
            //->brandName('HERCOD')
            ->brandLogo(asset('images/logo.png')) //Habilitar logo
            ->favicon(asset('images/favicon2.png'))
            ->darkModeBrandLogo(asset('images/logodark.jpg'))
            ->passwordReset()
            //->emailVerification()
            ->profile()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                ProyectosPorDepartamentoChart::class,
                //Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                FilamentEditProfilePlugin::make()
                    ->setIcon('heroicon-o-pencil-square')
                    ->setNavigationGroup('Administracion')
                    ->shouldShowDeleteAccountForm(false),
                RenewPasswordPlugin::make()
                    ->timestampColumn('inicio_primera_vez'),
                FilamentBackgroundsPlugin::make()
                    ->imageProvider(
                        MyImages::make()
                            ->directory('images/backgrounds')
                    ), 
                LightSwitchPlugin::make(),
                FilamentSpatieLaravelBackupPlugin::make()
                    ->noTimeout()
                    ->usingPage(Backups::class),
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),

                ActivitylogPlugin::make()
                ->label('Log')
                ->pluralLabel('Bitacora')
                ->navigationItem(true)
                ->navigationGroup('Seguridad')
                ->navigationIcon('heroicon-o-document-text')
                ->navigationCountBadge(true)
                ->navigationSort(2),
            ]);
    }
}
