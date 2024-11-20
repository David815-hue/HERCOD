<?php

namespace App\Filament\Resources\ProyectoResource\Pages;

use App\Filament\Resources\ProyectoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use ArielMejiaDev\FilamentPrintable\Actions\PrintAction;
use App\Models\Proyecto;
use App\Models\Estimaciones;
use App\Models\Tarea;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Notifications\Notification;
use Carbon\Carbon;

class ListProyectos extends ListRecords
{
    protected static string $resource = ProyectoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Crear'),
        ];
    }

    //Metodo para exportar por ID a PDF
    public function exportarPDFParaProyecto(Proyecto $proyecto)
    {
        $persona = $proyecto->persona; // Accedo al Modelo Proyecto relacionado...
        if (!$proyecto) {
            Notification::make()
                ->title('¡Proyecto no encontrado!')
                ->danger()
                ->send();
            return; 
        }

        $fecha = Carbon::now()->format('d-m-Y');
        $pdf = PDF::loadView('proyecto-id-pdf', compact('proyecto', 'persona'))->setPaper('a4', 'landscape');
        return $pdf->download("Proyecto {$proyecto->Nombre_Proyecto} - {$fecha}.pdf");
    }

    public function exportarPDFParaEstimaciones(Estimaciones $estimacion)
    {
        $proyecto = $estimacion->proyecto; // Accedo al Modelo Proyecto relacionado...
        $persona = $proyecto->persona; // Accedo al Modelo Persona relacionado con ID_Proyecto y Encargado(ID)
        if (!$estimacion || !$proyecto) {
            Notification::make()
                ->title('¡Estimación o Proyecto no encontrado!')
                ->danger()
                ->send();
            return;
        }

        $fecha = Carbon::now()->format('d-m-Y'); 
        $pdf = PDF::loadView('estimacion-id-pdf', compact('estimacion', 'proyecto', 'persona'))->setPaper('a4', 'landscape');
        return $pdf->download("Estimaciones {$proyecto->Nombre_Proyecto} - {$fecha}.pdf");
    }

    public function exportarPDFParaTareas(Tarea $tarea)
    {
        $proyecto = $tarea->proyecto; // Accedo al Modelo Proyecto relacionado...
        $persona = $proyecto->persona; // Accedo al Modelo Persona relacionado con ID_Tarea y Encargado(ID)

        if (!$tarea || !$proyecto) {
            Notification::make()
                ->title('¡Tarea o Proyecto no encontrado!')
                ->danger()
                ->send();
            return;
        }

        $fecha = Carbon::now()->format('d-m-Y');
        $pdf = PDF::loadView('tarea-id-pdf', compact('tarea', 'proyecto', 'persona'))->setPaper('a4', 'landscape');
        return $pdf->download("Tareas {$proyecto->Nombre_Proyecto} - {$fecha}.pdf");
    }
    // Método para exportar a PDF
    public function exportarPDF()
    {
        $proyectos = Proyecto::with(relations: ['persona', 'estimaciones'])->get(); // Relaciono las tablas Persona y Estimaciones con Proyecto...
        
        $fecha = Carbon::now()->format('d-m-Y');
        $pdf = Pdf::loadView('reportes-proyectos-pdf', compact('proyectos'))->setPaper('a4', 'landscape');
        return response()->streamDownload(
            fn() => print($pdf->output()),
            "Reporte Proyectos - {$fecha}.pdf"
        );
    }
}
