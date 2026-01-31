@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">
                <i class="fas fa-globe-americas mr-2"></i> Gestión de Áreas Protegidas
            </h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></div>
                <div class="breadcrumb-item active">Áreas Protegidas</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-lg">
                        <div class="card-header custom-card-header">
                            <h4><i class="fas fa-list-ul mr-2"></i> Listado de Áreas Protegidas</h4>
                            <div class="card-header-action">
                                <a href="{{ route('areas.create') }}" class="btn btn-primary btn-create">
                                    <i class="fas fa-plus-circle mr-1"></i> Nueva Área Protegida
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <strong>Por favor corrige los siguientes errores:</strong>
                                    <ul class="mb-0 mt-2 pl-4">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <div class="table-responsive" style="max-height: calc(100vh - 350px); overflow-y: auto;">
                                <table class="table table-striped table-hover table-md" id="table-areas">
                                    <thead class="bg-secondary text-white">
                                        <tr>
                                            <th class="sticky-header" style="width: 25%">Área Protegida</th>
                                            <th class="sticky-header" style="width: 50%">Descripción</th>
                                            <th class="sticky-header" style="width: 15%">Estado</th>
                                            <th class="sticky-header text-center" style="width: 10%">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($areas as $area)
                                            <tr>
                                                <td>
                                                    <strong class="text-dark">{{ $area->area }}</strong>
                                                </td>
                                                <td>
                                                    @if ($area->descripcion)
                                                        {{ Str::limit($area->descripcion, 70) }}
                                                    @else
                                                        <span class="text-muted font-italic">Sin descripción</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge badge-{{ $area->estado ? 'success' : 'secondary' }} badge-lg">
                                                        {{ $area->estado ? 'Activa' : 'Inactiva' }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group" role="group">
                                                        
                                                        {{-- CORRECCIÓN 1: Pasamos el objeto $area, no el ID --}}
                                                        <a href="{{ route('areas.edit', $area) }}"
                                                            class="btn btn-icon btn-sm btn-info text-white"
                                                            data-toggle="tooltip" title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>

                                                        {{-- CORRECCIÓN 2: Pasamos $area en el route, pero mantenemos $area->id para el ID del formulario HTML --}}
                                                        <form action="{{ route('areas.toggle', $area) }}" method="POST"
                                                            class="d-inline ml-1" id="form-toggle-{{ $area->id }}">
                                                            @csrf @method('PATCH')
                                                            <button type="button"
                                                                class="btn btn-icon btn-sm btn-warning text-white"
                                                                onclick="confirmarAccion({{ $area->id }}, '{{ $area->estado ? 'desactivar' : 'activar' }}')"
                                                                data-toggle="tooltip"
                                                                title="{{ $area->estado ? 'Desactivar' : 'Activar' }}">
                                                                <i class="fas {{ $area->estado ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                                            </button>
                                                        </form>

                                                        {{-- CORRECCIÓN 3: Pasamos $area en el route --}}
                                                        <form action="{{ route('areas.destroy', $area) }}"
                                                            method="POST" class="d-inline ml-1"
                                                            id="form-delete-{{ $area->id }}">
                                                            @csrf @method('DELETE')
                                                            <button type="button" class="btn btn-icon btn-sm btn-danger"
                                                                onclick="confirmarEliminar({{ $area->id }})"
                                                                {{ $area->zonas_count > 0 ? 'disabled' : '' }}
                                                                data-toggle="tooltip"
                                                                title="{{ $area->zonas_count > 0 ? 'No se puede eliminar' : 'Eliminar' }}">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4">No hay áreas protegidas registradas.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end mt-3">{{ $areas->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            })
        @endif

        function confirmarAccion(id, accion) {
            Swal.fire({
                title: accion === 'desactivar' ? '¿Desactivar?' : '¿Activar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('form-toggle-' + id).submit();
            })
        }

        function confirmarEliminar(id) {
            Swal.fire({
                title: '¿Eliminar Área?',
                text: "No podrás revertir esto.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('form-delete-' + id).submit();
            })
        }
    </script>
@endpush

@push('css')
    <style>
    .btn-group .btn-icon {
        border-radius: 4px !important; 
        margin: 0 2px; 
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .btn-create {
        background-color: #007bff !important; 
        border-color: #007bff !important;
        color: #ffffff !important;
        box-shadow: 0 2px 6px rgba(0, 123, 255, 0.4) !important; 
    }

    .btn-create:hover {
        background-color: #0069d9 !important; 
        border-color: #0062cc !important;
        transform: translateY(-2px); 
    }

    .btn-group .btn-icon:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .btn-info { background-color: #f43a53 !important; color: white !important; }
    .btn-warning { background-color: #ffa426 !important; }
    .btn-danger { background-color: #fc544b !important; color: white !important; }

    .badge-lg {
        padding: 6px 12px;
        font-size: 12px;
        border-radius: 30px;
    }
    </style>
@endpush