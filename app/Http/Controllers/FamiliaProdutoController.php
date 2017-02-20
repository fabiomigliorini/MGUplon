<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;
use MGLara\Models\SecaoProduto;
use MGLara\Models\FamiliaProduto;
use MGLara\Models\GrupoProduto;
use Carbon\Carbon;

class FamiliaProdutoController extends Controller
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
        $model = new FamiliaProduto();
        $parent = SecaoProduto::findOrFail($request->get('codsecaoproduto'));
        return view('familia-produto.create', compact('model', 'request', 'parent'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new FamiliaProduto($request->all());
        
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        
        $model->codsecaoproduto = $request->get('codsecaoproduto');
        $model->save();
        Session::flash('flash_success', 'Família Criada!');
        return redirect("familia-produto/$model->codfamiliaproduto");    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        
        $model = FamiliaProduto::find($id);
        $parametros = self::filtroEstatico($request, 'familia-produto.show', ['ativo' => 1]);
        $parametros['codfamiliaproduto'] = $id;
        $grupos = GrupoProduto::search($parametros);
        return view('familia-produto.show', compact('model', 'grupos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = FamiliaProduto::findOrFail($id);
        return view('familia-produto.edit',  compact('model'));
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
        $model = FamiliaProduto::findOrFail($id);
        $model->fill($request->all());

        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);

        $model->save();
        
        Session::flash('flash_success', "Família '{$model->familiaproduto}' Atualizada!");
        return redirect("familia-produto/$model->codfamiliaproduto"); 
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
            FamiliaProduto::find($id)->delete();
            $ret = ['resultado' => true, 'mensagem' => 'Família excluída com sucesso!'];
        }
        catch(\Exception $e){
            $ret = ['resultado' => false, 'mensagem' => 'Erro ao excluir família!', 'exception' => $e];
        }
        return json_encode($ret);
    }    

    public function inativar(Request $request)
    {
        $model = FamiliaProduto::find($request->get('codfamiliaproduto'));
        if($request->get('acao') == 'ativar')
        {
            $model->inativo = null;
            $msg = "Família '{$model->familiaproduto}' Reativada!";
        }
        else
        {
            $model->inativo = Carbon::now();
            $msg = "Família '{$model->familiaproduto}' Inativada!";
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
            $item = FamiliaProduto::findOrFail($request->get('id'));
            return [
                'id' => $item->codfamiliaproduto,
                'familiaproduto' => $item->familiaproduto,
                'inativo' => $item->inativo,
            ];
        } else {

            // Numero da Pagina
            $params['page'] = $params['page']??1;
            
            // Monta Query
            $qry = FamiliaProduto::where('codsecaoproduto', '=', $request->codsecaoproduto);
            
            if(!empty($params['term'])) {
                foreach (explode(' ', $params['term']) as $palavra) {
                    if (!empty($palavra)) {
                        $qry->whereRaw("(tblfamiliaproduto.familiaproduto ilike '%{$palavra}%')");
                    }
                }
            }

            if ($request->get('somenteAtivos') == 'true') {
                $qry->ativo();
            }
            
            // Total de registros
            $total = $qry->count();
            
            // Ordenacao e dados para retornar
            $qry->select('codfamiliaproduto', 'familiaproduto', 'inativo');
            $qry->orderBy('familiaproduto', 'ASC');
            $qry->limit($registros_por_pagina);
            $qry->offSet($registros_por_pagina * ($params['page']-1));
            
            // Percorre registros
            $results = [];
            foreach ($qry->get() as $item) {
                $results[] = [
                    'id' => $item->codfamiliaproduto,
                    'familiaproduto' => $item->familiaproduto,
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
