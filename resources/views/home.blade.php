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
                    {{-- TARJETA ZONAS (Ahora redirige directamente) --}}
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

        {{-- NOTA: Se ha eliminado el HTML del Modal "zonasModal" --}}

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

                // --- CAMBIO AQUÍ: Redirección directa en lugar de Modal ---
                document.getElementById('zonasCard').addEventListener('click', function() {
                    window.location.href = "{{ route('zonas.index') }}";
                });
                
                document.getElementById('AreasCard').addEventListener('click', function() { window.location.href = "{{ route('areas.index') }}"; });
                document.getElementById('UsuariosCard').addEventListener('click', function() { window.location.href = "{{ route('usuarios.index') }}"; });
            });
        </script>

    @else
        <section class="section">
            <div class="section-header">
                <h3 class="page__heading">Inicio</h3>
            </div>
            <div class="section-body">
                <div class="card">
                    <div class="card-body text-center p-5">
                        <img src="{{ asset('img/Cochabamba-Bolivia.png') }}" alt="Logo" width="535" class="mb-4"> 
                        <h2 class="text-primary">¡Bienvenido al Programa Gestión de la Biodiversidad! 🌳</h2>
                        <h4 class="text-secondary mt-3">Hola, {{ Auth::user()->name }}</h4>
                        
                        <p class="lead mt-4">
                            Has ingresado correctamente al sistema. <br>
                            Tu rol actual es: <span class="badge badge-primary">{{ Auth::user()->roles->pluck('name')->first() }}</span>
                        </p>

                        <div class="alert alert-light mt-4 d-inline-block text-left">
                            <i class="fas fa-info-circle mr-2"></i> 
                            Por favor, utiliza el <strong>menú lateral izquierdo</strong> para acceder a los módulos habilitados para tu cuenta 
                            (como Revisión de Sugerencias o Comentarios).
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endrole

@endsection