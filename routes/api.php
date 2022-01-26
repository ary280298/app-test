<?php

use App\Http\Controllers\KaryawanController;
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
Route::post('karyawan/add', [KaryawanController::class, 'store']);
Route::post('karyawan/update', [KaryawanController::class, 'update']);
Route::post('karyawan/delete', [KaryawanController::class, 'delete']);
// Route::get('karyawan/generate/{id}', [KaryawanController::class, 'generateDocx']);
