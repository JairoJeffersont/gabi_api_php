<?php

namespace GabineteDigital\Models;

use GabineteDigital\Middleware\Database;
use PDO;

class PostagemStatus {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function criar($dados) {
        $query = "INSERT INTO postagem_status 
                  (postagem_status_nome, postagem_status_descricao, postagem_status_criado_por)
                  VALUES (:postagem_status_nome, :postagem_status_descricao, :postagem_status_criado_por)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':postagem_status_nome', $dados['postagem_status_nome']);
        $stmt->bindParam(':postagem_status_descricao', $dados['postagem_status_descricao']);
        $stmt->bindParam(':postagem_status_criado_por', $dados['postagem_status_criado_por'], PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function listar() {
        $query = "SELECT * FROM view_postagens_status ORDER BY postagem_status_nome ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscar($coluna, $valor) {
        $query = "SELECT * FROM view_postagens_status WHERE $coluna = :valor";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':valor', $valor, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function atualizar($postagem_status_id, $dados) {
        $query = "UPDATE postagem_status 
                  SET postagem_status_nome = :postagem_status_nome, 
                      postagem_status_descricao = :postagem_status_descricao
                  WHERE postagem_status_id = :postagem_status_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':postagem_status_nome', $dados['postagem_status_nome']);
        $stmt->bindParam(':postagem_status_descricao', $dados['postagem_status_descricao']);
        $stmt->bindParam(':postagem_status_id', $postagem_status_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function apagar($postagem_status_id) {
        $query = "DELETE FROM postagem_status WHERE postagem_status_id = :postagem_status_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':postagem_status_id', $postagem_status_id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
