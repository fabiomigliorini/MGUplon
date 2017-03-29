<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;

use MGLara\Repositories\ProdutoBarraRepository;
use MGLara\Repositories\ProdutoRepository;

use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;

use Carbon\Carbon;

/**
 * @property  ProdutoBarraRepository $repository 
 * @property  ProdutoRepository $produtoRepository 
 */
class ProdutoBarraController extends Controller
{

    public function __construct(ProdutoBarraRepository $repository, ProdutoRepository $produtoRepository) {
        $this->repository = $repository;
        $this->produtoRepository = $produtoRepository;
        $this->bc = new Breadcrumb('Produto Barra');
        //$this->bc->addItem('Produto Barra', url('produto-barra'));
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
        return view('produto-barra.index', ['bc'=>$this->bc, 'filtro'=>$filtro]);
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
        $columns[0] = 'codprodutobarra';
        $columns[1] = 'inativo';
        $columns[2] = 'codprodutobarra';
        $columns[3] = 'variacao';
        $columns[4] = 'codproduto';
        $columns[5] = 'barras';
        $columns[6] = 'referencia';
        $columns[7] = 'codmarca';
        $columns[8] = 'codprodutoembalagem';
        $columns[9] = 'codprodutovariacao';

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
                url('produto-barra', $reg->codprodutobarra),
                formataData($reg->inativo, 'C'),
                formataCodigo($reg->codprodutobarra),
                $reg->variacao,
                $reg->codproduto,
                $reg->barras,
                $reg->referencia,
                $reg->codmarca,
                $reg->codprodutoembalagem,
                $reg->codprodutovariacao,
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
        $this->repository->new();
        
        // autoriza
        $this->repository->authorize('create');
        
        // instancia produto
        $this->produtoRepository->findOrFail($request->get('codproduto'));
        
        // breadcrumb
        $this->bc->addItem('Produto', url('produto'));
        $this->bc->addItem($this->produtoRepository->model->produto, url('produto', $this->produtoRepository->model->codproduto));
        $this->bc->addItem('Novo Código de Barras');
        
        // retorna view
        return view('produto-barra.create', ['bc'=>$this->bc, 'model'=>$this->repository->model, 'produto'=>$this->produtoRepository->model]);
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
        Session::flash('flash_create', 'Produto Barra criado!');
        
        // redireciona para o view
        return redirect("produto-barra/{$this->repository->model->codprodutobarra}");
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
        $this->bc->addItem($this->repository->model->variacao);
        $this->bc->header = $this->repository->model->variacao;
        
        // retorna show
        return view('produto-barra.show', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        $this->bc->addItem($this->repository->model->variacao, url('produto-barra', $this->repository->model->codprodutobarra));
        $this->bc->header = $this->repository->model->variacao;
        $this->bc->addItem('Alterar');
        
        // retorna formulario edit
        return view('produto-barra.edit', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        Session::flash('flash_update', 'Produto Barra alterado!');
        
        // redireciona para view
        return redirect("produto-barra/{$this->repository->model->codprodutobarra}"); 
    }
    
}
