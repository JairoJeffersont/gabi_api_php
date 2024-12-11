<?php

namespace GabineteDigital\Controllers;

use GabineteDigital\Models\Orgao;
use GabineteDigital\Middleware\Logger;
use PDOException;

class OrgaoController {
    private $orgaoModel;
    private $logger;

    public function __construct() {
        $this->orgaoModel = new Orgao();
        $this->logger = new Logger();
    }

    public function criarOrgao($dados) {
        $camposObrigatorios = ['orgao_nome', 'orgao_endereco', 'orgao_municipio', 'orgao_estado', 'orgao_email', 'orgao_tipo', 'orgao_criado_por'];

        if (!filter_var($dados['orgao_email'], FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'invalid_email', 'message' => 'Email inválido.', 'status_code' => 400];
        }

        foreach ($camposObrigatorios as $campo) {
            if (!isset($dados[$campo])) {
                return ['status' => 'bad_request', 'message' => "O campo '$campo' é obrigatório.", 'status_code' => 400];
            }
        }

        try {
            $this->orgaoModel->criar($dados);
            return ['status' => 'success', 'message' => 'Órgão inserido com sucesso.', 'status_code' => 201];
        } catch (PDOException $e) {

            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                return ['status' => 'duplicated', 'message' => 'O órgão já está cadastrado.', 'status_code' => 409];
            } else {
                $this->logger->novoLog('orgao_error', $e->getMessage());
                return ['status' => 'error', 'message' => 'Erro interno do servidor', 'status_code' => 500];
            }
        }
    }

    public function listarOrgaos($itens, $pagina, $ordem, $ordenarPor, $termo, $filtro) {
        try {
            $result = $this->orgaoModel->listar($itens, $pagina, $ordem, $ordenarPor, $termo, $filtro);

            $total = (isset($result[0]['total'])) ? $result[0]['total'] : 0;
            $totalPaginas = ceil($total / $itens);

            if (empty($result)) {
                return ['status' => 'empty', 'message' => 'Nenhum órgão encontrado.', 'status_code' => 200];
            }

            return ['status' => 'success', 'total_paginas' => $totalPaginas, 'dados' => $result, 'status_code' => 200];
        } catch (PDOException $e) {
            $this->logger->novoLog('orgao_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno do servidor.', 'status_code' => 500];
        }
    }

    public function buscarOrgao($coluna, $valor) {
        try {
            $orgao = $this->orgaoModel->buscar($coluna, $valor);
            if ($orgao) {
                return ['status' => 'success', 'dados' => $orgao, 'status_code' => 200];
            } else {
                return ['status' => 'not_found', 'message' => 'Órgão não encontrado.', 'status_code' => 200];
            }
        } catch (PDOException $e) {
            $this->logger->novoLog('orgao_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno do servidor', 'status_code' => 500];
        }
    }

    public function atualizarOrgao($orgao_id, $dados) {
        if (!filter_var($dados['orgao_email'], FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'invalid_email', 'message' => 'Email inválido.', 'status_code' => 400];
        }

        try {
            $this->orgaoModel->atualizar($orgao_id, $dados);
            return ['status' => 'success', 'message' => 'Órgão atualizado com sucesso.', 'status_code' => 200];
        } catch (PDOException $e) {
            $this->logger->novoLog('orgao_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno do servidor', 'status_code' => 500];
        }
    }

    public function apagarOrgao($orgao_id) {
        try {
            $result = $this->buscarOrgao('orgao_id', $orgao_id);

            $this->orgaoModel->apagar($orgao_id);
            return ['status' => 'success', 'message' => 'Órgão apagado com sucesso.', 'status_code' => 200];
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'FOREIGN KEY') !== false) {
                return ['status' => 'error', 'message' => 'Erro: Não é possível apagar o órgão. Existem registros dependentes.', 'status_code' => 409];
            }

            $this->logger->novoLog('orgao_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno do servidor', 'status_code' => 500];
        }
    }
}
