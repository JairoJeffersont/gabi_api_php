<?php



require_once './autoloader.php';



$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {

    case 'GET':
        $configs = require './src/Configs/config.php';

        $resposta = [
            'status' => 'success',
            'status_code' => 200,
            'message' => 'Configurações da API',
            'dados' => [
                'deputado' => [
                    'id_deputado' => $configs['deputado']['id'],
                    'nome_deputado' => $configs['deputado']['nome'],
                    'estado_deputado' => $configs['deputado']['estado'],
                    'ano_primeiro_mandato' => $configs['deputado']['ano_primeiro_mandato'],
                    'email_deputado' => $configs['deputado']['email_deputado'],
                    'telefone_gabinete' => $configs['deputado']['telefone_gabinete']
                ],
                'app' => [
                    'base_url_api' => rtrim($_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/', '')
                ]
            ]
        ];

        break;
    default:
        $resposta = ["status" => 405, "message" => "Método não permitido"];
        break;
}



header('Content-Type: application/json');
http_response_code($resposta['status_code'] ?? 200);
unset($resposta['status_code']);

echo json_encode($resposta);
