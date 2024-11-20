<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TareaController;
use App\Filament\Resources\ProyectoResource\Pages\ListProyectos;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Filament\Resources\EmpresaResource\Pages\ListEmpresa;
use App\Filament\Resources\EmpleadosResource\Pages\ListEmpleados;



Route::post('/update-tarea-status', [TareaController::class, 'updateStatus'])->name('updateTareaStatus');
Route::get('/pdf/generate/usuario/{user}', [ListUsers::class, 'exportarPDFParaUsuario'])
    ->name('pdf.usuario');
Route::get('/pdf/generate/proyecto/{proyecto}', [ListProyectos::class, 'exportarPDFParaProyecto'])
    ->name('pdf.proyecto');
Route::get('/pdf/generate/estimacion/{estimacion}', [ListProyectos::class, 'exportarPDFParaEstimaciones'])
    ->name('pdf.estimacion');
Route::get('/pdf/generate/tarea/{tarea}', [ListProyectos::class, 'exportarPDFParaTareas'])
    ->name('pdf.tarea');
Route::get('/pdf/generate/empresa/{empresa}', [ListEmpresa::class, 'exportarPDFParaEmpresas'])
    ->name('pdf.empresa');
Route::get('/pdf/generate/empleado/{empleado}', [ListEmpleados::class, 'exportarPDFParaEmpleados'])
    ->name('pdf.empleado');
