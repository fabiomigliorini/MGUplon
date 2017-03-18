<?php

namespace MGLara\Repositories;
    
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

use MGLara\Models\EstoqueMovimento;
use MGLara\Models\EstoqueSaldoConferencia;
use MGLara\Models\EstoqueMovimentoTipo;

/**
 * Description of EstoqueMovimentoRepository
 * 
 * @property  Validator $validator
 * @property  EstoqueMovimento $model
 */
class EstoqueMovimentoRepository extends MGRepository {
    
    public function boot() {
        $this->model = new EstoqueMovimento();
    }
    
    //put your code here
    public function validate($data = null, $id = null) {
        
        if (empty($data)) {
            $data = $this->modell->getAttributes();
        }
        
        if (empty($id)) {
            $id = $this->model->codestoquemovimento;
        }
        
        $this->validator = Validator::make($data, [
            'codestoquemovimentotipo' => [
                'numeric',
                'required',
            ],
            'entradaquantidade' => [
                'digits',
                'numeric',
                'nullable',
            ],
            'entradavalor' => [
                'digits',
                'numeric',
                'nullable',
            ],
            'saidaquantidade' => [
                'digits',
                'numeric',
                'nullable',
            ],
            'saidavalor' => [
                'digits',
                'numeric',
                'nullable',
            ],
            'codnegocioprodutobarra' => [
                'numeric',
                'nullable',
            ],
            'codnotafiscalprodutobarra' => [
                'numeric',
                'nullable',
            ],
            'codestoquemes' => [
                'numeric',
                'required',
            ],
            'manual' => [
                'boolean',
                'required',
            ],
            'data' => [
                'date',
                'required',
            ],
            'codestoquemovimentoorigem' => [
                'numeric',
                'nullable',
            ],
            'observacoes' => [
                'max:200',
                'nullable',
            ],
            'codestoquesaldoconferencia' => [
                'numeric',
                'nullable',
            ],
        ], [
            'codestoquemovimentotipo.numeric' => 'O campo "codestoquemovimentotipo" deve ser um número!',
            'codestoquemovimentotipo.required' => 'O campo "codestoquemovimentotipo" deve ser preenchido!',
            'entradaquantidade.digits' => 'O campo "entradaquantidade" deve conter no máximo 3 dígitos!',
            'entradaquantidade.numeric' => 'O campo "entradaquantidade" deve ser um número!',
            'entradavalor.digits' => 'O campo "entradavalor" deve conter no máximo 2 dígitos!',
            'entradavalor.numeric' => 'O campo "entradavalor" deve ser um número!',
            'saidaquantidade.digits' => 'O campo "saidaquantidade" deve conter no máximo 3 dígitos!',
            'saidaquantidade.numeric' => 'O campo "saidaquantidade" deve ser um número!',
            'saidavalor.digits' => 'O campo "saidavalor" deve conter no máximo 2 dígitos!',
            'saidavalor.numeric' => 'O campo "saidavalor" deve ser um número!',
            'codnegocioprodutobarra.numeric' => 'O campo "codnegocioprodutobarra" deve ser um número!',
            'codnotafiscalprodutobarra.numeric' => 'O campo "codnotafiscalprodutobarra" deve ser um número!',
            'codestoquemes.numeric' => 'O campo "codestoquemes" deve ser um número!',
            'codestoquemes.required' => 'O campo "codestoquemes" deve ser preenchido!',
            'manual.boolean' => 'O campo "manual" deve ser um verdadeiro/falso (booleano)!',
            'manual.required' => 'O campo "manual" deve ser preenchido!',
            'data.date' => 'O campo "data" deve ser uma data!',
            'data.required' => 'O campo "data" deve ser preenchido!',
            'codestoquemovimentoorigem.numeric' => 'O campo "codestoquemovimentoorigem" deve ser um número!',
            'observacoes.max' => 'O campo "observacoes" não pode conter mais que 200 caracteres!',
            'codestoquesaldoconferencia.numeric' => 'O campo "codestoquesaldoconferencia" deve ser um número!',
        ]);

        return $this->validator->passes();
        
    }
    
    public function used($id = null) {
        if (!empty($id)) {
            $this->findOrFail($id);
        }
        
        if ($this->model->EstoqueMovimentoS->count() > 0) {
            return 'Estoque Movimento sendo utilizada em "EstoqueMovimento"!';
        }
        
        return false;
    }
    
    public function listing($filters = [], $sort = [], $start = null, $length = null) {
        
        // Query da Entidade
        $qry = EstoqueMovimento::query();
        
        // Filtros
         if (!empty($filters['codestoquemovimento'])) {
            $qry->where('codestoquemovimento', '=', $filters['codestoquemovimento']);
        }

         if (!empty($filters['codestoquemovimentotipo'])) {
            $qry->where('codestoquemovimentotipo', '=', $filters['codestoquemovimentotipo']);
        }

         if (!empty($filters['entradaquantidade'])) {
            $qry->where('entradaquantidade', '=', $filters['entradaquantidade']);
        }

         if (!empty($filters['entradavalor'])) {
            $qry->where('entradavalor', '=', $filters['entradavalor']);
        }

         if (!empty($filters['saidaquantidade'])) {
            $qry->where('saidaquantidade', '=', $filters['saidaquantidade']);
        }

         if (!empty($filters['saidavalor'])) {
            $qry->where('saidavalor', '=', $filters['saidavalor']);
        }

         if (!empty($filters['codnegocioprodutobarra'])) {
            $qry->where('codnegocioprodutobarra', '=', $filters['codnegocioprodutobarra']);
        }

         if (!empty($filters['codnotafiscalprodutobarra'])) {
            $qry->where('codnotafiscalprodutobarra', '=', $filters['codnotafiscalprodutobarra']);
        }

         if (!empty($filters['codestoquemes'])) {
            $qry->where('codestoquemes', '=', $filters['codestoquemes']);
        }

         if (!empty($filters['manual'])) {
            $qry->where('manual', '=', $filters['manual']);
        }

         if (!empty($filters['data'])) {
            $qry->where('data', '=', $filters['data']);
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

         if (!empty($filters['codestoquemovimentoorigem'])) {
            $qry->where('codestoquemovimentoorigem', '=', $filters['codestoquemovimentoorigem']);
        }

         if (!empty($filters['observacoes'])) {
            $qry->palavras('observacoes', $filters['observacoes']);
        }

         if (!empty($filters['codestoquesaldoconferencia'])) {
            $qry->where('codestoquesaldoconferencia', '=', $filters['codestoquesaldoconferencia']);
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
            , 'recordsTotal' => EstoqueMovimento::count()
            , 'data' => $qry->get()
        ];
        
    }
    
    public static function movimentaEstoqueSaldoConferencia (EstoqueSaldoConferencia $conf) 
    {
        EstoqueSaldoRepository::atualizaUltimaConferencia($conf->EstoqueSaldo);

        $codestoquemovimentoGerado = [];
        
        $repo = new EstoqueMovimentoRepository();
        
        if (empty($conf->inativo)) {

            if ($mov = $conf->EstoqueMovimentoS->first()) {
                $repo->model = $mov;
            } else {
                $repo->new();
            }

            $mes = EstoqueMesRepository::buscaOuCria($conf->EstoqueSaldo, $conf->data);

            $repo->model->codestoquemes = $mes->codestoquemes;
            $repo->model->codestoquemovimentotipo = EstoqueMovimentoTipo::AJUSTE;
            $repo->model->manual = false;
            $repo->model->data = $conf->data;

            $quantidade = $conf->quantidadeinformada - $conf->quantidadesistema;
            $valor = $quantidade * $conf->customedioinformado;

            if ($quantidade >= 0) {
                $repo->model->entradaquantidade = $quantidade;
                $repo->model->saidaquantidade = null;
                $repo->model->entradavalor = $valor;
                $repo->model->saidavalor = null;
            } else {
                $repo->model->entradaquantidade = null;
                $repo->model->saidaquantidade = abs($quantidade);
                $repo->model->entradavalor = null;
                $repo->model->saidavalor = abs($valor);
            }

            $repo->model->codestoquesaldoconferencia = $conf->codestoquesaldoconferencia;

            $repo->save();

            //armazena estoquemovimento gerado
            $codestoquemovimentoGerado[] = $repo->model->codestoquemovimento;
        }

        //Apaga estoquemovimento excedente que existir anexado ao negocioprodutobarra
        $movExcedente = 
                EstoqueMovimento
                ::whereNotIn('codestoquemovimento', $codestoquemovimentoGerado)
                ->where('codestoquesaldoconferencia', $conf->codestoquesaldoconferencia)
                ->get();
        foreach ($movExcedente as $mov)
        {
            foreach ($mov->EstoqueMovimentoS as $movDest)
            {
                $movDest->codestoquemovimentoorigem = null;
                $movDest->save();
            }
            $repo->model = $mov;
            $repo->delete();
        }
        
        return true;
       
    }
    
    public function create($data = null) {
        $ret = parent::create($data);
        EstoqueMesRepository::calculaCustoMedio($this->model->EstoqueMes);
        return $ret;
    }
    
    public function update($id = null, $data = null) {
        $anterior = $this->model->fresh();
        $ret = parent::update($id, $data);
        EstoqueMesRepository::calculaCustoMedio($this->model->EstoqueMes);
        if ($anterior->codestoquemes != $this->model->codestoquemes) {
            EstoqueMesRepository::calculaCustoMedio($anterior->EstoqueMes);
        }
        return $ret;
    }

    public function delete($id = null) {
        $mes = $this->model->EstoqueMes;
        $ret = parent::delete($id);
        EstoqueMesRepository::calculaCustoMedio($mes);
        return $ret;
    }
    
    
    public function activate($id = null) {
        $ret = parent::activate($id);
        EstoqueMesRepository::calculaCustoMedio($this->model->EstoqueMes);
        return $ret;
    }
    
    public function inactivate($id = null) {
        $ret = parent::inactivate($id);
        EstoqueMesRepository::calculaCustoMedio($this->model->EstoqueMes);
        return $ret;
    }
    
    
}
