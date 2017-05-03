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
    
    public function listagemProdutos($model = null) {
        
        
        if (empty($model)) {
            $model = $this->model;
        }
        
        $qry = $model->PranchetaProdutoS()->ativo();
        
        $qry->select([
            'tblpranchetaproduto.codpranchetaproduto',
            'tblpranchetaproduto.observacoes',
            'tblproduto.codproduto',
            'tblproduto.produto',
            'tblproduto.preco',
            'tblsecaoproduto.codsecaoproduto',
            'tblsecaoproduto.secaoproduto',
            'tblfamiliaproduto.codfamiliaproduto',
            'tblfamiliaproduto.familiaproduto',
            'tblgrupoproduto.codgrupoproduto',
            'tblgrupoproduto.grupoproduto',
            'tblsubgrupoproduto.codsubgrupoproduto',
            'tblsubgrupoproduto.subgrupoproduto',
            'tblsecaoproduto.secaoproduto',
            'tblfamiliaproduto.familiaproduto',
            'tblgrupoproduto.grupoproduto',
            'tblsubgrupoproduto.subgrupoproduto',
            'tblunidademedida.sigla',
        ]);
        
        $qry->leftJoin('tblproduto', 'tblproduto.codproduto', '=', 'tblpranchetaproduto.codproduto');
        $qry->leftJoin('tblmarca', 'tblmarca.codmarca', '=', 'tblproduto.codmarca');
        $qry->leftJoin('tblsubgrupoproduto', 'tblsubgrupoproduto.codsubgrupoproduto', '=', 'tblproduto.codsubgrupoproduto');
        $qry->leftJoin('tblgrupoproduto', 'tblgrupoproduto.codgrupoproduto', '=', 'tblsubgrupoproduto.codgrupoproduto');
        $qry->leftJoin('tblfamiliaproduto', 'tblfamiliaproduto.codfamiliaproduto', '=', 'tblgrupoproduto.codfamiliaproduto');
        $qry->leftJoin('tblsecaoproduto', 'tblsecaoproduto.codsecaoproduto', '=', 'tblfamiliaproduto.codsecaoproduto');
        $qry->leftJoin('tblunidademedida', 'tblunidademedida.codunidademedida', '=', 'tblproduto.codunidademedida');
        
        $qry->orderBy('tblsecaoproduto.secaoproduto', 'asc');
        $qry->orderBy('tblfamiliaproduto.familiaproduto', 'asc');
        $qry->orderBy('tblgrupoproduto.grupoproduto', 'asc');
        $qry->orderBy('tblsubgrupoproduto.subgrupoproduto', 'asc');
        $qry->orderBy('tblproduto.produto', 'asc');
        
        $itens = $qry->get();
        
        $secoes = collect([]);
        
        foreach ($itens as $item) {
            
            //Secao
            if (!isset($secoes[$item->codsecaoproduto])) {
                $secao = (object) [
                    'codsecaoproduto' => $item->codsecaoproduto,
                    'secaoproduto' => $item->secaoproduto,
                    'quantidadeprodutos' => 0,
                    'familiaproduto' => collect([]),
                ];
                $secoes[$item->codsecaoproduto] = $secao;
            }
            $secoes[$item->codsecaoproduto]->quantidadeprodutos++;
            
            //Familia
            if (!isset($secoes[$item->codsecaoproduto]->familiaproduto[$item->codfamiliaproduto])) {
                $familia = (object)[
                    'codfamiliaproduto' => $item->codfamiliaproduto,
                    'familiaproduto' => $item->familiaproduto,
                    'quantidadeprodutos' => 0,
                    'grupoproduto' => collect([]),
                ];
                $secoes[$item->codsecaoproduto]->familiaproduto[$item->codfamiliaproduto] = $familia;
            }
            $secoes[$item->codsecaoproduto]->familiaproduto[$item->codfamiliaproduto]->quantidadeprodutos++;
            
            //Grupo
            if (!isset($secoes[$item->codsecaoproduto]->familiaproduto[$item->codfamiliaproduto]->grupoproduto[$item->codgrupoproduto])) {
                $grupo = (object)[
                    'codgrupoproduto' => $item->codgrupoproduto,
                    'grupoproduto' => $item->grupoproduto,
                    'quantidadeprodutos' => 0,
                    'subgrupoproduto' => collect([]),
                ];
                $secoes[$item->codsecaoproduto]->familiaproduto[$item->codfamiliaproduto]->grupoproduto[$item->codgrupoproduto] = $grupo;
            }
            $secoes[$item->codsecaoproduto]->familiaproduto[$item->codfamiliaproduto]->grupoproduto[$item->codgrupoproduto]->quantidadeprodutos++;
            
            //Sub-Grupo
            if (!isset($secoes[$item->codsecaoproduto]->familiaproduto[$item->codfamiliaproduto]->grupoproduto[$item->codgrupoproduto]->subgrupoproduto[$item->codsubgrupoproduto])) {
                $subgrupo = (object)[
                    'codsubgrupoproduto' => $item->codsubgrupoproduto,
                    'subgrupoproduto' => $item->subgrupoproduto,
                    'quantidadeprodutos' => 0,
                    'produto' => collect([]),
                ];
                $secoes[$item->codsecaoproduto]->familiaproduto[$item->codfamiliaproduto]->grupoproduto[$item->codgrupoproduto]->subgrupoproduto[$item->codsubgrupoproduto] = $subgrupo;
            }
            $secoes[$item->codsecaoproduto]->familiaproduto[$item->codfamiliaproduto]->grupoproduto[$item->codgrupoproduto]->subgrupoproduto[$item->codsubgrupoproduto]->quantidadeprodutos++;
            
            //Imagens
            $imagem = collect([]);
            foreach ($item->Produto->ProdutoImagemS as $img) {
                $imagem[] = (object)[
                    'codimagem' => $img->codimagem,
                    'url' => asset('public/imagens/' . $img->observacoes),
                ];
            }
            
            //Embalagem
            $embalagem = collect([]);
            $embalagem[0] = (object)[
                'codprodutoembalagem' => null,
                'codembalagem' => $item->codembalagem,
                'sigla' => $item->Produto->UnidadeMedida->sigla,
                'quantidade' => 1,
                'preco' => $item->Produto->preco,
                'precocalculado' => false,
                'variacao' => collect([]),
            ];
            foreach ($item->Produto->ProdutoEmbalagemS()->orderBy('quantidade')->get() as $pe) {
                $preco = $pe->preco;
                $precocalculado = false;
                if (empty($preco)) {
                    $preco = $item->Produto->preco * $pe->quantidade;
                    $precocalculado = true;
                }
                $embalagem[$pe->codprodutoembalagem] = (object)[
                    'codprodutoembalagem' => $pe->codprodutoembalagem,
                    'codembalagem' => $pe->codembalagem,
                    'sigla' => $pe->UnidadeMedida->sigla,
                    'quantidade' => $pe->quantidade,
                    'preco' => $preco,
                    'precocalculado' => $precocalculado,
                    'variacao' => collect([]),
                ];
            }
            
            $variacao = collect([]);
            foreach ($item->Produto->ProdutoVariacaoS()->orderByRaw('variacao asc nulls first')->get() as $pv) {
                
                foreach ($pv->ProdutoBarraS()->orderBy('barras')->get() as $pb) {
                    $codprodutoembalagem = $pb->codprodutoembalagem;
                    if (empty($codprodutoembalagem)) {
                        $codprodutoembalagem = 0;
                    }
                    
                    if (!isset($embalagem[$codprodutoembalagem]->variacao[$pv->codprodutovariacao])) {
                        $variacao = (object)[
                            'codprodutovariacao' => $pv->codprodutovariacao,
                            'variacao' => $pv->variacao,
                            'marca' => empty($pv->codmarca)?'':$pv->Marca->marca,
                            'barras' => collect([]),
                        ];
                        $embalagem[$codprodutoembalagem]->variacao[$pv->codprodutovariacao] = $variacao;
                    }
                    
                    $barras = (object)[
                        'codprodutobarra' => $pb->codprodutobarra,
                        'barras' => $pb->barras,
                    ];
                    
                    $embalagem[$codprodutoembalagem]->variacao[$pv->codprodutovariacao]->barras[$pb->codprodutobarra] = $barras;
                    
                }
                
            }
            
            //Produto
            $produto = (object)[
                'codpranchetaproduto' => $item->codpranchetaproduto,
                'observacoes' => $item->observacoes,
                'codproduto' => $item->codproduto,
                'produto' => $item->produto,
                'preco' => $item->preco,
                'sigla' => $item->sigla,
                'marca' => $item->marca,
                'imagem' => $imagem,
                'embalagem' => $embalagem,
                'variacao' => $variacao,
            ];
            $secoes[$item->codsecaoproduto]->familiaproduto[$item->codfamiliaproduto]->grupoproduto[$item->codgrupoproduto]->subgrupoproduto[$item->codsubgrupoproduto]->produto[$item->codproduto] = $produto;
            
        }
        
        $ret = [
            'quantidadeprodutos' => $itens->count(),
            'secaoproduto' => $secoes,
        ];
        
        return $ret;
    }
    
}
