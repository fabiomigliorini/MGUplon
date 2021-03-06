<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;

use MGLara\Repositories\PranchetaProdutoRepository;

use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;

use Carbon\Carbon;

/**
 * @property  PranchetaProdutoRepository $repository 
 */
class PranchetaProdutoController extends Controller
{

    public function __construct(PranchetaProdutoRepository $repository) {
        $this->repository = $repository;
        $this->bc = new Breadcrumb('Produtos da Prancheta');
        $this->bc->addItem('Produtos da Prancheta', url('prancheta-produto'));
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
                    'column' => 4,
                    'dir' => 'ASC',
                ]],
            ];
        }
        
        
        // retorna View
        return view('prancheta-produto.index', ['bc'=>$this->bc, 'filtro'=>$filtro]);
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
        $columns[0] = 'tblpranchetaproduto.codpranchetaproduto';
        $columns[1] = 'tblpranchetaproduto.inativo';
        $columns[2] = 'tblpranchetaproduto.codpranchetaproduto';
        $columns[3] = 'tblpranchetaproduto.codproduto';
        $columns[4] = 'tblproduto.produto';
        $columns[5] = 'tblprancheta.prancheta';
        $columns[6] = 'tblpranchetaproduto.observacoes';

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
                url('prancheta-produto', $reg->codpranchetaproduto),
                formataData($reg->inativo, 'C'),
                formataCodigo($reg->codpranchetaproduto),
                formataCodigo($reg->codproduto, 6),
                $reg->produto,
                $reg->prancheta,
                $reg->observacoes,
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
        return view('prancheta-produto.create', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        Session::flash('flash_create', 'Produto da Prancheta criado!');
        
        // redireciona para o view
        return redirect("prancheta-produto/{$this->repository->model->codpranchetaproduto}");
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
        $this->bc->addItem($this->repository->model->Produto->produto);
        $this->bc->header = $this->repository->model->Produto->produto;
        
        // retorna show
        return view('prancheta-produto.show', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        $this->bc->addItem($this->repository->model->Produto->produto, url('prancheta-produto', $this->repository->model->codpranchetaproduto));
        $this->bc->header = $this->repository->model->Produto->produto;
        $this->bc->addItem('Alterar');
        
        // retorna formulario edit
        return view('prancheta-produto.edit', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        Session::flash('flash_update', 'Produto da Prancheta alterado!');
        
        // redireciona para view
        return redirect("prancheta-produto/{$this->repository->model->codpranchetaproduto}"); 
    }
    
}
