<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;

use MGLara\Repositories\EstoqueMesRepository;
use MGLara\Repositories\EstoqueLocalRepository;
use MGLara\Repositories\ProdutoVariacaoRepository;
use MGLara\Repositories\EstoqueLocalProdutoVariacaoRepository;
use MGLara\Repositories\EstoqueSaldoRepository;

use MGLara\Models\EstoqueLocal;
use MGLara\Models\ProdutoVariacao;
use MGLara\Models\EstoqueLocalProdutoVariacao;
use MGLara\Models\EstoqueSaldo;
use MGLara\Models\EstoqueMes;

use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;

use Carbon\Carbon;

/**
 * @property  EstoqueMesRepository $repository 
 */
class EstoqueMesController extends Controller
{

    public function __construct(EstoqueMesRepository $repository) {
        $this->repository = $repository;
        $this->bc = new Breadcrumb('Kardex Estoque');
        $this->bc->addItem('Kardex Estoque', url('estoque-mes'));
    }
    
    /**
     * Monta json para alimentar a Datatable do index
     * 
     * @param  Request $request
     * @return  json
     */
    public function datatableMovimento(Request $request) {
        
        // Autorizacao
        $this->repository->authorize('listing');

        // Grava Filtro para montar o formulario da proxima vez que o index for carregado
        $this->setFiltro([
            'filtros' => $request['filtros'],
            'order' => $request['order'],
        ]);
        
        // Ordenacao
        $columns[0] = 'codestoquemes';
        $columns[1] = 'inativo';
        $columns[2] = 'codestoquemes';
        $columns[3] = 'codestoquemes';
        $columns[4] = 'codestoquesaldo';
        $columns[5] = 'mes';
        $columns[6] = 'inicialquantidade';
        $columns[7] = 'inicialvalor';
        $columns[8] = 'entradaquantidade';
        $columns[9] = 'entradavalor';
        $columns[10] = 'saidaquantidade';
        $columns[11] = 'saidavalor';
        $columns[12] = 'saldoquantidade';
        $columns[13] = 'saldovalor';
        $columns[14] = 'customedio';

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
                url('estoque-mes', $reg->codestoquemes),
                formataData($reg->inativo, 'C'),
                formataCodigo($reg->codestoquemes),
                $reg->codestoquemes,
                $reg->codestoquesaldo,
                $reg->mes,
                $reg->inicialquantidade,
                $reg->inicialvalor,
                $reg->entradaquantidade,
                $reg->entradavalor,
                $reg->saidaquantidade,
                $reg->saidavalor,
                $reg->saldoquantidade,
                $reg->saldovalor,
                $reg->customedio,
            ];
        }
        
        // Envelopa os dados no formato do data table
        $ret = new Datatable($request['draw'], $recordsTotal, $recordsFiltered, $data);
        
        // Retorna o JSON
        return collect($ret);
        
    }

    /**
     * Display the specified resource.
     *
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $model = $this->repository->findOrFail($id);
        
        return $this->showKardex(
            $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->EstoqueLocal, 
            $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao, 
            $model->EstoqueSaldo->EstoqueLocalProdutoVariacao, 
            $model->EstoqueSaldo, 
            $model,
            $model->EstoqueSaldo->fiscal,
            $model->mes->year,
            $model->mes->month
        );
        
    }
    
    public function kardex(Request $request, int $codestoquelocal, int $codprodutovariacao, string $fiscal, int $ano, int $mes)
    {
        
        $fiscal = ($fiscal == 'fiscal');
        
        $repo_el = new EstoqueLocalRepository();
        $el = $repo_el->findOrFail($codestoquelocal);
        
        $repo_pv = new ProdutoVariacaoRepository();
        $pv = $repo_pv->findOrFail($codprodutovariacao);
        
        $repo_elpv = new EstoqueLocalProdutoVariacaoRepository();
        $es = null;
        $em = null;
        if ($elpv = $repo_elpv->busca($codestoquelocal, $codprodutovariacao)) {
            $repo_es = new EstoqueSaldoRepository();
            
            if ($es = $repo_es->busca($elpv, $fiscal)) {
                $repo_mes = new EstoqueMesRepository();
                $em = $repo_mes->busca($es, Carbon::create($ano, $mes, 1));
            }
        }
        
        return $this->showKardex($el, $pv, $elpv, $es, $em, $fiscal, $ano, $mes);
        
    }
    
    private function showKardex (EstoqueLocal $el, ProdutoVariacao $pv, $elpv, $es, $em, bool $fiscal, int $ano, int $mes)
    {
        //autorizacao
        $this->repository->authorize('view');
        
        $filtro['order'] = [[
            'column' => 1,
            'dir' => 'ASC',
        ]];
        
        $repo_el = new EstoqueLocalRepository();
        $els = $repo_el->all();
        
        $pvs = $pv->Produto->ProdutoVariacaoS()->orderByRaw('variacao asc nulls first')->get();
        
        // breadcrumb
        $this->bc->header = 'Kardex: ' . $pv->Produto->produto;
        $this->bc->addItem($this->bc->header);

        $ems = [];
        if (!empty($es)) {
            $ems = $es->EstoqueMesS()->orderBy('mes', 'asc')->get();
        }
        
        $movs = [];
        if (!empty($em)) {
            $movs = $this->repository->movimentoKardex($em);
        }
        
        // retorna show
        return view('estoque-mes.show', [
            'bc'=>$this->bc, 
            
            // Registro corrente
            'pv'=>$pv, 
            'el'=>$el, 
            'elpv'=>$elpv, 
            'es'=>$es, 
            'em'=>$em,
            'movs'=>$movs,
            
            // Registros da navegacao
            'els'=>$els, 
            'pvs'=>$pvs, 
            'ems'=>$ems, 

            // da url
            'fiscal'=>$fiscal, 
            'ano'=>$ano, 
            'mes'=>$mes, 
            
            
            //'anteriores'=>$anteriores, 
            //'proximos'=>$proximos, 
            //'filtro'=>$filtro, 
            //'model'=>$this->repository->model
        ]);
    }
    
}
