<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use MGLara\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use MGLara\Models\GrupoProduto;
use MGLara\Models\SubGrupoProduto;
use MGLara\Models\Produto;
use Carbon\Carbon;

class SubGrupoProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $model = new SubGrupoProduto();
        $parent = GrupoProduto::findOrFail($request->get('codsubgrupoproduto'));
        return view('sub-grupo-produto.create', compact('model','parent', 'request'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new SubGrupoProduto($request->all());
        
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        
        $model->codsubgrupoproduto = $request->get('codsubgrupoproduto');
        $model->save();
        
        Session::flash('flash_success', 'Sub Grupo Criado!');
        return redirect("sub-grupo-produto/$model->codsubsubgrupoproduto");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $model = SubGrupoProduto::findOrFail($id);
        $parametros = self::filtroEstatico($request, 'sub-grupo-produto.show', ['ativo' => 1]);
        $parametros['codsubsubgrupoproduto'] = $id;
        $produtos = Produto::search($parametros)->orderBy('produto', 'ASC')->paginate(15);
        return view('sub-grupo-produto.show', compact('model', 'produtos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = SubGrupoProduto::findOrFail($id);
        return view('sub-grupo-produto.edit',  compact('model'));
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
        $model = SubGrupoProduto::findOrFail($id);
        $model->fill($request->all());
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        
        $model->save();
        
        Session::flash('flash_success', "Sub Grupo '{$model->subsubgrupoproduto}' Atualizado!");
        return redirect("sub-grupo-produto/$model->codsubsubgrupoproduto");         
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
            SubGrupoProduto::find($id)->delete();
            $ret = ['resultado' => true, 'mensagem' => 'Sub Grupo excluído com sucesso!'];
        }
        catch(\Exception $e){
            $ret = ['resultado' => false, 'mensagem' => 'Erro ao excluir sub grupo!', 'exception' => $e];
        }
        return json_encode($ret);
    }   
    
    public function inativar(Request $request)
    {
        $model = SubGrupoProduto::find($request->get('codsubsubgrupoproduto'));
        if($request->get('acao') == 'ativar')
        {
            $model->inativo = null;
            $msg = "Sub Grupo '{$model->subsubgrupoproduto}' Reativado!";
        }
        else
        {
            $model->inativo = Carbon::now();
            $msg = "Sub Grupo '{$model->subsubgrupoproduto}' Inativado!";
        }
        
        $model->save();
        Session::flash('flash_success', $msg);
    }    

    public function select2(Request $request)
    {
        // Parametros que o Selec2 envia
        $params = $request->get('params');
        
        // Quantidade de registros por pagina
        $registros_por_pagina = 100;
        
        if(!empty($request->get('id'))) {    
            // Monta Retorno
            $item = SubGrupoProduto::findOrFail($request->get('id'));
            return [
                'id' => $item->codsubgrupoproduto,
                'subgrupoproduto' => $item->subgrupoproduto,
                'inativo' => $item->inativo,
            ];
        } else {

            // Numero da Pagina
            $params['page'] = $params['page']??1;
            
            // Monta Query
            $qry = SubGrupoProduto::where('codgrupoproduto', '=', $request->codgrupoproduto);
            
            if(!empty($params['term'])) {
                foreach (explode(' ', $params['term']) as $palavra) {
                    if (!empty($palavra)) {
                        $qry->whereRaw("(tblsubgrupoproduto.subgrupoproduto ilike '%{$palavra}%')");
                    }
                }
            }

            if ($request->get('somenteAtivos') == 'true') {
                $qry->ativo();
            }
            
            // Total de registros
            $total = $qry->count();
            
            // Ordenacao e dados para retornar
            $qry->select('codsubgrupoproduto', 'subgrupoproduto', 'inativo');
            $qry->orderBy('subgrupoproduto', 'ASC');
            $qry->limit($registros_por_pagina);
            $qry->offSet($registros_por_pagina * ($params['page']-1));
            
            // Percorre registros
            $results = [];
            foreach ($qry->get() as $item) {
                $results[] = [
                    'id' => $item->codsubgrupoproduto,
                    'subgrupoproduto' => $item->subgrupoproduto,
                    'inativo' => $item->inativo,
                ];
            }
            
            // Monta Retorno
            return [
                'results' => $results,
                'params' => $params,
                'pagination' =>  [
                    'more' => ($total > $params['page'] * $registros_por_pagina)?true:false,
                ]
            ];
        }
    }
}
