<?php

namespace MGLara\Repositories;
    
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use MGLara\Library\Cmc7\Cmc7;
use MGLara\Models\Cheque;
use MGLara\Repositories\BancoRepository;

/**
 * Description of ChequeRepository
 * 
 * @property  Validator $validator
 * @property  Cheque $model
 */
class ChequeRepository extends MGRepository {
    
    public function boot() {
        $this->model = new Cheque();
        $this->bancoRepository = New BancoRepository();
    }
    
    //put your code here
    public function validate($data = null, $id = null) {
        
        if (empty($data)) {
            $data = $this->modell->getAttributes();
        }
        
        if (empty($id)) {
            $id = $this->model->codcheque;
        }
        
        $this->validator = Validator::make($data, [
            'cmc7' => [
                'max:50',
                'nullable',
            ],
            'codbanco' => [
                'numeric',
                'required',
            ],
            'agencia' => [
                'max:10',
                'required',
            ],
            'contacorrente' => [
                'max:15',
                'required',
            ],
            'emitente' => [
                'max:100',
                'nullable',
            ],
            'numero' => [
                'max:15',
                'required',
            ],
            'emissao' => [
                'date',
                'required',
            ],
            'vencimento' => [
                'date',
                'required',
            ],
            'repasse' => [
                'date',
                'nullable',
            ],
            'destino' => [
                'max:50',
                'nullable',
            ],
            'devolucao' => [
                'date',
                'nullable',
            ],
            'motivodevolucao' => [
                'max:50',
                'nullable',
            ],
            'observacao' => [
                'max:200',
                'nullable',
            ],
            'lancamento' => [
                'date',
                'nullable',
            ],
            'cancelamento' => [
                'date',
                'nullable',
            ],
            'valor' => [
                'digits',
                'numeric',
                'required',
            ],
            'codpessoa' => [
                'numeric',
                'required',
            ],
            'indstatus' => [
                'numeric',
                'required',
            ],
            'codtitulo' => [
                'numeric',
                'nullable',
            ],
        ], [
            'cmc7.max' => 'O campo "cmc7" não pode conter mais que 50 caracteres!',
            'codbanco.numeric' => 'O campo "codbanco" deve ser um número!',
            'codbanco.required' => 'O campo "codbanco" deve ser preenchido!',
            'agencia.max' => 'O campo "agencia" não pode conter mais que 10 caracteres!',
            'agencia.required' => 'O campo "agencia" deve ser preenchido!',
            'contacorrente.max' => 'O campo "contacorrente" não pode conter mais que 15 caracteres!',
            'contacorrente.required' => 'O campo "contacorrente" deve ser preenchido!',
            'emitente.max' => 'O campo "emitente" não pode conter mais que 100 caracteres!',
            'numero.max' => 'O campo "numero" não pode conter mais que 15 caracteres!',
            'numero.required' => 'O campo "numero" deve ser preenchido!',
            'emissao.date' => 'O campo "emissao" deve ser uma data!',
            'emissao.required' => 'O campo "emissao" deve ser preenchido!',
            'vencimento.date' => 'O campo "vencimento" deve ser uma data!',
            'vencimento.required' => 'O campo "vencimento" deve ser preenchido!',
            'repasse.date' => 'O campo "repasse" deve ser uma data!',
            'destino.max' => 'O campo "destino" não pode conter mais que 50 caracteres!',
            'devolucao.date' => 'O campo "devolucao" deve ser uma data!',
            'motivodevolucao.max' => 'O campo "motivodevolucao" não pode conter mais que 50 caracteres!',
            'observacao.max' => 'O campo "observacao" não pode conter mais que 200 caracteres!',
            'lancamento.date' => 'O campo "lancamento" deve ser uma data!',
            'cancelamento.date' => 'O campo "cancelamento" deve ser uma data!',
            'valor.digits' => 'O campo "valor" deve conter no máximo 2 dígitos!',
            'valor.numeric' => 'O campo "valor" deve ser um número!',
            'valor.required' => 'O campo "valor" deve ser preenchido!',
            'codpessoa.numeric' => 'O campo "codpessoa" deve ser um número!',
            'codpessoa.required' => 'O campo "codpessoa" deve ser preenchido!',
            'indstatus.numeric' => 'O campo "indstatus" deve ser um número!',
            'indstatus.required' => 'O campo "indstatus" deve ser preenchido!',
            'codtitulo.numeric' => 'O campo "codtitulo" deve ser um número!',
        ]);

        return $this->validator->passes();
        
    }
    
    public function used($id = null) {
        if (!empty($id)) {
            $this->findOrFail($id);
        }
        
        if ($this->model->ChequeRepasseChequeS->count() > 0) {
            return 'Cheque sendo utilizada em "ChequeRepasseCheque"!';
        }
        
        if ($this->model->ChequeEmitenteS->count() > 0) {
            return 'Cheque sendo utilizada em "ChequeEmitente"!';
        }
        
        if ($this->model->CobrancaS->count() > 0) {
            return 'Cheque sendo utilizada em "Cobranca"!';
        }
        
        return false;
    }
    
    public function listing($filters = [], $sort = [], $start = null, $length = null) {
        
        // Query da Entidade
        $qry = Cheque::query();
        
        // Filtros
         if (!empty($filters['codcheque'])) {
            $qry->where('codcheque', '=', $filters['codcheque']);
        }

         if (!empty($filters['cmc7'])) {
            $qry->palavras('cmc7', $filters['cmc7']);
        }

         if (!empty($filters['codbanco'])) {
            $qry->where('codbanco', '=', $filters['codbanco']);
        }

         if (!empty($filters['agencia'])) {
            $qry->palavras('agencia', $filters['agencia']);
        }

         if (!empty($filters['contacorrente'])) {
            $qry->palavras('contacorrente', $filters['contacorrente']);
        }

         if (!empty($filters['emitente'])) {
            $qry->palavras('emitente', $filters['emitente']);
        }

         if (!empty($filters['numero'])) {
            $qry->palavras('numero', $filters['numero']);
        }

         if (!empty($filters['emissao'])) {
            $qry->where('emissao', '=', $filters['emissao']);
        }

         if (!empty($filters['vencimento'])) {
            $qry->where('vencimento', '=', $filters['vencimento']);
        }

         if (!empty($filters['repasse'])) {
            $qry->where('repasse', '=', $filters['repasse']);
        }

         if (!empty($filters['destino'])) {
            $qry->palavras('destino', $filters['destino']);
        }

         if (!empty($filters['devolucao'])) {
            $qry->where('devolucao', '=', $filters['devolucao']);
        }

         if (!empty($filters['motivodevolucao'])) {
            $qry->palavras('motivodevolucao', $filters['motivodevolucao']);
        }

         if (!empty($filters['observacao'])) {
            $qry->palavras('observacao', $filters['observacao']);
        }

         if (!empty($filters['lancamento'])) {
            $qry->where('lancamento', '=', $filters['lancamento']);
        }

         if (!empty($filters['alteracao'])) {
            $qry->where('alteracao', '=', $filters['alteracao']);
        }

         if (!empty($filters['cancelamento'])) {
            $qry->where('cancelamento', '=', $filters['cancelamento']);
        }

         if (!empty($filters['valor'])) {
            $qry->where('valor', '=', $filters['valor']);
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

         if (!empty($filters['codpessoa'])) {
            $qry->where('codpessoa', '=', $filters['codpessoa']);
        }

         if (!empty($filters['indstatus'])) {
            $qry->where('indstatus', '=', $filters['indstatus']);
        }

         if (!empty($filters['codtitulo'])) {
            $qry->where('codtitulo', '=', $filters['codtitulo']);
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
        return $qry->get();
        
    }
    
    public function parseCmc7(){
         $cmc7n = new Cmc7($this->cmc7);
         $this->codbanco = $this->bancoRepository->model->where('numerobanco', '=', $cmc7n->banco())->first()->codbanco;
         $this->agencia = $cmc7n->agencia();
         $this->contacorrente = $cmc7n->contacorrente();
         $this->numero = $cmc7n->numero();
    }
    
    public function findUltimoMesmoEmitente($banco, $agencia, $contacorrente){

        $query = Cheque::query();
        $query->where('codbanco', $banco);
        $query->where('agencia', $agencia);
        $query->where('contacorrente', $contacorrente);
        $query->orderBy('criacao', 'DESC');

        return $query->first();

    }
}
