<?php

namespace MGLara\Repositories;

use Illuminate\Support\Facades\Gate;
use MGLara\Models\MGModel;
use Carbon\Carbon;    
/**
 * Description of Repository
 *
 * @property string $model_class
 * @property Validator $validator
 * @property MGModel $model
 */
abstract class MGRepository {
    
    public function __construct() {
        $this->boot();
        $this->model_class = get_class($this->model);
    }
    
    public function validate($data = null, $id = null) {
        return true;
    }
    

    /**
     * Verifica se usuario tem permissao
     * 
     * @param type $ability
     * @return boolean
     */
    public function authorize($ability) {
        if (!Gate::allows($ability, $this->model)) {
            abort(403);
        }
        return true;
    }

    
    /**
     * cria um novo model com base nos atributos
     * 
     * @return MGModel
     */
    public function new($attributes = []) {
        if (!$this->model = new $this->model_class($attributes)) {
            return false;
        }
        return $this->model;
    }
    
    /**
     * salva um novo model
     * 
     * @param type $data
     * @return boolean
     */
    public function create($data = null) {
        
        if (!empty($data)) {
            $this->new($data);
        }
        
        if ($this->model->exists) {
            return false;
        }
        
        return $this->model->save();
        
    }
    

    /**
     * preenche o model
     * 
     * @param type $data
     */
    public function fill($data) {
        $this->model->fill($data);
    }
    
    /**
     * altera o model
     * 
     * @param type $id
     * @param type $data
     * @return boolean
     */
    public function update($id = null, $data = null) {
        if (!empty($id)) {
            $this->findOrFail($id);
        }
        if (!empty($data)) {
            $this->fill($data);
        }
        return $this->model->save();
    }
    
    
    /**
     * Exclui um model
     * 
     * @param type $id
     * @return type
     */
    public function delete($id = null) {
        if (!empty($id)) {
            $this->findOrFail($id);
        }
        return $this->model->delete();
    }
    
    
    /**
     * Verifica se o model esta sendo referenciado por outros
     * 
     * @param type $id
     * @return boolean
     */
    public function used($id = null) {
        return false;
    }
    

    /**
     * Ativa um model
     * 
     * @param type $id
     * @return boolean
     */
    public function activate($id = null) {
        if (!empty($id)) {
            $this->findOrFail($id);
        }
        if (empty($this->model->inativo)) {
            return true;
        }
        $this->model->inativo = null;
        return $this->model->save();
    }
    
    /**
     * inativa um model
     * 
     * @param type $id
     * @return boolean
     */
    public function inactivate($id = null) {
        if (!empty($id)) {
            $this->findOrFail($id);
        }
        if (!empty($this->model->inativo)) {
            return true;
        }
        $this->model->inativo = Carbon::now();
        return $this->model->save();
    }
    
    /**
     * total de registros do model
     * 
     * @return integer
     */
    public function count() {
        return $this->model_class::count();
    }
    
    /**
     * busca um registro
     * 
     * @param integer $id
     * @return MGModel
     */
    public function findOrFail($id) {
        return $this->model = $this->model_class::findOrFail($id);
    }
    
    public function save() {
        if ($this->model->exists) {
            return $this->update();
        } else {
            return $this->create();
        }
    }
    
    
}
