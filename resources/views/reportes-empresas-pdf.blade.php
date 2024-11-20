<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de empresas</title>
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

    <h2 style="text-align: center;">Listado de Empresas</h2>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>RTN</th>
                <th>Nombre de Empresa</th>
                <th>Creado por</th>
                <th>Fecha Creación</th>
                <th>Departamento</th>
                <th>Municipio</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($empresas as $empresa)
            <tr>
                <td>{{ $empresa->ID_Empresa }}</td>
                <td>{{ $empresa->RTN }}</td>
                <td>{{ $empresa->Nombre_Empresa }}</td>
                <td>{{ $empresa->Creado_Por }}</td>
                <td>{{ $empresa->Fecha_Creacion }}</td>
                <td>{{ $empresa->ID_Departamento }}</td>
                <td>{{ $empresa->ID_Municipio }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <footer>
        Página {PAGE_NUM} de {PAGE_COUNT}
    </footer>
</body>

</html>