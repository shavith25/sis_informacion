<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Zonas y Áreas Protegidas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            padding: 20px;
        }

        h3 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color:#e3f2fd ; 
        }

        body {
        font-family: Arial, sans-serif;
        font-size: 11px; 
        line-height: 1.4;
        }

        h3 {
        font-size: 16px;
        }

        h4 {
        font-size: 14px;
        }

        table, th, td {
        font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="container">
    <table style="width: 100%; border: none; margin-bottom: 15px;">
            <tr>
                <td style="width: 60px; border: none;">
                    <img src="{{ public_path('img/imagen2.jpg') }}" alt="Logo" height="90">
                </td>
                <td style="text-align: right; border: none; font-size: 11px;">
                    <strong>Programa Gestión de la Biodiversidad (PGB)</strong><br>
                    Gobierno Autónomo Departamental de Cochabamba
                </td>
            </tr>
        </table>

        <h3 style="text-align: center;">Zonas por Áreas Protegidas</h3>
    @foreach($zonasPorArea as $area)
    <div class="area-block">
        <table class="zona-table">
            <thead>
                <tr>
                    <th colspan="2" style="background-color: #dfe6e9; font-size: 16px; text-align: left;">
                        Área Protegidas: {{ $area->area }}
                    </th>
                </tr>
                <tr>
                    <th>Nombre de Zona</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>
                @if($area->zonas && count($area->zonas) > 0)
                    @foreach($area->zonas as $zona)
                        @php
                            $bgColor = $loop->index % 2 === 0 ? '#f2f2f2' : '#ffffff';
                        @endphp

                        <tr style="background-color: {{ $bgColor }};">
                            <td>{{ $zona->nombre }}</td>
                            <td>{{ $zona->descripcion }}</td>
                        </tr>
                        <tr style="background-color: {{ $bgColor }};">
                            <td colspan="2">
                                <strong>Imagen del Mapa:</strong><br>
                                    @php
                                        
                                        $imagenMapa = $zona->historial->firstWhere('imagen_mapa', '!=', null)->imagen_mapa ?? null;
                                    @endphp

                                    @if($imagenMapa)
                                        <img src="{{ $imagenMapa }}" alt="Mapa de Zona" style="width: 100%; max-height: 400px; object-fit: contain;">
                                    @else
                                        <div class="zona-img">Sin imagen disponible</div>
                                    @endif

                            </td>
                        </tr>
                        
                        @if($zona->eventos && count($zona->eventos) > 0)
                            <tr style="background-color: {{ $bgColor }};">
                                <td colspan="2">
                                    <strong>Eventos:</strong>
                                    <table style="width: 100%; margin-top: 10px; border: 1px solid #ccc; border-collapse: collapse;">
                                        <thead>
                                            <tr>
                                                <th style="background-color: #f1f8e9;">Nombre</th>
                                                <th style="background-color: #f1f8e9;">Descripción</th>
                                                <th style="background-color: #f1f8e9;">Tipo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($zona->eventos as $evento)
                                                <tr>
                                                    <td>{{ $evento->titulo ?? 'Sin nombre' }}</td>
                                                    <td>{{ $evento->descripcion ?? 'Sin descripción' }}</td>
                                                    <td>{{ $evento->tipo ?? 'Sin descripción' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @else
                    <tr>
                        <td colspan="2" style="text-align: center; color: #888;">No hay zonas registradas para esta área</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
@endforeach

<table style="width: 100%; margin-bottom: 20px; border-collapse: collapse; text-align: center;">
            
            <tr>
                <td colspan="3" style="background-color: #e3f2fd; padding: 15px; border: 1px solid #90caf9;">
                    <div style="font-size: 20px; font-weight: bold; color: #0d47a1;">
                        {{ $totalAreas }}
                    </div>
                    <div style="font-size: 12px; color: #555;">Total de Áreas Protegidas</div>
                </td>
                <td colspan="4" style="background-color: #f1f8e9; padding: 15px; border: 1px solid #a5d6a7;">
                    <div style="font-size: 20px; font-weight: bold; color: #1b5e20;">
                        {{ $totalZonas }}
                    </div>
                    <div style="font-size: 12px; color: #555;">Total de Zonas</div>
                </td>
            </tr>
        
            <tr>
                <td style="background-color: #fff3e0; padding: 15px; border: 1px solid #ffcc80;">
                    <div style="font-size: 18px; font-weight: bold; color: #e65100;">
                        {{ $totalIncendios }}
                    </div>
                    <div style="font-size: 11px; color: #555;">Total de Incendios</div>
                </td>
                <td style="background-color: #fce4ec; padding: 15px; border: 1px solid #f8bbd0;">
                    <div style="font-size: 18px; font-weight: bold; color: #c2185b;">
                        {{ $totalAvasallamientos }}
                    </div>
                    <div style="font-size: 11px; color: #555;">Total de Avasallamientos</div>
                </td>
                <td style="background-color: #e0f7fa; padding: 15px; border: 1px solid #80deea;">
                    <div style="font-size: 18px; font-weight: bold; color: #006064;">
                        {{ $totalInundaciones }}
                    </div>
                    <div style="font-size: 11px; color: #555;">Total de Inundaciones</div>
                </td>
                <td style="background-color: #ede7f6; padding: 15px; border: 1px solid #b39ddb;">
                    <div style="font-size: 18px; font-weight: bold; color: #4527a0;">
                        {{ $totalOtros }}
                    </div>
                    <div style="font-size: 11px; color: #555;">Total de Otros</div>
                </td>
                <td style="background-color: #fffde7; padding: 15px; border: 1px solid #fff59d;">
                    <div style="font-size: 18px; font-weight: bold; color: #f57f17;">
                        {{ $totalSequias }}
                    </div>
                    <div style="font-size: 11px; color: #555;">Total de Sequías</div>
                </td>
                <td style="background-color: #f3e5f5; padding: 15px; border: 1px solid #ce93d8;">
                    <div style="font-size: 18px; font-weight: bold; color: #6a1b9a;">
                        {{ $totalLoteamientos }}
                    </div>
                    <div style="font-size: 11px; color: #555;">Total de Loteamientos</div>
                </td>
                <td style="background-color: #e8f5e9; padding: 15px; border: 1px solid #a5d6a7;">
                    <div style="font-size: 18px; font-weight: bold; color: #2e7d32;">
                        {{ $totalBiodiversidad }}
                    </div>
                    <div style="font-size: 11px; color: #555;">Afectación a la biodiversidad</div>
                </td>
            </tr>
        </table>
    </div>
    
    <div style="page-break-before: always; text-align: center;">
        <h3 style="text-decoration: none !important;">Resumen en Gráfico de Problemas</h3>

        <img src="https://quickchart.io/chart?c={
            type:'pie',
            data: {
                labels:[
                    'Incendios',
                    'Avasallamientos',
                    'Inundaciones',
                    'Sequias',
                    'Loteamientos',
                    'Biodiversidad',
                    'Otros'
                ],
                datasets:[{
                    data:[
                        {{ $totalIncendios }},
                        {{ $totalAvasallamientos }},
                        {{ $totalInundaciones }},
                        {{ $totalSequias }},
                        {{ $totalLoteamientos }},
                        {{ $totalBiodiversidad }},
                        {{ $totalOtros }}
                    ]
                }]
            }
        }" 
        alt="Gráficos de Pastel de Problemas" style="width: 500px; height: auto;">
    </div>

</body>
</html>
