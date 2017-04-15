<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codpranchetaprodutobarra           NOT NULL DEFAULT nextval('tblpranchetaprodutobarra_codpranchetaprodutobarra_seq'::regclass)
 * @property  varchar(200)                   $observacoes                        
 * @property  timestamp                      $criacao                            
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuariocriacao                  
 * @property  bigint                         $codusuarioalteracao                
 * @property  bigint                         $codprancheta                       NOT NULL
 * @property  bigint                         $codprodutobarra                    NOT NULL
 *
 * Chaves Estrangeiras
 * @property  Usuario                        $UsuarioCriacao
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Prancheta                      $Prancheta
 * @property  ProdutoBarra                   $ProdutoBarra
 *
 * Tabelas Filhas
 */

class PranchetaProdutoBarra extends MGModel
{
    protected $table = 'tblpranchetaprodutobarra';
    protected $primaryKey = 'codpranchetaprodutobarra';
    protected $fillable = [
          'observacoes',
             'codprancheta',
         'codprodutobarra',
    ];
    protected $dates = [
        'criacao',
        'alteracao',
    ];


    // Chaves Estrangeiras
    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }

    public function Prancheta()
    {
        return $this->belongsTo(Prancheta::class, 'codprancheta', 'codprancheta');
    }

    public function ProdutoBarra()
    {
        return $this->belongsTo(ProdutoBarra::class, 'codprodutobarra', 'codprodutobarra');
    }


    // Tabelas Filhas

}
