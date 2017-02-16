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
