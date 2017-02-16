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
use MGLara\Library\JsonEnvelope\Resultado;

use Carbon\Carbon;

class UnidadeMedidaController extends Controller
{

    public function __construct() {
        $this->model_class = 'UnidadeMedida';
        $this->bc = new Breadcrumb('Unidades de Medida');
        $this->bc->addItem('Unidades de Medida', url('unidade-medida'));
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
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
        try{
            $class = "\\MGLara\\Models\\{$this->model_class}";
            $class::find($id)->delete();
            $ret = new Resultado(true);
        }
        catch(\Exception $e){
            $ret = new Resultado(false, null, $e);
        }
        return $ret->response();
    }
    
    public function ativar($id) {
        $class = "\\MGLara\\Models\\{$this->model_class}";
        $model = $class::findOrFail($id);
        if (!empty($model->inativo)) {
            $model->ativar();
            $ret = new Resultado(true);
        } else {
            $ret = new Resultado(false, 'Já está Ativo!');
        }
        return $ret->response();
    }
    
    public function inativar($id) {
        $class = "\\MGLara\\Models\\{$this->model_class}";
        $model = $class::findOrFail($id);
        if (empty($model->inativo)) {
            $model->inativar();
            $ret = new Resultado(true);
        } else {
            $ret = new Resultado(false, 'Já está inativo!');
        }
        return $ret->response();
    }
        
    
    public function datatable(Request $request) {
        
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
        foreach ($request['order'] as $order) {
            $ums->orderBy($columns[$order['column']], $order['dir']);
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
