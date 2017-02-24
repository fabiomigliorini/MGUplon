<?php

namespace MGLara\Repositories;
    
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;


use MGLara\Models\GrupoUsuario;

/**
 * Description of GrupoUsuarioRepository
 * 
 * @property Validator $validator
 * @property GrupoUsuario $model
 */
class GrupoUsuarioRepository extends MGRepository {
    
    public function boot() {
        $this->model = new GrupoUsuario();
    }
    
    //put your code here
    public function validate($data = null, $id = null) {
        
        if (empty($data)) {
            $data = $model->getAttributes();
        }
        
        if (empty($id)) {
            $id = $model->codgrupousuario;
        }
        
        if (!empty($id)) {
            $unique_grupousuario = 'unique:tblgrupousuario,grupousuario,'.$id.',codgrupousuario';
            $unique_sigla = 'unique:tblgrupousuario,sigla,'.$id.',codgrupousuario';
        } else {
            $unique_grupousuario = 'unique:tblgrupousuario,grupousuario';
            $unique_sigla = 'unique:tblgrupousuario,sigla';
        }           
        
        $this->validator = Validator::make($data, [
            'grupousuario' => "required|$unique_grupousuario",  
            'sigla' => "required|$unique_sigla",  
        ], [
            'grupousuario.required' => 'O campo Descrição não pode ser vazio',
            'grupousuario.unique' => 'Esta descrição já esta cadastrada',
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
        $qry = GrupoUsuario::query();
        
        // Filtros
        if (!empty($filters['codgrupousuario'])) {
            $qry->where('codgrupousuario', '=', $filters['codgrupousuario']);
        }
        
        if (!empty($filters['grupousuario'])) {
            foreach(explode(' ', $filters['grupousuario']) as $palavra) {
                if (!empty($palavra)) {
                    $qry->where('grupousuario', 'ilike', "%$palavra%");
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
