<?php

namespace MGLara\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use MGLara\Library\JsonEnvelope\Resultado;

use Carbon\Carbon;

/**
 * @property Breadcrumb $bc Breadcrumb
 * @property string $model_class Classe do Model Principal
 */
abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Converte os campos com string de data em um array para Carbon
     * 
     * @param array $array_dados
     * @param array $campos_data
     * @param string $formato da Data
     */
    public static function datasParaCarbon ($array_dados = [], $campos_data = [], $formato = null)
    {
        foreach ($campos_data as $campo) {
            if (!empty($array_dados[$campo])) {
                if (!($array_dados[$campo] instanceof Carbon)) {
                    $array_dados[$campo] = new Carbon($array_dados[$campo], $formato);
                }
            }
        }
        return $array_dados;
    }
    
    /**
     * Decide se vai utilizar filtro Padrao, da Sessao ou do Request
     * 
     * @param Request $request
     * @param string $chave
     * @param array $filtro_padrao
     * @param array $campos_data
     * @return array
     */
    public static function filtroEstatico(Request $request, $chave = null, array $filtro_padrao = [], array $campos_data = [])
    {
        $chave = $this->montaChaveFiltro(null, $chave);
        
        $filtro_request = $request->all();

        // Se veio request GET com filtro
        if (count($filtro_request)) {
            $this->setFiltro($filtro_request, null, $chave);
            $retorno = $filtro_request;
        } else {
            if (!$retorno = $this->getFiltro(null, $chave)) {
                $retorno = $filtro_padrao;
            }
        }
        
        // Converte as datas
        //$retorno = self::datasParaCarbon($retorno, $campos_data);
        
        return $retorno;

    }

    /**
     * Monta a chave da sessao para armazenar o filtro
     * @param string $sufixo Sufixo a ser utilizado na chave da sessao
     * @param string $chave Chave da sessao a ser utilizada
     * @return string
     */
    public function montaChaveFiltro ($sufixo = null, $chave = null) {
        $chave = $chave??str_replace('\\', ".", get_class($this));
        if (!empty($sufixo)) {
            $chave .= ".$sufixo";
        }
        return $chave;
    }

    /**
     * Armazena filtro de busca na sessao
     * @param array $filtros Array com os filtros para gravar na sessao
     * @param string $sufixo Sufixo a ser utilizado na chave da sessao
     * @param string $chave Chave da sessao a ser utilizada
     * @return array
     */
    public function setFiltro($filtro, $sufixo = null, $chave = null) {
        $chave = $this->montaChaveFiltro($sufixo, $chave);
        return session([$chave => $filtro]);
    }
    
    /**
     * Recupera filtro de busca armazenado na sessao
     * @param string $sufixo Sufixo a ser utilizado na chave da sessao
     * @param string $chave Chave da sessao a ser utilizada
     * @return array
     */
    public function getFiltro($sufixo = null, $chave = null) {
        $chave = $this->montaChaveFiltro($sufixo, $chave);
        return session($chave);
    }
    
}
