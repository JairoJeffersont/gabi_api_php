<?php

namespace GabineteDigital\Models;

use GabineteDigital\Middleware\Database;
use PDO;

class PessoaTipo {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function criar($dados) {
        $query = "INSERT INTO pessoas_tipos (pessoa_tipo_nome, pessoa_tipo_descricao, pessoa_tipo_criado_por)
                  VALUES (:pessoa_tipo_nome, :pessoa_tipo_descricao, :pessoa_tipo_criado_por)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':pessoa_tipo_nome', $dados['pessoa_tipo_nome']);
        $stmt->bindParam(':pessoa_tipo_descricao', $dados['pessoa_tipo_descricao']);
        $stmt->bindParam(':pessoa_tipo_criado_por', $dados['pessoa_tipo_criado_por'], PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function atualizar($pessoa_tipo_id, $dados) {
        $query = "UPDATE pessoas_tipos 
                  SET pessoa_tipo_nome = :pessoa_tipo_nome, 
                      pessoa_tipo_descricao = :pessoa_tipo_descricao
                  WHERE pessoa_tipo_id = :pessoa_tipo_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':pessoa_tipo_nome', $dados['pessoa_tipo_nome']);
        $stmt->bindParam(':pessoa_tipo_descricao', $dados['pessoa_tipo_descricao']);
        $stmt->bindParam(':pessoa_tipo_id', $pessoa_tipo_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function listar() {
        $query = "SELECT * FROM view_pessoas_tipos ORDER BY pessoa_tipo_nome ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscar($coluna, $valor) {
        $query = "SELECT * FROM view_pessoas_tipos WHERE $coluna = :valor";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':valor', $valor, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function apagar($pessoa_tipo_id) {
        $query = "DELETE FROM pessoas_tipos WHERE pessoa_tipo_id = :pessoa_tipo_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':pessoa_tipo_id', $pessoa_tipo_id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
