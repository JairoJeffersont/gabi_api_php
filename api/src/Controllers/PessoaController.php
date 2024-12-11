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
            return ['status' => 'invalid_email', 'message' => 'Email inválido.'];
        }

        foreach ($camposObrigatorios as $campo) {
            if (!isset($dados[$campo])) {
                return ['status' => 'bad_request', 'message' => "O campo '$campo' é obrigatório."];
            }
        }

        if (isset($dados['foto']['tmp_name']) && !empty($dados['foto']['tmp_name'])) {
            $uploadResult = $this->uploadFile->salvarArquivo($this->pasta_foto, $dados['foto']);

            if ($uploadResult['status'] == 'upload_ok') {
                $dados['pessoa_foto'] = $this->pasta_foto . $uploadResult['filename'];
            } else {

                if ($uploadResult['status'] == 'error') {
                    return ['status' => 'error', 'message' => 'Erro ao fazer upload.'];
                }
            }
        }

        try {
            $this->pessoaModel->criar($dados);
            return ['status' => 'success', 'message' => 'Pessoa inserida com sucesso.'];
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                return ['status' => 'duplicated', 'message' => 'A pessoa já está cadastrada.'];
            } else {
                $this->logger->novoLog('pessoa_error', $e->getMessage());
                return ['status' => 'error', 'message' => 'Erro interno do servidor'];
            }
        }
    }

    public function listarPessoas($itens, $pagina, $ordem, $ordenarPor, $termo, $filtro) {
        try {
            $result = $this->pessoaModel->listar($itens, $pagina, $ordem, $ordenarPor, $termo, $filtro);

            $total = (isset($result[0]['total'])) ? $result[0]['total'] : 0;
            $totalPaginas = ceil($total / $itens);

            if (empty($result)) {
                return ['status' => 'empty', 'message' => 'Nenhuma pessoa encontrada.'];
            }

            return ['status' => 'success', 'total_paginas' => $totalPaginas, 'dados' => $result];
        } catch (PDOException $e) {
            $this->logger->novoLog('pessoa_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno do servidor.'];
        }
    }

    public function buscarPessoa($coluna, $valor) {
        try {
            $pessoa = $this->pessoaModel->buscar($coluna, $valor);
            if ($pessoa) {
                return ['status' => 'success', 'dados' => $pessoa];
            } else {
                return ['status' => 'not_found', 'message' => 'Pessoa não encontrada.'];
            }
        } catch (PDOException $e) {
            $this->logger->novoLog('pessoa_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno do servidor'];
        }
    }

    public function atualizarPessoa($pessoa_id, $dados) {
        if (!filter_var($dados['pessoa_email'], FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'invalid_email', 'message' => 'Email inválido.'];
        }

        if (isset($dados['foto']['tmp_name']) && !empty($dados['foto']['tmp_name'])) {
            $uploadResult = $this->uploadFile->salvarArquivo($this->pasta_foto, $dados['foto']);
            if ($uploadResult['status'] == 'upload_ok') {
                $dados['pessoa_foto'] = $this->pasta_foto . $uploadResult['filename'];
            } else {
                if ($uploadResult['status'] == 'error') {
                    return ['status' => 'error', 'message' => 'Erro ao fazer upload.'];
                }
            }
        } else {
            $dados['pessoa_foto'] = null;
        }

        try {
            $this->pessoaModel->atualizar($pessoa_id, $dados);
            return ['status' => 'success', 'message' => 'Pessoa atualizada com sucesso.'];
        } catch (PDOException $e) {
            $this->logger->novoLog('pessoa_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno do servidor'];
        }
    }

    public function apagarPessoa($pessoa_id) {
        try {
            $result = $this->buscarPessoa('pessoa_id', $pessoa_id);

            if ($result['status'] == 'not_found') {
                return $result;
            }

            $this->pessoaModel->apagar($pessoa_id);
            return ['status' => 'success', 'message' => 'Pessoa apagada com sucesso.'];
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'FOREIGN KEY') !== false) {
                return ['status' => 'error', 'message' => 'Erro: Não é possível apagar a pessoa. Existem registros dependentes.'];
            }

            $this->logger->novoLog('pessoa_error', $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro interno do servidor'];
        }
    }
}
