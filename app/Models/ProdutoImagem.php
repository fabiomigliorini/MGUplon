<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codprodutoimagem                   NOT NULL DEFAULT nextval('tblprodutoimagem_codprodutoimagem_seq'::regclass)
 * @property  bigint                         $codproduto                         NOT NULL
 * @property  bigint                         $codimagem                          NOT NULL
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 *
 * Chaves Estrangeiras
 * @property  Produto                        $Produto
 * @property  Imagem                         $Imagem
 *
 * Tabelas Filhas
 */

class ProdutoImagem extends MGModel
{
    protected $table = 'tblprodutoimagem';
    protected $primaryKey = 'codprodutoimagem';
    protected $fillable = [
          'codproduto',
         'codimagem',
        ];
    protected $dates = [
        'criacao',
        'alteracao',
    ];


    // Chaves Estrangeiras
    public function Produto()
    {
        return $this->belongsTo(Produto::class, 'codproduto', 'codproduto');
    }

    public function Imagem()
    {
        return $this->belongsTo(Imagem::class, 'codimagem', 'codimagem');
    }


    // Tabelas Filhas

}
