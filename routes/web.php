<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\AyudaController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\ComunidadArchivoController;
use App\Http\Controllers\ConcientizacionController;
use App\Http\Controllers\DatosController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\DetalleController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\EspecieController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LimitesController;
use App\Http\Controllers\MapasAreaController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\NoticiaController;
use App\Http\Controllers\PanelConcientizacionController;
use App\Http\Controllers\ProvinciaController;
use App\Http\Controllers\ReporteAmbientalController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\SugerenciaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ZonasController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 1. RUTAS PÚBLICAS (ACCESO LIBRE)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Zonas y Límites (Solo lectura)
Route::get('/zonas-protegidas', [ZonasController::class, 'landing'])->name('zonas.landing');
Route::get('/zonas-protegidas/{zona}', [ZonasController::class, 'detalleZona'])->name('zonas.detalle.public');
Route::get('/areas-protegidas', [ZonasController::class, 'listadoPublico'])->name('zonas.listadoPublico');
Route::get('/areas-protegidas/{zona}/detalle', [ZonasController::class, 'verInformacion'])->name('zonas.verInformacion');

Route::get('/limites', [LimitesController::class, 'publico'])->name('limites.publico');
Route::get('/limites/provincias/{departamento_id}', [LimitesController::class, 'getProvincias'])->name('limites.publico.provincias')->whereNumber('departamento_id');
Route::get('/limites/municipios/{provincia_id}', [LimitesController::class, 'getMunicipios'])->name('limites.publico.municipios')->whereNumber('provincia_id');
Route::get('/limites/detalle/{tipo}/{id}', [LimitesController::class, 'getDetalle'])->name('limites.publico.detalle');

Route::get('/concientizacion', [ConcientizacionController::class, 'index'])->name('concientizacion.index');

/*
|--------------------------------------------------------------------------
| 2. RUTAS PÚBLICAS CON ANTI-SPAM (THROTTLE)
|--------------------------------------------------------------------------
| IMPORTANTE: Aplicamos 'throttle:60,1' (60 intentos por minuto) para evitar
| ataques de denegación de servicio (DoS) o spam masivo en tus formularios.
*/
Route::middleware(['throttle:60,1'])->group(function () {
    // Lectura pública (API)
    Route::get('/public/comentarios', [ConcientizacionController::class, 'getPublicComentarios'])->name('public.comentarios.index');
    Route::get('/public/sugerencias', [ConcientizacionController::class, 'getPublicSugerencias'])->name('public.sugerencias.index');
    Route::get('/public/reportes', [ConcientizacionController::class, 'getPublicReportes'])->name('public.reportes.index');
    Route::get('/public/archivos', [ConcientizacionController::class, 'getPublicArchivos'])->name('public.archivos.index');

    // Escritura pública (POST) - ¡Aquí es donde más atacan los bots!
    Route::post('/public/comentarios', [ComentarioController::class, 'store'])->name('public.comentarios.store');
    Route::post('/public/sugerencias', [SugerenciaController::class, 'store'])->name('public.sugerencias.store');
    Route::post('/public/reportes', [ReporteAmbientalController::class, 'store'])->name('public.reportes.store');
    Route::post('/public/archivos', [ComunidadArchivoController::class, 'store'])->name('public.archivos.store');
});

Auth::routes();

/*
|--------------------------------------------------------------------------
| 3. RUTAS PROTEGIDAS (REQUIEREN LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Gestión de Zonas
    Route::get('/zonas/mapa', [ZonasController::class, 'mapa'])->name('zonas.mapa');
    Route::get('/zonas/registradas', [ZonasController::class, 'registradas'])->name('zonas.registradas');
    Route::get('/zonas/{zona}/historial', [ZonasController::class, 'historial'])->name('zonas.historial');
    Route::get('/zonas/{zona}/detalle-admin', [ZonasController::class, 'detalle'])->name('zonas.detalle');
    Route::post('/zonas/{zona}/datos', [ZonasController::class, 'guardarDatos'])->name('zonas.guardar-datos');
    Route::post('/zonas/deleteFile', [ZonasController::class, 'deleteFile'])->name('zonas.deleteFile');
    Route::post('/zonas/{id}/generarMapaPDF', [ZonasController::class, 'generarMapaPDF'])->name('zonas.generarMapaPDF');
    Route::patch('/zonas/{zona}/change-status', [ZonasController::class, 'changeStatus'])->name('zonas.change-status');
    Route::resource('zonas', ZonasController::class);

    // Datos y Detalles
    Route::get('/datos/todos', [ZonasController::class, 'getAllDatos'])->name('datos.todos');
    Route::get('/datos/{id}/detalle', [ZonasController::class, 'getDatoById'])->name('datos.detalle');
    Route::get('/datos/zona/{zona}/detalle', [DatosController::class, 'getDetalle'])->name('datos.detalle.por-zona');
    Route::post('/datos', [DatosController::class, 'store'])->name('datos.store');
    Route::delete('/datos/{id}', [ZonasController::class, 'destroyDato'])->name('datos.destroy');
    Route::get('/detalle/{tipo}/{id}', [DetalleController::class, 'show'])->name('detalle.show');

    // Mapas
    Route::get('/mapa-areas', [DepartamentoController::class, 'mapa'])->name('mapa-areas.index');
    Route::get('/mapa-areas/{id}/areas', [DepartamentoController::class, 'getByDepartamento'])->name('mapa-areas.byDepartamento');
    Route::get('/mapa-areas/listado', [AreaController::class, 'getAreas'])->name('mapa-areas.listado');
    Route::get('/mapa-areas/zonas', [MapasAreaController::class, 'getZonas'])->name('mapa-areas.zonas');
    Route::get('/mapa-areas/provincia/{provinciaId}/municipios', [MapasAreaController::class, 'getMunicipiosByProvincia'])->name('mapa-areas.municipios');
    Route::get('/mapa-areas/municipio/{municipioId}/geometria', [MapasAreaController::class, 'getMunicipioGeometria'])->name('mapa-areas.geometria');
    Route::post('/mapa-areas/store', [MapasAreaController::class, 'storeFromMapa'])->name('mapa-areas.storeFromMapa');
    Route::post('/mapa-areas/store-zona', [MapasAreaController::class, 'storeZonaFromMapa'])->name('mapa-areas.storeZonaFromMapa');
    Route::post('/mapa-areas/store-zona-incidencia', [MapasAreaController::class, 'storeZonaIncidenciaFromMapa'])->name('mapa-areas.storeZonaIncidenciaFromMapa');
    
    // Otros recursos generales
    Route::patch('/areas/{area}/toggle', [AreaController::class, 'toggleEstado'])->name('areas.toggle');
    Route::resource('areas', AreaController::class);
    Route::resource('especies', EspecieController::class)->parameters(['especies' => 'especie']);
    Route::resource('noticias', NoticiaController::class);
    
    // Ayuda y Documentos
    Route::get('/ayuda', [AyudaController::class, 'index'])->name('ayuda.index');
    Route::post('/ayuda/subir', [AyudaController::class, 'subir'])->name('ayuda.subir');
    Route::get('/documento/descargar/{id}', [AyudaController::class, 'descargar'])->name('documento.descargar');
    Route::delete('/documento/{id}', [AyudaController::class, 'eliminar'])->name('documento.eliminar');
    Route::resource('documentos', DocumentoController::class);
    
    Route::get('/reportes', [ReportesController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/exportar-pdf', [ReportesController::class, 'exportarPDF'])->name('reportes.exportarPDF');


    /*
    |--------------------------------------------------------------------------
    | 4. ZONA DE SEGURIDAD (SOLO ADMINISTRADORES)
    |--------------------------------------------------------------------------
    */
    Route::group(['middleware' => ['role:Administrador']], function () {
        
        // Gestión de Seguridad (¡CRÍTICO!)
        Route::patch('/usuarios/{usuario}/change-status', [UsuarioController::class, 'changeStatus'])->name('usuarios.change-status');
        Route::get('/usuarios/inactivos', [UsuarioController::class, 'getInactiveUsers'])->name('usuarios.inactivos');
        Route::resource('usuarios', UsuarioController::class);
        Route::resource('roles', RolController::class);

        // Aprobación de contenido (Moderación)
        Route::prefix('admin')->name('admin.')->group(function () {
            // Comentarios
            Route::get('/comentarios', [ComentarioController::class, 'index'])->name('comentarios.index');
            Route::patch('/comentarios/{id}/approve', [ComentarioController::class, 'approve'])->name('comentarios.approve');
            Route::delete('/comentarios/{id}', [ComentarioController::class, 'destroy'])->name('comentarios.destroy');
            
            // Sugerencias
            Route::get('/sugerencias', [SugerenciaController::class, 'adminIndex'])->name('sugerencias.index');
            Route::patch('/sugerencias/{sugerencia}/approve', [SugerenciaController::class, 'approve'])->name('sugerencias.approve');
            Route::delete('/sugerencias/{sugerencia}', [SugerenciaController::class, 'destroy'])->name('sugerencias.destroy');
            
            // Reportes
            Route::get('/reportes-ambientales', [ReporteAmbientalController::class, 'adminIndex'])->name('reportes_ambientales.index');
            Route::patch('/reportes-ambientales/{reporte}/approve', [ReporteAmbientalController::class, 'approve'])->name('reportes_ambientales.approve');
            Route::delete('/reportes-ambientales/{reporte}', [ReporteAmbientalController::class, 'destroy'])->name('reportes_ambientales.destroy');
            
            // Media
            Route::get('/media', [ComunidadArchivoController::class, 'adminIndex'])->name('media.index');
            Route::patch('/media/{archivo}/approve', [ComunidadArchivoController::class, 'approve'])->name('media.approve');
            Route::delete('/media/{archivo}', [ComunidadArchivoController::class, 'destroy'])->name('media.destroy');
        });

        // Configuración de límites y sistema
        Route::get('/limites-gestion', [LimitesController::class, 'index'])->name('limites.index');
        Route::prefix('limites')->name('limites.')->group(function () {
            Route::resource('departamentos', DepartamentoController::class);
            Route::resource('provincias', ProvinciaController::class);
            Route::resource('municipios', MunicipioController::class);
        });
        
        Route::resource('panel-concientizaciones', PanelConcientizacionController::class)
            ->names('panelConcientizaciones')
            ->except(['show']);
    });

});