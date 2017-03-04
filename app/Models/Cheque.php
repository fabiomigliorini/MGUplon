<?php

namespace MGLara\Models;
use MGLara\Models\Banco;
/**
 * Campos
 * @property  bigint                         $codcheque                          NOT NULL DEFAULT nextval('tblcheque_codcheque_seq'::regclass)
 * @property  varchar(50)                    $cmc7                               
 * @property  bigint                         $codbanco                           NOT NULL
 * @property  varchar(10)                    $agencia                            NOT NULL
 * @property  varchar(15)                    $contacorrente                      NOT NULL
 * @property  varchar(100)                   $emitente                           
 * @property  varchar(15)                    $numero                             NOT NULL
 * @property  date                           $emissao                            NOT NULL
 * @property  date                           $vencimento                         NOT NULL
 * @property  date                           $repasse                            
 * @property  varchar(50)                    $destino                            
 * @property  date                           $devolucao                          
 * @property  varchar(50)                    $motivodevolucao                    
 * @property  varchar(200)                   $observacao                         
 * @property  timestamp                      $lancamento                         
 * @property  timestamp                      $alteracao                          
 * @property  timestamp                      $cancelamento                       
 * @property  numeric(14,2)                  $valor                              NOT NULL
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  bigint                         $codpessoa                          NOT NULL
 * @property  smallint                       $indstatus                          NOT NULL DEFAULT 1
 * @property  bigint                         $codtitulo                          
 *
 * Chaves Estrangeiras
 * @property  Banco                          $Banco
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 * @property  Pessoa                         $Pessoa
 * @property  Titulo                         $Titulo
 *
 * Tabelas Filhas
 * @property  ChequeRepasseCheque[]          $ChequeRepasseChequeS
 * @property  ChequeEmitente[]               $ChequeEmitenteS
 * @property  Cobranca[]                     $CobrancaS
 */

class Cheque extends MGModel
{

    const INDSTATUS_AREPASSAR = 1;
    const INDSTATUS_REPASSADO = 2;
    const INDSTATUS_DEVOLVIDO = 3;
    const INDSTATUS_EMCOBRANCA = 4;
    const INDSTATUS_LIQUIDADO = 5;

    public static $indstatus_descricao = [
        self::INDSTATUS_AREPASSAR => 'À Repassar',
        self::INDSTATUS_REPASSADO => 'Repassado',
        self::INDSTATUS_DEVOLVIDO => 'Devolvido',
        self::INDSTATUS_EMCOBRANCA => 'Em Cobranca',
        self::INDSTATUS_LIQUIDADO => 'Liquidado'
    ];

    public static $indstatus_class = [
        self::INDSTATUS_AREPASSAR => 'label-primary',
        self::INDSTATUS_REPASSADO => 'label-warning',
        self::INDSTATUS_DEVOLVIDO => 'label-danger',
        self::INDSTATUS_EMCOBRANCA => 'label-danger',
        self::INDSTATUS_LIQUIDADO => 'label-success'
    ];

    protected $table = 'tblcheque';
    protected $primaryKey = 'codcheque';
    protected $fillable = [
          'cmc7',
         'codbanco',
         'agencia',
         'contacorrente',
         'emitente',
         'numero',
         'emissao',
         'vencimento',
         'observacao',
         'valor',
         'codpessoa',
         'indstatus'
    ];
    protected $dates = [
        'emissao',
        'vencimento',
        'repasse',
        'devolucao',
        'lancamento',
        'alteracao',
        'cancelamento',
        'criacao',
    ];


    // Chaves Estrangeiras
    public function Banco()
    {
        return $this->belongsTo(Banco::class, 'codbanco', 'codbanco');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
    }

    public function Pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'codpessoa', 'codpessoa');
    }

    public function Titulo()
    {
        return $this->belongsTo(Titulo::class, 'codtitulo', 'codtitulo');
    }


    // Tabelas Filhas
    public function ChequeRepasseChequeS()
    {
        return $this->hasMany(ChequeRepasseCheque::class, 'codcheque', 'codcheque');
    }

    public function ChequeEmitenteS()
    {
        return $this->hasMany(ChequeEmitente::class, 'codcheque', 'codcheque');
    }

    public function CobrancaS()
    {
        return $this->hasMany(Cobranca::class, 'codcheque', 'codcheque');
    }


}
