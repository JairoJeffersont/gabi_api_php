<?php

namespace GabineteDigital\Models;

use GabineteDigital\Middleware\Database;
use PDO;

class Proposicao {
    private $conn;
    private $config;


    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
        $this->config = require './src/Configs/config.php';
    }

    public function inserirProposicao($dados) {

        $deleteQuery = "DELETE FROM proposicoes WHERE proposicao_ano = :proposicao_ano";
        $deleteStmt = $this->conn->prepare($deleteQuery);
        $deleteStmt->bindParam(':proposicao_ano', $dados[0]['proposicao_ano'], PDO::PARAM_INT);
        $deleteStmt->execute();

        $query = "INSERT INTO proposicoes (proposicao_id, proposicao_numero, proposicao_titulo, proposicao_ano, proposicao_tipo, proposicao_ementa, proposicao_apresentacao, proposicao_arquivada, proposicao_aprovada) VALUES (:proposicao_id, :proposicao_numero, :proposicao_titulo, :proposicao_ano, :proposicao_tipo, :proposicao_ementa, :proposicao_apresentacao, :proposicao_arquivada, :proposicao_aprovada)";

        $stmt = $this->conn->prepare($query);

        foreach ($dados as $proposicao) {
            $stmt->bindParam(':proposicao_id', $proposicao['proposicao_id'], PDO::PARAM_INT);
            $stmt->bindParam(':proposicao_numero', $proposicao['proposicao_numero'], PDO::PARAM_INT);
            $stmt->bindParam(':proposicao_titulo', $proposicao['proposicao_titulo']);
            $stmt->bindParam(':proposicao_ano', $proposicao['proposicao_ano'], PDO::PARAM_INT);
            $stmt->bindParam(':proposicao_tipo', $proposicao['proposicao_tipo']);
            $stmt->bindParam(':proposicao_ementa', $proposicao['proposicao_ementa']);
            $stmt->bindParam(':proposicao_apresentacao', $proposicao['proposicao_apresentacao']);
            $stmt->bindParam(':proposicao_arquivada', $proposicao['proposicao_arquivada'], PDO::PARAM_INT);
            $stmt->bindParam(':proposicao_aprovada', $proposicao['proposicao_aprovada'], PDO::PARAM_INT);
            $stmt->execute();
        }

        return true;
    }

    public function inserirProposicaoAutor($dados) {


        $deleteQuery = "DELETE FROM proposicoes_autores WHERE proposicao_autor_ano = :proposicao_autor_ano";
        $deleteStmt = $this->conn->prepare($deleteQuery);
        $deleteStmt->bindParam(':proposicao_autor_ano', $dados[0]['proposicao_autor_ano'], PDO::PARAM_INT);
        $deleteStmt->execute();

        $query = "INSERT INTO proposicoes_autores (proposicao_id, proposicao_autor_id, proposicao_autor_nome, proposicao_autor_partido, proposicao_autor_estado, proposicao_autor_proponente, proposicao_autor_assinatura, proposicao_autor_ano) VALUES (:proposicao_id, :proposicao_autor_id, :proposicao_autor_nome, :proposicao_autor_partido, :proposicao_autor_estado, :proposicao_autor_proponente, :proposicao_autor_assinatura, :proposicao_autor_ano)";

        $stmt = $this->conn->prepare($query);

        foreach ($dados as $proposicao) {
            $stmt->bindParam(':proposicao_id', $proposicao['proposicao_id'], PDO::PARAM_INT);
            $stmt->bindParam(':proposicao_autor_id', $proposicao['proposicao_autor_id'], PDO::PARAM_INT);
            $stmt->bindParam(':proposicao_autor_nome', $proposicao['proposicao_autor_nome']);
            $stmt->bindParam(':proposicao_autor_partido', $proposicao['proposicao_autor_partido']);
            $stmt->bindParam(':proposicao_autor_estado', $proposicao['proposicao_autor_estado']);
            $stmt->bindParam(':proposicao_autor_proponente', $proposicao['proposicao_autor_proponente'], PDO::PARAM_INT);
            $stmt->bindParam(':proposicao_autor_assinatura', $proposicao['proposicao_autor_assinatura'], PDO::PARAM_INT);
            $stmt->bindParam(':proposicao_autor_ano', $proposicao['proposicao_autor_ano'], PDO::PARAM_INT);

            $stmt->execute();
        }

        return true;
    }

    public function buscarProposicao($coluna, $id) {

        $query = 'SELECT * FROM view_proposicoes WHERE ' . $coluna . ' = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function proposicoesGabinete($itens, $pagina, $ordenarPor, $ordem, $tipo, $ano, $termo, $arquivada) {
        $pagina = $pagina;
        $itens = $itens;
        $offset = ($pagina - 1) * $itens;
        $assinatura = 1;
        $proponente = 1;


        if (empty($termo)) {
            if (empty($tipo)) {
                $query = "SELECT view_proposicoes.*, (SELECT COUNT(*) FROM view_proposicoes WHERE proposicao_arquivada = :arquivada AND proposicao_autor_id = :proposicao_autor_id AND proposicao_ano = :ano AND proposicao_autor_assinatura = :assinatura AND proposicao_autor_proponente = :proponente) as total FROM view_proposicoes WHERE proposicao_arquivada = :arquivada AND proposicao_autor_id = :proposicao_autor_id AND proposicao_ano = :ano AND proposicao_autor_assinatura = :assinatura AND proposicao_autor_proponente = :proponente ORDER BY $ordenarPor $ordem LIMIT :offset, :itens";
            } else {
                $query = "SELECT view_proposicoes.*, (SELECT COUNT(*) FROM view_proposicoes WHERE proposicao_arquivada = :arquivada AND proposicao_autor_id = :proposicao_autor_id AND proposicao_tipo = :tipo AND proposicao_ano = :ano AND proposicao_autor_assinatura = :assinatura AND proposicao_autor_proponente = :proponente) as total FROM view_proposicoes WHERE proposicao_arquivada = :arquivada AND proposicao_autor_id = :proposicao_autor_id AND proposicao_tipo = :tipo AND proposicao_ano = :ano AND proposicao_autor_assinatura = :assinatura AND proposicao_autor_proponente = :proponente ORDER BY $ordenarPor $ordem LIMIT :offset, :itens";
            }
        } else {
            if (empty($tipo)) {
                $query = "SELECT view_proposicoes.*, (SELECT COUNT(*) FROM view_proposicoes WHERE proposicao_arquivada = :arquivada AND proposicao_autor_id = :proposicao_autor_id AND (proposicao_ementa LIKE :termo OR proposicao_titulo LIKE :termo OR :termo = '') AND proposicao_autor_assinatura = :assinatura AND proposicao_autor_proponente = :proponente) as total FROM view_proposicoes WHERE proposicao_arquivada = :arquivada AND proposicao_autor_id = :proposicao_autor_id AND (proposicao_ementa LIKE :termo OR proposicao_titulo LIKE :termo OR :termo = '') AND proposicao_autor_assinatura = :assinatura AND proposicao_autor_proponente = :proponente ORDER BY $ordenarPor $ordem LIMIT :offset, :itens";
            } else {
                $query = "SELECT view_proposicoes.*, (SELECT COUNT(*) FROM view_proposicoes WHERE proposicao_arquivada = :arquivada AND proposicao_autor_id = :proposicao_autor_id AND proposicao_tipo = :tipo AND (proposicao_ementa LIKE :termo OR proposicao_titulo LIKE :termo OR :termo = '') AND proposicao_autor_assinatura = :assinatura AND proposicao_autor_proponente = :proponente) as total FROM view_proposicoes WHERE proposicao_arquivada = :arquivada AND proposicao_autor_id = :proposicao_autor_id AND proposicao_tipo = :tipo AND (proposicao_ementa LIKE :termo OR proposicao_titulo LIKE :termo OR :termo = '') AND proposicao_autor_assinatura = :assinatura AND proposicao_autor_proponente = :proponente ORDER BY $ordenarPor $ordem LIMIT :offset, :itens";
            }
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':proposicao_autor_id', $this->config['deputado']['id'], PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':itens', $itens, PDO::PARAM_INT);
        $stmt->bindValue(':arquivada', $arquivada, PDO::PARAM_INT);
        $stmt->bindValue(':termo', '%' . $termo . '%', PDO::PARAM_STR);
        if (!empty($ano) && empty($termo)) {
            $stmt->bindValue(':ano', $ano, PDO::PARAM_INT);
        }
        if (!empty($tipo)) {
            $stmt->bindValue(':tipo', $tipo, PDO::PARAM_STR);
        }
        $stmt->bindValue(':assinatura', $assinatura, PDO::PARAM_INT);
        $stmt->bindValue(':proponente', $proponente, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarAutores($id) {
        $query = 'SELECT * FROM proposicoes_autores WHERE proposicao_id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
