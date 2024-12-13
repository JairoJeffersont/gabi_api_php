<?php

namespace GabineteDigital\Controllers;

use GabineteDigital\Middleware\UploadFile;
use GabineteDigital\Models\Oficio;
use GabineteDigital\Middleware\Logger;
use PDOException;

class OficioController {
    private $oficioModel;
    private $logger;
    private $uploadFile;
    private $pasta_oficios;

    public function __construct() {
        $this->oficioModel = new Oficio();
        $this->logger = new Logger();
        $this->uploadFile = new UploadFile();
        $this->pasta_oficios = 'arquivos/oficios/';
    }

    public function criarOficio($dados) {
        $camposObrigatorios = ['oficio_titulo', 'oficio_ano', 'oficio_orgao', 'oficio_criado_por'];

        foreach ($camposObrigatorios as $campo) {
            if (!isset($dados[$campo]) || empty($dados[$campo])) {
                return ['status' => 'bad_request', 'status_code' => 400, 'message' => "O campo '$campo' é obrigatório."];
            }
        }

        if (isset($dados['arquivo']['tmp_name']) && !empty($dados['arquivo']['tmp_name'])) {
            $uploadResult = $this->uploadFile->salvarArquivo($this->pasta_oficios, $dados['arquivo']);

            if ($uploadResult['status'] == 'upload_ok') {
                $dados['oficio_arquivo'] = $this->pasta_oficios . $uploadResult['filename'];
            } else {

                if ($uploadResult['status'] == 'error') {
                    return ['status' => 'error', 'status_code' => 500, 'message' => 'Erro ao fazer upload.'];
                }
            }
        }

        try {
            $this->oficioModel->criar($dados);
            return ['status' => 'success', 'status_code' => 200, 'message' => 'Ofício criado com sucesso.'];
        } catch (PDOException $e) {

            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                return ['status' => 'duplicated', 'status_code' => 409, 'message' => 'Já existe um ofício com esse título.'];
            } else {
                $this->logger->novoLog('oficio_error', $e->getMessage());
                return ['status' => 'error', 'status_code' => 500, 'message' => 'Erro interno do servidor'];
            }
        }
    }

    public function listarOficios($ano = null, $termo = null) {
        try {
            $oficios = $this->oficioModel->listar($ano, $termo);

            if (empty($oficios)) {
                return ['status' => 'empty', 'status_code' => 404, 'message' => 'Nenhum ofício registrado.'];
            }

            return ['status' => 'success', 'status_code' => 200, 'dados' => $oficios];
        } catch (PDOException $e) {
            $this->logger->novoLog('oficio_error', $e->getMessage());
            return ['status' => 'error', 'status_code' => 500, 'message' => 'Erro interno do servidor'];
        }
    }

    public function buscarOficio($coluna, $valor) {
        try {
            $oficio = $this->oficioModel->buscar($coluna, $valor);
            if ($oficio) {
                return ['status' => 'success', 'status_code' => 200, 'dados' => $oficio];
            } else {
                return ['status' => 'not_found', 'status_code' => 404, 'message' => 'Ofício não encontrado.'];
            }
        } catch (PDOException $e) {
            $this->logger->novoLog('oficio_error', $e->getMessage());
            return ['status' => 'error', 'status_code' => 500, 'message' => 'Erro interno do servidor'];
        }
    }

    public function atualizarOficio($oficio_id, $dados) {
        $camposObrigatorios = ['oficio_titulo', 'oficio_ano', 'oficio_orgao'];

        foreach ($camposObrigatorios as $campo) {
            if (!isset($dados[$campo]) || empty($dados[$campo])) {
                return ['status' => 'bad_request', 'status_code' => 400, 'message' => "O campo '$campo' é obrigatório."];
            }
        }

        $result = $this->buscarOficio('oficio_id', $oficio_id);

        if ($result['status'] === 'not_found') {
            return $result;
        }

        if (isset($dados['arquivo']['tmp_name']) && !empty($dados['arquivo']['tmp_name'])) {
            $uploadResult = $this->uploadFile->salvarArquivo($this->pasta_oficios, $dados['arquivo']);
            if ($uploadResult['status'] == 'upload_ok') {
                $dados['oficio_arquivo'] = $this->pasta_oficios . $uploadResult['filename'];
            } else {
                if ($uploadResult['status'] == 'error') {
                    return ['status' => 'error', 'status_code' => 500, 'message' => 'Erro ao fazer upload.'];
                }
            }
        } else {
            $dados['oficio_arquivo'] = null;
        }

        try {
            $this->oficioModel->atualizar($oficio_id, $dados);
            return ['status' => 'success', 'status_code' => 200, 'message' => 'Ofício atualizado com sucesso.'];
        } catch (PDOException $e) {
            $this->logger->novoLog('oficio_error', $e->getMessage());
            return ['status' => 'error', 'status_code' => 500, 'message' => 'Erro interno do servidor'];
        }
    }

    public function apagarOficio($oficio_id) {
        try {
            $result = $this->buscarOficio('oficio_id', $oficio_id);

            if ($result['status'] === 'not_found') {
                return $result;
            }

            unlink($result['dados'][0]['oficio_arquivo']);

            $this->oficioModel->apagar($oficio_id);
            return ['status' => 'success', 'status_code' => 200, 'message' => 'Ofício apagado com sucesso.'];
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'FOREIGN KEY') !== false) {
                return ['status' => 'error', 'status_code' => 400, 'message' => 'Erro: Não é possível apagar o ofício. Existem registros dependentes.'];
            }

            $this->logger->novoLog('oficio_error', $e->getMessage());
            return ['status' => 'error', 'status_code' => 500, 'message' => 'Erro interno do servidor'];
        }
    }
}
