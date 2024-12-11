<?php

namespace GabineteDigital\Models;

use GabineteDigital\Middleware\Database;
use PDO;

class Profissao {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function criar($dados) {
        $query = "INSERT INTO pessoas_profissoes (pessoas_profissoes_nome, pessoas_profissoes_descricao, pessoas_profissoes_criado_por)
                  VALUES (:pessoas_profissoes_nome, :pessoas_profissoes_descricao, :pessoas_profissoes_criado_por)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':pessoas_profissoes_nome', $dados['pessoas_profissoes_nome']);
        $stmt->bindParam(':pessoas_profissoes_descricao', $dados['pessoas_profissoes_descricao']);
        $stmt->bindParam(':pessoas_profissoes_criado_por', $dados['pessoas_profissoes_criado_por']);

        return $stmt->execute();
    }

    public function atualizar($pessoas_profissoes_id, $dados) {
        $query = "UPDATE pessoas_profissoes 
                  SET pessoas_profissoes_nome = :pessoas_profissoes_nome, 
                      pessoas_profissoes_descricao = :pessoas_profissoes_descricao
                  WHERE pessoas_profissoes_id = :pessoas_profissoes_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':pessoas_profissoes_nome', $dados['pessoas_profissoes_nome']);
        $stmt->bindParam(':pessoas_profissoes_descricao', $dados['pessoas_profissoes_descricao']);
        $stmt->bindParam(':pessoas_profissoes_id', $pessoas_profissoes_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function listar() {
        $query = "SELECT * FROM view_profissoes ORDER BY pessoas_profissoes_nome ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscar($coluna, $valor) {
        $query = "SELECT * FROM view_profissoes WHERE $coluna = :valor";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':valor', $valor, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function apagar($pessoas_profissoes_id) {
        $query = "DELETE FROM pessoas_profissoes WHERE pessoas_profissoes_id = :pessoas_profissoes_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':pessoas_profissoes_id', $pessoas_profissoes_id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
