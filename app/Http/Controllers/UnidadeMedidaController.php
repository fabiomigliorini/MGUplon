<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;

use MGLara\Models\UnidadeMedida;

use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;

use Carbon\Carbon;

class UnidadeMedidaController extends Controller
{

    public function __construct() {
        $this->bc = new Breadcrumb('Unidades de Medida');
        $this->bc->addItem('Unidades de Medida', url('unidade-medida'));
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $this->authorize('list', UnidadeMedida::class);
        $this->bc->addItem('Listagem');
        if (!$filtro = $this->getFiltro()) {
            $filtro = [
                'inativo' => 1,
            ];
        }
        return view('unidade-medida.index', ['bc'=>$this->bc, 'filtro'=>$filtro]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', UnidadeMedida::class);
        $this->bc->addItem('Nova');
        $model = new UnidadeMedida();
        return view('unidade-medida.create', ['bc'=>$this->bc, 'model'=>$model]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', UnidadeMedida::class);
        $model = new UnidadeMedida($request->all());
        
        if (!$model->validate()) {
            $this->throwValidationException($request, $model->_validator);
        }
        
        $model->save();
        Session::flash('flash_create', 'Registro criado!');
        return redirect("unidade-medida/$model->codunidademedida");    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $model = UnidadeMedida::findOrFail($id);
        $this->authorize('view', $model);
        $this->bc->addItem($model->unidademedida);
        $this->bc->header = $model->unidademedida;
        return view('unidade-medida.show', ['bc'=>$this->bc, 'model'=>$model]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = UnidadeMedida::findOrFail($id);
        $this->authorize('update', $model);
        $this->bc->addItem($model->unidademedida, url('unidade-medida', $model->codunidademedida));
        $this->bc->header = $model->unidademedida;
        $this->bc->addItem('Alterar');
        return view('unidade-medida.edit', ['bc'=>$this->bc, 'model'=>$model]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model = UnidadeMedida::findOrFail($id);
        $this->authorize('update', $model);
        $model->fill($request->all());

        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);

        $model->save();
        
        Session::flash('flash_update', 'Registro alterado!');
        return redirect("unidade-medida/$model->codunidademedida"); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = UnidadeMedida::find($id);
        $this->authorize('delete', $model);
        if ($model->ProdutoS->count() > 0) {
            return ['OK' => false, 'mensagem' => 'Unidade de Medida está sendo utilizada em Produtos!'];
        }
        return ['OK' => $model->delete()];
    }
    
    public function ativar($id) {
        $model = UnidadeMedida::find($id);
        $this->authorize('update', $model);
        return ['OK' => $model->ativar()];
    }
    
    public function inativar($id) {
        $model = UnidadeMedida::find($id * 200);
        $this->authorize('update', $model);
        return ['OK' => $model->inativar()];
    }
        
    
    public function datatable(Request $request) {
        
        $this->authorize('list', UnidadeMedida::class);
        
        // Query da Entidade
        $ums = UnidadeMedida::query();

        $this->setFiltro($request['filtros']);
        
        //dd($request['filtros']);
        
        // Filtros
        if (!empty($request['filtros']['codunidademedida'])) {
            $ums->where('codunidademedida', '=', $request['filtros']['codunidademedida']);
        }
        
        if (!empty($request['filtros']['unidademedida'])) {
            foreach(explode(' ', $request['filtros']['unidademedida']) as $palavra) {
                if (!empty($palavra)) {
                    $ums->where('unidademedida', 'ilike', "%$palavra%");
                }
            }
        }
        
        if (!empty($request['filtros']['sigla'])) {
            foreach(explode(' ', $request['filtros']['sigla']) as $palavra) {
                if (!empty($palavra)) {
                    $ums->where('sigla', 'ilike', "%$palavra%");
                }
            }
        }
        
        // Registros
        switch ($request['filtros']['inativo']) {
            case 2: //Inativos
                $ums = $ums->inativo();
                break;

            case 9: //Todos
                break;

            case 1: //Ativos
            default:
                $ums = $ums->ativo();
                break;
        }
        
        $recordsTotal = UnidadeMedida::count();
        $recordsFiltered = $ums->count();
        
        // Paginacao
        $ums->offset($request['start']);
        $ums->limit($request['length']);
        
        // Ordenacao
        $columns[0] = 'url';
        $columns[1] = 'inativo';
        $columns[2] = 'codunidademedida';
        $columns[3] = 'unidademedida';
        $columns[4] = 'sigla';
        $columns[5] = 'criacao';
        $columns[6] = 'alteracao';
        if (!empty($request['order'])) {
            foreach ($request['order'] as $order) {
                $ums->orderBy($columns[$order['column']], $order['dir']);
            }
        }
        
        // Registros
        $ums = $ums->get();
        $data = [];
        foreach ($ums as $um) {
            $data[] = [
                url('unidade-medida', $um->codunidademedida),
                formataData($um->inativo, 'C'),
                formataCodigo($um->codunidademedida),
                $um->unidademedida,
                $um->sigla,
                formataData($um->criacao, 'C'),
                formataData($um->alteracao, 'C'),
            ];
        }
        
        // Envelope Retorno
        $ret = new Datatable($request['draw'], $recordsTotal, $recordsFiltered, $data);
        
        // Retorno
        return $ret->response();
        
    }
    
}
