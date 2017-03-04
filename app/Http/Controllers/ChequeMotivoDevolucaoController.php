<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;

use MGLara\Repositories\ChequeMotivoDevolucaoRepository;

use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;

use Carbon\Carbon;

/**
 * @property  ChequeMotivoDevolucaoRepository $repository 
 */
class ChequeMotivoDevolucaoController extends Controller
{

    public function __construct(ChequeMotivoDevolucaoRepository $repository) {
        $this->repository = $repository;
        $this->bc = new Breadcrumb('Cheque Motivo Devolucao');
        $this->bc->addItem('Cheque Motivo Devolucao', url('cheque-motivo-devolucao'));
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
        return view('cheque-motivo-devolucao.index', ['bc'=>$this->bc, 'filtro'=>$filtro]);
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
        $columns[0] = 'codchequemotivodevolucao';
        $columns[1] = 'inativo';
        $columns[2] = 'codchequemotivodevolucao';
        $columns[3] = 'chequemotivodevolucao';
        $columns[4] = 'numero';

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
                url('cheque-motivo-devolucao', $reg->codchequemotivodevolucao),
                formataData($reg->inativo, 'C'),
                formataCodigo($reg->codchequemotivodevolucao),
                $reg->chequemotivodevolucao,
                $reg->numero,
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
        return view('cheque-motivo-devolucao.create', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        Session::flash('flash_create', 'Cheque Motivo Devolucao criado!');
        
        // redireciona para o view
        return redirect("cheque-motivo-devolucao/{$this->repository->model->codchequemotivodevolucao}");
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
        $this->bc->addItem($this->repository->model->chequemotivodevolucao);
        $this->bc->header = $this->repository->model->chequemotivodevolucao;
        
        // retorna show
        return view('cheque-motivo-devolucao.show', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        $this->bc->addItem($this->repository->model->chequemotivodevolucao, url('cheque-motivo-devolucao', $this->repository->model->codchequemotivodevolucao));
        $this->bc->header = $this->repository->model->chequemotivodevolucao;
        $this->bc->addItem('Alterar');
        
        // retorna formulario edit
        return view('cheque-motivo-devolucao.edit', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        Session::flash('flash_update', 'Cheque Motivo Devolucao alterado!');
        
        // redireciona para view
        return redirect("cheque-motivo-devolucao/{$this->repository->model->codchequemotivodevolucao}"); 
    }
    
}
