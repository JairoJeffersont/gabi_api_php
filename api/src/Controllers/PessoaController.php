<?php

namespace GabineteDigital\Controllers;

use GabineteDigital\Models\Pessoa;
use GabineteDigital\Middleware\Logger;
use GabineteDigital\Middleware\UploadFile;
use PDOException;

class PessoaController {
    private $pessoaModel;
    private $logger;
    private $uploadFile;
    private $pasta_foto;

    public function __construct() {
        $this->pessoaModel = new Pessoa();
        $this->uploadFile = new UploadFile();
        $this->pasta_foto = 'arquivos/fotos_pessoas/';
        $this->logger = new Logger();
    }

    public function criarPessoa($dados) {
        $camposObrigatorios = ['pessoa_nome', 'pessoa_email', 'pessoa_telefone', 'pessoa_endereco', 'pessoa_estado', 'pessoa_criada_por'];

        if (!filter_var($dados['pessoa_email'], FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'invalid_email', 'status_code' => 400, 'message' => 'Email inválido.'];
        }

        foreach ($camposObrigatorios as $campo) {
            if (!isset($dados[$campo])) {
                return ['status' => 'bad_request', 'status_code' => 400, 'message' => "O campo '$campo' é obrigatório."];
            }
        }

        if (isset($dados['foto']['tmp_name']) && !empty($dados['foto']['tmp_name'])) {
            $uploadResult = $this->uploadFile->salvarArquivo($this->pasta_foto, $dados['foto']);

            if ($uploadResult['status'] == 'upload_ok') {
                $dados['pessoa_foto'] = $this->pasta_foto . $uploadResult['filename'];
            } else {
                return ['status' => 'error', 'status_code' => 500, 'message' => 'Erro ao fazer upload.'];
            }
        }

        try {
            $this->pessoaModel->criar($dados);
            return ['status' => 'success', 'status_code' => 201, 'message' => 'Pessoa inserida com sucesso.'];
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                return ['status' => 'duplicated', 'status_code' => 409, 'message' => 'A pessoa já está cadastrada.'];
            } else {
                $this->logger->novoLog('pessoa_error', $e->getMessage());
                return ['status' => 'error', 'status_code' => 500, 'message' => 'Erro interno do servidor'];
            }
        }
    }

    public function listarPessoas($itens, $pagina, $ordem, $ordenarPor, $termo, $filtro) {
        try {
            $result = $this->pessoaModel->listar($itens, $pagina, $ordem, $ordenarPor, $termo, $filtro);

            $total = (isset($result[0]['total'])) ? $result[0]['total'] : 0;
            $totalPaginas = ceil($total / $itens);

            if (empty($result)) {
                return ['status' => 'empty', 'status_code' => 404, 'message' => 'Nenhuma pessoa encontrada.'];
            }

            return ['status' => 'success', 'status_code' => 200, 'total_paginas' => $totalPaginas, 'dados' => $result];
        } catch (PDOException $e) {
            $this->logger->novoLog('pessoa_error', $e->getMessage());
            return ['status' => 'error', 'status_code' => 500, 'message' => 'Erro interno do servidor.'];
        }
    }

    public function buscarPessoa($coluna, $valor) {
        try {
            $pessoa = $this->pessoaModel->buscar($coluna, $valor);
            if ($pessoa) {
                return ['status' => 'success', 'status_code' => 200, 'dados' => $pessoa];
            } else {
                return ['status' => 'not_found', 'status_code' => 404, 'message' => 'Pessoa não encontrada.'];
            }
        } catch (PDOException $e) {
            $this->logger->novoLog('pessoa_error', $e->getMessage());
            return ['status' => 'error', 'status_code' => 500, 'message' => 'Erro interno do servidor'];
        }
    }

    public function atualizarPessoa($pessoa_id, $dados) {
        if (!filter_var($dados['pessoa_email'], FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'invalid_email', 'status_code' => 400, 'message' => 'Email inválido.'];
        }

        if (isset($dados['foto']['tmp_name']) && !empty($dados['foto']['tmp_name'])) {
            $uploadResult = $this->uploadFile->salvarArquivo($this->pasta_foto, $dados['foto']);
            if ($uploadResult['status'] == 'upload_ok') {
                $dados['pessoa_foto'] = $this->pasta_foto . $uploadResult['filename'];
            } else {
                return ['status' => 'error', 'status_code' => 500, 'message' => 'Erro ao fazer upload.'];
            }
        } else {
            $dados['pessoa_foto'] = null;
        }

        try {
            $this->pessoaModel->atualizar($pessoa_id, $dados);
            return ['status' => 'success', 'status_code' => 200, 'message' => 'Pessoa atualizada com sucesso.'];
        } catch (PDOException $e) {
            $this->logger->novoLog('pessoa_error', $e->getMessage());
            return ['status' => 'error', 'status_code' => 500, 'message' => 'Erro interno do servidor'];
        }
    }

    public function apagarPessoa($pessoa_id) {
        try {
            $result = $this->buscarPessoa('pessoa_id', $pessoa_id);

            if ($result['status'] == 'not_found') {
                return $result;
            }

            $this->pessoaModel->apagar($pessoa_id);
            return ['status' => 'success', 'status_code' => 200, 'message' => 'Pessoa apagada com sucesso.'];
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'FOREIGN KEY') !== false) {
                return ['status' => 'error', 'status_code' => 400, 'message' => 'Erro: Não é possível apagar a pessoa. Existem registros dependentes.'];
            }

            $this->logger->novoLog('pessoa_error', $e->getMessage());
            return ['status' => 'error', 'status_code' => 500, 'message' => 'Erro interno do servidor'];
        }
    }
}
