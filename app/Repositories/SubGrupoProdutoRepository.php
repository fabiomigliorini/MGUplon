<?php

namespace MGLara\Repositories;
    
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use MGLara\Models\SubGrupoProduto;

/**
 * Description of SubGrupoProdutoRepository
 * 
 * @property Validator $validator
 * @property SubGrupoProduto $model
 */
class SubGrupoProdutoRepository extends MGRepository {
    
    public function boot() {
        $this->model = new SubGrupoProduto();
    }
    
    //put your code here
    public function validate($data = null, $id = null) {
        
        if (empty($data)) {
            $this->model->getAttributes();
        }
        
        if (empty($id)) {
            $this->model->codsubgrupoproduto;
        }
        
        $this->validator = Validator::make($data, [
            'codgrupoproduto' => 'required', 
            'subgrupoproduto' => [
                'required',
                'min:3',
                Rule::unique('tblsubgrupoproduto')->ignore($id, 'codsubgrupoproduto')
            ],            
        ], [
            'codgrupoproduto.required'  => 'Selecione um Grupo de produto!',
            'subgrupoproduto.required'   => 'Família de produto nao pode ser vazio!',
            'subgrupoproduto.min'        => 'Família de produto deve ter mais de 3 caracteres!',
            'subgrupoproduto.unique'     => 'Esta Família já esta cadastrada nessa seção!',            
        ]);

        return $this->validator->passes();
        
    }
    
    public function used($id = null) {
        if (!empty($id)) {
            $this->findOrFail($id);
        }
        if ($this->model->ProdutoS->count() > 0) {
            return 'Sub Grupo Produto sendo utilizada em Produto!';
        }
        return false;
    }
    
    public function listing($filters = [], $sort = [], $start = null, $length = null) {
        
        // Query da Entidade
        $qry = SubGrupoProduto::query();
        
        // Filtros
        if(!empty($filters['codgrupoproduto']))
            $query->where('codgrupoproduto', $filters['codgrupoproduto']);

        if (!empty($filters['codsubgrupoproduto'])) {
            $qry->where('codsubgrupoproduto', '=', $filters['codsubgrupoproduto']);
        }
        
        if (!empty($filters['subgrupoproduto'])) {
            foreach(explode(' ', $filters['subgrupoproduto']) as $palavra) {
                if (!empty($palavra)) {
                    $qry->where('subgrupoproduto', 'ilike', "%$palavra%");
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
