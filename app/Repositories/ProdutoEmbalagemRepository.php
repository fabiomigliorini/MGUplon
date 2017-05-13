<?php

namespace MGLara\Repositories;
    
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

use MGLara\Models\ProdutoEmbalagem;

/**
 * Description of ProdutoEmbalagemRepository
 * 
 * @property  Validator $validator
 * @property  ProdutoEmbalagem $model
 */
class ProdutoEmbalagemRepository extends MGRepository {
    
    public function boot() {
        $this->model = new ProdutoEmbalagem();
    }
    
    //put your code here
    public function validate($data = null, $id = null) {
        
        if (empty($data)) {
            $data = $this->model->getAttributes();
        }
        
        if (empty($id)) {
            $id = $this->model->codprodutoembalagem;
        }
        
        $this->validator = Validator::make($data, [
            'codproduto' => [
                'numeric',
                'nullable',
            ],
            'codunidademedida' => [
                'numeric',
                'nullable',
            ],
            'quantidade' => [
                'numeric',
                'nullable',
            ],
            'preco' => [
                'numeric',
                'nullable',
            ],
        ], [
            'codproduto.numeric' => 'O campo "codproduto" deve ser um número!',
            'codunidademedida.numeric' => 'O campo "codunidademedida" deve ser um número!',
            'quantidade.numeric' => 'O campo "quantidade" deve ser um número!',
            'preco.numeric' => 'O campo "preco" deve ser um número!',
        ]);

        return $this->validator->passes();
        
    }
    
    public function used($id = null) {
        if (!empty($id)) {
            $this->findOrFail($id);
        }
        
        if ($this->model->ProdutoBarraS->count() > 0) {
            return 'Produto Embalagem sendo utilizada em "ProdutoBarra"!';
        }
        
        if ($this->model->ProdutoHistoricoPrecoS->count() > 0) {
            return 'Produto Embalagem sendo utilizada em "ProdutoHistoricoPreco"!';
        }
        
        return false;
    }
    
    public function listing($filters = [], $sort = [], $start = null, $length = null) {
        
        // Query da Entidade
        $qry = ProdutoEmbalagem::query();
        
        // Filtros
         if (!empty($filters['codprodutoembalagem'])) {
            $qry->where('codprodutoembalagem', '=', $filters['codprodutoembalagem']);
        }

         if (!empty($filters['codproduto'])) {
            $qry->where('codproduto', '=', $filters['codproduto']);
        }

         if (!empty($filters['codunidademedida'])) {
            $qry->where('codunidademedida', '=', $filters['codunidademedida']);
        }

         if (!empty($filters['quantidade'])) {
            $qry->where('quantidade', '=', $filters['quantidade']);
        }

         if (!empty($filters['preco'])) {
            $qry->where('preco', '=', $filters['preco']);
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
            , 'recordsTotal' => ProdutoEmbalagem::count()
            , 'data' => $qry->get()
        ];
        
    }
    
}
