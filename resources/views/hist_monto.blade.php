@php
    // Obtener el ID del proyecto del registro actual
    $record = $getRecord();
    
    // Ahora sí podemos acceder al Id_Proyecto
    $proyectoId = $record->Id_Proyecto;

    // Llamar al procedimiento almacenado para obtener el historial de montos
    $historialMontos = DB::select('CALL SEL_HISTORIALMONTO(?)', [$proyectoId]);


@endphp

<div>
    @if (!empty($historialMontos))
        <table class="table-auto w-full mt-2 border border-gray-200 dark:border-gray-700">
            <thead>
                <tr class="bg-blue-500 text-white dark:bg-blue-700">
                    <th class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">Tipo Monto</th>
                    <th class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">Monto Anterior</th>
                    <th class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">Monto Nuevo</th>
                    <th class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">Fecha Modificacion</th>
                    <th class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">Usuario Modificacion</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($historialMontos as $historial)
                    <tr class="bg-white dark:bg-gray-900">
                        <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center font-bold">{{ $historial->TipoMonto }}</td>
                        <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">{{ $historial->Monto_Anterior }}</td>
                        <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">{{ $historial->Monto_Nuevo }}</td>
                        <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">{{ $historial->Fecha_Modificacion }}</td>
                        <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">{{ $historial->Modificado_Por }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    @else
        <p class="text-gray-700 dark:text-gray-300">No hay historial de Montos</p>
    @endif
</div>




