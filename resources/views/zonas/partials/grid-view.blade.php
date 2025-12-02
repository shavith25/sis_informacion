@if ($zonas->isEmpty())
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> No hay zonas registradas. Crea tu primera zona.
    </div>
@else
    <div class="row">
        @foreach ($zonas as $zona)
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-img-top-container">
                        @if($zona->imagenes && count($zona->imagenes) > 0)
                            <div id="carousel-{{ $zona->id }}" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach($zona->imagenes as $index => $imagen)
                                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                            <img src="{{ asset('storage/' . $imagen) }}"
                                                class="fixed-size-image"
                                                height="150"
                                                width="350"
                                                alt="Imagen de {{ $zona->nombre }}">
                                        </div>
                                    @endforeach
                                </div>
                                @if(count($zona->imagenes) > 1)
                                    <button class="carousel-control-prev bg-dark opacity-75" type="button" data-bs-target="#carousel-{{ $zona->id }}" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon"></span>
                                    </button>
                                    <button class="carousel-control-next bg-dark opacity-75" type="button" data-bs-target="#carousel-{{ $zona->id }}" data-bs-slide="next">
                                        <span class="carousel-control-next-icon"></span>
                                    </button>
                                @endif
                            </div>
                        @elseif($zona->videos && count($zona->videos) > 0)
                            <video class="fixed-size-image" controls>
                                <source src="{{ asset('storage/' . $zona->videos[0]) }}" type="video/mp4">
                                Tu navegador no soporta videos.
                            </video>
                        @else
                            <img src="{{ asset('img/logo.png') }}"
                                class="fixed-size-image"
                                height="150"
                                width="350"
                                alt="Imagen por defecto">
                        @endif
                    </div>

                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title font-weight-bold mb-0">{{ $zona->nombre }}</h5>
                            <span class="badge rounded-pill bg-{{ $zona->estado ? 'success' : 'secondary' }}">
                                {{ $zona->estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>

                        <p class="card-text text-muted small mb-2">
                            <i class="fas fa-layer-group"></i> {{ $zona->area->area }}
                        </p>

                        <p class="card-text text-secondary mb-3">
                            {{ Str::limit($zona->descripcion, 80, '...') }}
                        </p>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('zonas.show', $zona->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i> Detalles
                            </a>

                            <div class="btn-group">
                                <a href="{{ route('zonas.edit', $zona->id) }}"
                                class="btn btn-sm btn-outline-secondary"
                                title="Editar">
                                <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('zonas.change-status', $zona) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-{{ $zona->estado ? 'warning' : 'success' }}"
                                            title="{{ $zona->estado ? 'Desactivar' : 'Activar' }}">
                                        <i class="fas {{ $zona->estado ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if(method_exists($zonas, 'hasPages') && $zonas->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $zonas->links() }}
    </div>
    @endif
@endif

@if ($zonas->isEmpty() && request()->has('search'))
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle"></i> No se encontraron resultados para tu búsqueda.
    </div>
@endif
