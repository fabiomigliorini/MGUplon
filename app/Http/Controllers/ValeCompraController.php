<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;

use MGLara\Repositories\ValeCompraRepository;

use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;

use Carbon\Carbon;

/**
 * @property  ValeCompraRepository $repository 
 */
class ValeCompraController extends Controller
{

    public function __construct(ValeCompraRepository $repository) {
        $this->repository = $repository;
        $this->bc = new Breadcrumb('Vale Compras');
        $this->bc->addItem('Vale Compras', url('vale-compra'));
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index(Request $request) {
        
        // Permissao
        $this->repository->authorize('listing');
        
        // Breadcrumb
        $this->bc->addItem('Listagem');
        
        // Filtro da listagem
        if (!$filtro = $this->getFiltro()) {
            $filtro = [
                'inativo' => 1,
            ];
        }
        
        // retorna View
        return view('vale-compra.index', ['bc'=>$this->bc, 'filtro'=>$filtro]);
    }

    /**
     * Monta json para alimentar a Datatable do index
     * 
     * @param  Request $request
     * @return  json
     */
    public function datatable(Request $request) {
        
        // Autorizacao
        $this->repository->authorize('listing');

        // Grava Filtro para montar o formulario da proxima vez que o index for carregado
        $this->setFiltro($request['filtros']);
        
        // Ordenacao
        $columns[0] = 'codvalecompra';
        $columns[1] = 'inativo';
        $columns[2] = 'codvalecompra';
        $columns[3] = 'aluno';
        $columns[4] = 'turma';
        $columns[5] = 'total';
        $columns[6] = 'codpessoa';
        $columns[7] = 'codpessoafavorecido';
        $columns[8] = 'codvalecompramodelo';
        $columns[9] = 'criacao';
        $columns[10] = 'codusuariocriacao';

        $sort = [];
        if (!empty($request['order'])) {
            foreach ($request['order'] as $order) {
                $sort[] = [
                    'column' => $columns[$order['column']],
                    'dir' => $order['dir'],
                ];
            }
        }

        // Pega listagem dos registros
        $regs = $this->repository->listing($request['filtros'], $sort, $request['start'], $request['length']);
        
        // Monta Totais
        $recordsTotal = $this->repository->count();
        $recordsFiltered = $regs->count();
        
        // Formata registros para exibir no data table
        $data = [];
        foreach ($regs as $reg) {
            $data[] = [
                url('vale-compra', $reg->codvalecompra),
                formataData($reg->inativo, 'C'),
                formataCodigo($reg->codvalecompra),
                $reg->aluno,
                $reg->turma,
                $reg->total,
                $reg->Pessoa->fantasia,
                $reg->PessoaFavorecido->fantasia,
                $reg->ValeCompraModelo->modelo,
                formataData($reg->criacao, 'L'),
                $reg->UsuarioCriacao->usuario,
            ];
        }
        
        // Envelopa os dados no formato do data table
        $ret = new Datatable($request['draw'], $recordsTotal, $recordsFiltered, $data);
        
        // Retorna o JSON
        return collect($ret);
        
    }
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        // cria um registro em branco
        $this->repository->new();
        
        // autoriza
        $this->repository->authorize('create');
        
        // breadcrumb
        $this->bc->addItem('Novo');
        
        // retorna view
        return view('vale-compra.create', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        parent::store($request);
        
        // Mensagem de registro criado
        Session::flash('flash_create', 'Vale Compras criado!');
        
        // redireciona para o view
        return redirect("vale-compra/{$this->repository->model->codvalecompra}");
    }

    /**
     * Display the specified resource.
     *
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        // busca registro
        $this->repository->findOrFail($id);
        
        //autorizacao
        $this->repository->authorize('view');
        
        // breadcrumb
        $this->bc->addItem($this->repository->model->codvalecompra);
        $this->bc->header = $this->repository->model->codvalecompra;
        
        // retorna show
        return view('vale-compra.show', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // busca regstro
        $this->repository->findOrFail($id);
        
        // autorizacao
        $this->repository->authorize('update');
        
        // breadcrumb
        $this->bc->addItem($this->repository->model->codvalecompra, url('vale-compra', $this->repository->model->codvalecompra));
        $this->bc->header = $this->repository->model->codvalecompra;
        $this->bc->addItem('Alterar');
        
        // retorna formulario edit
        return view('vale-compra.edit', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        parent::update($request, $id);
        
        // mensagem re registro criado
        Session::flash('flash_update', 'Vale Compras alterado!');
        
        // redireciona para view
        return redirect("vale-compra/{$this->repository->model->codvalecompra}"); 
    }
    
}
