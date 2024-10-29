<?php

namespace App\Filament\Resources\EmpleadosResource\Pages;

use App\Filament\Resources\EmpleadosResource;

use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use App\Models\Persona;
use App\Models\Telefono;
use App\Models\Correo;
use App\Models\Empleados;
use App\Models\DepartamentoTrabajo;
use Illuminate\Support\Facades\Auth;


class CreateEmpleados extends CreateRecord
{
    protected static string $resource = EmpleadosResource::class;

    protected function handleRecordCreation(array $data): Model
{
       // Verificar si la persona ya existe
    $persona = Persona::where('DNI', $data['persona']['DNI'])->first();
    // 1. Crear la persona
    $persona = Persona::create([
        'DNI' => $data['persona']['DNI'],
        'Nombres' => $data['persona']['Nombres'],
        'Apellidos' => $data['persona']['Apellidos'],
        'Genero' => $data['persona']['Genero'],
    ]);

    // 2. Crear el correo asociado a la persona
    $correo = Correo::create([
        'ID_Persona' => $persona->ID_Persona,
        'Correo' => $data['persona']['correo']['Correo'],
    ]);

    // 3. Crear el teléfono asociado a la persona
    $telefono = Telefono::create([
        'ID_Persona' => $persona->ID_Persona,
        'Telefono' => $data['persona']['telefono']['Telefono'],
    ]);
// Crear un nuevo registro en la tabla TBL_Departamento_Trabajo
$departamentoTrabajo = DepartamentoTrabajo::create([
    'Dep_Trabajo' => $data['departamentoTrabajo']['Dep_Trabajo'],
]);



    // 4. Crear el empleado
    $empleado = Empleados::create([
        'Cargo' => $data['Cargo'],
        'Sueldo' => $data['Sueldo'],
        'Fecha_Ingreso' => $data['Fecha_Ingreso'],
        'ID_Persona' => $persona->ID_Persona,
        'ID_Departamento_trabajo' => $departamentoTrabajo->ID_Departamento_trabajo,
        'Creado_Por' => auth()->user()->Username,
    ]);

    




    return $empleado; // Asegúrate de retornar el empleado creado
}

}