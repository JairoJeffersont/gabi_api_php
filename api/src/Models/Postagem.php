<?php

namespace GabineteDigital\Models;

use GabineteDigital\Middleware\Database;
use PDO;

class Postagem {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function criar($dados) {
        $query = "INSERT INTO postagens 
                  (postagem_titulo, postagem_data, postagem_pasta, postagem_informacoes, postagem_midias, postagem_status, postagem_criada_por)
                  VALUES 
                  (:postagem_titulo, :postagem_data, :postagem_pasta, :postagem_informacoes, :postagem_midias, :postagem_status, :postagem_criada_por)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':postagem_titulo', $dados['postagem_titulo']);
        $stmt->bindParam(':postagem_data', $dados['postagem_data']);
        $stmt->bindParam(':postagem_pasta', $dados['postagem_pasta']);
        $stmt->bindParam(':postagem_informacoes', $dados['postagem_informacoes']);
        $stmt->bindParam(':postagem_midias', $dados['postagem_midias']);
        $stmt->bindParam(':postagem_status', $dados['postagem_status'], PDO::PARAM_INT);
        $stmt->bindParam(':postagem_criada_por', $dados['postagem_criada_por'], PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function atualizar($postagem_id, $dados) {
        
        $query = "UPDATE postagens 
                  SET postagem_titulo = :postagem_titulo, 
                      postagem_data = :postagem_data, 
                      postagem_informacoes = :postagem_informacoes, 
                      postagem_midias = :postagem_midias, 
                      postagem_status = :postagem_status 
                  WHERE postagem_id = :postagem_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':postagem_titulo', $dados['postagem_titulo']);
        $stmt->bindParam(':postagem_data', $dados['postagem_data']);
        $stmt->bindParam(':postagem_informacoes', $dados['postagem_informacoes']);
        $stmt->bindParam(':postagem_midias', $dados['postagem_midias']);
        $stmt->bindParam(':postagem_status', $dados['postagem_status'], PDO::PARAM_INT);
        $stmt->bindParam(':postagem_id', $postagem_id, PDO::PARAM_INT);

         $stmt->execute();
    }

    public function listar($ano, $itens, $pagina, $ordem, $ordenarPor, $status, $termo) {

        $pagina = (int)$pagina;
        $itens = (int)$itens;
        $offset = ($pagina - 1) * $itens;

        if ($status == 0) {
            if (empty($termo)) {
                $query = "SELECT view_postagens.*, (SELECT COUNT(postagem_id) FROM view_postagens WHERE YEAR(postagem_criada_em) = :ano) as total FROM view_postagens WHERE YEAR(postagem_criada_em) = :ano ORDER BY {$ordenarPor} {$ordem} LIMIT :offset, :itens";
            } else {
                $query = "SELECT view_postagens.*, (SELECT COUNT(postagem_id) FROM view_postagens WHERE postagem_titulo LIKE :termo OR postagem_informacoes LIKE :termo) as total FROM view_postagens WHERE postagem_titulo LIKE :termo OR postagem_informacoes LIKE :termo ORDER BY {$ordenarPor} {$ordem} LIMIT :offset, :itens";
            }
        } else {
            if (empty($termo)) {
                $query = "SELECT view_postagens.*, (SELECT COUNT(postagem_id) FROM view_postagens WHERE YEAR(postagem_criada_em) = :ano AND postagem_status_id = :status) as total FROM view_postagens WHERE YEAR(postagem_criada_em) = :ano AND postagem_status_id = :status ORDER BY {$ordenarPor} {$ordem} LIMIT :offset, :itens";
            } else {
                $query = "SELECT view_postagens.*, (SELECT COUNT(postagem_id) FROM view_postagens WHERE (postagem_titulo LIKE :termo OR postagem_informacoes LIKE :termo) AND postagem_status_id = :status) as total FROM view_postagens WHERE (postagem_titulo LIKE :termo OR postagem_informacoes LIKE :termo) AND postagem_status_id = :status ORDER BY {$ordenarPor} {$ordem} LIMIT :offset, :itens";
            }
        }

        $stmt = $this->conn->prepare($query);

        if (!empty($ano)) {
            $stmt->bindParam(':ano', $ano, PDO::PARAM_INT);
        }

        if (!empty($termo)) {
            $termoComCuringa = "%{$termo}%";
            $stmt->bindParam(':termo', $termoComCuringa, PDO::PARAM_STR);
        }

        if ($status != 0) {
            $stmt->bindValue(':status', $status, PDO::PARAM_INT);
        }

        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':itens', $itens, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscar($coluna, $valor) {
        $query = "SELECT * FROM view_postagens WHERE $coluna = :valor";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':valor', $valor, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function apagar($postagem_id) {
        $query = "DELETE FROM postagens WHERE postagem_id = :postagem_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':postagem_id', $postagem_id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
