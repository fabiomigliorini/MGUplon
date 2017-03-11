<?php

namespace MGLara\Repositories;
    
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

use MGLara\Models\ProdutoBarra;

/**
 * Description of ProdutoBarraRepository
 * 
 * @property  Validator $validator
 * @property  ProdutoBarra $model
 */
class ProdutoBarraRepository extends MGRepository {
    
    public function boot() {
        $this->model = new ProdutoBarra();
    }
    
    //put your code here
    public function validate($data = null, $id = null) {
        
        if (empty($data)) {
            $data = $this->modell->getAttributes();
        }
        
        if (empty($id)) {
            $id = $this->model->codprodutobarra;
        }
        
        $this->validator = Validator::make($data, [
            'codproduto' => [
                'numeric',
                'required',
            ],
            'variacao' => [
                'max:100',
                'nullable',
            ],
            'barras' => [
                'max:50',
                'required',
            ],
            'referencia' => [
                'max:50',
                'nullable',
            ],
            'codmarca' => [
                'numeric',
                'nullable',
            ],
            'codprodutoembalagem' => [
                'numeric',
                'nullable',
            ],
            'codprodutovariacao' => [
                'numeric',
                'required',
            ],
        ], [
            'codproduto.numeric' => 'O campo "codproduto" deve ser um número!',
            'codproduto.required' => 'O campo "codproduto" deve ser preenchido!',
            'variacao.max' => 'O campo "variacao" não pode conter mais que 100 caracteres!',
            'barras.max' => 'O campo "barras" não pode conter mais que 50 caracteres!',
            'barras.required' => 'O campo "barras" deve ser preenchido!',
            'referencia.max' => 'O campo "referencia" não pode conter mais que 50 caracteres!',
            'codmarca.numeric' => 'O campo "codmarca" deve ser um número!',
            'codprodutoembalagem.numeric' => 'O campo "codprodutoembalagem" deve ser um número!',
            'codprodutovariacao.numeric' => 'O campo "codprodutovariacao" deve ser um número!',
            'codprodutovariacao.required' => 'O campo "codprodutovariacao" deve ser preenchido!',
        ]);

        return $this->validator->passes();
        
    }
    
    public function used($id = null) {
        if (!empty($id)) {
            $this->findOrFail($id);
        }
        
        if ($this->model->ValeCompraModeloProdutoBarraS->count() > 0) {
            return 'Produto Barra sendo utilizada em "ValeCompraModeloProdutoBarra"!';
        }
        
        if ($this->model->ValeCompraProdutoBarraS->count() > 0) {
            return 'Produto Barra sendo utilizada em "ValeCompraProdutoBarra"!';
        }
        
        if ($this->model->CupomfiscalprodutobarraS->count() > 0) {
            return 'Produto Barra sendo utilizada em "Cupomfiscalprodutobarra"!';
        }
        
        if ($this->model->NegocioProdutoBarraS->count() > 0) {
            return 'Produto Barra sendo utilizada em "NegocioProdutoBarra"!';
        }
        
        if ($this->model->NfeterceiroitemS->count() > 0) {
            return 'Produto Barra sendo utilizada em "Nfeterceiroitem"!';
        }
        
        if ($this->model->NotaFiscalProdutoBarraS->count() > 0) {
            return 'Produto Barra sendo utilizada em "NotaFiscalProdutoBarra"!';
        }
        
        return false;
    }
    
    public function listing($filters = [], $sort = [], $start = null, $length = null) {
        
        // Query da Entidade
        $qry = ProdutoBarra::query();
        
        // Filtros
         if (!empty($filters['codprodutobarra'])) {
            $qry->where('codprodutobarra', '=', $filters['codprodutobarra']);
        }

         if (!empty($filters['codproduto'])) {
            $qry->where('codproduto', '=', $filters['codproduto']);
        }

         if (!empty($filters['variacao'])) {
            $qry->palavras('variacao', $filters['variacao']);
        }

         if (!empty($filters['barras'])) {
            $qry->palavras('barras', $filters['barras']);
        }

         if (!empty($filters['referencia'])) {
            $qry->palavras('referencia', $filters['referencia']);
        }

         if (!empty($filters['codmarca'])) {
            $qry->where('codmarca', '=', $filters['codmarca']);
        }

         if (!empty($filters['codprodutoembalagem'])) {
            $qry->where('codprodutoembalagem', '=', $filters['codprodutoembalagem']);
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

         if (!empty($filters['codprodutovariacao'])) {
            $qry->where('codprodutovariacao', '=', $filters['codprodutovariacao']);
        }

        
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
        return $qry->get();
        
    }
    
}
