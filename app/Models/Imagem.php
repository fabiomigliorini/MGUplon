<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codimagem                          NOT NULL DEFAULT nextval('tblimagem_codimagem_seq'::regclass)
 * @property  varchar(200)                   $observacoes                        
 * @property  timestamp                      $inativo                            
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  varchar(150)                   $arquivo                            
 *
 * Chaves Estrangeiras
 *
 * Tabelas Filhas
 * @property  FamiliaProduto[]               $FamiliaProdutoS
 * @property  GrupoProduto[]                 $GrupoProdutoS
 * @property  Marca[]                        $MarcaS
 * @property  ProdutoImagem[]                $ProdutoImagemS
 * @property  SecaoProduto[]                 $SecaoProdutoS
 * @property  SubGrupoProduto[]              $SubGrupoProdutoS
 * 
 * Relacionamentos N x N
 * @property  Produto[]                       $ProdutoS
 * 
 */

class Imagem extends MGModel
{
    protected $table = 'tblimagem';
    protected $primaryKey = 'codimagem';
    protected $fillable = [
        'observacoes',
        'arquivo',
    ];
    protected $dates = [
        'inativo',
        'criacao',
        'alteracao',
    ];


    // Chaves Estrangeiras

    // Tabelas Filhas
    public function FamiliaProdutoS()
    {
        return $this->hasMany(FamiliaProduto::class, 'codimagem', 'codimagem');
    }

    public function GrupoProdutoS()
    {
        return $this->hasMany(GrupoProduto::class, 'codimagem', 'codimagem');
    }

    public function MarcaS()
    {
        return $this->hasMany(Marca::class, 'codimagem', 'codimagem');
    }

    public function ProdutoImagemS()
    {
        return $this->hasMany(ProdutoImagem::class, 'codimagem', 'codimagem');
    }

    public function SecaoProdutoS()
    {
        return $this->hasMany(SecaoProduto::class, 'codimagem', 'codimagem');
    }

    public function SubGrupoProdutoS()
    {
        return $this->hasMany(SubGrupoProduto::class, 'codimagem', 'codimagem');
    }

    // Relacionamento N x N
    public function ProdutoS()
    {
        return $this->belongsToMany(Produto::class, 'tblprodutoimagem', 'codimagem', 'codproduto');        
    }

}
