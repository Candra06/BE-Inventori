<?php

use Illuminate\Http\Request;
use Illuminate\Routing\RouteGroup;
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

Route::prefix('barang')->group(function (){
    Route::get('/list', 'API\BarangController@index');
    Route::post('/create', 'API\BarangController@store');
    Route::get('/detail/{id}', 'API\BarangController@show');
    Route::post('/update/{id}', 'API\BarangController@update');
});

Route::prefix('pemasukan')->group(function(){
    Route::post('create', 'API\PemasukanController@store');
});
Route::prefix('pengeluaran')->group(function(){
    Route::post('create', 'API\PengeluaranController@store');
});
Route::post('/login', 'API\AuthController@login');
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
