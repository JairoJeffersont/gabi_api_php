<?php

require_once '../autoloader.php';

require_once './src/Middleware/verify_token.php';


use GabineteDigital\Controllers\ProposicaoController;

$proposicaoController = new ProposicaoController();
$metodo = $_SERVER['REQUEST_METHOD'];

$acao = $_GET['acao'] ?? 'proposicoesGabinete';
$ano = $_GET['ano'] ?? date('Y');
$id = $_GET['id'] ?? 0;


switch ($metodo) {
    case 'GET':
        $itens = $_GET['itens'] ?? 10;
        $pagina = $_GET['pagina'] ?? 1;
        $ordenarPor = $_GET['ordenarPor'] ?? 'proposicao_id';
        $ordem = $_GET['ordem'] ?? 'DESC';
        $tipo = $_GET['tipo'] ?? 'PL';
        $ano = $_GET['ano'] ?? $ano;
        $termo = $_GET['termo'] ?? '';
        $arquivada = $_GET['arquivada'] ?? 0;

        if ($acao == 'proposicoesGabinete') {
            $resposta = $proposicaoController->proposicoesGabinete($itens, $pagina, $ordenarPor, $ordem, $tipo, $ano, $termo, $arquivada);
        } else if ($acao == 'buscarAutores') {
            $resposta = $proposicaoController->buscarAutores($id);
        } else if ($acao == 'buscarProposicao') {
            $resposta = $proposicaoController->buscarProposicao('proposicao_id', $id);
        } else if ($acao == 'buscarUltimaProposicao') {
            $resposta = $proposicaoController->buscarUltimaProposicao($id);
        } else if ($acao == 'buscarTramitacoes') {
            $resposta = $proposicaoController->buscarTramitacoes($id);
        } else if ($acao == 'buscarRelacionadas') {
            $resposta = $proposicaoController->buscarRelacionadas($id);
        } else {
            $resposta = ["status" => 405, "status_code" => 405, "message" => "Ação não encontrada"];
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
