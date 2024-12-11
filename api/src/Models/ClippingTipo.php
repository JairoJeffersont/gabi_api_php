<?php

namespace GabineteDigital\Models;

use GabineteDigital\Middleware\Database;
use PDO;

class ClippingTipo {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function criar($dados) {
        $query = "INSERT INTO clipping_tipos (clipping_tipo_nome, clipping_tipo_descricao, clipping_tipo_criado_por)
                  VALUES (:clipping_tipo_nome, :clipping_tipo_descricao, :clipping_tipo_criado_por)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':clipping_tipo_nome', $dados['clipping_tipo_nome']);
        $stmt->bindParam(':clipping_tipo_descricao', $dados['clipping_tipo_descricao']);
        $stmt->bindParam(':clipping_tipo_criado_por', $dados['clipping_tipo_criado_por'], PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function atualizar($clipping_tipo_id, $dados) {
        $query = "UPDATE clipping_tipos 
                  SET clipping_tipo_nome = :clipping_tipo_nome, 
                      clipping_tipo_descricao = :clipping_tipo_descricao
                  WHERE clipping_tipo_id = :clipping_tipo_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':clipping_tipo_nome', $dados['clipping_tipo_nome']);
        $stmt->bindParam(':clipping_tipo_descricao', $dados['clipping_tipo_descricao']);
        $stmt->bindParam(':clipping_tipo_id', $clipping_tipo_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function listar() {
        $query = "SELECT * FROM view_clipping_tipos ORDER BY clipping_tipo_nome ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscar($coluna, $valor) {
        $query = "SELECT * FROM view_clipping_tipos WHERE $coluna = :valor";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':valor', $valor, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function apagar($clipping_tipo_id) {
        $query = "DELETE FROM clipping_tipos WHERE clipping_tipo_id = :clipping_tipo_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':clipping_tipo_id', $clipping_tipo_id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
