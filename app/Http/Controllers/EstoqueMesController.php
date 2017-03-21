<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;

use MGLara\Repositories\EstoqueMesRepository;

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
        // busca registro
        $this->repository->findOrFail($id);
        
        //autorizacao
        $this->repository->authorize('view');
        
        $anteriores = $this->repository->buscaAnteriores(7);
        $proximos = $this->repository->buscaProximos(14 - sizeof($anteriores));
        
        $filtro['order'] = [[
            'column' => 1,
            'dir' => 'ASC',
        ]];
        
        // breadcrumb
        $this->bc->addItem($this->repository->model->mes->format('M/Y'));
        $this->bc->header = $this->repository->model->mes->format('M/Y');
        
        // retorna show
        return view('estoque-mes.show', [
            'bc'=>$this->bc, 
            'anteriores'=>$anteriores, 
            'proximos'=>$proximos, 
            'filtro'=>$filtro, 
            'model'=>$this->repository->model
        ]);
    }

    public function kardex(Request $request, $fiscal, $codprodutovariacao, $codestoquelocal, $ano, $mes)
    {
        $fiscal = ($fiscal == 'fiscal');
        
        dd($fiscal);
        
    }
    
}
