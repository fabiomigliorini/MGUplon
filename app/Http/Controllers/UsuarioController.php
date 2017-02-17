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
    }

    public function create() {
        $model = new Usuario();
        return view('usuario.create', compact('model'));
    }

    public function store(Request $request) {
        $model = new Usuario($request->all());
        if (!$model->validate())
            $this->throwValidationException($request, $model->_validator);
        $model->senha = bcrypt($model->senha);
        $model->save();
        Session::flash('flash_success', 'Usuário Criado!');
        return redirect("usuario/$model->codusuario");  
    }

    public function edit($codusuario) {
        $model = Usuario::findOrFail($codusuario);
                
        $usuario = Usuario::find(Auth::user()->codusuario);
        $grupos = $usuario->extractgrupos();
        $admin = false;
        foreach ($grupos as $grupo)
        {
            if ($grupo['grupo'] == '1') {
                $admin = true;
            }
        }

        if($admin) { 
            return view('usuario.edit',  compact('model'));
        } elseif(!$admin && $model->codusuario == $usuario->codusuario){
            return view('usuario.edit',  compact('model'));
        } else {
            return view('errors.403');
        }        
    }

    public function update($codusuario, Request $request) {
        $model = Usuario::findOrFail($codusuario);
        $model->fill($request->all());
        if (!$model->validate()) {
            $this->throwValidationException($request, $model->_validator);
        }
        if(empty($model->senha)) {
            unset($model->senha);
        }
        
        if(isset($model->senha)) {
            $model->senha = bcrypt($model->senha);
        }

        $model->save();        
        Session::flash('flash_success', "Usuário '{$model->usuario}' Atualizado!");
        return redirect("usuario/$model->codusuario"); 
    }
    
    public function show($codusuario) {
        $model = Usuario::find($codusuario);
        $usuario = Usuario::find(Auth::user()->codusuario);
        return view('usuario.show', compact('model', 'usuario'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            Usuario::find($id)->delete();
            $ret = ['resultado' => true, 'mensagem' => 'Usuário excluído com sucesso!'];
        }
        catch(\Exception $e){
            $ret = ['resultado' => false, 'mensagem' => 'Erro ao excluir usuário!', 'exception' => $e];
        }
        return json_encode($ret);
    }
    
    public function inativar(Request $request)
    {
        $model = Usuario::find($request->get('codusuario'));
        if($request->get('acao') == 'ativar')
        {
            $model->inativo = null;
            $msg = "Usuário '{$model->usuario}' Reativado!";
        }
        else
        {
            $model->inativo = Carbon::now();
            $msg = "Usuário '{$model->usuario}' Inativado!";
        }
        
        $model->save();
        Session::flash('flash_success', $msg);
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

//    public function listagemJson(Request $request){
//        if($request->get('q')) {
//            $marcas = Marca::marca($request->get('q'))->select('codmarca as id', 'marca')->take(10)->get();
//            return response()->json(['items' => $marcas]);       
//        } elseif($request->get('id')) {
//            $marca = Marca::find($request->get('id'));
//            return response()->json($marca);
//        }
//    }


    public function datatable(Request $request) {
        
        //$this->authorize('list', Usuario::class);
        
        // Query da Entidade
        $qry = Usuario::query();
        $qry->select([
            'tblusuario.codusuario',
            'tblusuario.inativo', 
            'tblusuario.usuario', 
            'tblpessoa.pessoa', 
            'tblfilial.filial']);
        $qry->leftJoin('tblpessoa', 'tblpessoa.codpessoa', '=', 'tblusuario.codpessoa');
        $qry->leftJoin('tblfilial', 'tblfilial.codfilial', '=', 'tblusuario.codfilial');

        $this->setFiltro($request['filtros']);
        
        //dd($request['filtros']);
        
        // Filtros
        if (!empty($request['filtros']['codusuario'])) {
            $qry->where('tblusuario.codusuario', '=', $request['filtros']['codusuario']);
        }
        
        if (!empty($request['filtros']['usuario'])) {
            foreach(explode(' ', $request['filtros']['usuario']) as $palavra) {
                if (!empty($palavra)) {
                    $qry->where('tblusuario.usuario', 'ilike', "%$palavra%");
                }
            }
        }
        
        if (!empty($request['filtros']['codfilial'])) {
            $qry->where('tblusuario.codfilial', '=', $request['filtros']['codfilial']);
        }
        
        if (!empty($request['filtros']['codpessoa'])) {
            $qry->where('tblusuario.codpessoa', '=', $request['filtros']['codpessoa']);
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
        $columns[0] = 'tblusuario.codusuario';
        $columns[1] = 'tblusuario.inativo';
        $columns[2] = 'tblusuario.codusuario';
        $columns[3] = 'tblusuario.usuario';
        $columns[4] = 'tblpessoa.pessoa';
        $columns[5] = 'tblfilial.filial';
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
                $reg->pessoa,
                $reg->filial,
            ];
        }
        
        // Envelope Retorno
        $ret = new Datatable($request['draw'], $recordsTotal, $recordsFiltered, $data);
        
        // Retorno
        return $ret->response();
        
    }
}
