<?php

require_once '../autoloader.php';

require_once './src/Middleware/verify_token.php';


use GabineteDigital\Controllers\OficioController;

$oficioController = new OficioController();
$metodo = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? 0;

switch ($metodo) {
    case 'GET':
        $resposta = $id != 0
            ? $oficioController->buscarOficio('oficio_id', $id)
            : $oficioController->listarOficios(
                $_GET['ano'] ?? null,
                $_GET['termo'] ?? null
            );
        break;

    case 'POST':
        if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
            $dados = json_decode(file_get_contents("php://input"), true);
            $dados['oficio_criado_por'] = $decoded_array['usuario_id'];
        } else {
            $dados = [
                'oficio_titulo' => htmlspecialchars($_POST['oficio_titulo'], ENT_QUOTES, 'UTF-8'),
                'oficio_resumo' => htmlspecialchars($_POST['oficio_resumo'], ENT_QUOTES, 'UTF-8'),
                'oficio_ano' => htmlspecialchars($_POST['oficio_ano'], ENT_QUOTES, 'UTF-8'),
                'oficio_orgao' => htmlspecialchars($_POST['oficio_orgao'], ENT_QUOTES, 'UTF-8'),
                'arquivo' => isset($_FILES['oficio_arquivo']) ? $_FILES['oficio_arquivo'] : null,
                'oficio_criado_por' => $decoded_array['usuario_id']
            ];
        }

        if ($id == 0) {
            $resposta = $oficioController->criarOficio($dados);
        } else {
            $resposta = $oficioController->atualizarOficio($id, $dados);
        }
        break;

    case 'DELETE':
        $resposta = $oficioController->apagarOficio($id);
        break;

    default:
        $resposta = ["status" => 405, "message" => "Método não permitido"];
        break;
}

header('Content-Type: application/json');
http_response_code($resposta['status_code'] ?? 200);
unset($resposta['status_code']);
echo json_encode($resposta);
