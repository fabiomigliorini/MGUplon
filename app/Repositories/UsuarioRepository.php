<?php

namespace MGLara\Repositories;
    
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;


use MGLara\Models\Usuario;

/**
 * Description of UsuarioRepository
 * 
 * @property Validator $validator
 * @property Usuario $model
 */
class UsuarioRepository extends MGRepository {
    
    public function boot() {
        $this->model = new Usuario();
    }
    
    //put your code here
    public function validate($data = null, $id = null) {
        
        if (empty($data)) {
            $data = $model->getAttributes();
        }
        
        if (empty($id)) {
            $id = $model->codusuario;
        }
        
        if (!empty($id)) {
            $unique_usuario = 'unique:tblusuario,usuario,'.$id.',codusuario';
            $unique_sigla = 'unique:tblusuario,sigla,'.$id.',codusuario';
        } else {
            $unique_usuario = 'unique:tblusuario,usuario';
            $unique_sigla = 'unique:tblusuario,sigla';
        }           
        
        $this->validator = Validator::make($data, [
            'usuario' => "required|$unique_usuario",  
            'sigla' => "required|$unique_sigla",  
        ], [
            'usuario.required' => 'O campo Descrição não pode ser vazio',
            'usuario.unique' => 'Esta descrição já esta cadastrada',
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
        $qry = Usuario::query();
        
        // Filtros
        if (!empty($filters['codusuario'])) {
            $qry->where('codusuario', '=', $filters['codusuario']);
        }
        
        if (!empty($filters['usuario'])) {
            foreach(explode(' ', $filters['usuario']) as $palavra) {
                if (!empty($palavra)) {
                    $qry->where('usuario', 'ilike', "%$palavra%");
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
