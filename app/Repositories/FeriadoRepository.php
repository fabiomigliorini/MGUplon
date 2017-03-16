<?php

namespace MGLara\Repositories;
    
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

use MGLara\Models\Feriado;

/**
 * Description of FeriadoRepository
 * 
 * @property  Validator $validator
 * @property  Feriado $model
 */
class FeriadoRepository extends MGRepository {
    
    public function boot() {
        $this->model = new Feriado();
    }
    
    //put your code here
    public function validate($data = null, $id = null) {
        
        if (empty($data)) {
            $data = $this->modell->getAttributes();
        }
        
        if (empty($id)) {
            $id = $this->model->codferiado;
        }
        
        $this->validator = Validator::make($data, [
            'data' => [
                'date',
                'required',
            ],
            'feriado' => [
                'max:100',
                'required',
            ],
        ], [
            'data.date' => 'O campo "data" deve ser uma data!',
            'data.required' => 'O campo "data" deve ser preenchido!',
            'feriado.max' => 'O campo "feriado" não pode conter mais que 100 caracteres!',
            'feriado.required' => 'O campo "feriado" deve ser preenchido!',
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
        $qry = Feriado::query();
        
        // Filtros
         if (!empty($filters['codferiado'])) {
            $qry->where('codferiado', '=', $filters['codferiado']);
        }

         if (!empty($filters['data'])) {
            $qry->where('data', '=', $filters['data']);
        }

         if (!empty($filters['feriado'])) {
            $qry->palavras('feriado', $filters['feriado']);
        }

         if (!empty($filters['codusuarioalteracao'])) {
            $qry->where('codusuarioalteracao', '=', $filters['codusuarioalteracao']);
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
        
        $count = $qry->count();
        
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
            , 'recordsTotal' => Feriado::count()
            , 'data' => $qry->get()
        ];        
    }
    
}
