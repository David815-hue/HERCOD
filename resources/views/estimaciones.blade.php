@php
    // Obtener el ID del proyecto del registro actual
    $record = $getRecord();
    
    // Ahora sí podemos acceder al Id_Proyecto
    $proyectoId = $record->Id_Proyecto;


    // Llamar al procedimiento almacenado para obtener el historial de montos
    $Estimacion = DB::select('CALL SEL_ESTIMACION(?, ?)', [$proyectoId, 'UNO']);


@endphp

<div>
    @if (!empty($Estimacion))
        <table class="table-auto w-full mt-2 border border-gray-200 dark:border-gray-700">
            <thead>
                <tr class="bg-blue-500 text-white dark:bg-blue-700">
                    <th class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">Estimacion</th>
                    <th class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">Fecha de Estimacion</th>
                    <th class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">Fecha de Subsanacion</th>
                    <th class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">Usuario Creacion</th>
                    <th class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">Fecha Creacion</th>
                    <th class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">Eliminar</th>


                </tr>
            </thead>
            <tbody>
                @foreach ($Estimacion as $estimacion)
                    <tr class="bg-white dark:bg-gray-900">
                        <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">{{ $estimacion->Estimacion }}</td>
                        <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">{{ $estimacion->Fecha_Estimacion }}</td>
                        <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">{{ $estimacion->Fecha_Subsanacion }}</td>
                        <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">{{ $estimacion->Creado_Por }}</td>
                        <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">{{ $estimacion->Fecha_Creacion }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    @else
        <p class="text-gray-700 dark:text-gray-300">No hay Estimaciones</p>
    @endif
</div>




