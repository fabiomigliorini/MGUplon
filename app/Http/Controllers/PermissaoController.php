<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;

use MGLara\Models\UnidadeMedida;

use MGLara\Library\Breadcrumb\Breadcrumb;

use Carbon\Carbon;

use MGLara\Policies\MGPolicy;
use MGLara\Policies\UnidadeMedidaPolicy;

class PermissaoController extends Controller
{

    public function __construct() {
        $this->bc = new Breadcrumb('Unidades de Medida');
        $this->bc->addItem('Unidades de Medida', url('unidade-medida'));
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        
        // Pega todos arquivos da pasta de Policies
        $arquivos = scandir(base_path() . '/app/Policies/');
        
        // Percorre arquivos para buscar metodos
        $classes = [];
        foreach ($arquivos as $arquivo) {
            
            // Ignora arquivos
            if (in_array($arquivo, ['.', '..', 'MGPolicy.php'])) {
                continue;
            }
            
            // Tira a extensao do arquivo
            $classe = str_replace('.php', '', $arquivo);
            
            // Pega metodos da Classe
            $metodos = get_class_methods("MGLara\\Policies\\{$classe}");
            
            // Metodos para serem ignorados
            $metodos = array_diff($metodos, ['before']);
            
            // Acumula na listagem de classes
            $classes[] = [
                'classe' => $classe,
                'metodos' => $metodos
            ];
        }
        
        //$p = new UnidadeMedidaPolicy;        
        dd($classes);
        
        
        
        dd($d);
        $classes = get_declared_classes();
        
        dd($classes);
    }
    
}
