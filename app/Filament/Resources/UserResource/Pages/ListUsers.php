<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use ArielMejiaDev\FilamentPrintable\Actions\PrintAction;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf; // Para generar el PDF
use Filament\Notifications\Notification;
use Carbon\Carbon;
class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Crear'),
        ];
    }

    //Metodo para exportar por ID a PDF
    public function exportarPDFParaUsuario(User $user)
    {
        $usuario = $user;
        // $rol = $usuario->roles();
        if (!$usuario) {
            Notification::make()
                ->title('¡Usuario no encontrado!')
                ->danger()
                ->send();
            return;
        }
        $fecha = Carbon::now()->format('d-m-Y'); 
        $pdf = PDF::loadView('user-id-pdf', compact('usuario'))->setPaper('a4', 'landscape');
        // Habilita variables {PAGE_NUM} y {PAGE_COUNT}
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isPhpEnabled', true);

        return $pdf->download("Usuario {$usuario->username} - {$fecha}.pdf");
    }

    public function exportarPDF()
    {
        $usuarios = User::all();

        $fecha = Carbon::now()->format('d-m-Y');
        $pdf = Pdf::loadView('reportes-usuarios-pdf', compact('usuarios'))->setPaper('a4', 'landscape'); 
        return response()->streamDownload(
            fn() => print($pdf->output()),
            "Reporte Usuarios - {$fecha}.pdf"
        );
    }
}
