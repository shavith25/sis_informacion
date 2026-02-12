<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Zonas y Áreas Protegidas</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* --- UTILIDADES --- */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        .mb-10 { margin-bottom: 10px; }
        .mb-20 { margin-bottom: 20px; }
        .w-100 { width: 100%; }
        .uppercase { text-transform: uppercase; }

        /* --- HEADER --- */
        .header-container {
            border-bottom: 2px solid #004a80; /* Azul Institucional */
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        
        .header-title {
            color: #004a80;
            font-size: 18px;
            margin: 0;
        }

        .header-subtitle {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }

        /* --- SECCIONES DE ÁREAS --- */
        .area-container {
            margin-bottom: 20px;
            page-break-inside: auto;
        }
        
        .area-header {
            page-break-after: avoid;
            background-color: #004a80;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        /* --- ZONAS (Estilo Ficha) --- */
        .zona-card {
            border: 1px solid #ddd;
            border-top: none; 
            padding: 12px;
            background-color: #fff;
            margin-bottom: 12px;
            page-break-inside: auto;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .info-table th {
            text-align: left;
            color: #555;
            width: 120px;
            vertical-align: top;
            padding: 4px 0;
        }

        .info-table td {
            color: #000;
            padding: 4px 0;
            vertical-align: top;
        }

        /* --- IMAGEN DEL MAPA --- */
        .map-container {
            border: 1px solid #eee;
            padding: 5px;
            background: #f9f9f9;
            text-align: center;
            border-radius: 4px;
        }

        .map-img {
            max-height: 350px;
            max-width: 100%;
            object-fit: contain;
        }

        .no-img {
            padding: 40px;
            color: #999;
            font-style: italic;
            background: #f0f0f0;
        }

        /* --- TABLA DE EVENTOS --- */
        .event-table {
            vertical-align: top;
            padding: 8px 6px;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .event-table th {
            background-color: #f1f1f1;
            border-bottom: 2px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        .event-table td {
            border-bottom: 1px solid #eee;
            padding: 6px;
        }

        .page-break {
            page-break-after: always;
        }

        /* --- RESUMEN KPI (ESTADÍSTICAS) --- */
        .kpi-table {
            width: 100%;
            border-spacing: 10px;
            border-collapse: separate; 
        }

        .kpi-card {
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 15px;
            text-align: center;
            background: #fff;
        }

        .kpi-number {
            font-size: 24px;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .kpi-label {
            font-size: 10px;
            text-transform: uppercase;
            color: #777;
            letter-spacing: 0.5px;
        }

        /* Colores para KPIs */
        .kpi-blue { border-top: 4px solid #004a80; color: #004a80; }
        .kpi-green { border-top: 4px solid #28a745; color: #28a745; }
        .kpi-red { border-top: 4px solid #dc3545; color: #dc3545; }
        .kpi-orange { border-top: 4px solid #fd7e14; color: #fd7e14; }
        .kpi-purple { border-top: 4px solid #6f42c1; color: #6f42c1; }
        .kpi-cyan { border-top: 4px solid #17a2b8; color: #17a2b8; }
    </style>
</head>
<body>

    <table class="w-100 header-container">
        <tr>
            <td width="20%">
                <img src="{{ public_path('img/imagen2.jpg') }}" alt="Logo" style="height: 100px; width: auto;">
            </td>
            <td width="80%" class="text-right">
                <h1 class="header-title">GOBIERNO AUTÓNOMO DEPARTAMENTAL DE COCHABAMBA</h1>
                <div class="header-subtitle">Programa Gestión de la Biodiversidad (PGB)</div>
                <div style="font-size: 11px; color: #888; margin-top: 5px;">Fecha de reporte: {{ date('d/m/Y') }}</div>
            </td>
        </tr>
    </table>

    <h2 class="text-center mb-20" style="color: #333; text-decoration: underline;">REPORTE TÉCNICO DE ZONAS Y ÁREAS PROTEGIDAS</h2>

    @foreach($zonasPorArea as $area)
        <div class="area-container">
            <div class="area-header">
                <span class="text-bold">ÁREA PROTEGIDA:</span> {{ strtoupper($area->area) }}
            </div>

            <div class="zona-card">
                @if($area->zonas && count($area->zonas) > 0)
                    @foreach($area->zonas as $zona)
                        
                        <table class="info-table">
                            <tr>
                                <th>Nombre de Zona:</th>
                                <td class="text-bold" style="font-size: 13px;">{{ $zona->nombre }}</td>
                            </tr>
                            <tr>
                                <th>Descripción:</th>
                                <td>{{ $zona->descripcion }}</td>
                            </tr>
                        </table>

                        <div class="mb-10">
                            <strong>Mapa Satelital de Referencia:</strong>
                        </div>
                        <div class="map-container mb-20">
                            @php
                                $h = $zona->historial
                                    ->whereNotNull('imagen_mapa')
                                    ->sortByDesc('created_at')
                                    ->first();

                                $imagenMapa = $h ? $h->imagen_mapa : null;
                            @endphp

                            @if($imagenMapa)
                                <img src="{{ $imagenMapa }}" class="map-img">
                            @else
                                <div class="no-img">No hay imagen cartográfica disponible para esta zona.</div>
                            @endif
                        </div>

                        @if($zona->eventos && count($zona->eventos) > 0)
                            <div class="mb-10" style="background: #f8f9fa; padding: 10px; border-left: 3px solid #004a80;">
                                <strong style="color: #004a80;">Registro de Incidencias / Eventos:</strong>
                                <table class="event-table">
                                    <thead>
                                        <tr>
                                            <th width="30%">Evento</th>
                                            <th width="50%">Detalle</th>
                                            <th width="20%">Tipo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($zona->eventos as $evento)
                                            @php
                                                $imgs = $evento->medios->where('tipo','imagen');
                                            @endphp

                                        <tr>
                                            <td style="vertical-align: top;">
                                                {{ $evento->titulo ?? 'N/A' }}
                                            </td>

                                            <td style="vertical-align: top;">
                                                <div>{{ $evento->descripcion ?? 'Sin descripción' }}</div>

                                                @if($imgs->count())
                                                    <div style="font-weight:bold; margin-top:6px; margin-bottom:4px;">Imágenes:</div>

                                                    <table style="border-collapse:collapse;">
                                                    <tr>
                                                        @foreach ($imgs->take(4) as $img)
                                                            @php $p = public_path('storage/' . $img->url); @endphp
                                                            @if(file_exists($p))
                                                                <td style="padding-right:6px;">
                                                                    <img src="{{ $p }}"
                                                                        style="display:block; width:110px; height:75px; object-fit:cover; border:1px solid #ccc; border-radius:4px;">
                                                                    </td>
                                                            @endif
                                                        @endforeach
                                                    </tr>
                                                </table>

                                                    @if($imgs->count() > 4)
                                                        <div style="font-size:10px; color:#666; margin-top:3px;">
                                                            +{{ $imgs->count() - 4 }} más...
                                                        </div>
                                                    @endif
                                                @endif
                                            </td>

                                            <td style="vertical-align: top;">
                                                <span style="background:#eee; padding:2px 5px; border-radius:3px; font-size:10px;">
                                                    {{ strtoupper($evento->tipo) }}
                                                </span>
                                            </td>
                                    </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        @endif

                        @if(!$loop->last)
                            <hr style="border: 0; border-top: 1px dashed #ccc; margin: 20px 0;">
                        @endif

                    @endforeach
                @else
                    <div class="text-center" style="padding: 20px; color: #666;">
                        No existen zonas registradas para esta área protegida.
                    </div>
                @endif
            </div>
        </div>
    @endforeach

    <div style="page-break"></div>

    <h2 class="text-center mb-20" style="color: #004a80;">RESUMEN ESTADÍSTICO</h2>

    <table class="kpi-table mb-20">
        <tr>
            <td width="50%">
                <div class="kpi-card kpi-blue">
                    <span class="kpi-number">{{ $totalAreas }}</span>
                    <span class="kpi-label">Áreas Protegidas</span>
                </div>
            </td>
            <td width="50%">
                <div class="kpi-card kpi-green">
                    <span class="kpi-number">{{ $totalZonas }}</span>
                    <span class="kpi-label">Zonas Monitoreadas</span>
                </div>
            </td>
        </tr>
    </table>

    <h4 style="margin-bottom: 10px; color: #555; border-bottom: 1px solid #ddd; padding-bottom: 5px;">Desglose de Incidencias</h4>
    
    <table class="kpi-table mb-20">
        <tr>
            <td>
                <div class="kpi-card kpi-red">
                    <span class="kpi-number">{{ $totalIncendios }}</span>
                    <span class="kpi-label">Incendios</span>
                </div>
            </td>
            <td>
                <div class="kpi-card kpi-orange">
                    <span class="kpi-number">{{ $totalAvasallamientos }}</span>
                    <span class="kpi-label">Avasallamientos</span>
                </div>
            </td>
            <td>
                <div class="kpi-card kpi-cyan">
                    <span class="kpi-number">{{ $totalInundaciones }}</span>
                    <span class="kpi-label">Inundaciones</span>
                </div>
            </td>
            <td>
                <div class="kpi-card kpi-purple">
                    <span class="kpi-number">{{ $totalLoteamientos }}</span>
                    <span class="kpi-label">Loteamientos</span>
                </div>
            </td>
        </tr>
    </table>

    <table class="kpi-table mb-20">
        <tr>
            <td width="33%">
                <div class="kpi-card" style="border-top: 4px solid #f9ca24; color: #f9ca24;">
                    <span class="kpi-number">{{ $totalSequias }}</span>
                    <span class="kpi-label">Sequías</span>
                </div>
            </td>
            <td width="33%">
                <div class="kpi-card" style="border-top: 4px solid #6ab04c; color: #6ab04c;">
                    <span class="kpi-number">{{ $totalBiodiversidad }}</span>
                    <span class="kpi-label">Afect. Biodiversidad</span>
                </div>
            </td>
            <td width="33%">
                <div class="kpi-card" style="border-top: 4px solid #535c68; color: #535c68;">
                    <span class="kpi-number">{{ $totalOtros }}</span>
                    <span class="kpi-label">Otros</span>
                </div>
            </td>
        </tr>
    </table>

    <div class="text-center" style="margin-top: 30px; border: 1px solid #eee; padding: 20px; border-radius: 8px;">
        <h4 class="text-center mb-10" style="color: #333;">Representación Gráfica Porcentual</h4>
        <img src="https://quickchart.io/chart?c={
            type:'doughnut',
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
                    ],
                    backgroundColor: [
                        'rgb(220, 53, 69)',
                        'rgb(253, 126, 20)',
                        'rgb(23, 162, 184)',
                        'rgb(249, 202, 36)',
                        'rgb(111, 66, 193)',
                        'rgb(40, 167, 69)',
                        'rgb(83, 92, 104)'
                    ]
                }]
            },
            options: {
                plugins: {
                    datalabels: { display: true, color: 'white' }
                }
            }
        }" 
        alt="Gráfico de Problemas" style="width: 450px; height: auto;">
    </div>

</body>
</html>