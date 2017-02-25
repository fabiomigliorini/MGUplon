<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use MGLara\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use MGLara\Repositories\GeradorCodigoRepository;
use MGLara\Library\Breadcrumb\Breadcrumb;

/**
 * 
 * @property GeradorCodigoRepository $repository 
 */

class GeradorCodigoController extends Controller
{

    public function __construct(GeradorCodigoRepository $repository) {
        $this->repository = $repository;
        $this->bc = new Breadcrumb('Gerador de Código');
        $this->bc->addItem('Gerador de Código', url('gerador-codigo'));
    }
    
    public function index() {
        $tabelas = $this->repository->buscaTabelas();
        return view('gerador-codigo.index', ['tabelas' => $tabelas, 'bc' => $this->bc]);
    }

    public function show(Request $request, $id) {
        $model = $this->repository->buscaArquivoModel($id);
        return view('gerador-codigo.show', ['model'=>$model, 'tabela' => $id]);
    }
    
    public function showModel(Request $request, $tabela) {
        $conteudo = $this->repository->geraModel($tabela, $request->model, $request->titulo);
        return view('gerador-codigo.model', [
            'tabela'=>$request->tabela, 
            'model'=>$request->model, 
            'titulo'=>$request->titulo,
            'conteudo'=>$conteudo,
        ]);
    }

    public function storeModel(Request $request, $tabela) {
        return ['OK' => $this->repository->salvaModel($tabela, $request->model, $request->titulo)];
    }
    
    public function showRepository(Request $request, $tabela) {
        $conteudo = $this->repository->geraRepository($tabela, $request->model, $request->titulo);
        return view('gerador-codigo.repository', [
            'tabela'=>$request->tabela, 
            'model'=>$request->model, 
            'titulo'=>$request->titulo,
            'conteudo'=>$conteudo,
        ]);
    }

    public function storeRepository(Request $request, $tabela) {
        return ['OK' => $this->repository->salvaRepository($tabela, $request->model, $request->titulo)];
    }
}
