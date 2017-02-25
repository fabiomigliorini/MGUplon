<?php

namespace MGLara\Repositories;
    
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use MGLara\Models\GrupoProduto;

/**
 * Description of GrupoProdutoRepository
 * 
 * @property Validator $validator
 * @property GrupoProduto $model
 */
class GrupoProdutoRepository extends MGRepository {
    
    public function boot() {
        $this->model = new GrupoProduto();
    }
    
    //put your code here
    public function validate($data = null, $id = null) {
        
        if (empty($data)) {
            $this->model->getAttributes();
        }
        
        if (empty($id)) {
            $this->model->codgrupoproduto;
        }
        
        $this->validator = Validator::make($data, [
            'codfamiliaproduto' => 'required', 
            'grupoproduto' => [
                'required',
                'min:3',
                Rule::unique('tblgrupoproduto')->ignore($id, 'codgrupoproduto')
            ],            
        ], [
            'codfamiliaproduto.required'  => 'Selecione uma Família de produto!',
            'grupoproduto.required'   => 'Família de produto nao pode ser vazio!',
            'grupoproduto.min'        => 'Família de produto deve ter mais de 3 caracteres!',
            'grupoproduto.unique'     => 'Esta Família já esta cadastrada nessa seção!',            
        ]);

        return $this->validator->passes();
        
    }
    
    public function used($id = null) {
        if (!empty($id)) {
            $this->findOrFail($id);
        }
        if ($this->model->SubGrupoProdutoS->count() > 0) {
            return 'Grupo Produto sendo utilizada em Família Produto!';
        }
        return false;
    }
    
    public function listing($filters = [], $sort = [], $start = null, $length = null) {
        
        // Query da Entidade
        $qry = GrupoProduto::query();
        
        // Filtros
        if(!empty($filters['codfamiliaproduto']))
            $query->where('codfamiliaproduto', $filters['codfamiliaproduto']);

        if (!empty($filters['codgrupoproduto'])) {
            $qry->where('codgrupoproduto', '=', $filters['codgrupoproduto']);
        }
        
        if (!empty($filters['grupoproduto'])) {
            foreach(explode(' ', $filters['grupoproduto']) as $palavra) {
                if (!empty($palavra)) {
                    $qry->where('grupoproduto', 'ilike', "%$palavra%");
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
