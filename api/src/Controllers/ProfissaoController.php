<?php

namespace GabineteDigital\Controllers;

use GabineteDigital\Models\Profissao;
use GabineteDigital\Middleware\Logger;
use PDOException;

class ProfissaoController {
    private $pessoaProfissaoModel;
    private $logger;

    public function __construct() {
        $this->pessoaProfissaoModel = new Profissao();
        $this->logger = new Logger();
    }

    public function criarPessoaProfissao($dados) {
        $camposObrigatorios = ['pessoas_profissoes_nome', 'pessoas_profissoes_descricao', 'pessoas_profissoes_criado_por'];

        foreach ($camposObrigatorios as $campo) {
            if (!isset($dados[$campo]) || empty($dados[$campo])) {
                return ['status' => 'bad_request', 'message' => "O campo '$campo' é obrigatório.", 'status_code' => 400];
            }
        }

        try {
            $this->pessoaProfissaoModel->criar($dados);
            return ['status' => 'success', 'message' => 'Profissão criada com sucesso.', 'status_code' => 201];
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                return ['status' => 'duplicated', 'message' => 'A profissão já está cadastrada.', 'status_code' => 409];
            } else {
                $this->logger->novoLog('pessoa_profissao_error', $e->getMessage());
                return ['status' => 'error', 'message' => 'Erro interno do servidor', 'status_code' => 500];
            }
        }
    }

    public function listarPessoasProfissoes() {
        try {
            $profissoes = $this->pessoaProfissaoModel->listar();

            if (empty($profissoes)) {
                return ['status' => 'empty', 'message' => 'Nenhuma profissão registrada.', 'status_code' => 200];
            }

            return ['status' => 'success', 'message' => count($profissoes) . ' profissão(ões) encontrada(as)', 'dados' => $profissoes, 'status_code' => 200];
        } catch (PDOException $e) {
            $this->logger->novoLog('pessoa_profissao_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno do servidor', 'status_code' => 500];
        }
    }

    public function buscarPessoaProfissao($coluna, $valor) {
        try {
            $profissao = $this->pessoaProfissaoModel->buscar($coluna, $valor);
            if ($profissao) {
                return ['status' => 'success', 'dados' => $profissao, 'status_code' => 200];
            } else {
                return ['status' => 'not_found', 'message' => 'Profissão não encontrada.', 'status_code' => 404];
            }
        } catch (PDOException $e) {
            $this->logger->novoLog('pessoa_profissao_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno do servidor', 'status_code' => 500];
        }
    }

    public function atualizarPessoaProfissao($pessoas_profissoes_id, $dados) {
        $camposObrigatorios = ['pessoas_profissoes_nome', 'pessoas_profissoes_descricao'];

        foreach ($camposObrigatorios as $campo) {
            if (!isset($dados[$campo]) || empty($dados[$campo])) {
                return ['status' => 'bad_request', 'message' => "O campo '$campo' é obrigatório.", 'status_code' => 400];
            }
        }

        try {
            $this->pessoaProfissaoModel->atualizar($pessoas_profissoes_id, $dados);
            return ['status' => 'success', 'message' => 'Profissão atualizada com sucesso.', 'status_code' => 200];
        } catch (PDOException $e) {
            $this->logger->novoLog('pessoa_profissao_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno do servidor', 'status_code' => 500];
        }
    }

    public function apagarPessoaProfissao($pessoas_profissoes_id) {
        try {
            $result = $this->buscarPessoaProfissao('pessoas_profissoes_id', $pessoas_profissoes_id);

            if ($result['status'] === 'not_found') {
                return ['status' => 'not_found', 'message' => 'Profissão não encontrada.', 'status_code' => 404];
            }

            $this->pessoaProfissaoModel->apagar($pessoas_profissoes_id);
            return ['status' => 'success', 'message' => 'Profissão apagada com sucesso.', 'status_code' => 200];
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'FOREIGN KEY') !== false) {
                return ['status' => 'error', 'message' => 'Erro: Não é possível apagar a profissão. Existem registros dependentes.', 'status_code' => 409];
            }

            $this->logger->novoLog('pessoa_profissao_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno do servidor', 'status_code' => 500];
        }
    }
}
