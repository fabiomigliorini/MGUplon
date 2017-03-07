<?php

namespace MGLara\Repositories;
    
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

use MGLara\Models\ProdutoHistoricoPreco;

/**
 * Description of ProdutoHistoricoPrecoRepository
 * 
 * @property  Validator $validator
 * @property  ProdutoHistoricoPreco $model
 */
class ProdutoHistoricoPrecoRepository extends MGRepository {
    
    public function boot() {
        $this->model = new ProdutoHistoricoPreco();
    }
    
    //put your code here
    public function validate($data = null, $id = null) {
        
        if (empty($data)) {
            $data = $this->modell->getAttributes();
        }
        
        if (empty($id)) {
            $id = $this->model->codprodutohistoricopreco;
        }
        
        $this->validator = Validator::make($data, [
            'codproduto' => [
                'numeric',
                'required',
            ],
            'codprodutoembalagem' => [
                'numeric',
                'nullable',
            ],
            'precoantigo' => [
                'digits',
                'numeric',
                'nullable',
            ],
            'preconovo' => [
                'digits',
                'numeric',
                'nullable',
            ],
        ], [
            'codproduto.numeric' => 'O campo "codproduto" deve ser um número!',
            'codproduto.required' => 'O campo "codproduto" deve ser preenchido!',
            'codprodutoembalagem.numeric' => 'O campo "codprodutoembalagem" deve ser um número!',
            'precoantigo.digits' => 'O campo "precoantigo" deve conter no máximo 2 dígitos!',
            'precoantigo.numeric' => 'O campo "precoantigo" deve ser um número!',
            'preconovo.digits' => 'O campo "preconovo" deve conter no máximo 2 dígitos!',
            'preconovo.numeric' => 'O campo "preconovo" deve ser um número!',
        ]);

        return $this->validator->passes();
        
    }
    
    public function used($id = null) {
        if (!empty($id)) {
            $this->findOrFail($id);
        }
        
        return false;
    }
    
    public function listing($filters = [], $sort = [], $start = null, $length = null) {
        
        // Query da Entidade
        $qry = ProdutoHistoricoPreco::query();
        
        // Filtros
         if (!empty($filters['codprodutohistoricopreco'])) {
            $qry->where('codprodutohistoricopreco', '=', $filters['codprodutohistoricopreco']);
        }

         if (!empty($filters['codproduto'])) {
            $qry->where('codproduto', '=', $filters['codproduto']);
        }

         if (!empty($filters['codprodutoembalagem'])) {
            $qry->where('codprodutoembalagem', '=', $filters['codprodutoembalagem']);
        }

         if (!empty($filters['precoantigo'])) {
            $qry->where('precoantigo', '=', $filters['precoantigo']);
        }

         if (!empty($filters['preconovo'])) {
            $qry->where('preconovo', '=', $filters['preconovo']);
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
