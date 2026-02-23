@extends('layouts.app')

@section('content')

    @role('Administrador')
        <section class="section dashboard-bg" style="min-height: calc(100vh - 135px);">
            <div class="background-overlay"></div>

            <div class="section-header position-relative header-adjusted">
                <h3 class="page__heading text-primary">Programa Gestión de la Biodiversidad (PGB) 🌳</h3>
            </div>

            <div class="section-body" style="padding: 10px; position: relative; z-index: 2;">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                        <div class="card card-statistic-2 statistic-zone" style="cursor: pointer;" id="zonasCard">
                            <div class="card-icon card-icon-bg">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4 style="font-weight: bold; font-size: 18px;">Zonas Registradas</h4>
                                </div>
                                <div class="card-body">
                                    {{ $totalZonas }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                        <div class="card card-statistic-2 statistic-area" style="cursor: pointer;" id="AreasCard">
                            <div class="card-icon card-icon-bg">
                                <i class="fas fa-draw-polygon"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4 style="font-weight: bold; font-size: 18px;">Áreas Protegidas</h4>
                                </div>
                                <div class="card-body">
                                    {{ $totalAreas }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                        <div class="card card-statistic-2 statistic-user" style="cursor: pointer;" id="UsuariosCard">
                            <div class="card-icon card-icon-bg">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4 style="font-weight: bold; font-size: 18px;">Usuarios Registrados</h4>
                                </div>
                                <div class="card-body">
                                    {{ $totalUsuarios }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 col-md-12 mb-4">
                        <div class="card chart-card-custom h-90">
                            <div class="card-header">
                                <h4><i class="fas fa-chart-bar"></i> Zonas registradas por mes</h4>
                            </div>
                            <div class="card-body">
                                <canvas id="barChart" height="360" style="border-radius: 12px"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-6 col-md-12 mb-4">
                        <div class="card chart-card-custom h-90">
                            <div class="card-header">
                                <h4><i class="fas fa-chart-line"></i> Tendencia de registro de Zonas</h4>
                            </div>
                            <div class="card-body">
                                <canvas id="lineChart" height="360" style="border-radius: 12px"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <style>
            .dashboard-bg { overflow: hidden; border-radius: 12px; }
            .section-header { margin-bottom: 20px; padding: 10px 20px; border-bottom: none; }
            .header-adjusted { margin-top: 40px; padding-bottom: 20px; }
            .page__heading { font-weight: 700; text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5); z-index: 2; }
            .card-statistic-2 { border-radius: 10px; box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15); transition: transform 0.3s ease; min-height: 100px; cursor: pointer; }
            .card-statistic-2:hover { transform: translateY(-5px); }
            .card-icon-bg { color: white; padding: 15px; border-radius: 50%; font-size: 24px; position: absolute; top: 20px; left: 20px; }
            .card-statistic-2 .card-wrap { padding: 20px 20px 20px 100px; }
            .statistic-zone .card-icon-bg { background-color: #1e8449 !important; }
            .statistic-area .card-icon-bg { background-color: #5cb85c !important; }
            .statistic-user .card-icon-bg { background-color: #007bff !important; } 
            .badge-active { background-color: #28a745; color: white; padding: 5px 10px; border-radius: 4px; }
            .badge-inactive { background-color: #6c757d; color: white; padding: 5px 10px; border-radius: 4px; }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const labels = @json($labels ?? []); 
                const dataZonas = @json($data ?? []);
                
                const dataTendencia = dataZonas.map((d, i) => { return d + (i * 0.5); });

                const barCtx = document.getElementById('barChart').getContext('2d');
                new Chart(barCtx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{ label: 'Zonas Registradas (Barras)', data: dataZonas, backgroundColor: '#28a745' }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
                });

                const lineCtx = document.getElementById('lineChart').getContext('2d');
                new Chart(lineCtx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{ label: 'Tendencia', data: dataZonas, borderColor: '#007bff', fill: true }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
                });

                document.getElementById('zonasCard').addEventListener('click', function() {
                    window.location.href = "{{ route('zonas.index') }}";
                });
                
                document.getElementById('AreasCard').addEventListener('click', function() { window.location.href = "{{ route('areas.index') }}"; });
                document.getElementById('UsuariosCard').addEventListener('click', function() { window.location.href = "{{ route('usuarios.index') }}"; });
            });
        </script>

    @else
    <section class="section">

<style>
    .gov-hero{
        border-radius: 18px;
        padding: 28px 28px;
        background: linear-gradient(135deg, #1e3a8a, #2563eb, #4f46e5);
        color: #fff;
        box-shadow: 0 18px 50px rgba(30,58,138,.25);
        position: relative;
        overflow: hidden;
    }

    .gov-hero::after{
        content:"";
        position:absolute; inset:-80px auto auto -80px;
        width:200px; height:200px; border-radius:50%;
        background: rgba(255,255,255,.10);
        filter: blur(0px);
    }

    .gov-hero::before{
        content:"";
        position:absolute; inset:auto -80px -80px auto;
        width:260px; height:260px; border-radius:50%;
        background: rgba(255,255,255,.08);
    }

    .kpi-card{
        border-radius: 16px;
        border: 1px solid rgba(15,23,42,.08);
        box-shadow: 0 12px 30px rgba(15,23,42,.06);
        transition: transform .15s ease, box-shadow .15s ease;
        overflow:hidden;
        background:#fff;
    }

    .kpi-card:hover{ transform: translateY(-2px); box-shadow: 0 18px 40px rgba(15,23,42,.10); }
    .kpi-topline{ height: 4px; background: linear-gradient(90deg,#2563eb,#4f46e5); }
    .kpi-icon{
        width:44px; height:44px; border-radius:12px;
        display:flex; align-items:center; justify-content:center;
        background: rgba(37,99,235,.10);
        color:#1d4ed8;
        font-weight:800;
        flex: 0 0 auto;
    }

    .kpi-value{ font-size: 28px; font-weight: 900; color:#0f172a; line-height:1; }
    .kpi-label{ color:#64748b; font-weight: 700; margin:0; }
    .kpi-sub{ color:#94a3b8; font-size:.9rem; margin:0; }

    .panel-card{
        border-radius: 16px;
        border: 1px solid rgba(15,23,42,.08);
        box-shadow: 0 12px 30px rgba(15,23,42,.06);
        background:#fff;
        overflow:hidden;
    }

    .panel-head{
        padding: 14px 18px;
        border-bottom:1px solid rgba(15,23,42,.08);
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
        background: linear-gradient(180deg, #f8fafc, #ffffff);
    }

    .panel-head:before{
        content:"";
        position:absolute; inset:0;
        border-radius:16px;
        background: rgba(255,255,255,.10);
        pointer-events:none;
    }

    .panel-head *{ position:relative; z-index:1; }
    .panel-title{
        margin:0;
        font-weight: 900;
        color:#0f172a;
        letter-spacing:-.01em;
    }

    .panel-title::before{
        content:"";
        position:absolute; inset:0;
        border-radius:16px;
        background: rgba(79,70,229,.10);
        filter: blur(12px);
        z-index:-1;
    }

    .panel-body{ padding: 18px; }

    .quick-grid{
        display:grid;
        grid-template-columns: repeat(4, minmax(0,1fr));
        gap:12px;
    }
    
    @media(max-width: 1400px){ .quick-grid{ grid-template-columns: repeat(3, minmax(0,1fr)); } }
    @media(max-width: 1200px){ .quick-grid{ grid-template-columns: repeat(2, minmax(0,1fr)); } }
    @media(max-width: 600px){ .quick-grid{ grid-template-columns: 1fr; } }

    .quick-btn{
        border-radius: 14px;
        padding: 14px 14px;
        border: 1px solid rgba(15,23,42,.08);
        background: #ffffff;
        text-decoration:none;
        display:flex;
        gap:12px;
        align-items:flex-start;
        box-shadow: 0 10px 24px rgba(15,23,42,.05);
        transition: transform .15s ease, box-shadow .15s ease;
        height:100%;
    }

    .quick-btn:focus{
        outline: 2px solid #4f46e5;
        outline-offset: 2px;
    }

    .quick-btn:hover{ transform: translateY(-2px); box-shadow: 0 16px 34px rgba(15,23,42,.10); text-decoration:none; }
    .quick-title{ margin:0; font-weight: 900; color:#0f172a; }
    .quick-desc{ margin:0; color:#64748b; font-size:.92rem; }
    .quick-ico{
        width:40px;height:40px;border-radius:12px;
        background: rgba(79,70,229,.10);
        display:flex;align-items:center;justify-content:center;
        color:#4338ca;font-weight:900;
        flex:0 0 auto;
    }

    .table td, .table th{ vertical-align: middle !important; }
    .badge-soft{
        border-radius: 999px;
        padding: 6px 10px;
        font-weight: 800;
        background: rgba(37,99,235,.10);
        color:#1d4ed8;
        border: 1px solid rgba(37,99,235,.15);
    }

</style>

<div class="section-header">
    <h3 class="page__heading">Bienvenido: {{ Auth::user()->name }}</h3>
</div>

<div class="section-body">

    {{-- HERO --}}
    <div class="gov-hero mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between" style="gap:14px;">
            <div>
                <h2 class="mb-1" style="font-weight:900; letter-spacing:-.02em;">
                    Programa de Gestión de la Biodiversidad
                </h2>

                <div style="opacity:.92;">
                    Bienvenido/a, <strong>{{ Auth::user()->name }}</strong> ·
                    Rol: <span class="badge badge-light">{{ Auth::user()->roles->pluck('name')->first() ?? 'Sin Rol' }}</span>
                </div>
            </div>

            <div class="text-right" style="opacity:.92;">
                <div style="font-weight:800;">Estado del sistema</div>
                <div style="font-size:.92rem;">Acceso autenticado · Control por permisos</div>
            </div>
        </div>
    </div>

    {{-- KPIs --}}
    <div class="row">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="kpi-card">
                <div class="kpi-topline"></div>
                    <div class="p-3 d-flex align-items-center" style="gap:12px;">
                <div class="kpi-icon">Z</div>
                    <div>
                        <div class="kpi-value">{{ $stats['zonas'] ?? 0 }}</div>
                        <p class="kpi-label">Zonas protegidas</p>
                        <p class="kpi-sub">Registros disponibles</p>
                    </div>
                </div>
            </div>
        </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="kpi-card">
            <div class="kpi-topline"></div>
                <div class="p-3 d-flex align-items-center" style="gap:12px;">
                <div class="kpi-icon">A</div>
                <div>
                <div class="kpi-value">{{ $stats['areas'] ?? 0 }}</div>
                <p class="kpi-label">Áreas protegidas</p>
                <p class="kpi-sub">Inventario institucional</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="kpi-card">
            <div class="kpi-topline"></div>
            <div class="p-3 d-flex align-items-center" style="gap:12px;">
                <div class="kpi-icon">E</div>
                <div>
                <div class="kpi-value">{{ $stats['especies'] ?? 0 }}</div>
                <p class="kpi-label">Especies</p>
                <p class="kpi-sub">Catálogo registrado</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="kpi-card">
            <div class="kpi-topline"></div>
                <div class="p-3 d-flex align-items-center" style="gap:12px;">
                <div class="kpi-icon">R</div>
                    <div>
                    <div class="kpi-value">{{ $stats['reportes'] ?? 0 }}</div>
                    <p class="kpi-label">Reportes</p>
                    <p class="kpi-sub">Incidencias / registros</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Accesos rápidos por permiso --}}
    <div class="panel-card mb-4">
        <div class="panel-head">
            <h5 class="panel-title">Accesos rápidos</h5>
            <span class="badge-soft">Disponibles según permisos</span>
        </div>

        <div class="panel-body">
            <div class="quick-grid">

            @can('ver-zona')
            <a class="quick-btn" href="{{ route('zonas.index') }}">
                <div class="quick-ico">🗺️</div>
                <div>
                    <p class="quick-title">Zonas</p>
                    <p class="quick-desc">Gestión y registro de zonas protegidas.</p>
                </div>
            </a>
            @endcan

            @can('ver-area')
            <a class="quick-btn" href="{{ route('areas.index') }}">
                <div class="quick-ico">🏞️</div>
                <div>
                <p class="quick-title">Áreas</p>
                <p class="quick-desc">Listado y administración de áreas.</p>
                </div>
            </a>
            @endcan

            @can('ver-especie')
            <a class="quick-btn" href="{{ route('especies.index') }}">
                <div class="quick-ico">🧬</div>
                <div>
                <p class="quick-title">Especies</p>
                <p class="quick-desc">Catálogo de especies y datos asociados.</p>
                </div>
            </a>
            @endcan

            @can('ver-reporte')
            <a class="quick-btn" href="{{ route('reportes.index') }}">
                <div class="quick-ico">📄</div>
                <div>
                <p class="quick-title">Reportes</p>
                <p class="quick-desc">Revisión y exportación de información.</p>
                </div>
            </a>
            @endcan

            {{-- Si no tiene ningún permiso relevante --}}
            @if(
                !Auth::user()->can('ver-zona') &&
                !Auth::user()->can('ver-area') &&
                !Auth::user()->can('ver-especie') &&
                !Auth::user()->can('ver-reporte')
            )
            <div class="alert alert-light mb-0" style="border-radius:14px;">
                <strong>Sin accesos asignados.</strong>
                Solicita al administrador permisos para habilitar módulos.
            </div>
            @endif

            </div>
        </div>
    </div>

    {{-- Actividad reciente --}}
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="panel-card">
                <div class="panel-head">
                    <h5 class="panel-title">Últimas Zonas registradas</h5>
                    @can('ver-zona')
                        <a href="{{ route('zonas.index') }}" class="btn btn-sm btn-primary">Ver todo</a>
                    @endcan
                </div>
                <div class="panel-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                        <thead>
                        <tr>
                            <th>Zona</th>
                            <th class="text-center">Estado</th>
                            <th class="text-right">Fecha</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse(($ultimasZonas ?? []) as $z)
                            <tr>
                            <td><strong>{{ $z->nombre ?? '—' }}</strong></td>
                            <td class="text-center">
                                <span class="badge badge-light">
                                {{ $z->estado ?? '—' }}
                                </span>
                            </td>
                            <td class="text-right">
                                {{ optional($z->created_at)->format('d/m/Y') }}
                            </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted p-4">Sin registros recientes.</td></tr>
                        @endforelse
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="panel-card">
                <div class="panel-head">
                    <h5 class="panel-title">Últimas Áreas registradas</h5>
                    @can('ver-area')
                    <a href="{{ route('areas.index') }}" class="btn btn-sm btn-primary">Ver todo</a>
                    @endcan
                </div>
                <div class="panel-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th>Área</th>
                                <th class="text-center">Estado</th>
                                <th class="text-right">Fecha</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse(($ultimasAreas ?? []) as $a)
                                <tr>
                                <td><strong>{{ $a->nombre ?? '—' }}</strong></td>
                                <td class="text-center">
                                    <span class="badge badge-light">
                                    {{ $a->estado ?? '—' }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    {{ optional($a->created_at)->format('d/m/Y') }}
                                </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted p-4">Sin registros recientes.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</section>
@endrole

@endsection