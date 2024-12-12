<?php

require_once './autoloader.php';

require_once './src/Middleware/verify_token.php';

use GabineteDigital\Controllers\PessoaController;

$pessoaController = new PessoaController();
$metodo = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? 0;

switch ($metodo) {
    case 'GET':
        $resposta = $id != 0
            ? $pessoaController->buscarPessoa('pessoa_id', $id)
            : $pessoaController->listarPessoas(
                $_GET['itens'] ?? 10,
                $_GET['pagina'] ?? 1,
                $_GET['ordem'] ?? 'asc',
                $_GET['ordenarPor'] ?? 'pessoa_nome',
                $_GET['termo'] ?? '',
                $_GET['filtro'] ?? ''
            );
        break;

    case 'POST':
        if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
            $dados = json_decode(file_get_contents("php://input"), true);
            $dados['pessoa_criada_por'] = $decoded_array['usuario_id'];
        } else {
            $dados = [
                'pessoa_nome' => htmlspecialchars($_POST['pessoa_nome'], ENT_QUOTES, 'UTF-8'),
                'pessoa_aniversario' => htmlspecialchars($_POST['pessoa_aniversario'], ENT_QUOTES, 'UTF-8'),
                'pessoa_email' => htmlspecialchars($_POST['pessoa_email'], ENT_QUOTES, 'UTF-8'),
                'pessoa_telefone' => htmlspecialchars($_POST['pessoa_telefone'], ENT_QUOTES, 'UTF-8'),
                'pessoa_endereco' => htmlspecialchars($_POST['pessoa_endereco'], ENT_QUOTES, 'UTF-8'),
                'pessoa_bairro' => htmlspecialchars($_POST['pessoa_bairro'], ENT_QUOTES, 'UTF-8'),
                'pessoa_municipio' => htmlspecialchars($_POST['pessoa_municipio'], ENT_QUOTES, 'UTF-8'),
                'pessoa_estado' => htmlspecialchars($_POST['pessoa_estado'], ENT_QUOTES, 'UTF-8'),
                'pessoa_cep' => htmlspecialchars($_POST['pessoa_cep'], ENT_QUOTES, 'UTF-8'),
                'pessoa_sexo' => htmlspecialchars($_POST['pessoa_sexo'], ENT_QUOTES, 'UTF-8'),
                'pessoa_facebook' => htmlspecialchars($_POST['pessoa_facebook'], ENT_QUOTES, 'UTF-8'),
                'pessoa_instagram' => htmlspecialchars($_POST['pessoa_instagram'], ENT_QUOTES, 'UTF-8'),
                'pessoa_x' => htmlspecialchars($_POST['pessoa_x'], ENT_QUOTES, 'UTF-8'),
                'pessoa_informacoes' => htmlspecialchars($_POST['pessoa_informacoes'], ENT_QUOTES, 'UTF-8'),
                'pessoa_profissao' => (int)$_POST['pessoa_profissao'],
                'pessoa_cargo' => htmlspecialchars($_POST['pessoa_cargo'], ENT_QUOTES, 'UTF-8'),
                'pessoa_tipo' => (int)$_POST['pessoa_tipo'],
                'pessoa_orgao' => (int)$_POST['pessoa_orgao'],
                'foto' => isset($_FILES['pessoa_foto']) ? $_FILES['pessoa_foto'] : null,
                'pessoa_criada_por' => $decoded_array['usuario_id']
            ];
        }

        if ($id == 0) {
            $resposta = $pessoaController->criarPessoa($dados);
        } else {
            $resposta = $pessoaController->atualizarPessoa($id, $dados);
        }
        break;

    case 'DELETE':
        $resposta = $pessoaController->apagarPessoa($id);
        break;

    default:
        $resposta = ["status" => 405, "message" => "Método não permitido"];
        break;
}

header('Content-Type: application/json');
http_response_code($resposta['status_code'] ?? 200);
unset($resposta['status_code']);
echo json_encode($resposta);
