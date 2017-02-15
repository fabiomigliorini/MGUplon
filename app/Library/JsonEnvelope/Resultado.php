<?php

namespace MGLara\Library\JsonEnvelope;

use Illuminate\Support\Facades\Response;

/**
 * Description of EnvelopeJson
 *
 * @author escmig98
 * @property bool $resultado Se requisicao foi executado com sucesso
 * @property string $mensagem Mensagem da operacao
 * @property Exception $exception Excecao
 */
class Resultado {

    public function __construct($resultado = true, $mensagem = null, $exception = null) {
        $this->resultado = $resultado;
        if (empty($mensagem)) {
            $this->mensagem = ($resultado)?'Executado com sucesso!':'Falha na execução!';
        } else {
            $this->mensagem = $mensagem;
        }
        $this->exception = $exception;
    }
    
    public function response() {
        return Response::json($this);
    }
    
}