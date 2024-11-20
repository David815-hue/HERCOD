<?php

namespace App\Filament\Resources\EmpleadosResource\Pages;

use App\Filament\Resources\EmpleadosResource;
use App\Models\DepartamentoTrabajo;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use ArielMejiaDev\FilamentPrintable\Actions\PrintAction;
use App\Models\Persona;
use App\Models\Empleados;
use Barryvdh\DomPDF\Facade\Pdf; // Para generar el PDF
use Filament\Notifications\Notification;
use Carbon\Carbon;

class ListEmpleados extends ListRecords
{
    protected static string $resource = EmpleadosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Crear'),
        ];
    }

    protected function getDefaultTableRecordUrlUsing(): ?\Closure
    {
        return fn($record): string => route('filament.admin.resources.empleados.view', ['record' => $record]);
    }

    //Metodo para exportar por ID a PDF
    public function exportarPDFParaEmpleados(Empleados $empleado)
    {

        $persona = $empleado->persona; // Accedo al Modelo Persona relacionado...
        $departamento = $empleado->departamentoTrabajo; // Accedo al Modelo Persona relacionado...

        if (!$empleado) {
            Notification::make()
                ->title('Empleado no encontrado')
                ->danger()
                ->send();
            return;
        }
        $fecha = Carbon::now()->format('d-m-Y');
        $pdf = PDF::loadView('empleado-id-pdf', compact('empleado', 'persona', 'departamento'))->setPaper('a4', 'landscape');
        return $pdf->download("Empleado {$persona->Nombres} {$persona->Apellidos} - {$fecha}.pdf");
    }

    // Método para exportar a PDF
    public function exportarPDF()
    {
        $empleados = Empleados::all();
        // $empleados = Empleados::with('persona');

        $fecha = Carbon::now()->format('d-m-Y');
        $pdf = Pdf::loadView('reportes-empleados-pdf', compact('empleados'))->setPaper('a4', 'landscape');
        return response()->streamDownload(
            fn() => print ($pdf->output()),
            "Reporte Empleados - {$fecha}.pdf"
        );
    }
}