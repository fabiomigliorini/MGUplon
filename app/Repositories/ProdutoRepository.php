<?php

namespace MGLara\Repositories;
    
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use MGLara\Models\Produto;
//use MGLara\Repositories\MarcaRepository;
use MGLara\Models\EstoqueLocal;
/**
 * Description of ProdutoRepository
 * 
 * @property  Validator $validator
 * @property  Produto $model
 */
class ProdutoRepository extends MGRepository {
    
    public function boot() {
        $this->model = new Produto();
    }
    
    public function getPrecocAttribute()
    {
        return $this->preco;
    }    
    
    //put your code here
    public function validate($data = null, $id = null) {
        
        if (empty($data)) {
            $data = $this->modell->getAttributes();
        }
        
        if (empty($id)) {
            $id = $this->model->codproduto;
        }
/*        
        Validator::extend('nomeMarca', function ($attribute, $value, $parameters)
        {
            dd($value);
            $query = DB::table('tblmetafilialpessoa')
                    ->where('codmetafilial', $parameters[0])
                    ->where('codcargo', env('CODCARGO_SUBGERENTE'));
            
            $count = $query->count();
            if ($count > 1){
                return false;
            }
            return true;        
        });  
*/        
        //$this->app['validator']->extend('validaMarca', function ($attribute, $value, $parameters)

        Validator::extend('nomeMarca', function ($attribute, $value, $parameters)        
        {
            $marca = new MarcaRepository();
            $marca = $marca->findOrFail($parameters[0]);
            if (!empty($value) && !empty($parameters[0]) == '')
            {
                if (strpos(strtoupper($value), strtoupper($marca->marca)) === false) {
                    return false;
                } else {
                    return true;
                }    
            } else {
                return true;
            }
        });         
                
        
        $this->validator = Validator::make($data, [
            'produto' => [
                'max:100',
                'min:10',
                'required',
                Rule::unique('tblproduto')->ignore($id, 'codproduto'),
                'nomeMarca:'.$data['codmarca'],
            ],
            'referencia' => [
                'max:50',
                'nullable',
            ],
            'codunidademedida' => [
                'numeric',
                'required',
            ],
            'codsubgrupoproduto' => [
                'numeric',
                'required',
            ],
            'codmarca' => [
                'numeric',
                'required',
            ],
            'preco' => [
                'numeric',
                'nullable',
            ],
            'importado' => [
                'boolean',
                'required',
            ],
            'codtributacao' => [
                'numeric',
                'required',
            ],
            'codtipoproduto' => [
                'numeric',
                'required',
            ],
            'site' => [
                'boolean',
                'required',
            ],
            'descricaosite' => [
                'max:1024',
                'nullable',
            ],
            'codncm' => [
                'numeric',
                'nullable',
            ],
            'codcest' => [
                'numeric',
                'nullable',
            ],
            'observacoes' => [
                'max:255',
                'nullable',
            ],
            'codopencart' => [
                'numeric',
                'nullable',
            ],
            'codopencartvariacao' => [
                'numeric',
                'nullable',
            ],
        ], [
            'produto.max' => 'O campo "produto" não pode conter mais que 100 caracteres!',
            'produto.min' => 'O campo "produto" não pode conter menos que 9 caracteres!',
            'produto.required' => 'O campo "produto" deve ser preenchido!',
            'produto.nome_marca' => 'Não contem o nome da marca no campo "produto"!',
            
            'referencia.max' => 'O campo "referencia" não pode conter mais que 50 caracteres!',
            'codunidademedida.numeric' => 'O campo "codunidademedida" deve ser um número!',
            'codunidademedida.required' => 'O campo "codunidademedida" deve ser preenchido!',
            'codsubgrupoproduto.numeric' => 'O campo "codsubgrupoproduto" deve ser um número!',
            'codsubgrupoproduto.required' => 'O campo "codsubgrupoproduto" deve ser preenchido!',
            'codmarca.numeric' => 'O campo "codmarca" deve ser um número!',
            'codmarca.required' => 'O campo "codmarca" deve ser preenchido!',
            'preco.numeric' => 'O campo "preco" deve ser um número!',
            'importado.boolean' => 'O campo "importado" deve ser um verdadeiro/falso (booleano)!',
            'importado.required' => 'O campo "importado" deve ser preenchido!',
            'codtributacao.numeric' => 'O campo "codtributacao" deve ser um número!',
            'codtributacao.required' => 'O campo "codtributacao" deve ser preenchido!',
            'codtipoproduto.numeric' => 'O campo "codtipoproduto" deve ser um número!',
            'codtipoproduto.required' => 'O campo "codtipoproduto" deve ser preenchido!',
            'site.boolean' => 'O campo "site" deve ser um verdadeiro/falso (booleano)!',
            'site.required' => 'O campo "site" deve ser preenchido!',
            'descricaosite.max' => 'O campo "descricaosite" não pode conter mais que 1024 caracteres!',
            'codncm.numeric' => 'O campo "codncm" deve ser um número!',
            'codcest.numeric' => 'O campo "codcest" deve ser um número!',
            'observacoes.max' => 'O campo "observacoes" não pode conter mais que 255 caracteres!',
            'codopencart.numeric' => 'O campo "codopencart" deve ser um número!',
            'codopencartvariacao.numeric' => 'O campo "codopencartvariacao" deve ser um número!',
        ]);

        return $this->validator->passes();
        
    }
    
    public function used($id = null) {
        if (!empty($id)) {
            $this->findOrFail($id);
        }
        
        if ($this->model->ProdutoimagemS->count() > 0) {
            return 'Produto sendo utilizada em "Produtoimagem"!';
        }
        
        if ($this->model->ProdutoVariacaoS->count() > 0) {
            return 'Produto sendo utilizada em "ProdutoVariacao"!';
        }
        
        if ($this->model->ProdutoBarraS->count() > 0) {
            return 'Produto sendo utilizada em "ProdutoBarra"!';
        }
        
        if ($this->model->ProdutoEmbalagemS->count() > 0) {
            return 'Produto sendo utilizada em "ProdutoEmbalagem"!';
        }
        
        if ($this->model->ProdutoHistoricoPrecoS->count() > 0) {
            return 'Produto sendo utilizada em "ProdutoHistoricoPreco"!';
        }
        
        return false;
    }
    
    public function listing($filters = [], $sort = [], $start = null, $length = null) {
        
        // Query da Entidade
        $qry = Produto::query();
        
        // Filtros
         if (!empty($filters['codproduto'])) {
            $qry->where('codproduto', '=', $filters['codproduto']);
        }

         if (!empty($filters['produto'])) {
            $qry->palavras('produto', $filters['produto']);
        }

         if (!empty($filters['referencia'])) {
            $qry->palavras('referencia', $filters['referencia']);
        }

         if (!empty($filters['codunidademedida'])) {
            $qry->where('codunidademedida', '=', $filters['codunidademedida']);
        }

         if (!empty($filters['codsubgrupoproduto'])) {
            $qry->where('codsubgrupoproduto', '=', $filters['codsubgrupoproduto']);
        }

         if (!empty($filters['codmarca'])) {
            $qry->where('codmarca', '=', $filters['codmarca']);
        }

         if (!empty($filters['preco'])) {
            $qry->where('preco', '=', $filters['preco']);
        }

         if (!empty($filters['importado'])) {
            $qry->where('importado', '=', $filters['importado']);
        }

         if (!empty($filters['codtributacao'])) {
            $qry->where('codtributacao', '=', $filters['codtributacao']);
        }

          if (!empty($filters['codtipoproduto'])) {
            $qry->where('codtipoproduto', '=', $filters['codtipoproduto']);
        }

         if (!empty($filters['site'])) {
            $qry->where('site', '=', $filters['site']);
        }

         if (!empty($filters['descricaosite'])) {
            $qry->palavras('descricaosite', $filters['descricaosite']);
        }

         if (!empty($filters['alteracao'])) {
            $qry->where('alteracao', '=', $filters['alteracao']);
        }

         if (!empty($filters['codusuarioalteracao'])) {
            $qry->where('codusuarioalteracao', '=', $filters['codusuarioalteracao']);
        }

         if (!empty($filters['criacao'])) {
            $qry->where('criacao', '=', $filters['criacao']);
        }

         if (!empty($filters['codusuariocriacao'])) {
            $qry->where('codusuariocriacao', '=', $filters['codusuariocriacao']);
        }

         if (!empty($filters['codncm'])) {
            $qry->where('codncm', '=', $filters['codncm']);
        }

         if (!empty($filters['codcest'])) {
            $qry->where('codcest', '=', $filters['codcest']);
        }

         if (!empty($filters['observacoes'])) {
            $qry->palavras('observacoes', $filters['observacoes']);
        }

         if (!empty($filters['codopencart'])) {
            $qry->where('codopencart', '=', $filters['codopencart']);
        }

         if (!empty($filters['codopencartvariacao'])) {
            $qry->where('codopencartvariacao', '=', $filters['codopencartvariacao']);
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
            , 'recordsTotal' => Produto::count()
            , 'data' => $qry->get()
        ];
        
    }
    
    public function getArraySaldoEstoque()
    {

        // Array de Retorno
        $arrRet = [
            'local' => [],
            //'total' => [],
        ];
        
        // Array com Totais
        $arrTotal = [
            'estoqueminimo' => null,
            'estoquemaximo' => null,
            'fisico' => [
                'saldoquantidade' => null,
                'saldovalor' => null,
                'customedio' => null,
                'ultimaconferencia' => null,
            ],
            'fiscal' => [
                'saldoquantidade' => null,
                'saldovalor' => null,
                'customedio' => null,
                'ultimaconferencia' => null,
            ],
            'variacao' => [],
        ];
        
        // Array com Totais Por Variacao
        $pvs = $this->model->ProdutoVariacaoS()->orderBy(DB::raw("coalesce(variacao, '')"), 'ASC')->get();
        foreach ($pvs as $pv) {
            $arrTotalVar[$pv->codprodutovariacao] = [
                'codprodutovariacao' => $pv->codprodutovariacao,
                'variacao' => $pv->variacao,
                'estoqueminimo' => null,
                'estoquemaximo' => null,
                'corredor' => null,
                'prateleira' => null,
                'coluna' => null,
                'bloco' => null,
                'fisico' => [
                    'saldoquantidade' => null,
                    'saldovalor' => null,
                    'customedio' => null,
                    'ultimaconferencia' => null,
                ],
                'fiscal' => [
                    'saldoquantidade' => null,
                    'saldovalor' => null,
                    'customedio' => null,
                    'ultimaconferencia' => null,
                ],
            ];
        }
        
        // Percorrre todos os Locais
        foreach (EstoqueLocal::ativo()->orderBy('codestoquelocal', 'asc')->get() as $el) {
            
            // Array com Totais por Local
            $arrLocal = [
                'codestoquelocal' => $el->codestoquelocal,
                'estoquelocal' => $el->estoquelocal,
                'estoqueminimo' => null,
                'estoquemaximo' => null,
                'fisico' => [
                    'saldoquantidade' => null,
                    'saldovalor' => null,
                    'customedio' => null,
                    'ultimaconferencia' => null,
                ],
                'fiscal' => [
                    'saldoquantidade' => null,
                    'saldovalor' => null,
                    'customedio' => null,
                    'ultimaconferencia' => null,
                ],
                'variacao' => [],
            ];
            
            
            foreach ($pvs as $pv) {
                
                // Array com Saldo de Cada EstoqueLocalProdutoVariacao
                $arrVar = [
                    'codprodutovariacao' => $pv->codprodutovariacao,
                    'variacao' => $pv->variacao,
                    'codestoquelocalprodutovariacao' => null,
                    'estoqueminimo' => null,
                    'estoquemaximo' => null,
                    'corredor' => null,
                    'prateleira' => null,
                    'coluna' => null,
                    'bloco' => null,
                    'fisico' => [
                        'codestoquesaldo' => null,
                        'saldoquantidade' => null,
                        'saldovalor' => null,
                        'customedio' => null,
                        'ultimaconferencia' => null,
                    ],
                    'fiscal' => [
                        'codestoquesaldo' => null,
                        'saldoquantidade' => null,
                        'saldovalor' => null,
                        'customedio' => null,
                        'ultimaconferencia' => null,
                    ],
                ];
                
                //Se já existe a combinação de Variacao para o Local
                if ($elpv = $pv->EstoqueLocalProdutoVariacaoS()->where('codestoquelocal', $el->codestoquelocal)->first()) {
                    
                    $arrVar['codestoquelocalprodutovariacao'] = $elpv->codestoquelocalprodutovariacao;

                    //Acumula Estoque Mínimo
                    $arrVar['estoqueminimo'] = $elpv->estoqueminimo;
                    if (!empty($elpv->estoqueminimo)) {
                        $arrLocal['estoqueminimo'] += $elpv->estoqueminimo;
                        $arrTotal['estoqueminimo'] += $elpv->estoqueminimo;
                        $arrTotalVar[$pv->codprodutovariacao]['estoqueminimo'] += $elpv->estoqueminimo;
                    }
                    
                    //Acumula Estoque Máximo
                    $arrVar['estoquemaximo'] = $elpv->estoquemaximo;
                    if (!empty($elpv->estoquemaximo)) {
                        $arrLocal['estoquemaximo'] += $elpv->estoquemaximo;
                        $arrTotal['estoquemaximo'] += $elpv->estoquemaximo;
                        $arrTotalVar[$pv->codprodutovariacao]['estoquemaximo'] += $elpv->estoquemaximo;
                    }

                    $arrVar['corredor'] = $elpv->corredor;
                    if (!empty($elpv->corredor)) {
                        $arrLocal['corredor'] = $elpv->corredor;
                    }

                    $arrVar['prateleira'] = $elpv->prateleira;
                    if (!empty($elpv->prateleira)) {
                        $arrLocal['prateleira'] = $elpv->prateleira;
                    }

                    $arrVar['coluna'] = $elpv->coluna;
                    if (!empty($elpv->coluna)) {
                        $arrLocal['coluna'] = $elpv->coluna;
                    }

                    $arrVar['bloco'] = $elpv->bloco;
                    if (!empty($elpv->bloco)) {
                        $arrLocal['bloco'] = $elpv->bloco;
                    }
                    
                    //Percorre os Saldos Físico e Fiscal
                    foreach($elpv->EstoqueSaldoS as $es) {
                        
                        $tipo = ($es->fiscal == true)?'fiscal':'fisico';
                        
                        $arrVar[$tipo]["codestoquesaldo"] = $es->codestoquesaldo;

                        //Acumula as quantidades de Saldo
                        $arrVar[$tipo]["saldoquantidade"] = $es->saldoquantidade;
                        $arrLocal[$tipo]["saldoquantidade"] += $es->saldoquantidade;
                        $arrTotal[$tipo]["saldoquantidade"] += $es->saldoquantidade;
                        $arrTotalVar[$pv->codprodutovariacao][$tipo]["saldoquantidade"] += $es->saldoquantidade;
                        
                        //Acumula os valores de Saldo
                        $arrVar[$tipo]["saldovalor"] = $es->saldovalor;
                        $arrLocal[$tipo]["saldovalor"] += $es->saldovalor;
                        $arrTotal[$tipo]["saldovalor"] += $es->saldovalor;
                        $arrTotalVar[$pv->codprodutovariacao][$tipo]["saldovalor"] += $es->saldovalor;
                        
                        $arrVar[$tipo]["customedio"] = $es->customedio;
                        
                        $arrVar[$tipo]["ultimaconferencia"] = $es->ultimaconferencia;
                        
                        //Pega a data de conferência mais antiga para o total do Local
                        if (empty($arrLocal[$tipo]["ultimaconferencia"])) {
                            $arrLocal[$tipo]["ultimaconferencia"] = $es->ultimaconferencia;
                        } elseif (!empty($es->ultimaconferencia) && $es->ultimaconferencia < $arrLocal[$tipo]["ultimaconferencia"]) {
                            $arrLocal[$tipo]["ultimaconferencia"] = $es->ultimaconferencia;
                        }
                        
                        //Pega a data de conferência mais antiga para o total da variacao
                        if (empty($arrTotalVar[$pv->codprodutovariacao][$tipo]["ultimaconferencia"])) {
                            $arrTotalVar[$pv->codprodutovariacao][$tipo]["ultimaconferencia"] = $es->ultimaconferencia;
                        } elseif (!empty($es->ultimaconferencia) && $es->ultimaconferencia < $arrTotalVar[$pv->codprodutovariacao][$tipo]["ultimaconferencia"]) {
                            $arrTotalVar[$pv->codprodutovariacao][$tipo]["ultimaconferencia"] = $es->ultimaconferencia;                            
                        }
                        
                        //Pega a data de conferência mais antiga para o total geral
                        if (empty($arrTotal[$tipo]["ultimaconferencia"])) {
                            $arrTotal[$tipo]["ultimaconferencia"] = $es->ultimaconferencia;
                        } elseif (!empty($es->ultimaconferencia) && $es->ultimaconferencia < $arrTotal[$tipo]["ultimaconferencia"]) {
                            $arrTotal[$tipo]["ultimaconferencia"] = $es->ultimaconferencia;                            
                        }
                        
                    }

                }
                
                // Adiciona variacao ao array de locais
                $arrLocal['variacao'][$pv->codprodutovariacao] = $arrVar;
                
            }
            
            // Calcula o custo médio do Local
            if ($arrLocal['fisico']['saldoquantidade'] > 0)
                $arrLocal['fisico']['customedio'] = $arrLocal['fisico']['saldovalor'] / $arrLocal['fisico']['saldoquantidade'];
            if ($arrLocal['fiscal']['saldoquantidade'] > 0)
                $arrLocal['fiscal']['customedio'] = $arrLocal['fiscal']['saldovalor'] / $arrLocal['fiscal']['saldoquantidade'];
            
            // Adiciona local no array de retorno
            $arrRet['local'][$el->codestoquelocal] = $arrLocal;
            
        }
        
        // Calcula o custo médio dos totais de cada variacao
        foreach($arrTotalVar as $codvariacao => $arr) {
            if ($arrTotalVar[$codvariacao]['fisico']['saldoquantidade'] > 0)
                $arrTotalVar[$codvariacao]['fisico']['customedio'] = $arrTotalVar[$codvariacao]['fisico']['saldovalor'] / $arrTotalVar[$codvariacao]['fisico']['saldoquantidade'];
            if ($arrTotalVar[$codvariacao]['fiscal']['saldoquantidade'] > 0)
                $arrTotalVar[$codvariacao]['fiscal']['customedio'] = $arrTotalVar[$codvariacao]['fiscal']['saldovalor'] / $arrTotalVar[$codvariacao]['fiscal']['saldoquantidade'];
        }
        
        // Adiciona totais das variações ao array de totais
        $arrTotal['variacao'] = $arrTotalVar;

        // calcula o custo médio do total
        if ($arrTotal['fisico']['saldoquantidade'] > 0)
            $arrTotal['fisico']['customedio'] = $arrTotal['fisico']['saldovalor'] / $arrTotal['fisico']['saldoquantidade'];
        if ($arrTotal['fiscal']['saldoquantidade'] > 0)
            $arrTotal['fiscal']['customedio'] = $arrTotal['fiscal']['saldovalor'] / $arrTotal['fiscal']['saldoquantidade'];
        
        // Adiciona totais no array de retorno
        $arrRet['local']['total'] = $arrTotal;
        //$arrRet['total'] = $arrTotal;

        /*
        echo json_encode($arrRet);
        die();
        */
        
        //retorna
        return $arrRet;
    }
    
}
