<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Usuarios', '2')
                ->color('success')
                ->description('Usuarios registrados')
                ->chart([0, 1, 3, 4]) // Datos del gráfico
                ->icon('heroicon-o-users'),
            Stat::make('Empleados', '1')
                ->color('success')
                ->description('Empleados registrados')
                ->chart([0, 1, 2, 5]) // Datos del gráfico
                ->icon('heroicon-o-user-group'),
            Stat::make('Empresas', '3')
                ->color('success')
                ->description('Empresas registradas')
                ->chart([0, 1, 3, 4]) // Datos del gráfico
                ->icon('heroicon-o-building-office'),
            Stat::make('Proyectos Ingresados', '5')
                ->color('primary')
                ->chart([0, 2, 4, 6]) // Datos del gráfico
                ->icon('heroicon-o-briefcase'),
            Stat::make('Proyectos Completados', '2')
                ->color('warning')
                ->chart([0, 1, 2]) // Datos del gráfico
                ->icon('heroicon-o-check-circle'),
            Stat::make('Proyectos En progreso', '3')
                ->color('info')
                ->chart([0, 1, 2, 3]) // Datos del gráfico
                ->icon('heroicon-o-arrow-right-circle'),
        ];

        
    }

   
}
