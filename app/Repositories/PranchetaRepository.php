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
        
        if ($this->model->PranchetaprodutobarraS->count() > 0) {
            return 'Prancheta sendo utilizada em "Pranchetaprodutobarra"!';
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
    
    public function montaListagemProdutos($model = null) {
        if (empty($model)) {
            $model = $this->model;
        }
        
        $qry = $model->PranchetaProdutoBarraS()->ativo();
        
        $qry->select([
            'tblpranchetaprodutobarra.codpranchetaprodutobarra',
            'tblpranchetaprodutobarra.observacoes',
            'tblprodutobarra.codprodutobarra',
            'tblproduto.codproduto',
            'tblproduto.produto',
            'tblproduto.preco',
            'tblprodutovariacao.variacao',
            'tblsecaoproduto.secaoproduto',
            'tblfamiliaproduto.familiaproduto',
            'tblgrupoproduto.grupoproduto',
            'tblsubgrupoproduto.codsubgrupoproduto',
            'tblsubgrupoproduto.subgrupoproduto',
            'tblsecaoproduto.secaoproduto',
            'tblfamiliaproduto.familiaproduto',
            'tblgrupoproduto.grupoproduto',
            'tblsubgrupoproduto.subgrupoproduto',
            'tblprodutobarra.barras',
            'tblprodutoembalagem.codprodutoembalagem',
            'tblprodutoembalagem.quantidade',
            'tblprodutoembalagem.preco as precoembalagem',
            'tblunidademedidaembalagem.sigla as siglaembalagem',
            'tblunidademedida.sigla as sigla',
        ]);
        
        $qry->leftJoin('tblprodutobarra', 'tblprodutobarra.codprodutobarra', '=', 'tblpranchetaprodutobarra.codprodutobarra');
        $qry->leftJoin('tblprodutovariacao', 'tblprodutovariacao.codprodutovariacao', '=', 'tblprodutobarra.codprodutovariacao');
        $qry->leftJoin('tblproduto', 'tblproduto.codproduto', '=', 'tblprodutovariacao.codproduto');
        $qry->leftJoin('tblsubgrupoproduto', 'tblsubgrupoproduto.codsubgrupoproduto', '=', 'tblproduto.codsubgrupoproduto');
        $qry->leftJoin('tblgrupoproduto', 'tblgrupoproduto.codgrupoproduto', '=', 'tblsubgrupoproduto.codgrupoproduto');
        $qry->leftJoin('tblfamiliaproduto', 'tblfamiliaproduto.codfamiliaproduto', '=', 'tblgrupoproduto.codfamiliaproduto');
        $qry->leftJoin('tblsecaoproduto', 'tblsecaoproduto.codsecaoproduto', '=', 'tblfamiliaproduto.codsecaoproduto');
        $qry->leftJoin('tblprodutoembalagem', 'tblprodutoembalagem.codprodutoembalagem', '=', 'tblprodutobarra.codprodutoembalagem');
        $qry->leftJoin('tblunidademedida as tblunidademedidaembalagem', 'tblunidademedidaembalagem.codunidademedida', '=', 'tblprodutoembalagem.codunidademedida');
        $qry->leftJoin('tblunidademedida', 'tblunidademedida.codunidademedida', '=', 'tblproduto.codunidademedida');
        
        $qry->orderBy('tblsecaoproduto.secaoproduto', 'asc');
        $qry->orderBy('tblfamiliaproduto.familiaproduto', 'asc');
        $qry->orderBy('tblgrupoproduto.grupoproduto', 'asc');
        $qry->orderBy('tblsubgrupoproduto.subgrupoproduto', 'asc');
        $qry->orderBy('tblproduto.produto', 'asc');
        $qry->orderBy('tblprodutovariacao.variacao', 'asc');
        $qry->orderBy('tblprodutobarra.barras', 'asc');
        
        $prods = $qry->get();
        $codprodutos = [];
        foreach ($prods as $i => $prod) {
            $codprodutos[$prod->codproduto] = $prod->codproduto;
            
            $descricao = $prod->produto;
            if (!empty($prod->variacao)) {
                $descricao .= " {$prod->variacao}";
            }
            if (!empty($prod->quantidade)) {
                $quantidade = formataNumero($prod->quantidade, 0);
                $descricao .= " C/{$quantidade}";
                $preco = $prod->preco_embalagem;
                if (empty($prod->preco)) {
                    $preco = $prod->quantidade * $prod->preco;
                }
                $prods[$i]->preco = $preco;
            }
            $prods[$i]->descricao = $descricao;
        }


        $prods = $prods->groupBy('codsubgrupoproduto');
        
        $repo_img = new ProdutoImagemRepository();
        $imgs = $repo_img->buscaPorProdutos($codprodutos);
        
        return [
            'produtos' => $prods,
            'imagens' => $imgs,
        ];
        
    }
    
}
