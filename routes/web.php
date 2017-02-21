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
    
    Route::get('/', 'InicialController@inicial');

    /* select2 */
    Route::get('pessoa/select2', 'PessoaController@select2');
    Route::get('filial/select2', 'FilialController@select2');
    Route::get('marca/select2', 'MarcaController@select2');
    Route::get('grupo-produto/select2', 'GrupoProdutoController@select2');
    Route::get('ncm/select2', 'NcmController@select2');
    Route::get('cest/select2', 'CestController@select2');
    Route::get('sub-grupo-produto/select2', 'SubGrupoProdutoController@select2');
    Route::get('produto/select2', 'ProdutoController@select2Produto');
    Route::get('produto-variacao/select2', 'ProdutoVariacaoController@select2');
    Route::get('cidade/select2', 'CidadeController@select2');
    Route::get('familia-produto/select2', 'FamiliaProdutoController@select2');
    Route::get('produto-barra/select2', 'ProdutoBarraController@select2');

    /* Marca */
    Route::resource('marca/inativar', 'MarcaController@inativar');
    Route::resource('marca/{id}/busca-codproduto', 'MarcaController@buscaCodproduto');
    Route::resource('marca', 'MarcaController');

    /* Seção Produto */
    Route::post('secao-produto/inativar', 'SecaoProdutoController@inativar');
    Route::resource('secao-produto', 'SecaoProdutoController');

    /* Família Produto */
    Route::post('familia-produto/inativar', 'FamiliaProdutoController@inativar');
    Route::resource('familia-produto', 'FamiliaProdutoController');

    /* GrupoProduto */
    Route::post('grupo-produto/inativar', 'GrupoProdutoController@inativar');
    Route::resource('grupo-produto/{id}/busca-codproduto', 'GrupoProdutoController@buscaCodproduto');
    Route::resource('grupo-produto', 'GrupoProdutoController');
    
    /* SubGrupoProduto */
    Route::resource('sub-grupo-produto/{id}/busca-codproduto', 'SubGrupoProdutoController@buscaCodproduto');
    Route::post('sub-grupo-produto/inativar', 'SubGrupoProdutoController@inativar');
    Route::resource('sub-grupo-produto', 'SubGrupoProdutoController');  
    
    /* Tipos de produto */
    Route::resource('tipo-produto', 'TipoProdutoController');

    /* Unidades de medida */
    Route::put('unidade-medida/{id}/activate', 'UnidadeMedidaController@activate');
    Route::put('unidade-medida/{id}/inactivate', 'UnidadeMedidaController@inactivate');
    Route::get('unidade-medida/datatable', 'UnidadeMedidaController@datatable');
    Route::resource('unidade-medida', 'UnidadeMedidaController');
    
    /* NCM */
    Route::resource('ncm', 'NcmController');

    /* Usuários */
    Route::put('usuario/{id}/ativar', 'UsuarioController@ativar');
    Route::put('usuario/{id}/inativar', 'UsuarioController@inativar');
    Route::get('usuario/datatable', 'UsuarioController@datatable');
    Route::get('usuario/{id}/grupos', 'UsuarioController@grupos');
    Route::post('usuario/{id}/grupos', 'UsuarioController@gruposCreate');
    Route::delete('usuario/{id}/grupos', 'UsuarioController@gruposDestroy');
    Route::resource('usuario/{codusuario}/permissao', 'UsuarioController@permissao');
    Route::resource('usuario', 'UsuarioController');

    /* Grupos de usuários */
    Route::put('grupo-usuario/{id}/ativar', 'GrupoUsuarioController@ativar');
    Route::put('grupo-usuario/{id}/inativar', 'GrupoUsuarioController@inativar');
    Route::get('grupo-usuario/datatable', 'GrupoUsuarioController@datatable');
    Route::resource('grupo-usuario', 'GrupoUsuarioController');

    /* Pessoa */
    
    /* permissao */
    Route::resource('permissao', 'PermissaoController', ['only' => ['index', 'store', 'destroy']]);
    
    
});