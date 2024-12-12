<?php

require_once '../autoloader.php';

require_once './src/Middleware/verify_token.php';


use GabineteDigital\Controllers\ProfissaoController;

$pessoaTipoController = new ProfissaoController();
$metodo = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? 0;

switch ($metodo) {
    case 'GET':
        $resposta = $id != 0 ? $pessoaTipoController->buscarPessoaProfissao('pessoas_profissoes_id', $id) : $pessoaTipoController->listarPessoasProfissoes();
        break;

    case 'POST':

        if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
            $dados = json_decode(file_get_contents("php://input"), true);
            $dados['pessoas_profissoes_criado_por'] = $decoded_array['usuario_id'];
        } else {
            $dados = [
                'pessoas_profissoes_nome' => htmlspecialchars($_POST['pessoas_profissoes_nome'], ENT_QUOTES, 'UTF-8'),
                'pessoas_profissoes_descricao' => htmlspecialchars($_POST['pessoas_profissoes_descricao'], ENT_QUOTES, 'UTF-8'),
                'pessoas_profissoes_criado_por' => $decoded_array['usuario_id']
            ];
        }

        if ($id == 0) {
            $resposta = $pessoaTipoController->criarPessoaProfissao($dados);
        } else {
            $resposta = $pessoaTipoController->atualizarPessoaProfissao($id, $dados);
        }
        break;

    case 'DELETE':
        if ($decoded_array['usuario_nivel'] != 1) {
            header('Content-Type: application/json');
            http_response_code(403);
            echo json_encode(['status' => 'forbidden', 'message' => 'Você não tem autorização para apagar esse tipo de profissão.']);
            exit;
        }

        $resposta = $pessoaTipoController->apagarPessoaProfissao($id);
        break;

    default:
        $resposta = ["status" => 405, "message" => "Método não permitido"];
        break;
}

header('Content-Type: application/json');
http_response_code($resposta['status_code'] ?? 200);
unset($resposta['status_code']);
echo json_encode($resposta);
