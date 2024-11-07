@php
    $proyectoId = $record->Id_Proyecto;

    // Llamar al procedimiento almacenado para obtener el historial de tareas
    $Tareas = DB::select('CALL SELE_TAREA(?)', [$proyectoId]);
@endphp

<div>
    @if (!empty($Tareas))
        <table class="table-auto w-full mt-2 border border-gray-200 dark:border-gray-700">
            <thead>
                <tr class="bg-blue-500 text-white dark:bg-blue-700">
                    <th class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">Tarea</th>
                    <th class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">Fecha Inicio</th>
                    <th class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">Responsable</th>
                    <th class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">Creado Por</th>
                    <th class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">Estado</th>
                    <th class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">Fecha Completado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($Tareas as $tarea)
                    <tr class="bg-white dark:bg-gray-900">
                        <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center font-bold">{{ $tarea->Descripcion }}</td>
                        <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">{{ $tarea->Fecha_Inicio }}</td>
                        <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">{{ $tarea->Responsable }}</td>
                        <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">{{ $tarea->Creado_Por }}</td>
                        <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">
                            <input type="checkbox" 
                                   class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                   @checked($tarea->Estado == 1)
                                   data-id="{{ $tarea->ID_Tarea }}"
                                   onchange="updateTareaStatus(this)">
                        </td>
                        <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">{{ $tarea->Fecha_Completado }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-gray-700 dark:text-gray-300">No hay historial de tareas</p>
    @endif
</div>
