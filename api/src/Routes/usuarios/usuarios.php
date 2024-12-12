<?php

require_once '../autoloader.php';

require_once './src/Middleware/verify_token.php';

use GabineteDigital\Controllers\UsuarioController;

$usuarioController = new UsuarioController();
$metodo = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? 0;

switch ($metodo) {
    case 'GET':
        $resposta = $id != 0 ? $usuarioController->buscarUsuario('usuario_id', $id) : $usuarioController->listarUsuarios();
        break;

    case 'POST':
        if ($decoded_array['usuario_nivel'] != 1) {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['status' => 'not_permitted', 'message' => 'Você não tem autorização para inserir ou atualizar usuários.']);
            exit;
        }

        if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
            $dados = json_decode(file_get_contents("php://input"), true);
        } else {
            $dados = [
                'usuario_nome' => htmlspecialchars($_POST['usuario_nome'], ENT_QUOTES, 'UTF-8'),
                'usuario_email' => htmlspecialchars($_POST['usuario_email'], ENT_QUOTES, 'UTF-8'),
                'usuario_telefone' => htmlspecialchars($_POST['usuario_telefone'], ENT_QUOTES, 'UTF-8'),
                'usuario_aniversario' => htmlspecialchars($_POST['usuario_aniversario'], ENT_QUOTES, 'UTF-8'),
                'usuario_ativo' => htmlspecialchars($_POST['usuario_ativo'], ENT_QUOTES, 'UTF-8'),
                'usuario_nivel' => htmlspecialchars($_POST['usuario_nivel'], ENT_QUOTES, 'UTF-8'),
                'usuario_senha' => htmlspecialchars($_POST['usuario_senha'], ENT_QUOTES, 'UTF-8'),
                'foto' => isset($_FILES['usuario_foto']) ? $_FILES['usuario_foto'] : null
            ];
        }

        if ($id == 0) {
            $resposta = $usuarioController->criarUsuario($dados);
        } else {
            $resposta = $usuarioController->atualizarUsuario($id, $dados);
        }
        break;

    case 'DELETE':

        if ($decoded_array['usuario_nivel'] != 1) {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['status' => 'not_permitted', 'message' => 'Você não tem autorização para apagar esse usuário.']);
            exit;
        }

        $resposta = $usuarioController->apagarUsuario($id);
        break;

    default:
        $resposta = ["status" => 405, "message" => "Método não permitido"];
        break;
}

header('Content-Type: application/json');
http_response_code($resposta['status_code'] ?? 200);
unset($resposta['status_code']);
echo json_encode($resposta);
