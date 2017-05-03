<?php

namespace MGLara\Repositories;
    
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

use MGLara\Models\Imagem;

/**
 * Description of ImagemRepository
 * 
 * @property  Validator $validator
 * @property  Imagem $model
 */
class ImagemRepository extends MGRepository {
    
    public function boot() {
        $this->model = new Imagem();
    }
    
    //put your code here
    public function validate($data = null, $id = null) {
        
        if (empty($data)) {
            $data = $this->modell->getAttributes();
        }
        
        if (empty($id)) {
            $id = $this->model->codimagem;
        }
        
        $this->validator = Validator::make($data, [
            'observacoes' => [
                'max:200',
                'nullable',
            ],
        ], [
            'observacoes.max' => 'O campo "observacoes" não pode conter mais que 200 caracteres!',
        ]);

        return $this->validator->passes();
        
    }
    
    public function used($id = null) {
        if (!empty($id)) {
            $this->findOrFail($id);
        }
        
        if ($this->model->FamiliaProdutoS->count() > 0) {
            return 'Imagem sendo utilizada em "FamiliaProduto"!';
        }
        
        if ($this->model->GrupoProdutoS->count() > 0) {
            return 'Imagem sendo utilizada em "GrupoProduto"!';
        }
        
        if ($this->model->MarcaS->count() > 0) {
            return 'Imagem sendo utilizada em "Marca"!';
        }
        
        if ($this->model->ProdutoimagemS->count() > 0) {
            return 'Imagem sendo utilizada em "Produtoimagem"!';
        }
        
        if ($this->model->SecaoProdutoS->count() > 0) {
            return 'Imagem sendo utilizada em "SecaoProduto"!';
        }
        
        if ($this->model->SubGrupoProdutoS->count() > 0) {
            return 'Imagem sendo utilizada em "SubGrupoProduto"!';
        }
        
        return false;
    }
    
    public function listing($filters = [], $sort = [], $start = null, $length = null) {
        
        // Query da Entidade
        $qry = Imagem::query();
        
        // Filtros
        if (!empty($filters['codimagem'])) {
            $qry->where('codimagem', '=', $filters['codimagem']);
        }

        if (!empty($filters['observacoes'])) {
            $qry->palavras('observacoes', $filters['observacoes']);
        }

        if (!empty($filters['criacao'])) {
            $qry->where('criacao', '=', $filters['criacao']);
        }

        if (!empty($filters['codusuariocriacao'])) {
            $qry->where('codusuariocriacao', '=', $filters['codusuariocriacao']);
        }

        if (!empty($filters['alteracao'])) {
            $qry->where('alteracao', '=', $filters['alteracao']);
        }

        if (!empty($filters['codusuarioalteracao'])) {
            $qry->where('codusuarioalteracao', '=', $filters['codusuarioalteracao']);
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
            , 'recordsTotal' => Imagem::count()
            , 'data' => $qry->get()
        ];
        
    }
    
    public function url ($model = null) {
        if (empty($model)) {
            $model = $this->model;
        }
        
        return asset('public/imagens/' . $model->observacoes);
    }
    
}
