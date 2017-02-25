<?php

namespace MGLara\Repositories;
    
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use MGLara\Models\TipoProduto;

/**
 * Description of TipoProdutoRepository
 * 
 * @property Validator $validator
 * @property TipoProduto $model
 */
class TipoProdutoRepository extends MGRepository {
    
    public function boot() {
        $this->model = new TipoProduto();
    }
    
    //put your code here
    public function validate($data = null, $id = null) {
        
        if (empty($data)) {
            $this->model->getAttributes();
        }
        
        if (empty($id)) {
            $this->model->codtipoproduto;
        }
        
        $this->validator = Validator::make($data, [
            'tipoproduto' => [
                'required',
                Rule::unique('tbltipoproduto')->ignore($id, 'codtipoproduto')
            ],            
        ], [
            'tipoproduto.required' => 'O campo Tipo Produto não pode ser vazio',
            'tipoproduto.unique' => 'Esta Descrição já esta cadastrada',
        ]);

        return $this->validator->passes();
        
    }
    
    public function used($id = null) {
        if (!empty($id)) {
            $this->findOrFail($id);
        }
        if ($this->model->ProdutoS->count() > 0) {
            return 'Tipo Produto sendo utilizada em Produto!';
        }
        return false;
    }
    
    public function listing($filters = [], $sort = [], $start = null, $length = null) {
        
        // Query da Entidade
        $qry = TipoProduto::query();
        
        // Filtros
        if (!empty($filters['codtipoproduto'])) {
            $qry->where('codtipoproduto', '=', $filters['codtipoproduto']);
        }
        
        if (!empty($filters['tipoproduto'])) {
            foreach(explode(' ', $filters['tipoproduto']) as $palavra) {
                if (!empty($palavra)) {
                    $qry->where('tipoproduto', 'ilike', "%$palavra%");
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
