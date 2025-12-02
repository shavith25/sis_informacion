@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">
                <i class="bi bi-shield-shaded me-2"></i> Reporte de Zonas y Áreas Protegidas
            </h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('home') }}">Inicio</a></div>
                <div class="breadcrumb-item">Reportes</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total de Áreas Protegidas</h4>
                            </div>
                            <div class="card-body">
                                {{ $totalAreas }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total de Zonas</h4>
                            </div>
                            <div class="card-body">
                                {{ $totalZonas }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-map-marker-alt"></i> Zonas por Área Protegida</h4>
                            <div class="card-header-action">
                                <a href="{{ route('reportes.exportarPDF') }}" class="btn" style="background-color: #2563EB; color: white; border: none; border-radius: 50px; padding: 8px 20px;">
                                    <i class="fas fa-file-pdf"></i> Exportar a PDF
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive report-table-container">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Área Protegida</th>
                                            <th class="text-center">Cantidad de Zonas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($zonasPorArea as $area)
                                            <tr>
                                                <td>{{ $area->area }}</td>
                                                <td class="text-center">{{ $area->zonas_count }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2" class="text-center">No hay datos para mostrar.</td>
                                            </tr>
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
@endsection

@push('css')
    <style>
        .report-table-container {
            height: calc(100vh - 480px);
        }

        .table thead th {
            position: sticky;
            top: 0;
            background-color: #f8f9fa;
            z-index: 10;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }
    </style>
@endpush
