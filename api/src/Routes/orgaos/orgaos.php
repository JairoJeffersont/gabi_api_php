<?php

require_once './autoloader.php';

require_once './src/Middleware/verify_token.php';


use GabineteDigital\Controllers\OrgaoController;

$orgaoController = new OrgaoController();
$metodo = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? 0;

switch ($metodo) {
    case 'GET':
        $resposta = $id != 0
            ? $orgaoController->buscarOrgao('orgao_id', $id)
            : $orgaoController->listarOrgaos(
                $_GET['itens'] ?? 10,
                $_GET['pagina'] ?? 1,
                $_GET['ordem'] ?? 'asc',
                $_GET['ordenarPor'] ?? 'orgao_nome',
                $_GET['termo'] ?? '',
                $_GET['filtro'] ?? ''
            );
        break;

    case 'POST':
        if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
            $dados = json_decode(file_get_contents("php://input"), true);
            $dados['orgao_criado_por'] = $decoded_array['usuario_id'];
        } else {
            $dados = [
                'orgao_nome' => htmlspecialchars($_POST['orgao_nome'], ENT_QUOTES, 'UTF-8'),
                'orgao_endereco' => htmlspecialchars($_POST['orgao_endereco'], ENT_QUOTES, 'UTF-8'),
                'orgao_municipio' => htmlspecialchars($_POST['orgao_municipio'], ENT_QUOTES, 'UTF-8'),
                'orgao_estado' => htmlspecialchars($_POST['orgao_estado'], ENT_QUOTES, 'UTF-8'),
                'orgao_email' => htmlspecialchars($_POST['orgao_email'], ENT_QUOTES, 'UTF-8'),
                'orgao_telefone' => htmlspecialchars($_POST['orgao_telefone'], ENT_QUOTES, 'UTF-8'),
                'orgao_cep' => htmlspecialchars($_POST['orgao_cep'], ENT_QUOTES, 'UTF-8'),
                'orgao_bairro' => htmlspecialchars($_POST['orgao_bairro'], ENT_QUOTES, 'UTF-8'),
                'orgao_tipo' => htmlspecialchars($_POST['orgao_tipo'], ENT_QUOTES, 'UTF-8'),
                'orgao_informacoes' => htmlspecialchars($_POST['orgao_informacoes'], ENT_QUOTES, 'UTF-8'),
                'orgao_site' => htmlspecialchars($_POST['orgao_site'], ENT_QUOTES, 'UTF-8'),
                'orgao_criado_por' => $decoded_array['usuario_id']
            ];
        }

        if ($id == 0) {
            $resposta = $orgaoController->criarOrgao($dados);
        } else {
            $resposta = $orgaoController->atualizarOrgao($id, $dados);
        }
        break;

    case 'DELETE':
        if ($decoded_array['usuario_nivel'] != 1) {
            header('Content-Type: application/json');
            http_response_code(403);
            echo json_encode(['status' => 'forbidden', 'message' => 'Você não tem autorização para apagar esse órgão.']);
            exit;
        }

        $resposta = $orgaoController->apagarOrgao($id);
        break;

    default:
        $resposta = ["status" => 405, "message" => "Método não permitido"];
        break;
}

header('Content-Type: application/json');
http_response_code($resposta['status_code'] ?? 200);
unset($resposta['status_code']);
echo json_encode($resposta);
