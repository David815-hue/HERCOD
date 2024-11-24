<?php

namespace App\Filament\Resources\EmpresaResource\Pages;

use App\Filament\Resources\EmpresaResource;
use App\Models\Departamentos;
use App\Models\Direcciones;
use App\Models\Municipio;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use ArielMejiaDev\FilamentPrintable\Actions\PrintAction;
use App\Models\Empresa;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Notifications\Notification;
use Carbon\Carbon;

class ListEmpresa extends ListRecords
{
    protected static string $resource = EmpresaResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Crear'),
        ];
    }

    //Metodo para exportar por ID a PDF
    public function exportarPDFParaEmpresas(Empresa $empresa)
    {
        $direccion = $empresa->direcciones;
        $municipio = $direccion->municipio;
        $departamento = $municipio->departamento;

        if (!$empresa) {
            Notification::make()
                ->title('Empresa no encontrado')
                ->danger()
                ->send();
            return;
        }
        $fecha = Carbon::now()->format('d-m-Y');
        $pdf = PDF::loadView('empresa-id-pdf', compact('empresa', 'municipio', 'departamento'))->setPaper('a4', 'landscape');
        return $pdf->download("Empresa {$empresa->Nombre_Empresa} - {$fecha}.pdf");
    }

    // Método para exportar a PDF
    public function exportarPDF()
    {
        $empresas = Empresa::with(['direcciones.municipio.departamento'])->get(); // Carga las relaciones correctamente
        //municipio, departamento

        $fecha = Carbon::now()->format('d-m-Y');
        $pdf = Pdf::loadView('reportes-empresas-pdf', compact('empresas'))->setPaper('a4', 'landscape');
        return response()->streamDownload(
            fn() => print ($pdf->output()),
            "Reporte Empresas - {$fecha}.pdf"
        );
    }
}