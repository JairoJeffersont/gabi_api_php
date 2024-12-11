<?php

namespace GabineteDigital\Models;

use GabineteDigital\Middleware\Database;
use PDO;

class Oficio {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function criar($dados) {
        $query = "INSERT INTO oficios (oficio_titulo, oficio_resumo, oficio_arquivo, oficio_ano, oficio_orgao, oficio_criado_por)
                  VALUES (:oficio_titulo, :oficio_resumo, :oficio_arquivo, :oficio_ano, :oficio_orgao, :oficio_criado_por)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':oficio_titulo', $dados['oficio_titulo']);
        $stmt->bindParam(':oficio_resumo', $dados['oficio_resumo']);
        $stmt->bindParam(':oficio_arquivo', $dados['oficio_arquivo']);
        $stmt->bindParam(':oficio_ano', $dados['oficio_ano'], PDO::PARAM_INT);
        $stmt->bindParam(':oficio_orgao', $dados['oficio_orgao'], PDO::PARAM_INT);
        $stmt->bindParam(':oficio_criado_por', $dados['oficio_criado_por'], PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function atualizar($oficio_id, $dados) {


        if (isset($dados['oficio_arquivo']) && !empty($dados['oficio_arquivo'])) {
            $query = "UPDATE oficios 
            SET oficio_titulo = :oficio_titulo, 
                oficio_resumo = :oficio_resumo, 
                oficio_arquivo = :oficio_arquivo, 
                oficio_ano = :oficio_ano,
                oficio_orgao = :oficio_orgao
            WHERE oficio_id = :oficio_id";
        } else {
            $query = "UPDATE oficios 
            SET oficio_titulo = :oficio_titulo, 
                oficio_resumo = :oficio_resumo, 
                oficio_ano = :oficio_ano,
                oficio_orgao = :oficio_orgao
            WHERE oficio_id = :oficio_id";
        }


        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':oficio_titulo', $dados['oficio_titulo']);
        $stmt->bindParam(':oficio_resumo', $dados['oficio_resumo']);
        if (isset($dados['oficio_arquivo']) && !empty($dados['oficio_arquivo'])) {
            $stmt->bindParam(':oficio_arquivo', $dados['oficio_arquivo']);
        }
        $stmt->bindParam(':oficio_ano', $dados['oficio_ano'], PDO::PARAM_INT);
        $stmt->bindParam(':oficio_orgao', $dados['oficio_orgao'], PDO::PARAM_INT);
        $stmt->bindParam(':oficio_id', $oficio_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function listar($ano, $termo) {
        if (empty($ano) && empty($termo)) {
            $query = "SELECT * FROM view_oficios ORDER BY oficio_titulo DESC";
        } else if (!empty($ano) && empty($termo)) {
            $query = "SELECT * FROM view_oficios WHERE oficio_ano = :oficio_ano ORDER BY oficio_titulo DESC";
        } else if (!empty($ano) && !empty($termo)) {
            $query = "SELECT * FROM view_oficios WHERE oficio_ano = :oficio_ano AND oficio_resumo LIKE :termo ORDER BY oficio_titulo DESC";
        } else if (empty($ano) && !empty($termo)) {
            $query = "SELECT * FROM view_oficios WHERE oficio_resumo LIKE :termo ORDER BY oficio_titulo DESC";
        }


        $stmt = $this->conn->prepare($query);

        if ($ano != '') {
            $stmt->bindParam(':oficio_ano', $ano);
        }
        if ($termo != '') {
            $termo = '%' . $termo . '%';
            $stmt->bindParam(':termo', $termo);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function buscar($coluna, $valor) {
        $query = "SELECT * FROM view_oficios WHERE $coluna = :valor";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':valor', $valor, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function apagar($oficio_id) {
        $query = "DELETE FROM oficios WHERE oficio_id = :oficio_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':oficio_id', $oficio_id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
