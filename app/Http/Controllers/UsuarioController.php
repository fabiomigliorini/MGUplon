<?php

namespace MGLara\Http\Controllers;

use MGLara\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
//use Illuminate\Hashing\BcryptHasher;
use Illuminate\Support\Facades\Auth;

use MGLara\Models\Usuario;
use MGLara\Models\GrupoUsuario;
use MGLara\Models\Filial;

use Carbon\Carbon;

use MGLara\Library\Breadcrumb\Breadcrumb;
use MGLara\Library\JsonEnvelope\Datatable;

class UsuarioController extends Controller
{
    public function __construct() {
        $this->bc = new Breadcrumb('Usuários');
        $this->bc->addItem('Usuários', url('usuario'));
    }
    
    public function index(Request $request) {
        //$this->authorize('list', Usuario::class);
        $this->bc->addItem('Listagem');
        if (!$filtro = $this->getFiltro()) {
            $filtro = [
                'inativo' => 1,
            ];
        }
        return view('usuario.index', ['bc'=>$this->bc, 'filtro'=>$filtro]);
        $filtro = $this->getFiltro();
        $parametros = self::filtroEstatico($request, 'usuario.index', ['ativo' => 1]);
        $model = Usuario::search($parametros)->orderBy('usuario', 'ASC')->paginate(20);
        return view('usuario.index', compact('model'));        
    }

    public function create() {
        //$this->authorize('create', GrupoUsuario::class);
        $model = new Usuario();
        $this->bc->addItem('Novo');
        return view('usuario.create', ['bc'=>$this->bc, 'model'=>$model]);
    }

    public function store(Request $request) {
        //$this->authorize('create', GrupoUsuario::class);
        $model = new Usuario($request->all());
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        $model->senha = bcrypt($model->senha);
        $model->save();
        Session::flash('flash_create', 'Usuário Criado!');
        return redirect("usuario/$model->codusuario");  
    }

    public function edit($id) {
        $model = Usuario::findOrFail($id);
        //$this->authorize('update', $model);
        $this->bc->addItem($model->usuario, url('usuario', $model->codusuario));
        $this->bc->header = $model->usuario;
        $this->bc->addItem('Alterar');        
        return view('usuario.edit',  ['bc'=>$this->bc, 'model'=>$model]);                
    }

    public function update(Request $request, $id) {
        $model = Usuario::findOrFail($id);
        //$this->authorize('update', $model);
        $model->fill($request->all());
        if(is_null($request->get('senha'))) {
            unset($model->senha);
        } else {
            $model->senha = bcrypt($model->senha);
        }

        if (!$model->validate()) {
            $this->throwValidationException($request, $model->_validator);
        }

        $model->save();        
        Session::flash('flash_update', "Usuário '{$model->usuario}' Atualizado!");
        return redirect("usuario/$model->codusuario"); 
    }
    
    public function show($id) {
        $model = Usuario::findOrFail($id);
        //$this->authorize('view', $model);
        $this->bc->addItem($model->usuario);
        $this->bc->header = $model->usuario;        
        return view('usuario.show', ['bc'=>$this->bc, 'model'=>$model]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Usuario::findOrFail($id);
        //$this->authorize('delete', $model);
        /*
        if ($model->UsuarioS->count() > 0) {
            return ['OK' => false, 'mensagem' => 'Grupo de Usuário está sendo utilizada em Usuários!'];
        }
        */
        return ['OK' => $model->delete()];
    }
    
    public function ativar($id) {
        $model = Usuario::findOrFail($id);
        //$this->authorize('update', $model);
        return ['OK' => $model->ativar()];
    }
    
    public function inativar($id) {
        $model = Usuario::findOrFail($id);
        //$this->authorize('update', $model);
        return ['OK' => $model->inativar()];
    }
    
    public function permissao(Request $request, $codusuario) {
        $model = Usuario::find($codusuario);
        $filiais = Filial::orderBy('codfilial', 'asc')->get();
        $parametros = self::filtroEstatico($request, 'usuario.permissao');        
        $grupos = GrupoUsuario::search($parametros)->orderBy('grupousuario', 'ASC')->paginate(20);
        
        
        return view('usuario.permissao', compact('model', 'grupos', 'filiais'));
    }

    public function attachPermissao(Request $request) {
        $model = Usuario::find($request->get('codusuario'));
        $model->GrupoUsuario()->attach($request->get('codgrupousuario'), ['codfilial' => $request->get('codfilial')]);
    }
    
    public function detachPermissao(Request $request) {
        DB::table( 'tblgrupousuariousuario' )
            ->where( 'codgrupousuario', '=', $request->codgrupousuario, 'and' )
            ->where( 'codusuario', '=', $request->codusuario, 'and' )
            ->where( 'codfilial', '=', $request->codfilial )
            ->delete();        
    }

    public function datatable(Request $request) {
        
        //$this->authorize('list', Usuario::class);
        
        // Query da Entidade
        $qry = Usuario::query();

        $this->setFiltro($request['filtros']);
        
        //dd($request['filtros']);
        
        // Filtros
        if (!empty($request['filtros']['codusuario'])) {
            $qry->where('codusuario', '=', $request['filtros']['codusuario']);
        }
        
        if (!empty($request['filtros']['usuario'])) {
            foreach(explode(' ', $request['filtros']['usuario']) as $palavra) {
                if (!empty($palavra)) {
                    $qry->where('usuario', 'ilike', "%$palavra%");
                }
            }
        }
        
        if (!empty($request['filtros']['codfilial'])) {
            $qry->where('codusuario', '=', $request['filtros']['codfilial']);
        }
        
        // Registros
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
        
        $recordsTotal = Usuario::count();
        $recordsFiltered = $qry->count();
        
        // Paginacao
        $qry->offset($request['start']);
        $qry->limit($request['length']);
        
        // Ordenacao
        $columns[0] = 'url';
        $columns[1] = 'inativo';
        $columns[2] = 'codusuario';
        $columns[3] = 'usuario';
        $columns[4] = 'codfilial';
        $columns[5] = 'criacao';
        $columns[6] = 'alteracao';
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
                url('usuario', $reg->codusuario),
                formataData($reg->inativo, 'C'),
                formataCodigo($reg->codusuario),
                $reg->usuario,
                $reg->Filial['filial'],
                formataData($reg->criacao, 'C'),
                formataData($reg->alteracao, 'C'),
            ];
        }
        
        // Envelope Retorno
        $ret = new Datatable($request['draw'], $recordsTotal, $recordsFiltered, $data);
        
        // Retorno
        return $ret->response();
        
    }
}
