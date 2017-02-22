<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;
use MGLara\Models\Ncm;

class NcmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $parametros = self::filtroEstatico($request, 'ncm.index');
        $parametros['codncmpai'] = 1;
        $model = Ncm::search($parametros)->orderBy('ncm', 'ASC')->paginate(20);
        $ncms = Ncm::find($request->get('ncmpai'));
        return view('ncm.index', compact('model', 'ncms'));        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new Ncm();
        return view('ncm.create', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new Ncm($request->all());
        
        if (!$model->validate()) {
            $this->throwValidationException($request, $model->_validator);
        }

        $model->save();
        Session::flash('flash_success', 'NCM Criada!');
        return redirect("ncm/$model->codncm"); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = Ncm::findOrFail($id);
        $filhos = $model->NcmS()->paginate(10);
        return view('ncm.show', compact('model', 'filhos'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = Ncm::findOrFail($id);
        return view('ncm.edit',  compact('model'));
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
        $model = Ncm::findOrFail($id);
        $model->fill($request->all());
        
        if (!$model->validate()) {
            $this->throwValidationException($request, $model->_validator);
        }

        $model->save();

        Session::flash('flash_success', "NCM '{$model->descricao}' Atualizada!");
        return redirect("ncm/$model->codncm");         
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
            Ncm::find($id)->delete();
            $ret = ['resultado' => true, 'mensagem' => 'NCM excluída com sucesso!'];
        }
        catch(\Exception $e){
            $ret = ['resultado' => false, 'mensagem' => 'Erro ao excluir NCM!', 'exception' => $e];
        }
        return json_encode($ret);
    } 
    
    public function listagemJson(Request $request) {
        if($request->get('q')) {
            return Ncm::select2($request->get('q'));
        } elseif($request->get('id')) {
            $ncm = Ncm::find($request->get('id'));
            return response()->json([
                'id' => $ncm->codncm,
                'ncm' => formataNcm($ncm->ncm),
                'descricao' => $ncm->descricao
            ]);
        }
    } 
    
    public function select2(Request $request)
    {

        // Parametros que o Selec2 envia
        $params = $request->get('params');
        
        // Quantidade de registros por pagina
        $registros_por_pagina = 100;
        
        // Se veio termo de busca
        if(!empty($params['term'])) {

            // Numero da Pagina
            $params['page'] = $params['page']??1;
            
            // Monta Query
            $qry = Ncm::query();
            
            // Condicoes de busca
            $numero = numeroLimpo(trim($params['term']));
            $qry->where('descricao', 'ILIKE', "%{$params['term']}%");

            if (!empty($numero)) {
                $qry->orWhere('ncm', 'ILIKE', "%$numero%");
            }
            
            
            //if ($request->get('somenteAtivos') == 'true') {
            //    $qry->ativo();
            //}
            
            // Total de registros
            $total = $qry->count();
            
            // Ordenacao e dados para retornar
            $qry->select('codncm', 'ncm', 'descricao');
            $qry->orderBy('ncm', 'ASC');
            $qry->limit($registros_por_pagina);
            $qry->offSet($registros_por_pagina * ($params['page']-1));
            
            // Percorre registros
            $results = [];
            foreach ($qry->get() as $item) {
                $results[] = [
                    'id' => $item->codncm,
                    'ncm' => formataNcm($item->ncm),
                    'descricao' => $item->descricao,
                    //'inativo' => formataData($item->inativo, 'C'),
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

        } elseif($request->get('id')) {
            
            // Monta Retorno
            $item = Ncm::findOrFail($request->get('id'));
            return [
                'id' => $item->codncm,
                'ncm' => formataNcm($item->ncm),
                'descricao' => $item->descricao,
                //'inativo' => formataData($item->inativo, 'C'),
            ];
        }
    }    
}
