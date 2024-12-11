<?php

namespace GabineteDigital\Models;

use GabineteDigital\Middleware\Database;
use PDO;

class Clipping {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // Criar um novo clipping
    public function criar($dados) {
        $query = "INSERT INTO clipping (clipping_resumo, clipping_titulo, clipping_link, clipping_orgao, clipping_arquivo, clipping_tipo, clipping_criado_por)
                  VALUES (:clipping_resumo, :clipping_titulo, :clipping_link, :clipping_orgao, :clipping_arquivo, :clipping_tipo, :clipping_criado_por)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':clipping_resumo', $dados['clipping_resumo']);
        $stmt->bindParam(':clipping_titulo', $dados['clipping_titulo']);
        $stmt->bindParam(':clipping_link', $dados['clipping_link']);
        $stmt->bindParam(':clipping_orgao', $dados['clipping_orgao'], PDO::PARAM_INT);
        $stmt->bindParam(':clipping_arquivo', $dados['clipping_arquivo']);
        $stmt->bindParam(':clipping_tipo', $dados['clipping_tipo'], PDO::PARAM_INT);
        $stmt->bindParam(':clipping_criado_por', $dados['clipping_criado_por'], PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Atualizar um clipping existente
    public function atualizar($clipping_id, $dados) {
        $query = "UPDATE clipping 
                  SET clipping_resumo = :clipping_resumo, 
                      clipping_titulo = :clipping_titulo,
                      clipping_link = :clipping_link,
                      clipping_orgao = :clipping_orgao,
                      clipping_arquivo = :clipping_arquivo,
                      clipping_tipo = :clipping_tipo
                  WHERE clipping_id = :clipping_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':clipping_resumo', $dados['clipping_resumo']);
        $stmt->bindParam(':clipping_titulo', $dados['clipping_titulo']);
        $stmt->bindParam(':clipping_link', $dados['clipping_link']);
        $stmt->bindParam(':clipping_orgao', $dados['clipping_orgao'], PDO::PARAM_INT);
        $stmt->bindParam(':clipping_arquivo', $dados['clipping_arquivo']);
        $stmt->bindParam(':clipping_tipo', $dados['clipping_tipo'], PDO::PARAM_INT);
        $stmt->bindParam(':clipping_id', $clipping_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Listar todos os clippings
    public function listar() {
        $query = "SELECT * FROM view_clipping ORDER BY clipping_titulo ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar clippings por uma coluna e valor
    public function buscar($coluna, $valor) {
        $query = "SELECT * FROM view_clipping WHERE $coluna = :valor";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':valor', $valor, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Apagar um clipping
    public function apagar($clipping_id) {
        $query = "DELETE FROM clipping WHERE clipping_id = :clipping_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':clipping_id', $clipping_id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
