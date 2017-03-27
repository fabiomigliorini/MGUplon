<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;

use MGLara\Repositories\ImagemRepository;
use MGLara\Repositories\MarcaRepository;
use MGLara\Repositories\ProdutoRepository;
 use MGLara\Repositories\SecaoProdutoRepository;
 use MGLara\Repositories\FamiliaProdutoRepository;
 use MGLara\Repositories\GrupoProdutoRepository;
 use MGLara\Repositories\SubGrupoProdutoRepository;

use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;

use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

use MGLara\Library\SlimImageCropper\Slim;

/**
 * @property  ImagemRepository              $repository 
 * @property  ProdutoRepository             $produtoRepository 
 * @property  MarcaRepository               $marcaRepository 
 * @property  SecaoProdutoRepository        $secaoProdutoRepository 
 * @property  FamiliaProdutoRepository      $familiaProdutoRepository 
 * @property  GrupoProdutoRepository        $grupoProdutoRepository 
 * @property  SubGrupoProdutoRepository     $subGrupoProdutoRepository 
 */

/*
1 - Criar campo 'arquivo' 
2 - UPDATE tblimagem SET arquivo = observacoes
 */

class ImagemController extends Controller
{

    public function __construct(
        ImagemRepository                $repository,
        ProdutoRepository               $produtoRepository,
        MarcaRepository                 $marcaRepository,
        SecaoProdutoRepository          $secaoProdutoRepository,
        FamiliaProdutoRepository        $familiaProdutoRepository,
        GrupoProdutoRepository          $grupoProdutoRepository,
        SubGrupoProdutoRepository       $subGrupoProdutoRepository
    ) {
        $this->repository                   = $repository;
        $this->produtoRepository            = $produtoRepository;
        $this->marcaRepository              = $marcaRepository;
        $this->secaoProdutoRepository       = $secaoProdutoRepository;
        $this->familiaProdutoRepository     = $familiaProdutoRepository;
        $this->grupoProdutoRepository       = $grupoProdutoRepository;
        $this->subGrupoProdutoRepository    = $subGrupoProdutoRepository;
        
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
        $data = $request->all();
        
        if (!$this->repository->validate($data)) {
            $this->throwValidationException($request, $this->repository->validator);
        }
        
        $this->repository->authorize('create');
        
        switch ($data['model']) {
            case 'marca': 
                $repo = $this->marcaRepository->findOrFail($data['id']);
                break;

            case 'secao-produto': $repo = $this->secaoProdutoRepository->findOrFail($data['id']);
                break;

            case 'familia-produto': 
                $repo = $this->familiaProdutoRepository->findOrFail($data['id']);
                break;

            case 'grupo-produto': 
                $repo = $this->grupoProdutoRepository->findOrFail($data['id']);
                break;

            case 'sub-grupo-produto': 
                $repo = $this->subGrupoProdutoRepository->findOrFail($data['id']);
                break;

            case 'produto': 
                $repo = $this->produtoRepository->findOrFail($data['id']);
                break;
        }
        
        $codimagem = Input::file('codimagem');
        $extensao = $codimagem->getClientOriginalExtension();
        
        $this->repository->new();
        $this->repository->create();
        
        $arquivo = $this->repository->model->codimagem . '.' . $extensao;
        
        if($data['model'] == 'produto') {

            // Carrega Imagens do SLIM
            $images = Slim::getImages();
            if (!isset($images[0])) {
                abort(500, 'Nenhuma imagem informada!');
            }
            $image = $images[0];

            // Se tipo for diferente de JPEG
            if ($image['input']['type'] != 'image/jpeg') {
                abort(500, 'Imagem deve ser um JPEG!');
            }

            // Anexa imagem ao produto
            $repo->ProdutoImagemS()->attach($this->repository->model->codimagem);

            // Salva o arquivo
            Slim::saveFile($image['output']['data'], $arquivo, './public/imagens', false);

            // Se havia alguma imagem para inativar
            if($data['imagem']) {
                //$imagem_inativa = $this->repository->model->find($data['imagem']);
                //$imagem_inativa->inativo = Carbon::now();
                //$imagem_inativa->save();
                $this->repository->model->inativo = Carbon::now();
                $this->repository->save();                
                $repo->ProdutoImagemS()->detach($data['imagem']);
            }
            
            Session::flash('flash_update', 'Imagem inserida.');
            return redirect("produto/{$data['id']}");

        } else {
        
            if(!is_null($repo->codimagem)) {
                $this->repository->model->inativo = Carbon::now();
                $this->repository->save();
            }

            $this->repository->model->arquivo = $arquivo;
            $this->repository->save();

            $diretorio = './public/imagens';

            try {

                $codimagem->move($diretorio, $arquivo);
                $repo->codimagem = $this->repository->model->codimagem;
                $repo->save();
                Session::flash('flash_create', 'Imagem cadastrada!');
                return redirect(modelUrl($request->get('model')).'/'. $data['id']);  

            } catch (\Symfony\Component\HttpFoundation\File\Exception\FileException $e) {

                Session::flash('flash_danger', "Não foi possível cadastrar essa imagem!");
                Session::flash('flash_danger_detail', $e->getMessage());
                return redirect("{$data['model']}/{$data['id']}");  
            }
            
            return redirect("imagem/{$this->repository->model->codimagem}");
        }
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
        $this->bc->addItem($this->repository->model->arquivo);
        $this->bc->header = $this->repository->model->arquivo;
        
        // retorna show
        return view('imagem.show', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    /*
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
    */

    /**
     * Update the specified resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    /*
    public function update(Request $request, $id)
    {
        
        parent::update($request, $id);
        
        // mensagem re registro criado
        Session::flash('flash_update', 'Imagem alterado!');
        
        // redireciona para view
        return redirect("imagem/{$this->repository->model->codimagem}"); 
    }
    */
    
    public function delete(Request $request)
    {
        $data = $request->all();
        try {
            
            switch ($data['model']) {
                case 'marca': 
                    $repo = $this->marcaRepository->findOrFail($data['id']);
                    break;

                case 'secao-produto': $repo = $this->secaoProdutoRepository->findOrFail($data['id']);
                    break;

                case 'familia-produto': 
                    $repo = $this->familiaProdutoRepository->findOrFail($data['id']);
                    break;

                case 'grupo-produto': 
                    $repo = $this->grupoProdutoRepository->findOrFail($data['id']);
                    break;

                case 'sub-grupo-produto': 
                    $repo = $this->subGrupoProdutoRepository->findOrFail($data['id']);
                    break;

                case 'produto': 
                    $repo = $this->produtoRepository->findOrFail($data['id']);
                    break;
            }            
            
            $this->repository->findOrFail($repo->codimagem);
            $this->repository->inativo = Carbon::now();
            $this->repository->save();              
            
            $repo->codimagem = null;
            $repo->save();
            
            Session::flash('flash_delete', 'Imagem deletada!');
            return Redirect::back();
        }
        catch(\Exception $e){
            dd($e);
            Session::flash('flash_error', "Erro ao excluir imagem! {$e}");
            return Redirect::back();
        }         
    }
    
}
