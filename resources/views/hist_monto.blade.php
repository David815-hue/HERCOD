@php
    // Obtener el ID del proyecto del registro actual
    $record = $getRecord();
    
    // Ahora sí podemos acceder al Id_Proyecto
    $proyectoId = $record->ID_Proyecto;

    // Llamar al procedimiento almacenado para obtener el historial de montos
    $historialMontos = DB::select('CALL SEL_HISTORIALMONTO(?)', [$proyectoId]);


@endphp


<div class="overflow-x-auto">
    @if (!empty($historialMontos))
        <table class="table-auto w-full mt-2 border border-gray-200 dark:border-gray-700 text-sm sm:text-base">
            <thead>
                <tr class="bg-blue-500 text-white dark:bg-blue-700">
                    <th class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">Tipo Monto</th>
                    <th class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">Monto Anterior</th>
                    <th class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">Monto Nuevo</th>
                    <th class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">Fecha Modificación</th>
                    <th class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">Usuario Modificación</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($historialMontos as $historial)
                    <tr class="bg-white dark:bg-gray-900">
                        <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center font-bold">{{ $historial->TipoMonto }}</td>
                        <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">L. {{ number_format($historial->Monto_Anterior, 2) }}</td>
                        <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">L. {{ number_format($historial->Monto_Nuevo, 2) }}</td>
                        <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">{{ \Carbon\Carbon::parse($historial->Fecha_Modificacion)->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 text-center">{{ $historial->Modificado_Por }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-gray-700 dark:text-gray-300">No hay historial de montos</p>
    @endif
</div>