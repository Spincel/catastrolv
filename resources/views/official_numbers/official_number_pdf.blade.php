<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Oficio de Asignación</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #111;
            margin: 0;
            padding: 20px;
        }
        
        /* Encabezado Estilo Membrete */
        .header-container {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 30px;
            position: relative;
            height: 80px; 
        }
        .header-left {
            position: absolute;
            left: 0;
            top: 0;
            width: 20%;
            text-align: left;
        }
        .header-center {
            position: absolute;
            left: 20%;
            width: 60%;
            text-align: center;
            top: 5px;
        }
        .header-right {
            position: absolute;
            right: 0;
            top: 10px;
            width: 20%;
            text-align: right;
        }
        
        .main-title {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .sub-title {
            font-size: 10px;
            font-weight: normal;
            text-transform: uppercase;
            margin: 2px 0;
        }
        
        .official-number-box {
            text-align: right;
        }
        .official-number-label {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            color: #444;
        }
        .official-number-value {
            font-size: 14px;
            font-weight: bold;
            color: #b91c1c; 
        }

        /* Datos del Solicitante */
        .info-section {
            margin-bottom: 20px;
            text-transform: uppercase;
            font-weight: bold;
            line-height: 1.4;
        }

        /* Texto Legal Justificado (Estilo Imagen) */
        .legal-text {
            text-align: justify;
            margin-bottom: 20px;
            line-height: 1.6;
            text-indent: 40px; /* Sangría como en el oficio */
        }

        /* Tablas */
        .section-header {
            background-color: #e5e7eb;
            text-align: center;
            font-weight: bold;
            padding: 5px;
            border: 1px solid #000;
            font-size: 10px;
            margin-top: 10px;
        }
        .tech-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .tech-table th, .tech-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
            font-size: 10px;
        }
        .tech-table th {
            background-color: #f9fafb;
            width: 25%;
        }

        /* Croquis */
        .croquis-container {
            text-align: center;
            margin-top: 10px;
            page-break-inside: avoid;
        }
        .croquis-box {
            display: inline-block;
            border: 2px solid #000;
            margin-top: 5px;
        }
        .croquis-img {
            width: 300px;
            height: 300px;
            object-fit: contain;
            display: block;
        }

        /* Firmas */
        .footer {
            margin-top: 60px;
            text-align: center;
            font-weight: bold;
        }
        .signature-line {
            border-top: 1px solid #000;
            width: 250px;
            margin: 0 auto;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <!-- ENCABEZADO -->
    <div class="header-container">
        <div class="header-left">
            <!-- <img src="{{ public_path('img/logo.png') }}" width="60"> -->
            <div style="background: #eee; width: 60px; height: 60px; line-height:60px; text-align:center; font-size:9px;">ESCUDO</div>
        </div>
        <div class="header-center">
            <div class="main-title">GOBIERNO MUNICIPAL DE SANTIAGO IXCUINTLA</div>
            <div class="sub-title">DIRECCIÓN DE ORDENAMIENTO TERRITORIAL,</div>
            <div class="sub-title">DESARROLLO URBANO Y ECOLOGÍA</div>
        </div>
        <div class="header-right">
            <div class="official-number-box">
                <div class="official-number-label">NÚMERO OFICIAL</div>
                <div class="official-number-value">{{ $officialNumber->official_number }}</div>
            </div>
        </div>
    </div>

    <!-- DATOS DEL SOLICITANTE -->
    <div class="info-section">
        <div>C. {{ $officialNumber->owner_name }}</div>
        <div>DOMICILIO: {{ $officialNumber->street_name }} #{{ $officialNumber->ext_number }} {{ $officialNumber->int_number ? 'INT. '.$officialNumber->int_number : '' }}</div>
        <div>COLONIA: {{ $officialNumber->suburb }}</div>
    </div>

    <!-- TEXTO LEGAL CON NO. DE SOLICITUD -->
    <div class="legal-text">
        @php
            // Formateo de fecha
            $fecha = \Carbon\Carbon::parse($officialNumber->treasury_date ?? $officialNumber->assignment_date);
            $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            $fechaTexto = $fecha->day . ' de ' . $meses[$fecha->month] . ' de ' . $fecha->year;
            
            // Formateo de Tipo
            $tipoPredio = ucfirst($officialNumber->property_type);
            if(strtolower($tipoPredio) == 'vivienda') $tipoPredio = 'Habitacional';
        @endphp

        De acuerdo a su Solicitud No. <strong>{{ $officialNumber->id }}</strong> de fecha <strong>{{ $fechaTexto }}</strong> y conforme al ingreso a Tesorería con Recibo No. <strong>{{ $officialNumber->treasury_office_number }}</strong>. Se le concede a partir de esta fecha el presente <strong>NÚMERO OFICIAL</strong> tipo <strong>{{ $tipoPredio }}</strong>, obligándose a acatar las disposiciones que rigen en esta Dirección, sustentado en el artículo 98 fracc. XIII del Reglamento de la Administración Pública para el Municipio de Santiago Ixcuintla, Nayarit.
    </div>

    <!-- TABLA DE DATOS TÉCNICOS -->
    <div class="section-header">DATOS TÉCNICOS DEL PREDIO</div>
    <table class="tech-table">
        <tr>
            <th>Superficie Total</th>
            <td>{{ number_format($officialNumber->area_sqm, 2) }} m² (Frente: {{ $officialNumber->front_measurement }}m x Fondo: {{ $officialNumber->depth_measurement }}m)</td>
        </tr>
        <!-- Referencia Cercana -->
        <tr>
            <th>Referencia de Ubicación</th>
            <td>{{ $officialNumber->referencia_cercana ?: 'Sin referencias registradas' }}</td>
        </tr>
    </table>

    <!-- CROQUIS -->
    <div class="croquis-container">
        <div style="font-weight: bold; font-size: 10px; margin-bottom: 5px;">CROQUIS DE UBICACIÓN</div>
        
        <div class="croquis-box">
            @if($officialNumber->croquis_base64)
                <img src="{{ $officialNumber->croquis_base64 }}" class="croquis-img">
            @else
                <div style="width: 400px; height: 400px; line-height: 400px;">Croquis no disponible</div>
            @endif
        </div>
    </div>

    <!-- FIRMAS -->
    <div class="footer">
        <div style="margin-bottom: 10px;">Santiago Ixcuintla, Nayarit a {{ $fechaTexto }}</div>
        <div style="margin-bottom: 40px;">ATENTAMENTE</div>
        
        <div class="signature-line"></div>
        <div style="font-size: 10px;">LIC. JOSE LUIS PALOMARES CASTILLO</div>
        <div style="font-size: 9px; font-weight: normal;">DIRECTOR DE ORDENAMIENTO TERRITORIAL,<br>DESARROLLO URBANO Y ECOLOGÍA</div>
    </div>

</body>
</html>