<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;

use MGLara\Repositories\PranchetaProdutoBarraRepository;

use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;

use Carbon\Carbon;

/**
 * @property  PranchetaProdutoBarraRepository $repository 
 */
class PranchetaProdutoBarraController extends Controller
{

    public function __construct(PranchetaProdutoBarraRepository $repository) {
        $this->repository = $repository;
        $this->bc = new Breadcrumb('Prancheta Produto Barra');
        $this->bc->addItem('Prancheta Produto Barra', url('prancheta-produto-barra'));
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
                'filtros' => [
                    'inativo' => 1,
                ],
                'order' => [[
                    'column' => 0,
                    'dir' => 'DESC',
                ]],
            ];
        }
        
        
        // retorna View
        return view('prancheta-produto-barra.index', ['bc'=>$this->bc, 'filtro'=>$filtro]);
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
        $this->setFiltro([
            'filtros' => $request['filtros'],
            'order' => $request['order'],
        ]);
        
        // Ordenacao
        $columns[0] = 'codpranchetaprodutobarra';
        $columns[1] = 'inativo';
        $columns[2] = 'codpranchetaprodutobarra';
        $columns[3] = 'codpranchetaprodutobarra';
        $columns[4] = 'observacoes';
        $columns[5] = 'codprancheta';
        $columns[6] = 'codprodutobarra';

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
        $recordsTotal = $regs['recordsTotal'];
        $recordsFiltered = $regs['recordsFiltered'];
        
        // Formata registros para exibir no data table
        $data = [];
        foreach ($regs['data'] as $reg) {
            $data[] = [
                url('prancheta-produto-barra', $reg->codpranchetaprodutobarra),
                formataData($reg->inativo, 'C'),
                formataCodigo($reg->codpranchetaprodutobarra),
                $reg->codpranchetaprodutobarra,
                $reg->observacoes,
                $reg->codprancheta,
                $reg->codprodutobarra,
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
    public function create(Request $request)
    {
        // cria um registro em branco
        $this->repository->new($request->all());
        
        // autoriza
        $this->repository->authorize('create');
        
        // breadcrumb
        $this->bc->addItem('Novo');
        
        // retorna view
        return view('prancheta-produto-barra.create', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        Session::flash('flash_create', 'Prancheta Produto Barra criado!');
        
        // redireciona para o view
        return redirect("prancheta-produto-barra/{$this->repository->model->codpranchetaprodutobarra}");
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
        $this->bc->addItem($this->repository->model->codpranchetaprodutobarra);
        $this->bc->header = $this->repository->model->codpranchetaprodutobarra;
        
        // retorna show
        return view('prancheta-produto-barra.show', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        $this->bc->addItem($this->repository->model->codpranchetaprodutobarra, url('prancheta-produto-barra', $this->repository->model->codpranchetaprodutobarra));
        $this->bc->header = $this->repository->model->codpranchetaprodutobarra;
        $this->bc->addItem('Alterar');
        
        // retorna formulario edit
        return view('prancheta-produto-barra.edit', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        Session::flash('flash_update', 'Prancheta Produto Barra alterado!');
        
        // redireciona para view
        return redirect("prancheta-produto-barra/{$this->repository->model->codpranchetaprodutobarra}"); 
    }
    
}
