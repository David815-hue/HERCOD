<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User; 
use App\Models\Proyecto;

class StatsOverview extends BaseWidget
{

    //use HasPageShield;

    protected function getStats(): array
    {

        $ProyectoenProgreso = Proyecto::where('Estado', 'En progreso')->count();
        $ProyectoCompletado = Proyecto::where('Estado', 'Completado')->count();


        return [

            Stat::make('Usuarios', User::count())
                ->color('success')
                ->description('Usuarios registrados')
                ->icon('heroicon-o-users'),
            Stat::make('Empleados', Empleados::count())
                ->color('success')
                ->description('Empleados registrados')
                ->icon('heroicon-o-user-group'),
            Stat::make('Empresas', Empresa::count())
                ->color('success')
                ->description('Empresas registradas')
                ->icon('heroicon-o-building-office'),
            Stat::make('Proyectos Ingresados', Proyecto::count())
                ->color('primary')
                ->icon('heroicon-o-briefcase'),
            Stat::make('Proyectos Completados', $ProyectoCompletado)
                ->color('warning')
                ->icon('heroicon-o-check-circle'),
            Stat::make('Proyectos En progreso', $ProyectoenProgreso)
                ->color('info')
                ->icon('heroicon-o-arrow-right-circle'),
        ];

        
    }
   
}
