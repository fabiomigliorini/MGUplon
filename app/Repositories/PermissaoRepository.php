<?php

namespace MGLara\Repositories;
    
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;


use MGLara\Models\Permissao;

/**
 * Description of PermissaoRepository
 * 
 * @property Validator $validator
 * @property Permissao $model
 */
class PermissaoRepository extends MGRepository {
    
    public function boot() {
        $this->model = new Permissao();
    }
    
    //put your code here
    public function validate($data = null, $id = null) {
        
        if (empty($data)) {
            $data = $model->getAttributes();
        }
        
        if (empty($id)) {
            $id = $model->codpermissao;
        }
        
        $this->validator = Validator::make($data, [
            // ...
        ], [
            //...
        ]);

        return $this->validator->passes();
        
    }
    
    public function used($id = null) {
        if (!empty($id)) {
            $this->findOrFail($id);
        }
        if ($this->model->GrupoUsuario->count() > 0) {
            return 'Permissão sendo utilizada em Grupo Usuario!';
        }
        return false;
    }
    
    public function listing($filters = [], $sort = [], $start = null, $length = null) {
        /*
        // Query da Entidade
        $qry = Permissao::query();
        
        // Filtros
        if (!empty($filters['codpermissao'])) {
            $qry->where('codpermissao', '=', $filters['codpermissao']);
        }
        
        if (!empty($filters['permissao'])) {
            foreach(explode(' ', $filters['permissao']) as $palavra) {
                if (!empty($palavra)) {
                    $qry->where('permissao', 'ilike', "%$palavra%");
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
        */
    }
    
}