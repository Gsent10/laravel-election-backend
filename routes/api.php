<?php

use App\Http\Controllers\ResultController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/pollingunits', [ResultController::class, 'index']);
Route::get('/wards', [ResultController::class, 'wards']);
Route::get('/pollingunit/{unitId}', [ResultController::class, 'unitResult']);
Route::get('/wardresult/{wardId}', [ResultController::class, 'wardResult']);
Route::get('/lgas', [ResultController::class, 'lgas']);
Route::get('/lgaresult/{lgaId}', [ResultController::class, 'lgaResult']);
Route::post('/upload', [ResultController::class, 'upload']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
