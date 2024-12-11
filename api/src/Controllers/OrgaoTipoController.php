<?php

namespace GabineteDigital\Controllers;

use GabineteDigital\Models\OrgaoTipo;
use GabineteDigital\Middleware\Logger;
use PDOException;

class OrgaoTipoController {
    private $orgaoTipoModel;
    private $logger;

    public function __construct() {
        $this->orgaoTipoModel = new OrgaoTipo();
        $this->logger = new Logger();
    }

    public function criarOrgaoTipo($dados) {
        $camposObrigatorios = ['orgao_tipo_nome', 'orgao_tipo_descricao', 'orgao_tipo_criado_por'];

        foreach ($camposObrigatorios as $campo) {
            if (!isset($dados[$campo]) || empty($dados[$campo])) {
                return ['status' => 'bad_request', 'status_code' => 400, 'message' => "O campo '$campo' é obrigatório."];
            }
        }

        try {
            $this->orgaoTipoModel->criar($dados);
            return ['status' => 'success', 'status_code' => 201, 'message' => 'Tipo de órgão criado com sucesso.'];
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                return ['status' => 'duplicated', 'status_code' => 409, 'message' => 'O tipo já está cadastrado.'];
            } else {
                $this->logger->novoLog('orgao_tipo_error', $e->getMessage());
                return ['status' => 'error', 'status_code' => 500, 'message' => 'Erro interno do servidor'];
            }
        }
    }

    public function listarOrgaosTipos() {
        try {
            $orgaosTipos = $this->orgaoTipoModel->listar();

            if (empty($orgaosTipos)) {
                return ['status' => 'empty', 'status_code' => 200, 'message' => 'Nenhum tipo de órgão registrado.'];
            }

            return ['status' => 'success', 'status_code' => 200, 'message' => count($orgaosTipos) . ' tipos(os) encontrado(os)', 'dados' => $orgaosTipos];
        } catch (PDOException $e) {
            $this->logger->novoLog('orgao_tipo_error', $e->getMessage());
            return ['status' => 'error', 'status_code' => 500, 'message' => 'Erro interno do servidor'];
        }
    }

    public function buscarOrgaoTipo($coluna, $valor) {
        try {
            $orgaoTipo = $this->orgaoTipoModel->buscar($coluna, $valor);
            if ($orgaoTipo) {
                return ['status' => 'success', 'status_code' => 200, 'dados' => $orgaoTipo];
            } else {
                return ['status' => 'not_found', 'status_code' => 200, 'message' => 'Tipo de órgão não encontrado.'];
            }
        } catch (PDOException $e) {
            $this->logger->novoLog('orgao_tipo_error', $e->getMessage());
            return ['status' => 'error', 'status_code' => 500, 'message' => 'Erro interno do servidor'];
        }
    }

    public function atualizarOrgaoTipo($orgao_tipo_id, $dados) {
        $camposObrigatorios = ['orgao_tipo_nome', 'orgao_tipo_descricao'];

        foreach ($camposObrigatorios as $campo) {
            if (!isset($dados[$campo]) || empty($dados[$campo])) {
                return ['status' => 'bad_request', 'status_code' => 400, 'message' => "O campo '$campo' é obrigatório."];
            }
        }

        try {
            $this->orgaoTipoModel->atualizar($orgao_tipo_id, $dados);
            return ['status' => 'success', 'status_code' => 200, 'message' => 'Tipo de órgão atualizado com sucesso.'];
        } catch (PDOException $e) {
            $this->logger->novoLog('orgao_tipo_error', $e->getMessage());
            return ['status' => 'error', 'status_code' => 500, 'message' => 'Erro interno do servidor'];
        }
    }

    public function apagarOrgaoTipo($orgao_tipo_id) {
        try {
            $result = $this->buscarOrgaoTipo('orgao_tipo_id', $orgao_tipo_id);

            if ($result['status'] === 'not_found') {
                return ['status' => 'not_found', 'status_code' => 404, 'message' => 'Tipo de órgão não encontrado.'];
            }

            $this->orgaoTipoModel->apagar($orgao_tipo_id);
            return ['status' => 'success', 'status_code' => 200, 'message' => 'Tipo de órgão apagado com sucesso.'];
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'FOREIGN KEY') !== false) {
                return ['status' => 'error', 'status_code' => 409, 'message' => 'Erro: Não é possível apagar o tipo de órgão. Existem registros dependentes.'];
            }

            $this->logger->novoLog('orgao_tipo_error', $e->getMessage());
            return ['status' => 'error', 'status_code' => 500, 'message' => 'Erro interno do servidor'];
        }
    }
}
