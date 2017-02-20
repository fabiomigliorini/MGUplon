<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use MGLara\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Models\FamiliaProduto;
use MGLara\Models\GrupoProduto;
use MGLara\Models\SubGrupoProduto;
use Carbon\Carbon;

class GrupoProdutoController extends Controller
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
        $model = new GrupoProduto();
        $parent = FamiliaProduto::findOrFail($request->get('codgrupoproduto'));
        return view('grupo-produto.create', compact('model', 'parent'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new GrupoProduto($request->all());
        $model->codgrupoproduto = $request->get('codgrupoproduto');
        
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        
        $model->save();
        
        Session::flash('flash_success', 'Grupo Criado!');
        return redirect("grupo-produto/$model->codgrupoproduto");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $model = GrupoProduto::findOrFail($id);
        $parametros = self::filtroEstatico($request, 'grupo-produto.show', ['ativo' => 1]);
        $parametros['codgrupoproduto'] = $id;
        $subgrupos = SubGrupoProduto::search($parametros);
        return view('grupo-produto.show', compact('model','subgrupos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = GrupoProduto::findOrFail($id);
        return view('grupo-produto.edit',  compact('model'));
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
        $model = GrupoProduto::findOrFail($id);
        $model->fill($request->all());
        
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        
        $model->save();
        
        Session::flash('flash_success', "Grupo '{$model->grupoproduto}' Atualizado!");
        return redirect("grupo-produto/$id");   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $i
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            GrupoProduto::find($id)->delete();
            $ret = ['resultado' => true, 'mensagem' => 'Grupo excluído com sucesso!'];
        }
        catch(\Exception $e){
            $ret = ['resultado' => false, 'mensagem' => 'Erro ao excluir grupo!', 'exception' => $e];
        }
        return json_encode($ret);
    }    

    public function inativar(Request $request)
    {
        $model = GrupoProduto::find($request->get('codgrupoproduto'));
        if($request->get('acao') == 'ativar')
        {
            $model->inativo = null;
            $msg = "Grupo '{$model->grupoproduto}' Reativado!";
        }
        else
        {
            $model->inativo = Carbon::now();
            $msg = "Grupo '{$model->grupoproduto}' Inativado!";
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
            $item = GrupoProduto::findOrFail($request->get('id'));
            return [
                'id' => $item->codgrupoproduto,
                'grupoproduto' => $item->grupoproduto,
                'inativo' => $item->inativo,
            ];
        } else {

            // Numero da Pagina
            $params['page'] = $params['page']??1;
            
            // Monta Query
            $qry = GrupoProduto::where('codfamiliaproduto', '=', $request->codfamiliaproduto);
            
            if(!empty($params['term'])) {
                foreach (explode(' ', $params['term']) as $palavra) {
                    if (!empty($palavra)) {
                        $qry->whereRaw("(tblgrupoproduto.grupoproduto ilike '%{$palavra}%')");
                    }
                }
            }

            if ($request->get('somenteAtivos') == 'true') {
                $qry->ativo();
            }
            
            // Total de registros
            $total = $qry->count();
            
            // Ordenacao e dados para retornar
            $qry->select('codgrupoproduto', 'grupoproduto', 'inativo');
            $qry->orderBy('grupoproduto', 'ASC');
            $qry->limit($registros_por_pagina);
            $qry->offSet($registros_por_pagina * ($params['page']-1));
            
            // Percorre registros
            $results = [];
            foreach ($qry->get() as $item) {
                $results[] = [
                    'id' => $item->codgrupoproduto,
                    'grupoproduto' => $item->grupoproduto,
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
