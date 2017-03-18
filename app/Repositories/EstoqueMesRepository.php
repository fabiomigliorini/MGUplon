<?php

namespace MGLara\Repositories;
    
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use Illuminate\Foundation\Bus\DispatchesJobs;

use MGLara\Models\EstoqueMes;
use MGLara\Models\EstoqueSaldo;
use MGLara\Models\EstoqueMovimentoTipo;

use MGLara\Jobs\EstoqueCalculaEstatisticas;
use MGLara\Jobs\EstoqueCalculaCustoMedio;


use MGLara\Repositories\EstoqueSaldoRepository;

/**
 * Description of EstoqueMesRepository
 * 
 * @property  Validator $validator
 * @property  EstoqueMes $model
 */
class EstoqueMesRepository extends MGRepository {
    
    use DispatchesJobs;
    
    public function boot() {
        $this->model = new EstoqueMes();
    }
    
    //put your code here
    public function validate($data = null, $id = null) {
        
        if (empty($data)) {
            $data = $this->modell->getAttributes();
        }
        
        if (empty($id)) {
            $id = $this->model->codestoquemes;
        }
        
        $this->validator = Validator::make($data, [
            'codestoquesaldo' => [
                'numeric',
                'required',
            ],
            'mes' => [
                'date',
                'required',
            ],
            'inicialquantidade' => [
                'digits',
                'numeric',
                'nullable',
            ],
            'inicialvalor' => [
                'digits',
                'numeric',
                'nullable',
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
            'saldoquantidade' => [
                'digits',
                'numeric',
                'nullable',
            ],
            'saldovalor' => [
                'digits',
                'numeric',
                'nullable',
            ],
            'customedio' => [
                'digits',
                'numeric',
                'nullable',
            ],
        ], [
            'codestoquesaldo.numeric' => 'O campo "codestoquesaldo" deve ser um número!',
            'codestoquesaldo.required' => 'O campo "codestoquesaldo" deve ser preenchido!',
            'mes.date' => 'O campo "mes" deve ser uma data!',
            'mes.required' => 'O campo "mes" deve ser preenchido!',
            'inicialquantidade.digits' => 'O campo "inicialquantidade" deve conter no máximo 3 dígitos!',
            'inicialquantidade.numeric' => 'O campo "inicialquantidade" deve ser um número!',
            'inicialvalor.digits' => 'O campo "inicialvalor" deve conter no máximo 2 dígitos!',
            'inicialvalor.numeric' => 'O campo "inicialvalor" deve ser um número!',
            'entradaquantidade.digits' => 'O campo "entradaquantidade" deve conter no máximo 3 dígitos!',
            'entradaquantidade.numeric' => 'O campo "entradaquantidade" deve ser um número!',
            'entradavalor.digits' => 'O campo "entradavalor" deve conter no máximo 2 dígitos!',
            'entradavalor.numeric' => 'O campo "entradavalor" deve ser um número!',
            'saidaquantidade.digits' => 'O campo "saidaquantidade" deve conter no máximo 3 dígitos!',
            'saidaquantidade.numeric' => 'O campo "saidaquantidade" deve ser um número!',
            'saidavalor.digits' => 'O campo "saidavalor" deve conter no máximo 2 dígitos!',
            'saidavalor.numeric' => 'O campo "saidavalor" deve ser um número!',
            'saldoquantidade.digits' => 'O campo "saldoquantidade" deve conter no máximo 3 dígitos!',
            'saldoquantidade.numeric' => 'O campo "saldoquantidade" deve ser um número!',
            'saldovalor.digits' => 'O campo "saldovalor" deve conter no máximo 2 dígitos!',
            'saldovalor.numeric' => 'O campo "saldovalor" deve ser um número!',
            'customedio.digits' => 'O campo "customedio" deve conter no máximo 6 dígitos!',
            'customedio.numeric' => 'O campo "customedio" deve ser um número!',
        ]);

        return $this->validator->passes();
        
    }
    
    public function used($id = null) {
        if (!empty($id)) {
            $this->findOrFail($id);
        }
        
        if ($this->model->EstoqueMovimentoS->count() > 0) {
            return 'Estoque Mes sendo utilizada em "EstoqueMovimento"!';
        }
        
        return false;
    }
    
    public function listing($filters = [], $sort = [], $start = null, $length = null) {
        
        // Query da Entidade
        $qry = EstoqueMes::query();
        
        // Filtros
         if (!empty($filters['codestoquemes'])) {
            $qry->where('codestoquemes', '=', $filters['codestoquemes']);
        }

         if (!empty($filters['codestoquesaldo'])) {
            $qry->where('codestoquesaldo', '=', $filters['codestoquesaldo']);
        }

         if (!empty($filters['mes'])) {
            $qry->where('mes', '=', $filters['mes']);
        }

         if (!empty($filters['inicialquantidade'])) {
            $qry->where('inicialquantidade', '=', $filters['inicialquantidade']);
        }

         if (!empty($filters['inicialvalor'])) {
            $qry->where('inicialvalor', '=', $filters['inicialvalor']);
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

         if (!empty($filters['saldoquantidade'])) {
            $qry->where('saldoquantidade', '=', $filters['saldoquantidade']);
        }

         if (!empty($filters['saldovalor'])) {
            $qry->where('saldovalor', '=', $filters['saldovalor']);
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

         if (!empty($filters['customedio'])) {
            $qry->where('customedio', '=', $filters['customedio']);
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
            , 'recordsTotal' => EstoqueMes::count()
            , 'data' => $qry->get()
        ];
        
    }
    
    
    public static function buscaProximos(EstoqueMes $model, $qtd = 7)
    {
        $ems = EstoqueMes::where('codestoquesaldo', $model->codestoquesaldo)
               ->where('mes', '>', $model->mes)
               ->orderBy('mes', 'asc')
               ->take($qtd)
               ->get();
        return $ems;
    }
    
    public static function buscaAnteriores(EstoqueMes $model, $qtd = 7)
    {
        $ems = EstoqueMes::where('codestoquesaldo', $model->codestoquesaldo)
               ->where('mes', '<', $model->mes)
               ->orderBy('mes', 'desc')
               ->take($qtd)
               ->get();
        return $ems->reverse();
    }
    
    public static function buscaOuCria(EstoqueSaldo $saldo, $data) 
    {
        
        $mes = Carbon::today();
        $mes->day = 1;
        $mes->month = $data->month;
        $mes->year = $data->year;
        
        // Se for fiscal cria somente um mês por ano, dezembro, até 2016
        if ($saldo->fiscal && $mes->year <= 2016) {
            $mes->month = 12;
        }
        
        $em = EstoqueMes::where('codestoquesaldo', $saldo->codestoquesaldo)->where('mes', $mes)->first();
        if ($em == false) {
            $em = new EstoqueMes;
            $em->codestoquesaldo = $saldo->codestoquesaldo;
            $em->mes = $mes;
            $em->save();
        }
        return $em;
        
    }
    
    public static function calculaCustoMedio(EstoqueMes $mes, $ciclo = 0) {

        Log::info('EstoqueMes::CalculaCustoMedio', ['codestoquemes' => $mes->codestoquemes, 'ciclo' => $ciclo]);
        
        if ($ciclo > 50) {
            Log::error('EstoqueMes::CalculaCustoMedio - Ciclo maior que 50', ['codestoquemes' => $mes->codestoquemes, 'ciclo' => $ciclo]);
            return;
        }
        
        $mes = $mes->fresh();
        
        // recalcula valor movimentacao com base no registro de origem
        $sql = "
            update tblestoquemovimento
            set entradavalor = orig.saidavalor / orig.saidaquantidade * tblestoquemovimento.entradaquantidade
                , saidavalor = orig.entradavalor / orig.entradaquantidade * tblestoquemovimento.saidaquantidade
            from tblestoquemovimento orig
            where tblestoquemovimento.codestoquemovimentoorigem = orig.codestoquemovimento
            and tblestoquemovimento.codestoquemes = {$mes->codestoquemes}
            ";
            
        $ret = DB::update($sql);
        
        
        //busca totais de registros nao baseados no custo medio
        $sql = "
            select 
                sum(entradaquantidade) as entradaquantidade
                , sum(entradavalor) as entradavalor
                , sum(saidaquantidade) as saidaquantidade
                , sum(saidavalor) as saidavalor
            from tblestoquemovimento mov
            left join tblestoquemovimentotipo tipo on (tipo.codestoquemovimentotipo = mov.codestoquemovimentotipo)
            where mov.codestoquemes = {$mes->codestoquemes}
            and tipo.preco in (" . EstoqueMovimentoTipo::PRECO_INFORMADO . ", " . EstoqueMovimentoTipo::PRECO_ORIGEM . ")";

        $mov = DB::select($sql);
        $mov = $mov[0];

        //busca saldo inicial
        $inicialquantidade = 0;
        $inicialvalor = 0;
        $anterior = self::buscaAnteriores($mes, 1);
        if (isset($anterior[0]))
        {
            $inicialquantidade = $anterior[0]->saldoquantidade;
            $inicialvalor = $anterior[0]->saldovalor;
        }

        //calcula custo medio
        $valor = $mov->entradavalor - $mov->saidavalor;
        $quantidade = $mov->entradaquantidade - $mov->saidaquantidade;
        if ($inicialquantidade > 0 && $inicialvalor > 0)
        {
            $valor += $inicialvalor;
            $quantidade += $inicialquantidade;
        }
        $customedio = 0;
        if ($quantidade != 0) {
            $customedio = abs($valor/$quantidade);
        }
        
        if (empty($customedio) && isset($anterior[0])) {
            $customedio = $anterior[0]->customedio;
        }

        if ($customedio > 100000) {
            return;
        }
        
        //recalcula valor movimentacao com base custo medio
        $sql = "
            update tblestoquemovimento
            set saidavalor = saidaquantidade * $customedio
                , entradavalor = entradaquantidade * $customedio
            where tblestoquemovimento.codestoquemes = {$mes->codestoquemes} 
            and tblestoquemovimento.codestoquemovimentotipo in 
                (select t.codestoquemovimentotipo from tblestoquemovimentotipo t where t.preco = " . EstoqueMovimentoTipo::PRECO_MEDIO . ")
            ";
            
        $ret = DB::update($sql);
        
        //busca totais movimentados do 
        $sql = "
            select 
                sum(entradaquantidade) entradaquantidade
                , sum(entradavalor) entradavalor
                , sum(saidaquantidade) saidaquantidade
                , sum(saidavalor) saidavalor
            from tblestoquemovimento mov
            left join tblestoquemovimentotipo tipo on (tipo.codestoquemovimentotipo = mov.codestoquemovimentotipo)
            where mov.codestoquemes = {$mes->codestoquemes}
            ";

        $mov = DB::select($sql);
        $mov = $mov[0];
        
        //calcula custo medio e totais novamente
        $mes->inicialquantidade = $inicialquantidade;
        //$mes->inicialvalor = $mes->inicialquantidade * $customedio;
        $mes->inicialvalor = $inicialvalor;
        $mes->entradaquantidade = $mov->entradaquantidade;
        $mes->entradavalor = $mov->entradavalor;
        $mes->saidaquantidade = $mov->saidaquantidade;
        $mes->saidavalor = $mov->saidavalor;
        $mes->saldoquantidade = $inicialquantidade + $mov->entradaquantidade - $mov->saidaquantidade;
        $mes->saldovalor = $mes->saldoquantidade * $customedio;
        $customedioanterior = $mes->customedio;
        $mes->customedio = $customedio;

        $mes->save();
        
        $customediodiferenca = abs($customedio - $customedioanterior);
        
        $mesesRecalcular = [];
        if ($customediodiferenca > 0.01)
        {
            $sql = "
                select distinct dest.codestoquemes
                from tblestoquemovimento orig
                inner join tblestoquemovimento dest on (dest.codestoquemovimentoorigem = orig.codestoquemovimento)
                where orig.codestoquemes = {$mes->codestoquemes}
                ";
            $ret = DB::select($sql);
            foreach ($ret as $row) {
                $mesesRecalcular[] = $row->codestoquemes;
            }
        }
        
        $proximo = self::buscaProximos($mes, 1);
        if (isset($proximo[0])) {
            $mesesRecalcular[] = $proximo[0]->codestoquemes;
        } else {
            $mes->EstoqueSaldo->saldoquantidade = $mes->saldoquantidade;
            $mes->EstoqueSaldo->saldovalor = $mes->saldovalor;
            $mes->EstoqueSaldo->customedio = $mes->customedio;
            $mes->EstoqueSaldo->save();
            
            //atualiza 'dataentrada'
            DB::update(DB::raw("
                update tblestoquesaldo
                set dataentrada = (
                        select 
                                x.data 
                        from (
                                select 
                                        mov.data
                                        , mov.entradaquantidade
                                        , sum(mov.entradaquantidade) over (order by mov.data desc) as soma
                                from tblestoquemes mes
                                inner join tblestoquemovimento mov on (mov.codestoquemes = mes.codestoquemes)
                                inner join tblestoquemovimentotipo tipo on (tipo.codestoquemovimentotipo = mov.codestoquemovimentotipo)
                                where mes.codestoquesaldo = tblestoquesaldo.codestoquesaldo
                                and mov.entradaquantidade is not null
                                and tipo.atualizaultimaentrada = true
                                ) x
                        where soma >= tblestoquesaldo.saldoquantidade
                        order by data DESC
                        limit 1
                )
                where tblestoquesaldo.codestoquesaldo = {$mes->codestoquesaldo}
            "));
        }
        
        $repo = new Self();
        if (!$mes->EstoqueSaldo->fiscal) {
            $repo->dispatch((new EstoqueCalculaEstatisticas($mes->EstoqueSaldo->EstoqueLocalProdutoVariacao->codprodutovariacao, $mes->EstoqueSaldo->EstoqueLocalProdutoVariacao->codestoquelocal))->onQueue('low'));
        }
        foreach ($mesesRecalcular as $mes) {
            $repo->dispatch((new EstoqueCalculaCustoMedio($mes, $ciclo +1))->onQueue('urgent'));
        }
        
    }
    
}
