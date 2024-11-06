<?php

namespace App\Filament\Widgets;

use App\Models\Departamentos;
use Filament\Widgets\LineChartWidget;
use Illuminate\Support\Facades\DB;

class ProyectosPorDepartamentoChart extends LineChartWidget
{
    protected static ?string $heading = 'Proyectos por Departamento';
    
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = Departamentos::select(
            'TBL_Departamento.Nom_Departamento',
            DB::raw('COALESCE(COUNT(DISTINCT tbl_proyectos.ID_Proyecto), 0) as total_proyectos')
        )
            ->leftJoin('TBL_Municipio', 'TBL_Departamento.ID_Departamento', '=', 'TBL_Municipio.ID_Departamento')
            ->leftJoin('tbl_proyectos', 'TBL_Municipio.ID_Municipio', '=', 'tbl_proyectos.ID_Municipio')
            ->groupBy('TBL_Departamento.ID_Departamento', 'TBL_Departamento.Nom_Departamento')
            ->orderBy('TBL_Departamento.Nom_Departamento')
            ->get();

        return [
            'labels' => $data->pluck('Nom_Departamento')->toArray(),
            'datasets' => [
                [
                    'label' => 'Proyectos',
                    'data' => $data->pluck('total_proyectos')->toArray(),
                ],
            ],
        ];
    }

    protected function getHeight(): int
    {
        return 500;
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'ticks' => [
                        'stepSize' => 1, // Mostrar de 1 en 1 en el eje Y
                    ],
                ],
            ],
        ];
    }
}