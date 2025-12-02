<?php

use App\Http\Controllers\DetalleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Esta ruta no parece tener conflicto, la dejamos por si la usas en otro lado
Route::get('/detalle/especie/{id}', [DetalleController::class, 'getEspecie']);