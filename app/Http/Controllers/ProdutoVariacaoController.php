<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

use MGLara\Http\Controllers\Controller;

use MGLara\Models\ProdutoVariacao;
use MGLara\Models\ProdutoBarra;
use MGLara\Models\Produto;


class ProdutoVariacaoController extends Controller
{
    public function __construct()
    {
        //...
    }
    public function show($id)
    {
        $model = ProdutoVariacao::findOrFail($id);
        return redirect("produto/$model->codproduto");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $model = new ProdutoVariacao();
        $produto = Produto::findOrFail($request->codproduto);
        return view('produto-variacao.create', compact('model', 'produto'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new ProdutoVariacao($request->all());

        $model->codproduto = $request->input('codproduto');

        DB::beginTransaction();

        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);

        try {
            if (!$model->save())
                throw new Exception ('Erro ao Criar Variação!');

            $pb = new ProdutoBarra();
            $pb->codproduto = $model->codproduto;
            $pb->codprodutovariacao = $model->codprodutovariacao;

            if (!$pb->save())
                throw new Exception ('Erro ao Criar Barras!');

            $i = 0;
            foreach ($model->Produto->ProdutoEmbalagemS as $pe)
            {
                $pb = new ProdutoBarra();
                $pb->codproduto = $model->codproduto;
                $pb->codprodutovariacao = $model->codprodutovariacao;
                $pb->codprodutoembalagem = $pe->codprodutoembalagem;

                if (!$pb->save())
                    throw new Exception ("Erro ao Criar Barras da embalagem {$pe->descricao}!");

                $i++;
            }


            DB::commit();
            Session::flash('flash_success', "Variação '{$model->variacao}' criada!");
            return redirect("produto/$model->codproduto");

        } catch (Exception $ex) {
            DB::rollBack();
            $this->throwValidationException($request, $model->_validator);
        }

        return redirect("produto/$model->codproduto");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = ProdutoVariacao::findOrFail($id);
        $produto = $model->Produto;
        return view('produto-variacao.edit',  compact('model', 'produto'));
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
        $model = ProdutoVariacao::findOrFail($id);
        $model->fill($request->all());

        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);

        $model->save();
        Session::flash('flash_success', "Variação '{$model->variacao}' alterada!");
        return redirect("produto/$model->codproduto");
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
            ProdutoVariacao::find($id)->delete();
            $ret = ['resultado' => true, 'mensagem' => 'Variação excluída com sucesso!'];
        }
        catch(\Exception $e){
            $ret = ['resultado' => false, 'mensagem' => 'Erro ao excluir variação!', 'exception' => $e];
        }
        return json_encode($ret);
    }

    public function select2(Request $request)
    {
        // Parametros que o Selec2 envia
        $params = $request->get('params');
        
        // Quantidade de registros por pagina
        $registros_por_pagina = 100;
        
        if($request->get('id')) {
            
            // Monta Retorno
            $item = ProdutoVariacao::findOrFail($request->get('id'));
            return [
                'id' => $item->codprodutovariacao,
                'variacao' => empty($item->variacao)?'{ Sem Variacao }':$item->variacao
            ];
        } else {

            // Numero da Pagina
            $params['page'] = $params['page']??1;
            
            // Monta Query
            $qry = ProdutoVariacao::where('codproduto', '=', $request->codproduto);

            foreach (explode(' ', trim($request->get('q'))) as $palavra) {
                if (!empty($palavra)) {
                    $qry->where('variacao', 'ilike', "%$palavra%");
                }
            }



            //if ($request->get('somenteAtivos') == 'true') {
            //    $qry->ativo();
            //}
            
            // Total de registros
            $total = $qry->count();
            
            // Ordenacao e dados para retornar
            $qry->select('variacao', 'codprodutovariacao');
            $qry->orderByRaw('variacao nulls first');
            $qry->limit($registros_por_pagina);
            $qry->offSet($registros_por_pagina * ($params['page']-1));
            
            // Percorre registros
            $results = [];
            foreach ($qry->get() as $item) {
                $results[] = [
                    'id' => $item->codprodutovariacao,
                    'variacao' => empty($item->variacao)?'{ Sem Variacao }':$item->variacao
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
