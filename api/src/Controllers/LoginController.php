<?php

namespace GabineteDigital\Controllers;

use GabineteDigital\Models\Usuario;
use GabineteDigital\Middleware\Logger;
use PDOException;

use Firebase\JWT\JWT;

class LoginController {
    private $usuarioModel;
    private $logger;
    private $config;

    public function __construct() {
        $this->usuarioModel = new Usuario();
        $this->logger = new Logger();
        $this->config = require './src/Configs/config.php';
    }

    public function Logar($email, $senha) {
        try {
            $result = $this->usuarioModel->buscar('usuario_email', $email);

            if ($this->config['master_user']['master_email'] == $email && $this->config['master_user']['master_pass'] == $senha) {
                $this->logger->novoLog('login_access', ' - ' . $this->config['master_user']['master_name']);

                $payload = [
                    'exp' => time() + 600
                ];

                $usuario = [
                    'id' => 1000,
                    'nome' => $this->config['master_user']['master_name'],
                    'email' => $this->config['master_user']['master_email'],
                    'nivel' => 1
                ];

                $jwt = JWT::encode($payload, $this->config['app']['token_key'], 'HS256');

                return ['status' => 'success', 'status_code' => 200, 'message' => 'Usuário verificado com sucesso.', 'usuario' => $usuario, 'token' => $jwt];
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return ['status' => 'invalid_email', 'status_code' => 400, 'message' => 'Email inválido.'];
            }

            if (empty($result)) {
                return ['status' => 'not_found', 'status_code' => 404, 'message' => 'Usuário não encontrado.'];
            }

            if (!$result[0]['usuario_ativo']) {
                return ['status' => 'deactivated', 'status_code' => 200, 'message' => 'Usuário desativado.'];
            }

            if (password_verify($senha, $result[0]['usuario_senha'])) {
                $this->logger->novoLog('login_access', ' - ' . $result[0]['usuario_nome']);

                $payload = [
                    'exp' => $this->config['app']['token_time'] * 3600
                ];

                $usuario = [
                    'id' => $result[0]['usuario_id'],
                    'nome' => $result[0]['usuario_nome'],
                    'email' => $result[0]['usuario_email'],
                    'nivel' => $result[0]['usuario_nivel']
                ];

                $jwt = JWT::encode($payload, $this->config['app']['token_key'], 'HS256');

                return ['status' => 'success', 'status_code' => 200, 'message' => 'Usuário verificado com sucesso.', 'usuario' => $usuario, 'token' => $jwt];
            } else {
                return ['status' => 'wrong_password', 'status_code' => 401, 'message' => 'Senha incorreta.'];
            }
        } catch (PDOException $e) {
            $this->logger->novoLog('login_error', $e->getMessage());
            return ['status' => 'error', 'status_code' => 500,  'message' => 'Erro interno do servidor.'];
        }
    }
}
