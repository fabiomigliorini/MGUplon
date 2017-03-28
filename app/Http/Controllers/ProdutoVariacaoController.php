<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;

use MGLara\Repositories\ProdutoVariacaoRepository;
use MGLara\Repositories\ProdutoRepository;
use MGLara\Repositories\ProdutoBarraRepository;


use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;

use Illuminate\Support\Facades\DB;

/**
 * @property  ProdutoVariacaoRepository $repository 
 * @property  ProdutoBarraRepository $produtoBarraRepository 
 * @property  ProdutoRepository $produtoRepository 
 */
class ProdutoVariacaoController extends Controller
{

    public function __construct(ProdutoVariacaoRepository $repository, ProdutoRepository $produtoRepository, ProdutoBarraRepository $produtoBarraRepository) {
        $this->repository = $repository;
        $this->produtoRepository = $produtoRepository;
        $this->produtoBarraRepository = $produtoBarraRepository;
        $this->bc = new Breadcrumb('Produto');
        //$this->bc->addItem('Produto');
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
        return view('produto-variacao.index', ['bc'=>$this->bc, 'filtro'=>$filtro]);
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
        $columns[0] = 'codprodutovariacao';
        $columns[1] = 'inativo';
        $columns[2] = 'codprodutovariacao';
        $columns[3] = 'codprodutovariacao';
        $columns[4] = 'codproduto';
        $columns[5] = 'variacao';
        $columns[6] = 'referencia';
        $columns[7] = 'codmarca';
        $columns[8] = 'codopencart';
        $columns[9] = 'dataultimacompra';
        $columns[10] = 'custoultimacompra';
        $columns[11] = 'quantidadeultimacompra';

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
                url('produto-variacao', $reg->codprodutovariacao),
                formataData($reg->inativo, 'C'),
                formataCodigo($reg->codprodutovariacao),
                $reg->codprodutovariacao,
                $reg->codproduto,
                $reg->variacao,
                $reg->referencia,
                $reg->codmarca,
                $reg->codopencart,
                $reg->dataultimacompra,
                $reg->custoultimacompra,
                $reg->quantidadeultimacompra,
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
        $this->bc->addItem('Nova Variação');
        
        // retorna view
        return view('produto-variacao.create', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // busca dados do formulario
        $data = $request->all();
        
        // preenche dados 
        $this->repository->new($data);
        
        // autoriza
        $this->repository->authorize('create');
        
        // valida dados
        if (!$this->repository->validate($data)) {
            $this->throwValidationException($request, $this->repository->validator);
        }
        
        DB::beginTransaction();
        
        try {
            if(!$this->repository->save()){
                throw new Exception ('Erro ao Criar Variação!');
            }
            
            $this->produtoBarraRepository->new([
                'codproduto' => $this->repository->model->codproduto,
                'codprodutovariacao' =>  $this->repository->model->codprodutovariacao
            ]);
            
            if(!$this->produtoBarraRepository->save()){
                throw new \Exception ('Erro ao Criar Barras!');
            }
            dd($this->produtoBarraRepository);
            $i = 0;
            foreach ($this->repository->Produto->ProdutoEmbalagemS as $pe)
            {
                $this->produtoBarraRepository->new([
                    'codproduto' => $this->repository->model->codproduto,
                    'codprodutovariacao' =>  $this->repository->model->codprodutovariacao,
                    'codprodutoembalagem' => $pe->codprodutoembalagem
                ]);                

                if (!$this->produtoBarraRepository->save()){
                    throw new Exception ("Erro ao Criar Barras da embalagem {$pe->descricao}!");
                }
                $i++;
            }

            DB::commit();
            
        } catch (\Exception $ex) {
            DB::rollBack();
            $this->throwValidationException($request, $this->repository->validator);
        }        

        // Mensagem de registro criado
        Session::flash('flash_create', 'Produto Variacao criado!');
        
        // redireciona para o view
        return redirect("produto/{$this->repository->model->codproduto}");
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
        $this->bc->addItem($this->repository->model->codprodutovariacao);
        $this->bc->header = $this->repository->model->codprodutovariacao;
        
        // retorna show
        return view('produto-variacao.show', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        $this->bc->addItem($this->repository->model->codprodutovariacao, url('produto-variacao', $this->repository->model->codprodutovariacao));
        $this->bc->header = $this->repository->model->codprodutovariacao;
        $this->bc->addItem('Alterar');
        
        // retorna formulario edit
        return view('produto-variacao.edit', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        Session::flash('flash_update', 'Produto Variacao alterado!');
        
        // redireciona para view
        return redirect("produto-variacao/{$this->repository->model->codprodutovariacao}"); 
    }
    
}
