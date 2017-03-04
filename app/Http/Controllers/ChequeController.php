<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;

use MGLara\Repositories\ChequeRepository;

use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;

use Carbon\Carbon;

use MGLara\Repositories\BancoRepository;
use MGLara\Repositories\PessoaRepository;
use MGLara\Library\Cmc7\Cmc7;

/**
 * @property  ChequeRepository $repository 
 * @property BancoRepository $bancoRepository
 * @property PessoaRepository pessoaRepository
 */
class ChequeController extends Controller
{

    public function __construct(ChequeRepository $repository, BancoRepository $bancoRepository,PessoaRepository $pessoaRepository) {
        $this->repository = $repository;
        $this->bancoRepository = $bancoRepository;
        $this->pessoaRepository = $pessoaRepository;
        $this->bc = new Breadcrumb('Cheque');
        $this->bc->addItem('Cheque', url('cheque'));
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
        return view('cheque.index', ['bc'=>$this->bc, 'filtro'=>$filtro]);
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
        $columns[0] = 'codcheque';
        $columns[1] = 'inativo';
        $columns[2] = 'codcheque';
        
        $columns[3] = 'codcheque';
        $columns[4] = 'codbanco';
        $columns[5] = 'agencia';
        $columns[6] = 'contacorrente';
        $columns[7] = 'numero';
        $columns[8] = 'codpessoa';
        $columns[9] = 'emitente';
        $columns[10] = 'valor';
        $columns[11] = 'emissao';
        $columns[12] = 'vencimento';
        $columns[13] = 'indstatus';

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
            //
            $emitentes = [];
            foreach($reg->ChequeEmitenteS as $emit){
                
                $emitentes[] = [
                    $emit['emitente']
                ];
            }
            
            $data[] = [
                url('cheque', $reg->codcheque),
                formataData($reg->inativo, 'C'),
                formataCodigo($reg->codcheque),
                $reg->codcheque,
                $reg->Banco->banco,
                $reg->agencia,
                $reg->contacorrente,
                $reg->numero,
                $reg->Pessoa->pessoa,
                $emitentes,
                $reg->valor,
                formataData($reg->emissao),
                formataData($reg->vencimento),
                $reg->indstatus
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
        return view('cheque.create', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        Session::flash('flash_create', 'Cheque criado!');
        
        // redireciona para o view
        return redirect("cheque/{$this->repository->model->codcheque}");
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
        $this->bc->addItem($this->repository->model->codcheque);
        $this->bc->header = $this->repository->model->codcheque;
        
        // retorna show
        return view('cheque.show', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        $this->bc->addItem($this->repository->model->codcheque, url('cheque', $this->repository->model->codcheque));
        $this->bc->header = $this->repository->model->codcheque;
        $this->bc->addItem('Alterar');
        
        // retorna formulario edit
        return view('cheque.edit', ['bc'=>$this->bc, 'model'=>$this->repository->model]);
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
        Session::flash('flash_update', 'Cheque alterado!');
        
        // redireciona para view
        return redirect("cheque/{$this->repository->model->codcheque}"); 
    }
    
    public function consulta($cmc7) {
        
        // Autorizacao
        $this->repository->authorize('listing');

        if($consultacmc7 = $this->repository->model->where('cmc7','=',$cmc7)->first()){
           return [
            'valido' => false,
            'error' => 'Já existe um cadastro com esse CMC7. #'.$consultacmc7->codcheque
            ];
           exit;
        }

        $cmc7n = new Cmc7($cmc7);

        //dd($cmc7n->banco());
        $ultimo = [
            'codpessoa' => null,
            'emitentes' => [],
        ];
        //------- Pesquisa se há emitentes para o cheque cadastrado
        if ($retorno = $this->repository->findUltimoMesmoEmitente($cmc7n->banco(), $cmc7n->agencia(), $cmc7n->contacorrente())) {
            
            $ultimo['codpessoa'] = $retorno->codpessoa;

            foreach ($retorno->ChequeEmitenteS as $emit) {
                $ultimo['emitentes'][] = [
                    'cnpj' => $emit->cnpj,
                    'emitente' => $emit->emitente,
                ];
                if($ultimo['codpessoa']== null){
                    if($pessoa = $this->pessoaRepository->model->where('cnpj', $emit->cnpj)->first()){
                        $ultimo['codpessoa'] = $pessoa['codpessoa'];
                    }
                }
            }
        }
        //------- Consulta Banco
        if($banco = $this->bancoRepository->model->where('numerobanco', '=', $cmc7n->banco())->first()){
            $banco_nome = $banco->banco;
        }else{
            $banco_nome = $cmc7n->banco();
        }
        //------ Consultar pelo emitente
        if($cmc7n->valido()==false){
            $error = 'CMC7 Inválido';
        }else{
            $error = null;
        }
        return [
            'valido' => $cmc7n->valido(),
            'error' => $error,
            'banco' => $banco_nome,
            'agencia' => $cmc7n->agencia(),
            'contacorrente' => $cmc7n->contacorrente(),
            'numero' => $cmc7n->numero(),
            'ultimo' => $ultimo,
        ];

    }
    
    public function consultaemitente($cnpj) {

        $retorno = [
            'codpessoa' => null,
            'pessoa' => null,
        ];
        if($pessoa = $this->pessoaRepository->model->where('cnpj', $cnpj)->first()){
            $retorno['codpessoa'] = $pessoa->codpessoa;
            $retorno['pessoa'] = $pessoa->pessoa;
        }
        return $retorno;
    }
    
}
