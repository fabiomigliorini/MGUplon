<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;

use MGLara\Repositories\ImagemRepository;

use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;
use MGLara\Library\SlimImageCropper\Slim;

use Carbon\Carbon;

/**
 * @property  ImagemRepository $repository 
 */
class ImagemController extends Controller
{

    public function __construct(ImagemRepository $repository) {
        $this->repository = $repository;
        $this->bc = new Breadcrumb('Imagem');
        $this->bc->addItem('Imagem', url('imagem'));
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
        return view('imagem.index', ['bc'=>$this->bc, 'filtro'=>$filtro]);
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
        $columns[0] = 'codimagem';
        $columns[1] = 'inativo';
        $columns[2] = 'codimagem';
        $columns[3] = 'codimagem';
        $columns[4] = 'observacoes';
        $columns[5] = 'arquivo';

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
                url('imagem', $reg->codimagem),
                formataData($reg->inativo, 'C'),
                formataCodigo($reg->codimagem),
                $reg->codimagem,
                $reg->observacoes,
                $reg->arquivo,
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
        return view('imagem.create', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $images = Slim::getImages();
        
        // busca dados do formulario
        $data = $request->all();
        $data['imagem'] = $images[0]['output']['data'];
        
        // valida dados
        if (!$this->repository->validate($data)) {
            $this->throwValidationException($request, $this->repository->validator);
        }

        // preenche dados 
        $this->repository->new($data);
        
        // autoriza
        $this->repository->authorize('create');
        
        // cria
        if (!$this->repository->create($data)) {
            abort(500);
        }
        
        // Mensagem de registro criado
        Session::flash('flash_create', 'Imagem criado!');
        
        // redireciona para o view
        return redirect("imagem/{$this->repository->model->codimagem}");
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
        $this->bc->addItem($this->repository->model->codimagem);
        $this->bc->header = $this->repository->model->codimagem;
        
        // retorna show
        return view('imagem.show', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        $this->bc->addItem($this->repository->model->codimagem, url('imagem', $this->repository->model->codimagem));
        $this->bc->header = $this->repository->model->codimagem;
        $this->bc->addItem('Alterar');
        
        // retorna formulario edit
        return view('imagem.edit', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        
        // Busca registro para autorizar
        $this->repository->findOrFail($id);

        // Valida dados
        $images = Slim::getImages();
        
        // busca dados do formulario
        $data = $request->all();
        $data['imagem'] = $images[0]['output']['data'];
        if (!$this->repository->validate($data, $id)) {
            $this->throwValidationException($request, $this->repository->validator);
        }
        
        // autorizacao
        $this->repository->authorize('update');
        
        // salva
        if (!$this->repository->update(null, $data)) {
            abort(500);
        }
        
        // mensagem re registro criado
        Session::flash('flash_update', 'Imagem alterado!');
        
        // redireciona para view
        return redirect("imagem/{$this->repository->model->codimagem}"); 
    }
    
}
