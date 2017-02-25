<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MGLara\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Description of Model
 *
 * @author escmig05
 */
abstract class MGModel extends Model {

    
    const CREATED_AT = 'criacao';
    const UPDATED_AT = 'alteracao';
    
    public $timestamps = true;
    
    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            if (Auth::user() !== NULL) {
                $model->attributes['codusuariocriacao'] = Auth::user()->codusuario;
                $model->attributes['codusuarioalteracao'] = Auth::user()->codusuario;
            }
        });
        
        static::updating(function($model) {
            if (Auth::user() !== NULL) {
                $model->attributes['codusuarioalteracao'] = Auth::user()->codusuario;
            }
        });
        
        static::saving(function($model) {
            foreach ($model->toArray() as $fieldName => $fieldValue) {
                if ( $fieldValue === '' ) {
                    $model->attributes[$fieldName] = null;
                }
            }
            return true;
        });
    }
    
    public function scopeAtivo($query) {
        $query->whereNull("{$this->table}.inativo");
    }
    
    public function scopeInativo($query) {
        $query->whereNotNull("{$this->table}.inativo");
    }
    
    public function scopePalavras($query, $campo, $palavras) {
        foreach(explode(' ', trim($palavras)) as $palavra) {
            if (!empty($palavra)) {
                $query->where($campo, 'ilike', "%$palavra%");
            }
        }
    }
    
}
