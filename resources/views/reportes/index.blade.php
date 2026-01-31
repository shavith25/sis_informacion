@extends('layouts.app')

@section('title', 'Reportes')

@push('css')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .card {
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border-radius: 15px;
            transition: transform 0.2s;
        }
        
        .card-statistic-1:hover {
            transform: translateY(-5px);
        }

        .card-icon-wrapper {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        .bg-gradient-primary { background: linear-gradient(45deg, #303f9f, #1976d2); }
        .bg-gradient-success { background: linear-gradient(45deg, #388e3c, #4caf50); }

        .table thead th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
        }

        .table tbody tr { transition: background-color 0.2s; }
        .table tbody tr:hover { background-color: #f8fafc; }

        .badge-custom {
            padding: 8px 12px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.9em;
        }

        .badge-status-active {
            background-color: #28a745 !important; 
            color: #ffffff !important; 
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .badge-status-inactive {
            background-color: #dc3545 !important; 
            color: #ffffff !important; 
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .search-box { position: relative; }
        .search-box input { padding-left: 35px; border-radius: 20px; }
        .search-box i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #aaa; }
    </style>
@endpush

@section('content')
    <section class="section">
        <div class="section-header border-0 mb-4">
            <h1 class="page__heading text-primary" style="font-weight: 700;">
                <i class="bi bi-pie-chart-fill me-2"></i> Dashboard del Panel de Reportes
            </h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Inicio</a></div>
                <div class="breadcrumb-item active">Reportes</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row mb-4">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="card card-statistic-1 h-100 p-3">
                        <div class="d-flex align-items-center">
                            <div class="card-icon-wrapper bg-gradient-primary me-3">
                                <i class="fas fa-globe-americas"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header p-0">
                                    <h4 class="text-muted mb-0" style="font-size: 0.9rem;">Áreas Protegidas</h4>
                                </div>
                                <div class="card-body p-0 mt-1">
                                    <span style="font-size: 1.8rem; font-weight: 800; color: #2c3e50;">
                                        {{ $totalAreas }}
                                    </span>
                                    <small class="text-success fw-bold ms-2"><i class="fas fa-check-circle"></i> Activas</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="card card-statistic-1 h-100 p-3">
                        <div class="d-flex align-items-center">
                            <div class="card-icon-wrapper bg-gradient-success me-3">
                                <i class="fas fa-map-marked-alt"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header p-0">
                                    <h4 class="text-muted mb-0" style="font-size: 0.9rem;">Zonas</h4>
                                </div>
                                <div class="card-body p-0 mt-1">
                                    <span style="font-size: 1.8rem; font-weight: 800; color: #2c3e50;">
                                        {{ $totalZonas }}
                                    </span>
                                    <small class="text-muted ms-2" style="font-size: 0.8rem;">Registradas en el sistema</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FILA 2: Contenido Principal --}}
            <div class="row">
                <div class="col-lg-8 col-md-12">
                    <div class="card h-100">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="m-0 text-primary font-weight-bold">
                                <i class="fas fa-list-ul me-2"></i> Detalle por Área Protegida
                            </h5>
                            
                            <div class="d-flex gap-2">
                                <div class="search-box d-none d-md-block">
                                    <i class="fas fa-search"></i>
                                    <input type="text" id="tableSearch" class="form-control form-control-sm" placeholder="Buscar área...">
                                </div>

                                <a href="{{ route('reportes.exportarPDF') }}" class="btn btn-danger btn-sm rounded-pill shadow-sm px-3">
                                    <i class="fas fa-file-pdf me-1"></i> DESCARGAR PDF
                                </a>
                            </div>
                        </div>
                        
                        <div class="card-body p-0">
                            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                <table class="table table-hover align-middle mb-0" id="reportTable">
                                    <thead style="position: sticky; top: 0; z-index: 5;">
                                        <tr>
                                            <th class="ps-4">Nombre del Área Protegida</th>
                                            <th class="text-center">Zonas</th>
                                            <th class="text-end pe-4">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($zonasPorArea as $area)
                                            <tr>
                                                <td class="ps-4 fw-500 text-dark">
                                                    {{ $area->area }}
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-light text-primary border border-primary badge-custom">
                                                        {{ $area->zonas_count }}
                                                    </span>
                                                </td>
                                                <td class="text-end pe-4">
                                                    @if($area->zonas_count > 0)
                                                        <span class="badge-status-active">
                                                            Activo
                                                        </span>
                                                    @else
                                                        <span class="badge-status-inactive">
                                                            Inactivo
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center py-5 text-muted">
                                                    <i class="fas fa-folder-open fa-2x mb-2"></i>
                                                    <p>No se encontraron registros.</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12 mt-4 mt-lg-0">
                    <div class="card h-100">
                        <div class="card-header bg-white py-3">
                            <h5 class="m-0 text-dark font-weight-bold" style="font-size: 1rem;">Distribución porcentual</h5>
                        </div>
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <div style="position: relative; width: 100%; height: 250px;">
                                <canvas id="zonesChart"></canvas>
                            </div>
                            <div class="mt-3 text-center text-muted small">
                                Distribución de zonas según las áreas protegidas registradas.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection

@push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const labels = @json($zonasPorArea->pluck('area'));
            const dataCounts = @json($zonasPorArea->pluck('zonas_count'));

            const ctx = document.getElementById('zonesChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: dataCounts,
                        backgroundColor: [
                            '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', 
                            '#858796', '#5a5c69', '#2e59d9', '#17a673', '#2c9faf'
                        ],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
                                font: { size: 10 }
                            }
                        }
                    },
                    cutout: '70%',
                },
            });

            const searchInput = document.getElementById('tableSearch');
            const table = document.getElementById('reportTable');
            
            if(searchInput && table){
                searchInput.addEventListener('keyup', function() {
                    const filter = searchInput.value.toLowerCase();
                    const rows = table.getElementsByTagName('tr');

                    for (let i = 1; i < rows.length; i++) {
                        const firstCol = rows[i].getElementsByTagName('td')[0];
                        if (firstCol) {
                            const txtValue = firstCol.textContent || firstCol.innerText;
                            if (txtValue.toLowerCase().indexOf(filter) > -1) {
                                rows[i].style.display = "";
                            } else {
                                rows[i].style.display = "none";
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush