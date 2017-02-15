<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Auth::routes();
Route::group(['middleware' => 'auth'], function() {
	Route::get('/', function () {
	    return view('welcome');
	});

    Route::put('unidade-medida/{id}/ativar', 'UnidadeMedidaController@ativar');
    Route::put('unidade-medida/{id}/inativar', 'UnidadeMedidaController@inativar');
    Route::get('unidade-medida/datatable', 'UnidadeMedidaController@datatable');
	Route::resource('unidade-medida', 'UnidadeMedidaController');






	
});