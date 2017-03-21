<?php

namespace MGLara\Repositories;
    
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

use MGLara\Models\NegocioProdutoBarra;

/**
 * Description of NegocioProdutoBarraRepository
 * 
 * @property  Validator $validator
 * @property  NegocioProdutoBarra $model
 */
class NegocioProdutoBarraRepository extends MGRepository {
    
    public function boot() {
        $this->model = new NegocioProdutoBarra();
    }
    
    //put your code here
    public function validate($data = null, $id = null) {
        
        if (empty($data)) {
            $data = $this->modell->getAttributes();
        }
        
        if (empty($id)) {
            $id = $this->model->codnegocioprodutobarra;
        }
        
        $this->validator = Validator::make($data, [
            'codnegocio' => [
                'numeric',
                'required',
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
            'codprodutobarra' => [
                'numeric',
                'required',
            ],
            'codnegocioprodutobarradevolucao' => [
                'numeric',
                'nullable',
            ],
        ], [
            'codnegocio.numeric' => 'O campo "codnegocio" deve ser um número!',
            'codnegocio.required' => 'O campo "codnegocio" deve ser preenchido!',
            'quantidade.numeric' => 'O campo "quantidade" deve ser um número!',
            'quantidade.required' => 'O campo "quantidade" deve ser preenchido!',
            'valorunitario.numeric' => 'O campo "valorunitario" deve ser um número!',
            'valorunitario.required' => 'O campo "valorunitario" deve ser preenchido!',
            'valortotal.numeric' => 'O campo "valortotal" deve ser um número!',
            'valortotal.required' => 'O campo "valortotal" deve ser preenchido!',
            'codprodutobarra.numeric' => 'O campo "codprodutobarra" deve ser um número!',
            'codprodutobarra.required' => 'O campo "codprodutobarra" deve ser preenchido!',
            'codnegocioprodutobarradevolucao.numeric' => 'O campo "codnegocioprodutobarradevolucao" deve ser um número!',
        ]);

        return $this->validator->passes();
        
    }
    
    public function used($id = null) {
        if (!empty($id)) {
            $this->findOrFail($id);
        }
        
        if ($this->model->NegocioProdutoBarraS->count() > 0) {
            return 'Negocio Produto Barra sendo utilizada em "NegocioProdutoBarra"!';
        }
        
        if ($this->model->CupomfiscalprodutobarraS->count() > 0) {
            return 'Negocio Produto Barra sendo utilizada em "Cupomfiscalprodutobarra"!';
        }
        
        if ($this->model->EstoqueMovimentoS->count() > 0) {
            return 'Negocio Produto Barra sendo utilizada em "EstoqueMovimento"!';
        }
        
        if ($this->model->NotaFiscalProdutoBarraS->count() > 0) {
            return 'Negocio Produto Barra sendo utilizada em "NotaFiscalProdutoBarra"!';
        }
        
        return false;
    }
    
    public function listing($filters = [], $sort = [], $start = null, $length = null) {
        $length = 10;
        // Query da Entidade
        $qry = NegocioProdutoBarra::query();
        
        // Filtros
         if (!empty($filters['codnegocioprodutobarra'])) {
            $qry->where('codnegocioprodutobarra', '=', $filters['codnegocioprodutobarra']);
        }

         if (!empty($filters['codnegocio'])) {
            $qry->where('codnegocio', '=', $filters['codnegocio']);
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

         if (!empty($filters['codprodutobarra'])) {
            $qry->where('codprodutobarra', '=', $filters['codprodutobarra']);
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

         if (!empty($filters['codnegocioprodutobarradevolucao'])) {
            $qry->where('codnegocioprodutobarradevolucao', '=', $filters['codnegocioprodutobarradevolucao']);
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
            , 'recordsTotal' => NegocioProdutoBarra::count()
            , 'data' => $qry->get()
        ];
        
    }
    
}
