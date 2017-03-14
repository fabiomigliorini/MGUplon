<?php

namespace MGLara\Repositories;
    
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

use MGLara\Models\EstoqueSaldoConferencia;

/**
 * Description of EstoqueSaldoConferenciaRepository
 * 
 * @property  Validator $validator
 * @property  EstoqueSaldoConferencia $model
 */
class EstoqueSaldoConferenciaRepository extends MGRepository {
    
    public function boot() {
        $this->model = new EstoqueSaldoConferencia();
    }
    
    //put your code here
    public function validate($data = null, $id = null) {
        
        if (empty($data)) {
            $data = $this->modell->getAttributes();
        }
        
        if (empty($id)) {
            $id = $this->model->codestoquesaldoconferencia;
        }
        
        $this->validator = Validator::make($data, [
            'codestoquesaldo' => [
                'numeric',
                'required',
            ],
            'quantidadesistema' => [
                'digits',
                'numeric',
                'nullable',
            ],
            'quantidadeinformada' => [
                'digits',
                'numeric',
                'required',
            ],
            'customediosistema' => [
                'digits',
                'numeric',
                'nullable',
            ],
            'customedioinformado' => [
                'digits',
                'numeric',
                'required',
            ],
            'data' => [
                'date',
                'required',
            ],
            'observacoes' => [
                'max:200',
                'nullable',
            ],
        ], [
            'codestoquesaldo.numeric' => 'O campo "codestoquesaldo" deve ser um número!',
            'codestoquesaldo.required' => 'O campo "codestoquesaldo" deve ser preenchido!',
            'quantidadesistema.digits' => 'O campo "quantidadesistema" deve conter no máximo 3 dígitos!',
            'quantidadesistema.numeric' => 'O campo "quantidadesistema" deve ser um número!',
            'quantidadeinformada.digits' => 'O campo "quantidadeinformada" deve conter no máximo 3 dígitos!',
            'quantidadeinformada.numeric' => 'O campo "quantidadeinformada" deve ser um número!',
            'quantidadeinformada.required' => 'O campo "quantidadeinformada" deve ser preenchido!',
            'customediosistema.digits' => 'O campo "customediosistema" deve conter no máximo 6 dígitos!',
            'customediosistema.numeric' => 'O campo "customediosistema" deve ser um número!',
            'customedioinformado.digits' => 'O campo "customedioinformado" deve conter no máximo 6 dígitos!',
            'customedioinformado.numeric' => 'O campo "customedioinformado" deve ser um número!',
            'customedioinformado.required' => 'O campo "customedioinformado" deve ser preenchido!',
            'data.date' => 'O campo "data" deve ser uma data!',
            'data.required' => 'O campo "data" deve ser preenchido!',
            'observacoes.max' => 'O campo "observacoes" não pode conter mais que 200 caracteres!',
        ]);

        return $this->validator->passes();
        
    }
    
    public function used($id = null) {
        if (!empty($id)) {
            $this->findOrFail($id);
        }
        
        if ($this->model->EstoqueMovimentoS->count() > 0) {
            return 'Estoque Saldo Conferencia sendo utilizada em "EstoqueMovimento"!';
        }
        
        return false;
    }
    
    public function listing($filters = [], $sort = [], $start = null, $length = null) {
        
        // Query da Entidade
        $qry = EstoqueSaldoConferencia::query();
        
        $qry->select([
            'tblestoquesaldoconferencia.codestoquesaldoconferencia',
            'tblestoquesaldoconferencia.inativo',
            'tblproduto.produto',
            'tblprodutovariacao.variacao',
            'tblestoquelocal.estoquelocal',
            'tblestoquesaldoconferencia.quantidadeinformada',
            'tblestoquesaldoconferencia.customedioinformado',
            'tblestoquesaldoconferencia.data',
            'tblusuario.usuario',
            'tblestoquesaldoconferencia.observacoes',
        ]);
        
        $qry->join('tblestoquesaldo', 'tblestoquesaldo.codestoquesaldo', '=', 'tblestoquesaldoconferencia.codestoquesaldo');
        $qry->join('tblestoquelocalprodutovariacao', 'tblestoquelocalprodutovariacao.codestoquelocalprodutovariacao', '=', 'tblestoquesaldo.codestoquelocalprodutovariacao');
        $qry->join('tblestoquelocal', 'tblestoquelocal.codestoquelocal', '=', 'tblestoquelocalprodutovariacao.codestoquelocal');
        $qry->join('tblprodutovariacao', 'tblprodutovariacao.codprodutovariacao', '=', 'tblestoquelocalprodutovariacao.codprodutovariacao');
        $qry->join('tblproduto', 'tblproduto.codproduto', '=', 'tblprodutovariacao.codproduto');
        $qry->leftJoin('tblusuario', 'tblusuario.codusuario', '=', 'tblestoquesaldoconferencia.codusuariocriacao');
        
        // Filtros
        if (!empty($filters['codestoquesaldoconferencia'])) {
            $qry->where('codestoquesaldoconferencia', '=', $filters['codestoquesaldoconferencia']);
        }
        
        if (!empty($filters['codproduto'])) {
            $qry->where('tblprodutovariacao.codproduto', '=', $filters['codproduto']);
        }
        
        if (!empty($filters['codestoquelocal'])) {
            $qry->where('tblestoquelocalprodutovariacao.codestoquelocal', '=', $filters['codestoquelocal']);
        }

        if (!empty($filters['codusuariocriacao'])) {
            $qry->where('tblestoquesaldoconferencia.codusuariocriacao', '=', $filters['codusuariocriacao']);
        }

        if (!empty($filters['data_de'])) {
            $qry->where('tblestoquesaldoconferencia.data', '>=', $filters['data_de']);
        }

        if (!empty($filters['data_ate'])) {
            $qry->where('tblestoquesaldoconferencia.data', '<=', $filters['data_ate']);
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
            switch ($s['column']) {
                case 'tblprodutovariacao.variacao':
                    if ($s['dir'] == 'asc') {
                        $qry->orderByRaw('tblprodutovariacao.variacao asc nulls first');
                    } else {
                        $qry->orderByRaw('tblprodutovariacao.variacao desc nulls last');
                    }
                    break;

                case 'tblproduto.produto':
                    $qry->orderBy($s['column'], $s['dir']);
                    if ($s['dir'] == 'asc') {
                        $qry->orderByRaw('tblprodutovariacao.variacao asc nulls first');
                    } else {
                        $qry->orderByRaw('tblprodutovariacao.variacao desc nulls last');
                    }
                    break;
                    
                default:
                    $qry->orderBy($s['column'], $s['dir']);
                    break;
            }
        }
        
        // Registros
        return [
            'recordsFiltered' => $count
            , 'recordsTotal' => EstoqueSaldoConferencia::count()
            , 'data' => $qry->get()
        ];
        
    }
    
}
