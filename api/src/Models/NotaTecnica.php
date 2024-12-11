<?php

namespace GabineteDigital\Models;

use GabineteDigital\Middleware\Database;
use PDO;

class NotaTecnica {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function criar($dados) {
        $query = "INSERT INTO notas_tecnicas 
                  (nota_proposicao, nota_titulo, nota_resumo, nota_texto, nota_criada_por) 
                  VALUES 
                  (:nota_proposicao, :nota_titulo, :nota_resumo, :nota_texto, :nota_criada_por)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':nota_proposicao', $dados['nota_proposicao'], PDO::PARAM_INT);
        $stmt->bindParam(':nota_titulo', $dados['nota_titulo']);
        $stmt->bindParam(':nota_resumo', $dados['nota_resumo']);
        $stmt->bindParam(':nota_texto', $dados['nota_texto']);
        $stmt->bindParam(':nota_criada_por', $dados['nota_criada_por'], PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function atualizar($nota_id, $dados) {
        $query = "UPDATE notas_tecnicas 
                  SET nota_proposicao = :nota_proposicao, 
                      nota_titulo = :nota_titulo, 
                      nota_resumo = :nota_resumo, 
                      nota_texto = :nota_texto
                  WHERE nota_proposicao = :nota_proposicao";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':nota_proposicao', $dados['nota_proposicao'], PDO::PARAM_INT);
        $stmt->bindParam(':nota_titulo', $dados['nota_titulo']);
        $stmt->bindParam(':nota_resumo', $dados['nota_resumo']);
        $stmt->bindParam(':nota_texto', $dados['nota_texto']);
        $stmt->bindParam(':nota_id', $nota_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function listar() {
        $query = "SELECT * FROM view_notas_tecnicas ORDER BY nota_criada_em DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscar($coluna, $valor) {
        $query = "SELECT * FROM view_notas_tecnicas WHERE $coluna = :valor";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':valor', $valor, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function apagar($nota_proposicao) {
        $query = "DELETE FROM notas_tecnicas WHERE nota_proposicao = :nota_proposicao";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nota_proposicao', $nota_proposicao, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
