<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Números Oficiales</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
        }
        h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        h2 {
            font-size: 14px;
            margin-bottom: 20px;
            font-weight: normal;
            color: #555;
        }
    </style>
</head>
<body>
    <h1>Reporte de Números Oficiales</h1>
    <h2>Total de Registros: {{ $totalRecords }}</h2>
    
    <table>
        <thead>
            <tr>
                <th>Folio Oficial</th>
                <th>Propietario</th>
                <th>Ubicación</th>
                <th>Uso de Predio</th>
                <th>Fecha de Asignación</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($numbers as $number)
                <tr>
                    <td>{{ $number->official_number }}</td>
                    <td>{{ $number->owner_name }}</td>
                    <td>{{ $number->street_name }} #{{ $number->ext_number }}, {{ $number->suburb }}</td>
                    <td>{{ $number->property_type == 'vivienda' ? 'Casa Habitación' : 'Comercial' }}</td>
                    <td>{{ \Carbon\Carbon::parse($number->assignment_date)->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">No hay números para mostrar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
