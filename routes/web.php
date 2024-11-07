<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TareaController;


/*Route::get('/', function () {
    return view('welcome');
}); */

Route::post('/update-tarea-status', [TareaController::class, 'updateStatus'])->name('updateTareaStatus');