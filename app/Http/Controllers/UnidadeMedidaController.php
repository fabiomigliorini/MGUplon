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
        //$parametros = self::filtroEstatico($request, 'unidade-medida.index');
        //$model = UnidadeMedida::search($parametros)->orderBy('unidademedida', 'ASC')->paginate(20);

        $this->bc->addItem('Listagem');
        //$model = UnidadeMedida::paginate(20);
        return view('unidade-medida.index', ['bc'=>$this->bc, 'model'=>$model]);
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
        
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        
        $model->save();
        Session::flash('flash_create', 'Registro inserido.');
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
        
        Session::flash('flash_update', 'Registro atualizado.');
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
            UnidadeMedida::find($id)->delete();
            $ret = ['resultado' => true, 'mensagem' => 'Unidade de medida excluída com sucesso!'];
        }
        catch(\Exception $e){
            $ret = ['resultado' => false, 'mensagem' => 'Erro ao excluir unidade de medida!', 'exception' => $e];
        }
        return json_encode($ret);
    }    
    
    public function datatable(Request $request) {
        
        // Colunas para Filtro
        $columns[0] = 'codunidademedida';
        $columns[1] = 'unidademedida';
        $columns[2] = 'sigla';
        $columns[3] = 'criacao';
        $columns[4] = 'alteracao';
        
        // Query da Entidade
        $ums = UnidadeMedida::query();
        
        // Filtros
        if (!empty($request['columns'][0]['search']['value'])) {
            $ums->where('codunidademedida', '=', $request['columns'][0]['search']['value']);
        }
        
        if (!empty($request['columns'][1]['search']['value'])) {
            foreach(explode(' ', $request['columns'][1]['search']['value']) as $palavra) {
                if (!empty($palavra)) {
                    $ums->where('unidademedida', 'ilike', "%$palavra%");
                }
            }
        }
        
        if (!empty($request['columns'][2]['search']['value'])) {
            foreach(explode(' ', $request['columns'][2]['search']['value']) as $palavra) {
                if (!empty($palavra)) {
                    $ums->where('sigla', 'ilike', "%$palavra%");
                }
            }
        }
        
        // Registros
        $recordsTotal = UnidadeMedida::count();
        $recordsFiltered = $ums->count();
        
        // Paginacao
        $ums->offset($request['start']);
        $ums->limit($request['length']);
        
        // Ordenacao
        //dd($request['order'] );
        foreach ($request['order'] as $order) {
            $ums->orderBy($columns[$order['column']], $order['dir']);
        }
        
        // Registros
        $ums = $ums->get();
        $data = [];
        foreach ($ums as $um) {
            $data[] = [
                '<a href="' . url('unidade-medida', $um->codunidademedida) . '">' . formataCodigo($um->codunidademedida) . '</a>',
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
