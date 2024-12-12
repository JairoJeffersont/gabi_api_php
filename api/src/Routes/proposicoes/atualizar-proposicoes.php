<?php

require_once '../autoloader.php';

require_once './src/Middleware/verify_token.php';


use GabineteDigital\Controllers\ProposicaoController;

$proposicaoController = new ProposicaoController();
$metodo = $_SERVER['REQUEST_METHOD'];
$acao = $_GET['acao'] ?? 'proposicoes';
$ano = $_GET['ano'] ?? date('Y');

switch ($metodo) {
    case 'GET':
        if ($acao == 'proposicoes') {
            $resposta = $proposicaoController->inserirProposicoes($ano);
        } else if ($acao == 'autores') {
            $resposta = $proposicaoController->inserirProposicoesAutores($ano);
        }
        break;
    default:
        $resposta = ["status" => 405, "status_code" => 405, "message" => "Método não permitido"];
        break;
}

header('Content-Type: application/json');
http_response_code($resposta['status_code'] ?? 200);
unset($resposta['status_code']);
echo json_encode($resposta);
