@extends('layouts.app')

@section('title', 'Gestión de Noticias')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading"><i class="fas fa-newspaper mr-2"></i> Gestión de Noticias</h3>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Inicio</a></div>
            <div class="breadcrumb-item">Noticias</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4><i class="fas fa-list text-primary mr-2"></i> Listado de Noticias</h4>
                        <div class="card-header-action">
                            <a href="{{ route('noticias.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus mr-1"></i> Nueva Noticia
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible show fade">
                                <div class="alert-body">
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <ul class="mb-0 pl-3">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <div class="table-responsive" style="height: calc(100vh - 350px)">
                            <table class="table table-striped table-hover" id="table-areas">
                                <thead style="position: sticky; top: 0; z-index: 10;">
                                    <tr class="bg-light">
                                        <th style="background: rgb(233, 236, 239);">Título</th>
                                        <th style="background: rgb(233, 236, 239);">Subtítulo</th>
                                        <th style="background: rgb(233, 236, 239);">Descripción</th>
                                        <th style="background: rgb(233, 236, 239);">Autor</th>
                                        <th style="background: rgb(233, 236, 239);">Fecha</th>
                                        <th style="background: rgb(233, 236, 239);">Imagen</th>
                                        <th style="background: rgb(233, 236, 239);" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($noticias as $noticia)
                                        <tr>
                                            <td style="vertical-align: middle;">{!! $noticia->titulo !!}</td>
                                            <td style="vertical-align: middle;">{{ $noticia->subtitulo }}</td>
                                            <td style="vertical-align: middle;">
                                                {{-- Limitamos el texto y limpiamos HTML por si acaso --}}
                                                {{ Str::limit(strip_tags($noticia->descripcion), 50) }}
                                            </td>
                                            <td style="vertical-align: middle;">{{ $noticia->autor }}</td>
                                            <td style="vertical-align: middle;">{{ $noticia->fecha_publicacion->format('d/m/Y') }}</td>
                                            <td style="vertical-align: middle;">
                                                @if($noticia->imagenes->count())
                                                    <img src="{{ asset('storage/' . $noticia->imagenes->first()->ruta) }}" 
                                                        class="img-thumbnail" 
                                                        style="width: 60px; height: 60px; object-fit: cover;" 
                                                        alt="Img">
                                                @else
                                                    <span class="badge badge-light">Sin imagen</span>
                                                @endif
                                            </td>
                                            <td class="text-center" style="vertical-align: middle;">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <a href="{{ route('noticias.edit', $noticia) }}" class="btn btn-sm btn-warning mr-2" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <form action="{{ route('noticias.destroy', $noticia) }}" method="POST" class="form-eliminar m-0">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmarEliminar(this)" title="Borrar">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="empty-state">
                                                    <div class="empty-state-icon">
                                                        <i class="fas fa-newspaper"></i>
                                                    </div>
                                                    <h2>No hay noticias registradas</h2>
                                                    <p class="lead">
                                                        Puedes crear una nueva noticia haciendo clic en el botón superior.
                                                    </p>
                                                </div>
                                            </td>
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
    .btn-primary {
        background-color: #2f55d4 !important; 
        border-color: #2f55d4 !important;
        box-shadow: none !important; 
        color: #ffffff !important;
        background-image: none !important; 
    }

    /* Efecto al pasar el mouse */
    .btn-primary:hover, 
    .btn-primary:focus, 
    .btn-primary:active {
        background-color: #2442a8 !important; 
        border-color: #2442a8 !important;
        box-shadow: none !important;
    }

    .table th {
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #596169;
        border-top: none;
    }
    
    .empty-state {
        text-align: center;
        padding: 40px 0;
    }

    .empty-state-icon {
        font-size: 40px;
        color: #cdd3d8;
        margin-bottom: 20px;
    }
    
    /* Ajuste para SweetAlert */
    .swal2-actions {
        gap: 10px;
    }
</style>
@endpush

@push('js')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmarEliminar(button) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción eliminará la noticia permanentemente.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#fc544b',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }
</script>
@endpush