<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;

use MGLara\Models\UnidadeMedida;

class UnidadeMedidaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        //$parametros = self::filtroEstatico($request, 'unidade-medida.index');
        //$model = UnidadeMedida::search($parametros)->orderBy('unidademedida', 'ASC')->paginate(20);
        $model = UnidadeMedida::paginate(20);
        return view('unidade-medida.index', compact('model'));



    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new UnidadeMedida();
        return view('unidade-medida.create', compact('model'));
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
        $model = UnidadeMedida::find($id);
        return view('unidade-medida.show', compact('model'));
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
        return view('unidade-medida.edit',  compact('model'));
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
}
