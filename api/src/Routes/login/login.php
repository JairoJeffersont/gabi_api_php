<?php

use GabineteDigital\Controllers\LoginController;

require_once './autoloader.php';

$loginController = new LoginController();

$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {

    case 'POST':

        if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
            $dados = json_decode(file_get_contents("php://input"), true);
        } else {
            $dados = [
                'email' => htmlspecialchars($_POST['usuario_nome'], ENT_QUOTES, 'UTF-8'),
                'senha' => htmlspecialchars($_POST['usuario_email'], ENT_QUOTES, 'UTF-8')
            ];
        }

        $resposta = $loginController->Logar($dados['email'], $dados['senha']);
        break;
    default:
        $resposta = ["status" => 405, "message" => "Método não permitido"];
        break;
}



header('Content-Type: application/json');
http_response_code($resposta['status_code'] ?? 200);
unset($resposta['status_code']);

echo json_encode($resposta);
