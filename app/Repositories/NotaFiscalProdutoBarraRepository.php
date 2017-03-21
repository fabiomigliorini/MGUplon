<?php

namespace MGLara\Repositories;
    
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

use MGLara\Models\NotaFiscalProdutoBarra;

/**
 * Description of NotaFiscalProdutoBarraRepository
 * 
 * @property  Validator $validator
 * @property  NotaFiscalProdutoBarra $model
 */
class NotaFiscalProdutoBarraRepository extends MGRepository {
    
    public function boot() {
        $this->model = new NotaFiscalProdutoBarra();
    }
    
    //put your code here
    public function validate($data = null, $id = null) {
        
        if (empty($data)) {
            $data = $this->modell->getAttributes();
        }
        
        if (empty($id)) {
            $id = $this->model->codnotafiscalprodutobarra;
        }
        
        $this->validator = Validator::make($data, [
            'codnotafiscal' => [
                'numeric',
                'required',
            ],
            'codprodutobarra' => [
                'numeric',
                'required',
            ],
            'codcfop' => [
                'numeric',
                'required',
            ],
            'descricaoalternativa' => [
                'max:100',
                'nullable',
            ],
            'quantidade' => [
                'numeric',
                'required',
            ],
            'valorunitario' => [
                'numeric',
                'required',
            ],
            'valortotal' => [
                'numeric',
                'required',
            ],
            'icmsbase' => [
                'numeric',
                'nullable',
            ],
            'icmspercentual' => [
                'numeric',
                'nullable',
            ],
            'icmsvalor' => [
                'numeric',
                'nullable',
            ],
            'ipibase' => [
                'numeric',
                'nullable',
            ],
            'ipipercentual' => [
                'numeric',
                'nullable',
            ],
            'ipivalor' => [
                'numeric',
                'nullable',
            ],
            'icmsstbase' => [
                'numeric',
                'nullable',
            ],
            'icmsstpercentual' => [
                'numeric',
                'nullable',
            ],
            'icmsstvalor' => [
                'numeric',
                'nullable',
            ],
            'csosn' => [
                'max:4',
                'nullable',
            ],
            'codnegocioprodutobarra' => [
                'numeric',
                'nullable',
            ],
            'icmscst' => [
                'numeric',
                'nullable',
            ],
            'ipicst' => [
                'numeric',
                'nullable',
            ],
            'piscst' => [
                'numeric',
                'nullable',
            ],
            'pisbase' => [
                'numeric',
                'nullable',
            ],
            'pispercentual' => [
                'numeric',
                'nullable',
            ],
            'pisvalor' => [
                'numeric',
                'nullable',
            ],
            'cofinscst' => [
                'numeric',
                'nullable',
            ],
            'cofinsbase' => [
                'numeric',
                'nullable',
            ],
            'cofinsvalor' => [
                'numeric',
                'nullable',
            ],
            'csllbase' => [
                'numeric',
                'nullable',
            ],
            'csllpercentual' => [
                'numeric',
                'nullable',
            ],
            'csllvalor' => [
                'numeric',
                'nullable',
            ],
            'irpjbase' => [
                'numeric',
                'nullable',
            ],
            'irpjpercentual' => [
                'numeric',
                'nullable',
            ],
            'irpjvalor' => [
                'numeric',
                'nullable',
            ],
            'cofinspercentual' => [
                'numeric',
                'nullable',
            ],
            'codnotafiscalprodutobarraorigem' => [
                'numeric',
                'nullable',
            ],
        ], [
            'codnotafiscal.numeric' => 'O campo "codnotafiscal" deve ser um número!',
            'codnotafiscal.required' => 'O campo "codnotafiscal" deve ser preenchido!',
            'codprodutobarra.numeric' => 'O campo "codprodutobarra" deve ser um número!',
            'codprodutobarra.required' => 'O campo "codprodutobarra" deve ser preenchido!',
            'codcfop.numeric' => 'O campo "codcfop" deve ser um número!',
            'codcfop.required' => 'O campo "codcfop" deve ser preenchido!',
            'descricaoalternativa.max' => 'O campo "descricaoalternativa" não pode conter mais que 100 caracteres!',
            'quantidade.numeric' => 'O campo "quantidade" deve ser um número!',
            'quantidade.required' => 'O campo "quantidade" deve ser preenchido!',
            'valorunitario.numeric' => 'O campo "valorunitario" deve ser um número!',
            'valorunitario.required' => 'O campo "valorunitario" deve ser preenchido!',
            'valortotal.numeric' => 'O campo "valortotal" deve ser um número!',
            'valortotal.required' => 'O campo "valortotal" deve ser preenchido!',
            'icmsbase.numeric' => 'O campo "icmsbase" deve ser um número!',
            'icmspercentual.numeric' => 'O campo "icmspercentual" deve ser um número!',
            'icmsvalor.numeric' => 'O campo "icmsvalor" deve ser um número!',
            'ipibase.numeric' => 'O campo "ipibase" deve ser um número!',
            'ipipercentual.numeric' => 'O campo "ipipercentual" deve ser um número!',
            'ipivalor.numeric' => 'O campo "ipivalor" deve ser um número!',
            'icmsstbase.numeric' => 'O campo "icmsstbase" deve ser um número!',
            'icmsstpercentual.numeric' => 'O campo "icmsstpercentual" deve ser um número!',
            'icmsstvalor.numeric' => 'O campo "icmsstvalor" deve ser um número!',
            'csosn.max' => 'O campo "csosn" não pode conter mais que 4 caracteres!',
            'codnegocioprodutobarra.numeric' => 'O campo "codnegocioprodutobarra" deve ser um número!',
            'icmscst.numeric' => 'O campo "icmscst" deve ser um número!',
            'ipicst.numeric' => 'O campo "ipicst" deve ser um número!',
            'piscst.numeric' => 'O campo "piscst" deve ser um número!',
            'pisbase.numeric' => 'O campo "pisbase" deve ser um número!',
            'pispercentual.numeric' => 'O campo "pispercentual" deve ser um número!',
            'pisvalor.numeric' => 'O campo "pisvalor" deve ser um número!',
            'cofinscst.numeric' => 'O campo "cofinscst" deve ser um número!',
            'cofinsbase.numeric' => 'O campo "cofinsbase" deve ser um número!',
            'cofinsvalor.numeric' => 'O campo "cofinsvalor" deve ser um número!',
            'csllbase.numeric' => 'O campo "csllbase" deve ser um número!',
            'csllpercentual.numeric' => 'O campo "csllpercentual" deve ser um número!',
            'csllvalor.numeric' => 'O campo "csllvalor" deve ser um número!',
            'irpjbase.numeric' => 'O campo "irpjbase" deve ser um número!',
            'irpjpercentual.numeric' => 'O campo "irpjpercentual" deve ser um número!',
            'irpjvalor.numeric' => 'O campo "irpjvalor" deve ser um número!',
            'cofinspercentual.numeric' => 'O campo "cofinspercentual" deve ser um número!',
            'codnotafiscalprodutobarraorigem.numeric' => 'O campo "codnotafiscalprodutobarraorigem" deve ser um número!',
        ]);

        return $this->validator->passes();
        
    }
    
    public function used($id = null) {
        if (!empty($id)) {
            $this->findOrFail($id);
        }
        
        if ($this->model->NotaFiscalProdutoBarraS->count() > 0) {
            return 'Nota Fiscal Produto Barra sendo utilizada em "NotaFiscalProdutoBarra"!';
        }
        
        if ($this->model->EstoqueMovimentoS->count() > 0) {
            return 'Nota Fiscal Produto Barra sendo utilizada em "EstoqueMovimento"!';
        }
        
        return false;
    }
    
    public function listing($filters = [], $sort = [], $start = null, $length = null) {
        
        // Query da Entidade
        $qry = NotaFiscalProdutoBarra::query();
        
        // Filtros
         if (!empty($filters['codnotafiscalprodutobarra'])) {
            $qry->where('codnotafiscalprodutobarra', '=', $filters['codnotafiscalprodutobarra']);
        }

         if (!empty($filters['codnotafiscal'])) {
            $qry->where('codnotafiscal', '=', $filters['codnotafiscal']);
        }

         if (!empty($filters['codprodutobarra'])) {
            $qry->where('codprodutobarra', '=', $filters['codprodutobarra']);
        }

         if (!empty($filters['codcfop'])) {
            $qry->where('codcfop', '=', $filters['codcfop']);
        }

         if (!empty($filters['descricaoalternativa'])) {
            $qry->palavras('descricaoalternativa', $filters['descricaoalternativa']);
        }

         if (!empty($filters['quantidade'])) {
            $qry->where('quantidade', '=', $filters['quantidade']);
        }

         if (!empty($filters['valorunitario'])) {
            $qry->where('valorunitario', '=', $filters['valorunitario']);
        }

         if (!empty($filters['valortotal'])) {
            $qry->where('valortotal', '=', $filters['valortotal']);
        }

         if (!empty($filters['icmsbase'])) {
            $qry->where('icmsbase', '=', $filters['icmsbase']);
        }

         if (!empty($filters['icmspercentual'])) {
            $qry->where('icmspercentual', '=', $filters['icmspercentual']);
        }

         if (!empty($filters['icmsvalor'])) {
            $qry->where('icmsvalor', '=', $filters['icmsvalor']);
        }

         if (!empty($filters['ipibase'])) {
            $qry->where('ipibase', '=', $filters['ipibase']);
        }

         if (!empty($filters['ipipercentual'])) {
            $qry->where('ipipercentual', '=', $filters['ipipercentual']);
        }

         if (!empty($filters['ipivalor'])) {
            $qry->where('ipivalor', '=', $filters['ipivalor']);
        }

         if (!empty($filters['icmsstbase'])) {
            $qry->where('icmsstbase', '=', $filters['icmsstbase']);
        }

         if (!empty($filters['icmsstpercentual'])) {
            $qry->where('icmsstpercentual', '=', $filters['icmsstpercentual']);
        }

         if (!empty($filters['icmsstvalor'])) {
            $qry->where('icmsstvalor', '=', $filters['icmsstvalor']);
        }

         if (!empty($filters['csosn'])) {
            $qry->palavras('csosn', $filters['csosn']);
        }

         if (!empty($filters['codnegocioprodutobarra'])) {
            $qry->where('codnegocioprodutobarra', '=', $filters['codnegocioprodutobarra']);
        }

         if (!empty($filters['alteracao'])) {
            $qry->where('alteracao', '=', $filters['alteracao']);
        }

         if (!empty($filters['codusuarioalteracao'])) {
            $qry->where('codusuarioalteracao', '=', $filters['codusuarioalteracao']);
        }

         if (!empty($filters['criacao'])) {
            $qry->where('criacao', '=', $filters['criacao']);
        }

         if (!empty($filters['codusuariocriacao'])) {
            $qry->where('codusuariocriacao', '=', $filters['codusuariocriacao']);
        }

         if (!empty($filters['icmscst'])) {
            $qry->where('icmscst', '=', $filters['icmscst']);
        }

         if (!empty($filters['ipicst'])) {
            $qry->where('ipicst', '=', $filters['ipicst']);
        }

         if (!empty($filters['piscst'])) {
            $qry->where('piscst', '=', $filters['piscst']);
        }

         if (!empty($filters['pisbase'])) {
            $qry->where('pisbase', '=', $filters['pisbase']);
        }

         if (!empty($filters['pispercentual'])) {
            $qry->where('pispercentual', '=', $filters['pispercentual']);
        }

         if (!empty($filters['pisvalor'])) {
            $qry->where('pisvalor', '=', $filters['pisvalor']);
        }

         if (!empty($filters['cofinscst'])) {
            $qry->where('cofinscst', '=', $filters['cofinscst']);
        }

         if (!empty($filters['cofinsbase'])) {
            $qry->where('cofinsbase', '=', $filters['cofinsbase']);
        }

         if (!empty($filters['cofinsvalor'])) {
            $qry->where('cofinsvalor', '=', $filters['cofinsvalor']);
        }

         if (!empty($filters['csllbase'])) {
            $qry->where('csllbase', '=', $filters['csllbase']);
        }

         if (!empty($filters['csllpercentual'])) {
            $qry->where('csllpercentual', '=', $filters['csllpercentual']);
        }

         if (!empty($filters['csllvalor'])) {
            $qry->where('csllvalor', '=', $filters['csllvalor']);
        }

         if (!empty($filters['irpjbase'])) {
            $qry->where('irpjbase', '=', $filters['irpjbase']);
        }

         if (!empty($filters['irpjpercentual'])) {
            $qry->where('irpjpercentual', '=', $filters['irpjpercentual']);
        }

         if (!empty($filters['irpjvalor'])) {
            $qry->where('irpjvalor', '=', $filters['irpjvalor']);
        }

         if (!empty($filters['cofinspercentual'])) {
            $qry->where('cofinspercentual', '=', $filters['cofinspercentual']);
        }

         if (!empty($filters['codnotafiscalprodutobarraorigem'])) {
            $qry->where('codnotafiscalprodutobarraorigem', '=', $filters['codnotafiscalprodutobarraorigem']);
        }

        
        $count = $qry->count();
    
        switch ($filters['inativo']) {
            case 2: //Inativos
                $qry = $qry->inativo();
                break;

            case 9: //Todos
                break;

            case 1: //Ativos
            default:
                $qry = $qry->ativo();
                break;
        }
        
        // Paginacao
        if (!empty($start)) {
            $qry->offset($start);
        }
        if (!empty($length)) {
            $qry->limit($length);
        }
        
        // Ordenacao
        foreach ($sort as $s) {
            $qry->orderBy($s['column'], $s['dir']);
        }
        
        // Registros
        return [
            'recordsFiltered' => $count
            , 'recordsTotal' => NotaFiscalProdutoBarra::count()
            , 'data' => $qry->get()
        ];
        
    }
    
}
