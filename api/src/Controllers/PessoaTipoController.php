<?php

namespace GabineteDigital\Controllers;

use GabineteDigital\Models\PessoaTipo;
use GabineteDigital\Middleware\Logger;
use PDOException;

class PessoaTipoController {
    private $pessoaTipoModel;
    private $logger;

    public function __construct() {
        $this->pessoaTipoModel = new PessoaTipo();
        $this->logger = new Logger();
    }

    public function criarPessoaTipo($dados) {
        $camposObrigatorios = ['pessoa_tipo_nome', 'pessoa_tipo_descricao', 'pessoa_tipo_criado_por'];

        foreach ($camposObrigatorios as $campo) {
            if (!isset($dados[$campo]) || empty($dados[$campo])) {
                return ['status' => 'bad_request', 'status_code' => 400, 'message' => "O campo '$campo' é obrigatório."];
            }
        }

        try {
            $this->pessoaTipoModel->criar($dados);
            return ['status' => 'success', 'status_code' => 201, 'message' => 'Tipo de pessoa criado com sucesso.'];
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                return ['status' => 'duplicated', 'status_code' => 409, 'message' => 'O tipo de pessoa já está cadastrado.'];
            } else {
                $this->logger->novoLog('pessoa_tipo_error', $e->getMessage());
                return ['status' => 'error', 'status_code' => 500, 'message' => 'Erro interno do servidor'];
            }
        }
    }

    public function listarPessoasTipos() {
        try {
            $pessoasTipos = $this->pessoaTipoModel->listar();

            if (empty($pessoasTipos)) {
                return ['status' => 'empty', 'status_code' => 204, 'message' => 'Nenhum tipo de pessoa registrado.'];
            }

            return ['status' => 'success', 'status_code' => 200, 'dados' => $pessoasTipos];
        } catch (PDOException $e) {
            $this->logger->novoLog('pessoa_tipo_error', $e->getMessage());
            return ['status' => 'error', 'status_code' => 500, 'message' => 'Erro interno do servidor'];
        }
    }

    public function buscarPessoaTipo($coluna, $valor) {
        try {
            $pessoaTipo = $this->pessoaTipoModel->buscar($coluna, $valor);
            if ($pessoaTipo) {
                return ['status' => 'success', 'status_code' => 200, 'dados' => $pessoaTipo];
            } else {
                return ['status' => 'not_found', 'status_code' => 404, 'message' => 'Tipo de pessoa não encontrado.'];
            }
        } catch (PDOException $e) {
            $this->logger->novoLog('pessoa_tipo_error', $e->getMessage());
            return ['status' => 'error', 'status_code' => 500, 'message' => 'Erro interno do servidor'];
        }
    }

    public function atualizarPessoaTipo($pessoa_tipo_id, $dados) {
        $camposObrigatorios = ['pessoa_tipo_nome', 'pessoa_tipo_descricao'];

        foreach ($camposObrigatorios as $campo) {
            if (!isset($dados[$campo]) || empty($dados[$campo])) {
                return ['status' => 'bad_request', 'status_code' => 400, 'message' => "O campo '$campo' é obrigatório."];
            }
        }

        try {
            $this->pessoaTipoModel->atualizar($pessoa_tipo_id, $dados);
            return ['status' => 'success', 'status_code' => 200, 'message' => 'Tipo de pessoa atualizado com sucesso.'];
        } catch (PDOException $e) {
            $this->logger->novoLog('pessoa_tipo_error', $e->getMessage());
            return ['status' => 'error', 'status_code' => 500, 'message' => 'Erro interno do servidor'];
        }
    }

    public function apagarPessoaTipo($pessoa_tipo_id) {
        try {
            $result = $this->buscarPessoaTipo('pessoa_tipo_id', $pessoa_tipo_id);

            if ($result['status'] === 'not_found') {
                return ['status' => 'not_found', 'status_code' => 404, 'message' => 'Tipo de pessoa não encontrado.'];
            }

            $this->pessoaTipoModel->apagar($pessoa_tipo_id);
            return ['status' => 'success', 'status_code' => 200, 'message' => 'Tipo de pessoa apagado com sucesso.'];
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'FOREIGN KEY') !== false) {
                return ['status' => 'error', 'status_code' => 500, 'message' => 'Erro: Não é possível apagar o tipo de pessoa. Existem registros dependentes.'];
            }

            $this->logger->novoLog('pessoa_tipo_error', $e->getMessage());
            return ['status' => 'error', 'status_code' => 500, 'message' => 'Erro interno do servidor'];
        }
    }
}
