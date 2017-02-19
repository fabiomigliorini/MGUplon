<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codgrupousuario                    NOT NULL DEFAULT nextval('tblgrupousuario_codgrupousuario_seq'::regclass)
 * @property  varchar(50)                    $grupousuario                       NOT NULL
 * @property  varchar(600)                   $observacoes                        
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  timestamp                      $inativo                            
 *
 * Chaves Estrangeiras
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  GrupoUsuarioPermissao[]        $GrupoUsuarioPermissaoS
 * @property  GrupoUsuarioUsuario[]          $GrupoUsuarioUsuarioS
 */

class GrupoUsuario extends MGModel
{
    protected $table = 'tblgrupousuario';
    protected $primaryKey = 'codgrupousuario';
    protected $fillable = [
        'grupousuario',
        'observacoes',
        'inativo',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
        'inativo',
    ];


    // Chaves Estrangeiras
    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
    }
    
    // // Tabelas Filhas (sem gerador)
    public function GrupoUsuarioPermissaoS()
    {
        return $this->hasMany(GrupoUsuarioPermissao::class, 'codgrupousuario', 'codgrupousuario');
    }

    public function GrupoUsuarioUsuarioS()
    {
        return $this->hasMany(GrupoUsuarioUsuario::class, 'codgrupousuario', 'codgrupousuario');
    }

    public function validate() {
    	
    	if ($this->codgrupousuario)
    		$unique_grupousuario = "unique:tblgrupousuario,grupousuario,$this->codgrupousuario,codgrupousuario|required|min:5";
    	else 
    		$unique_grupousuario = "unique:tblgrupousuario,grupousuario|required|min:5";
        
        $this->_regrasValidacao = [
            'grupousuario' => $unique_grupousuario,
        ];
    
        $this->_mensagensErro = [
            'grupousuario.unique' => 'Esse nome de grupo já esta utilizado',
        ];
        
        return parent::validate();
    }

}
