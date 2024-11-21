<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de {{ $persona->Nombres }} </title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        img {
            height: 25px;
            opacity: 0.4;
            margin-right: 15px;
        }

        .header .company-info {
            text-align: center;
        }

        .header h1 {
            font-size: 22px;
            margin: 0;
        }

        .header p {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Define márgenes para el contenido principal y espacio para el pie */
        body {
            margin-top: 20px;
            margin-bottom: 40px;
        }

        footer {
            position: fixed;
            bottom: 0px;
            left: 0;
            right: 0;
            height: 30px;
            text-align: center;
            font-size: 12px;
            color: #555;
            border-top: 1px solid #ddd;
            line-height: 30px;
        }

        @page {
            margin: 20px;
            margin-bottom: 60px;
            /* Espacio para el pie de página */
        }
    </style>
</head>

<body>
    <div class="header">
        <a><img src="{{ public_path('/images/logo.png') }}" alt="Logo de la Empresa"></a>
        <div class="company-info">
            <h1>HERCOD Constructora</h1>
            <p>Distrito Artemisa, El Trapiche, Tegucigalpa, Francisco Morazán, Honduras</p>
            <p>Teléfono: +504 9237-7721 | Correo: constructorahercod@gmail.com</p>
        </div>
    </div>

    <h2 style="text-align: center;">Reporte de {{ $proyecto->Nombre_Proyecto }} </h2>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Número de Contrato</th>
                <th>Nombre del Proyecto</th>
                <th>Fecha Firma de Contrato</th>
                <th>Fecha Orden de Inicio</th>
                <th>Monto Contractual</th>
                <th>Monto Anticipo</th>
                <th>Monto Final</th>
                <th>Direccion</th>
                <th>Estado</th>
                <th>Encargado</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $proyecto->ID_Proyecto }}</td>
                <td>{{ $proyecto->NumeroContrato }}</td>
                <td>{{ $proyecto->Nombre_Proyecto }}</td>
                <td>{{ $proyecto->Fecha_FirmaContrato }}</td>
                <td>{{ $proyecto->Fecha_OrdenInicio }}</td>
                <td>{{ number_format($proyecto->Monto_Contractual, 2, '.', ',') }}</td>
                <td>{{ number_format($proyecto->Anticipo, 2, '.', ',') }}</td>
                <td>{{ number_format($proyecto->Monto_Final, 2, '.', ',') }}</td>
                <td>{{ $proyecto->Direccion }}</td>
                <td>{{ $proyecto->Estado }}</td>
                <td>{{ $persona->Nombres }} {{ $persona->Apellidos }}</td>
            </tr>
            {{-- Verificar si el proyecto tiene tareas --}}
            <!-- @if ($tareas->isNotEmpty())
                <tr>
                    <td colspan="11">
                        <table style="width: 100%; border: none;">
                            <thead>
                            <tr>
                                <th>Descripción</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Completado</th>
                                <th>Estado</th>
                                <th>Creado por</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($tareas as $tarea)
                                <tr>
                                    <td>{{ $tarea->Descripcion }}</td>
                                    <td>{{ $tarea->Fecha_Inicio }}</td>
                                    <td>{{ $tarea->Fecha_Completado }}</td>
                                    <td>{{ $tarea->Estado }}</td>
                                    <td>{{ $tarea->Creado_Por }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
            @else
                <tr>
                    <td colspan="11" style="text-align: center;">
                        Este proyecto no tiene tareas.
                    </td>
                </tr>
            @endif -->
            {{-- Verificar si el proyecto tiene estimaciones --}}
            @if ($estimaciones->isNotEmpty())
                        <tr>
                            <td colspan="11">
                                <table style="width: 100%; border: none;">
                                    <thead>
                                        <tr>
                                            <th>Descripción</th>
                                            <th>Fecha creación</th>
                                            <th>Fecha de Subsanación</th>
                                            <th>Fecha de Estimación</th>
                                            <th>Estimación</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($estimaciones as $estimacion)
                                            <tr>
                                                <td>{{ $estimacion->Descripcion }}</td>
                                                <td>{{ $estimacion->Fecha_Creacion }}</td>
                                                <td>{{ $estimacion->Fecha_Subsanacion }}</td>
                                                <td>{{ $estimacion->Fecha_Estimacion }}</td>
                                                <td>{{ number_format($estimacion->Estimacion, 2, '.', ',') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        {{-- Calcular el total de estimaciones --}}
                        @php
                            $totalEstimaciones = $estimaciones->sum('Estimacion');
                            $anticipo = $proyecto->Anticipo ?? 0;
                            $montoContractual = $proyecto->Monto_Contractual ?? 0;
                            $montoFaltante = $montoContractual - $totalEstimaciones - $anticipo;
                        @endphp
                        <tr>
                            <td colspan="11" style="text-align: right; font-weight: bold;">
                                Total Estimaciones: {{ number_format($totalEstimaciones, 2, '.', ',') }}<br>
                                Anticipo: {{ number_format($anticipo, 2, '.', ',') }}<br>
                                Monto Contractual: {{ number_format($montoContractual, 2, '.', ',') }}<br>
                                Monto Faltante: {{ number_format($montoFaltante, 2, '.', ',') }}
                            </td>
                        </tr>
            @else
                <tr>
                    <td colspan="11" style="text-align: center;">
                        Este proyecto no tiene estimaciones.
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
    <footer>
        Página {PAGE_NUM} de {PAGE_COUNT}
    </footer>
    <!-- <footer>
        Página 
        <script type="text/php">
            if (isset($pdf)) {
                $pdf->page_script('
                    $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                    $pdf->text(270, 820, "Página " . $PAGE_NUM . " de " . $PAGE_COUNT, $font, 10);
                ');
            }
        </script> 
    </footer> -->
</body>

</html>