<?php

namespace MGLara\Repositories;
    
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;


use MGLara\Models\UnidadeMedida;

/**
 * Description of UnidadeMedidaRepository
 * 
 * @property Validator $validator
 * @property UnidadeMedida $model
 */
class UnidadeMedidaRepository extends MGRepository {
    
    public function boot() {
        $this->model = new UnidadeMedida();
    }
    
    //put your code here
    public function validate($data = null, $id = null) {
        
        if (empty($data)) {
            $data = $model->getAttributes();
        }
        
        if (empty($id)) {
            $id = $model->codunidademedida;
        }
        
        if (!empty($id)) {
            $unique_unidademedida = 'unique:tblunidademedida,unidademedida,'.$id.',codunidademedida';
            $unique_sigla = 'unique:tblunidademedida,sigla,'.$id.',codunidademedida';
        } else {
            $unique_unidademedida = 'unique:tblunidademedida,unidademedida';
            $unique_sigla = 'unique:tblunidademedida,sigla';
        }           
        
        $this->validator = Validator::make($data, [
            'unidademedida' => "required|$unique_unidademedida",  
            'sigla' => "required|$unique_sigla",  
        ], [
            'unidademedida.required' => 'O campo Descrição não pode ser vazio',
            'unidademedida.unique' => 'Esta descrição já esta cadastrada',
            'sigla.required' => 'O campo Sigla não pode ser vazio',
            'sigla.unique' => 'Esta sigla já esta cadastrado',
        ]);

        return $this->validator->passes();
        
    }
    
    public function used($id = null) {
        if (!empty($id)) {
            $this->findOrFail($id);
        }
        if ($this->model->ProdutoS->count() > 0) {
            return 'Unidade de medida sendo utilizada em Produtos!';
        }
        if ($this->model->ProdutoEmbalagemS->count() > 0) {
            return 'Unidade de medida sendo utilizada em Embalagens!';
        }
        return false;
    }
    
    public function listing($filters = [], $sort = [], $start = null, $length = null) {
        
        // Query da Entidade
        $qry = UnidadeMedida::query();
        
        // Filtros
        if (!empty($filters['codunidademedida'])) {
            $qry->where('codunidademedida', '=', $filters['codunidademedida']);
        }
        
        if (!empty($filters['unidademedida'])) {
            foreach(explode(' ', $filters['unidademedida']) as $palavra) {
                if (!empty($palavra)) {
                    $qry->where('unidademedida', 'ilike', "%$palavra%");
                }
            }
        }
        
        if (!empty($filters['sigla'])) {
            foreach(explode(' ', $filters['sigla']) as $palavra) {
                if (!empty($palavra)) {
                    $qry->where('sigla', 'ilike', "%$palavra%");
                }
            }
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
