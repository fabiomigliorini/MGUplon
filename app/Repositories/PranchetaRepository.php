<?php

namespace MGLara\Repositories;
    
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

use MGLara\Models\Prancheta;

/**
 * Description of PranchetaRepository
 * 
 * @property  Validator $validator
 * @property  Prancheta $model
 */
class PranchetaRepository extends MGRepository {
    
    public function boot() {
        $this->model = new Prancheta();
    }
    
    //put your code here
    public function validate($data = null, $id = null) {
        
        if (empty($data)) {
            $data = $this->modell->getAttributes();
        }
        
        if (empty($id)) {
            $id = $this->model->codprancheta;
        }
        
        $this->validator = Validator::make($data, [
            'prancheta' => [
                'max:50',
                'required',
            ],
            'observacoes' => [
                'max:200',
                'nullable',
            ],
        ], [
            'prancheta.max' => 'O campo "prancheta" não pode conter mais que 50 caracteres!',
            'prancheta.required' => 'O campo "prancheta" deve ser preenchido!',
            'observacoes.max' => 'O campo "observacoes" não pode conter mais que 200 caracteres!',
        ]);

        return $this->validator->passes();
        
    }
    
    public function used($id = null) {
        if (!empty($id)) {
            $this->findOrFail($id);
        }
        
        if ($this->model->PranchetaProdutoS->count() > 0) {
            return 'Prancheta sendo utilizada em "PranchetaProdutoS"!';
        }
        
        return false;
    }
    
    public function listing($filters = [], $sort = [], $start = null, $length = null) {
        
        // Query da Entidade
        $qry = Prancheta::query();
        
        // Filtros
         if (!empty($filters['codprancheta'])) {
            $qry->where('codprancheta', '=', $filters['codprancheta']);
        }

         if (!empty($filters['prancheta'])) {
            $qry->palavras('prancheta', $filters['prancheta']);
        }

         if (!empty($filters['alteracao'])) {
            $qry->where('alteracao', '=', $filters['alteracao']);
        }

         if (!empty($filters['criacao'])) {
            $qry->where('criacao', '=', $filters['criacao']);
        }

         if (!empty($filters['codusuarioalteracao'])) {
            $qry->where('codusuarioalteracao', '=', $filters['codusuarioalteracao']);
        }

         if (!empty($filters['codusuariocriacao'])) {
            $qry->where('codusuariocriacao', '=', $filters['codusuariocriacao']);
        }

         if (!empty($filters['observacoes'])) {
            $qry->palavras('observacoes', $filters['observacoes']);
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
            , 'recordsTotal' => Prancheta::count()
            , 'data' => $qry->get()
        ];
        
    }
    
    public function listagemProdutos($model = null, $codestoquelocal = null) {
        
        if (empty($model)) {
            $model = $this->model;
        }
        
        $qry = $model->PranchetaProdutoS()->ativo();
        
        $marca = collect([]);
        $secao = collect([]);
        $familia = collect([]);
        $grupo = collect([]);
        $subgrupo = collect([]);
        $produto = collect([]);
        foreach ($qry->get() as $item) {
            
            if (!isset($subgrupo[$item->Produto->codsubgrupoproduto])) {
                $subgrupo[$item->Produto->codsubgrupoproduto] = $item->Produto->SubGrupoProduto;
                
                if (!isset($grupo[$item->Produto->SubGrupoProduto->codgrupoproduto])) {
                    $grupo[$item->Produto->SubGrupoProduto->codgrupoproduto] = $item->Produto->SubGrupoProduto->GrupoProduto;
                    
                    if (!isset($familia[$item->Produto->SubGrupoProduto->GrupoProduto->codfamiliaproduto])) {
                        $familia[$item->Produto->SubGrupoProduto->GrupoProduto->codfamiliaproduto] = $item->Produto->SubGrupoProduto->GrupoProduto->FamiliaProduto;
                        
                        if (!isset($secao[$item->Produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->codsecaoproduto])) {
                            $secao[$item->Produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->codsecaoproduto] = $item->Produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->SecaoProduto;
                        }
                    }
                }
            
            }
            
            if (!isset($marca[$item->Produto->codmarca])) {
                $marca[$item->Produto->codmarca] = $item->Produto->Marca;
            }
            
            $quantidade = null;
            foreach ($item->Produto->ProdutoVariacaoS as $variacao) {
                $qry = $variacao->EstoqueLocalProdutoVariacaoS();
                if (!empty($codestoquelocal)) {
                    $qry = $qry->where('codestoquelocal', '=', $codestoquelocal);
                }
                foreach ($qry->get() as $elpv) {
                    foreach ($elpv->EstoqueSaldoS()->where('fiscal', false)->get() as $saldo) {
                        $quantidade += $saldo->saldoquantidade;
                    }
                }
            }
            
            $produto[$item->codproduto] = $item->Produto;
            $produto[$item->codproduto]->saldoquantidade = $quantidade;
            
        }
        
        $repo_img = new ProdutoImagemRepository();
        $imagem = $repo_img->buscaPorProdutos($produto->keys());
        
        $ret = [
            'marca' => $marca,
            'secao' => $secao,
            'familia' => $familia,
            'grupo' => $grupo,
            'subgrupo' => $subgrupo,
            'produto' => $produto,
            'imagem' => $imagem,
        ];
        
        return $ret;
    }
    
    public function detalhesProduto ($codproduto, $codestoquelocal = null) {
        $repo_prod = new ProdutoRepository();
        if (!$repo_prod->find($codproduto)) {
            return false;
        }
        
        $detalhes = $repo_prod->detalhes();
        
        return $detalhes;
    }
    
}
