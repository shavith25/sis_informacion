@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Explorar Límites Geográficos</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <p class="card-text">Explore los límites geográficos de departamentos, provincias y municipios.</p>
                        <div class="list-group">
                            {{-- Este enlace ahora apunta a la vista pública de límites --}}
                            <a href="{{ route('limites.publico') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-map mr-2"></i>
                                    Ver Límites Geográficos (Departamentos, Provincias, Municipios)
                                </div>
                                <span class="badge badge-primary badge-pill"><i class="fas fa-arrow-right"></i></span>
                            </a>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection