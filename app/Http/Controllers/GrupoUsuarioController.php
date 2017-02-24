<?php

namespace MGLara\Http\Controllers;

use MGLara\Http\Controllers\Controller;
use MGLara\Models\GrupoUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Models\Permissao;

use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;

class GrupoUsuarioController extends Controller
{
    
    public function __construct() {
        $this->bc = new Breadcrumb('Grupos de Usuários');
        $this->bc->addItem('Grupos de Usuários', url('grupo-usuario'));
    }
    
    public function index(Request $request) {
        $this->authorize('listing', GrupoUsuario::class);
        $this->bc->addItem('Listagem');
        if (!$filtro = $this->getFiltro()) {
            $filtro = [
                'inativo' => 1,
            ];
        }

        return view('grupo-usuario.index', ['bc'=>$this->bc, 'filtro'=>$filtro]);
    }

    public function create() {
        $this->authorize('create', GrupoUsuario::class);
        $model = new GrupoUsuario;
        $this->bc->addItem('Novo');
        return view('grupo-usuario.create', ['bc'=>$this->bc, 'model'=>$model]);
    }

    public function store(Request $request) {
        $this->authorize('create', GrupoUsuario::class);
        $model = new GrupoUsuario($request->all());
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        $model->save();
        Session::flash('flash_create', 'Registro criado!');
        return redirect("grupo-usuario/$model->codgrupousuario");  
    }

    public function edit($id) {
        $model = GrupoUsuario::findOrFail($id);
        $this->authorize('update', $model);
        $this->bc->addItem($model->grupousuario, url('grupo-usuario', $model->codgrupousuario));
        $this->bc->header = $model->grupousuario;
        $this->bc->addItem('Alterar');        
        return view('grupo-usuario.edit',  ['bc'=>$this->bc, 'model'=>$model]);
    }

    public function update(Request $request, $id) {
        $model = GrupoUsuario::findOrFail($id);
        $this->authorize('update', $model);
        $model->fill($request->all());
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        $model->save();
        
        Session::flash('flash_update', 'Registro alterado!');
        return redirect("grupo-usuario/$model->codgrupousuario"); 
    }
    
    public function show(Request $request, $id) {
        $model = GrupoUsuario::findOrFail($id);
        $this->authorize('view', $model);
        $this->bc->addItem($model->grupousuario);
        $this->bc->header = $model->grupousuario;

        return view('grupo-usuario.show', ['bc'=>$this->bc, 'model'=>$model]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = GrupoUsuario::findOrFail($id);
        $this->authorize('delete', $model);
        if ($model->UsuarioS->count() > 0) {
            return ['OK' => false, 'mensagem' => 'Grupo de Usuário está sendo utilizada em Usuários!'];
        }
        return ['OK' => $model->delete()];
    }
    
    public function ativar($id) {
        $model = GrupoUsuario::findOrFail($id);
        $this->authorize('update', $model);
        return ['OK' => $model->ativar()];
    }
    
    public function inativar($id) {
        $model = GrupoUsuario::findOrFail($id);
        $this->authorize('update', $model);
        return ['OK' => $model->inativar()];
    }
    
    public function datatable(Request $request) {
        
        $this->authorize('listing', Usuario::class);
        
        // Query da Entidade
        $qry = GrupoUsuario::query();

        $this->setFiltro($request['filtros']);
        
        // Filtros
        if (!empty($request['filtros']['codgrupousuario'])) {
            $qry->where('codgrupousuario', '=', $request['filtros']['codgrupousuario']);
        }
        
        if (!empty($request['filtros']['grupousuario'])) {
            foreach(explode(' ', $request['filtros']['grupousuario']) as $palavra) {
                if (!empty($palavra)) {
                    $qry->where('grupousuario', 'ilike', "%$palavra%");
                }
            }
        }

        switch ($request['filtros']['inativo']) {
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

               
        $recordsTotal = GrupoUsuario::count();
        $recordsFiltered = $qry->count();
        
        // Paginacao
        $qry->offset($request['start']);
        $qry->limit($request['length']);
        
        // Ordenacao
        $columns[0] = 'codgrupousuario';
        $columns[1] = 'inativo';
        $columns[2] = 'codgrupousuario';
        $columns[3] = 'grupousuario';

        if (!empty($request['order'])) {
            foreach ($request['order'] as $order) {
                $qry->orderBy($columns[$order['column']], $order['dir']);
            }
        }
        
        // Registros
        $regs = $qry->get();
        $data = [];
        foreach ($regs as $reg) {
            $data[] = [
                url('grupo-usuario', $reg->codgrupousuario),
                formataData($reg->inativo, 'C'),
                formataCodigo($reg->codgrupousuario),
                $reg->grupousuario,
            ];
        }
        
        // Envelope Retorno
        $ret = new Datatable($request['draw'], $recordsTotal, $recordsFiltered, $data);
        
        // Retorno
        return $ret->response();
        
    }

}
