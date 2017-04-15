<?php

namespace MGLara\Repositories;
    
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

use MGLara\Models\PranchetaProdutoBarra;

/**
 * Description of PranchetaProdutoBarraRepository
 * 
 * @property  Validator $validator
 * @property  PranchetaProdutoBarra $model
 */
class PranchetaProdutoBarraRepository extends MGRepository {
    
    public function boot() {
        $this->model = new PranchetaProdutoBarra();
    }
    
    //put your code here
    public function validate($data = null, $id = null) {
        
        if (empty($data)) {
            $data = $this->modell->getAttributes();
        }
        
        if (empty($id)) {
            $id = $this->model->codpranchetaprodutobarra;
        }
        
        $rules = [
            'observacoes' => [
                'max:200',
                'nullable',
            ],
            'codprancheta' => [
                'numeric',
                'required',
            ],
        ];

        if (empty($data['codprodutobarra'])) {
            $rules['barras'] = [
                'required',
            ];
        } else {
            $rules['codprodutobarra'] = [
                'numeric',
                'required',
            ];
        }
        
        
        $this->validator = Validator::make($data, $rules, [
            'observacoes.max' => 'O campo "observacoes" não pode conter mais que 200 caracteres!',
            'codprancheta.numeric' => 'O campo "codprancheta" deve ser um número!',
            'codprancheta.required' => 'O campo "codprancheta" deve ser preenchido!',
            'codprodutobarra.numeric' => 'O campo "codprodutobarra" deve ser um número!',
            'codprodutobarra.required' => 'O campo "codprodutobarra" deve ser preenchido!',
            'barras.required' => 'O campo "barras" deve ser preenchido!',
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
        $qry = PranchetaProdutoBarra::query();
        
        // Filtros
         if (!empty($filters['codpranchetaprodutobarra'])) {
            $qry->where('codpranchetaprodutobarra', '=', $filters['codpranchetaprodutobarra']);
        }

         if (!empty($filters['observacoes'])) {
            $qry->palavras('observacoes', $filters['observacoes']);
        }

         if (!empty($filters['criacao'])) {
            $qry->where('criacao', '=', $filters['criacao']);
        }

         if (!empty($filters['alteracao'])) {
            $qry->where('alteracao', '=', $filters['alteracao']);
        }

         if (!empty($filters['codusuariocriacao'])) {
            $qry->where('codusuariocriacao', '=', $filters['codusuariocriacao']);
        }

         if (!empty($filters['codusuarioalteracao'])) {
            $qry->where('codusuarioalteracao', '=', $filters['codusuarioalteracao']);
        }

         if (!empty($filters['codprancheta'])) {
            $qry->where('codprancheta', '=', $filters['codprancheta']);
        }

         if (!empty($filters['codprodutobarra'])) {
            $qry->where('codprodutobarra', '=', $filters['codprodutobarra']);
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
            , 'recordsTotal' => PranchetaProdutoBarra::count()
            , 'data' => $qry->get()
        ];
        
    }
    
    public function fill($data) {
        
        if (empty($data['codprodutobarra']) && !empty($data['barras'])) {
            $repo_pb = new ProdutoBarraRepository();
            if ($pb = $repo_pb->buscaPorBarras($data['barras'])) {
                $data['codprodutobarra'] = $pb->codprodutobarra;
            }
        }
        
        parent::fill($data);
        
        
    }
    
}
