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

    /* Marca */
    Route::get('marca/listagem-json', 'MarcaController@listagemJson');
    Route::resource('marca/inativar', 'MarcaController@inativar');
    Route::resource('marca/{id}/busca-codproduto', 'MarcaController@buscaCodproduto');
    Route::resource('marca', 'MarcaController');

    /* Seção Produto */
    Route::post('secao-produto/inativar', 'SecaoProdutoController@inativar');
    Route::resource('secao-produto', 'SecaoProdutoController');

    /* Família Produto */
    Route::post('familia-produto/inativar', 'FamiliaProdutoController@inativar');
    Route::resource('familia-produto/listagem-json', 'FamiliaProdutoController@listagemJson');
    Route::resource('familia-produto', 'FamiliaProdutoController');

    /* GrupoProduto */
    Route::post('grupo-produto/inativar', 'GrupoProdutoController@inativar');
    Route::resource('grupo-produto/listagem-json', 'GrupoProdutoController@listagemJson');
    Route::resource('grupo-produto/{id}/busca-codproduto', 'GrupoProdutoController@buscaCodproduto');
    Route::resource('grupo-produto', 'GrupoProdutoController');
    
    /* SubGrupoProduto */
    Route::resource('sub-grupo-produto/{id}/busca-codproduto', 'SubGrupoProdutoController@buscaCodproduto');
    Route::get('sub-grupo-produto/listagem-json', 'SubGrupoProdutoController@listagemJson');
    Route::post('sub-grupo-produto/inativar', 'SubGrupoProdutoController@inativar');
    Route::resource('sub-grupo-produto', 'SubGrupoProdutoController');	
    
    /* Tipos de produto */
    Route::resource('tipo-produto', 'TipoProdutoController');

    /* Unidades de medida */
    Route::put('unidade-medida/{id}/ativar', 'UnidadeMedidaController@ativar');
    Route::put('unidade-medida/{id}/inativar', 'UnidadeMedidaController@inativar');
    Route::get('unidade-medida/datatable', 'UnidadeMedidaController@datatable');
    Route::resource('unidade-medida', 'UnidadeMedidaController');
    
    /* NCM */
    Route::get('ncm/listagem-json', 'NcmController@listagemJson');
    Route::resource('ncm', 'NcmController');

    /* Usuários */
    Route::put('usuario/{id}/ativar', 'UsuarioController@ativar');
    Route::put('usuario/{id}/inativar', 'UsuarioController@inativar');
    Route::get('usuario/datatable', 'UsuarioController@datatable');
    Route::resource('usuario', 'UsuarioController');
    Route::post('usuario/inativar', 'UsuarioController@inativar');
    Route::resource('usuario/{codusuario}/permissao', 'UsuarioController@permissao');
    Route::post('usuario/attach-permissao', 'UsuarioController@attachPermissao');
    Route::post('usuario/detach-permissao', 'UsuarioController@detachPermissao');

    /* Grupos de usuários */
    Route::put('grupo-usuario/{id}/ativar', 'GrupoUsuarioController@ativar');
    Route::put('grupo-usuario/{id}/inativar', 'GrupoUsuarioController@inativar');
    Route::get('grupo-usuario/datatable', 'GrupoUsuarioController@datatable');
    Route::resource('grupo-usuario', 'GrupoUsuarioController');

    /* Pessoa */
    Route::get('pessoa/select2', 'PessoaController@select2');
    
    
});