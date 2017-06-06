<?php

namespace MGLara\Repositories;
    
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use DB;

use MGLara\Models\Imagem;

use Illuminate\Support\Facades\Input;
use MGLara\Library\SlimImageCropper\Slim;
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
            'arquivo' => [
                'max:150',
                'nullable',
            ],
        ], [
            'observacoes.max' => 'O campo "observacoes" não pode conter mais que 200 caracteres!',
            'arquivo.max' => 'O campo "arquivo" não pode conter mais que 150 caracteres!',
        ]);

        return $this->validator->passes();
        
    }
    
    public function used($id = null) {
        
        // Ignorar esses relacionamentos, metodo delete excluira automaticamente
        
        /*
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
        
        if ($this->model->ProdutoImagemS->count() > 0) {
            return 'Imagem sendo utilizada em "ProdutoImagem"!';
        }
        
        if ($this->model->SecaoProdutoS->count() > 0) {
            return 'Imagem sendo utilizada em "SecaoProduto"!';
        }
        
        if ($this->model->SubGrupoProdutoS->count() > 0) {
            return 'Imagem sendo utilizada em "SubGrupoProduto"!';
        }
        */
        
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

         if (!empty($filters['arquivo'])) {
            $qry->palavras('arquivo', $filters['arquivo']);
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
    
    public function create($data = null) {
        
        if (!empty($data)) {
            $this->new($data);
        }
        
        if ($this->model->exists) {
            return false;
        }
        
        $seq = DB::select('select nextval(\'tblimagem_codimagem_seq\') as codimagem');
        
        $this->model->codimagem = $seq[0]->codimagem;
        $this->model->arquivo = $seq[0]->codimagem .'.jpg';
        
        // Salva o arquivo
        //dd($this->model->path);
        Slim::saveFile($data['imagem'], $this->model->arquivo, $this->model->directory, false);
        
        return $this->model->save();
        
    }
        
    
    
    public function delete($id = null) {
        if (!empty($id)) {
            $this->findOrFail($id);
        }

        // Exclui relacionamento com Secao de Produto
        if ($this->model->SecaoProdutoS->count() > 0) {
            $repo = new SecaoProdutoRepository();
            foreach ($this->model->SecaoProdutoS as $model) {
                $repo->model = $model;
                $repo->model->codimagem = null;
                $repo->save();
            }
        }
        
        // Exclui relacionamento com Familia de Produto
        if ($this->model->FamiliaProdutoS->count() > 0) {
            $repo = new FamiliaProdutoRepository();
            foreach ($this->model->FamiliaProdutoS as $model) {
                $repo->model = $model;
                $repo->model->codimagem = null;
                $repo->save();
            }
        }
        
        // Exclui relacionamento com Grupo de Produto
        if ($this->model->GrupoProdutoS->count() > 0) {
            $repo = new GrupoProdutoRepository();
            foreach ($this->model->GrupoProdutoS as $model) {
                $repo->model = $model;
                $repo->model->codimagem = null;
                $repo->save();
            }
        }
        
        // Exclui relacionamento com Sub-Grupo de Produto
        if ($this->model->SubGrupoProdutoS->count() > 0) {
            $repo = new SubGrupoProdutoRepository();
            foreach ($this->model->SubGrupoProdutoS as $model) {
                $repo->model = $model;
                $repo->model->codimagem = null;
                $repo->save();
            }
        }
        
        // Exclui relacionamento com Sub-Grupo de Produto
        if ($this->model->MarcaS->count() > 0) {
            $repo = new MarcaRepository();
            foreach ($this->model->MarcaS as $model) {
                $repo->model = $model;
                $repo->model->codimagem = null;
                $repo->save();
            }
        }
        
        // Exclui relacionamento com Produto
        if ($this->model->ProdutoS->count() > 0) {
            $repo = new ProdutoRepository();
            foreach ($this->model->ProdutoS as $model) {
                $repo->model = $model;
                $repo->model->codimagem = null;
                $repo->save();
            }
        }
        
        // Exclui relacionamento com Sub-Grupo de Produto
        if ($this->model->ProdutoImagemS->count() > 0) {
            $repo = new ProdutoImagemRepository();
            foreach ($this->model->ProdutoImagemS as $model) {
                $repo->model = $model;
                $repo->delete();
            }
        }
        
        return $this->model->delete();
    }
    
}
