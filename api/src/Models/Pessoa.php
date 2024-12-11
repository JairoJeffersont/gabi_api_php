<?php

namespace GabineteDigital\Models;

use GabineteDigital\Middleware\Database;
use PDO;


class Pessoa {
    private $conn;
    private $config;


    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
        $this->config = require './src/Configs/config.php';

    }

    public function criar($dados) {
        $query = "INSERT INTO pessoas (
                        pessoa_nome, pessoa_aniversario, pessoa_email, pessoa_telefone, pessoa_endereco, 
                        pessoa_bairro, pessoa_municipio, pessoa_estado, pessoa_cep, pessoa_sexo, 
                        pessoa_facebook, pessoa_instagram, pessoa_x, pessoa_informacoes, pessoa_profissao, 
                        pessoa_cargo, pessoa_tipo, pessoa_orgao, pessoa_foto, pessoa_criada_por
                    ) VALUES (
                        :pessoa_nome, :pessoa_aniversario, :pessoa_email, :pessoa_telefone, :pessoa_endereco, 
                        :pessoa_bairro, :pessoa_municipio, :pessoa_estado, :pessoa_cep, :pessoa_sexo, 
                        :pessoa_facebook, :pessoa_instagram, :pessoa_x, :pessoa_informacoes, :pessoa_profissao, 
                        :pessoa_cargo, :pessoa_tipo, :pessoa_orgao, :pessoa_foto, :pessoa_criada_por
                    )";

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':pessoa_nome', $dados['pessoa_nome']);
        $stmt->bindParam(':pessoa_aniversario', $dados['pessoa_aniversario']);
        $stmt->bindParam(':pessoa_email', $dados['pessoa_email']);
        $stmt->bindParam(':pessoa_telefone', $dados['pessoa_telefone']);
        $stmt->bindParam(':pessoa_endereco', $dados['pessoa_endereco']);
        $stmt->bindParam(':pessoa_bairro', $dados['pessoa_bairro']);
        $stmt->bindParam(':pessoa_municipio', $dados['pessoa_municipio']);
        $stmt->bindParam(':pessoa_estado', $dados['pessoa_estado']);
        $stmt->bindParam(':pessoa_cep', $dados['pessoa_cep']);
        $stmt->bindParam(':pessoa_sexo', $dados['pessoa_sexo']);
        $stmt->bindParam(':pessoa_facebook', $dados['pessoa_facebook']);
        $stmt->bindParam(':pessoa_instagram', $dados['pessoa_instagram']);
        $stmt->bindParam(':pessoa_x', $dados['pessoa_x']);
        $stmt->bindParam(':pessoa_informacoes', $dados['pessoa_informacoes']);
        $stmt->bindParam(':pessoa_profissao', $dados['pessoa_profissao'], PDO::PARAM_INT);
        $stmt->bindParam(':pessoa_cargo', $dados['pessoa_cargo']);
        $stmt->bindParam(':pessoa_tipo', $dados['pessoa_tipo'], PDO::PARAM_INT);
        $stmt->bindParam(':pessoa_orgao', $dados['pessoa_orgao'], PDO::PARAM_INT);
        $stmt->bindParam(':pessoa_foto', $dados['pessoa_foto']);
        $stmt->bindParam(':pessoa_criada_por', $dados['pessoa_criada_por'], PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function atualizar($pessoa_id, $dados) {


        if (isset($dados['pessoa_foto']) && !empty($dados['pessoa_foto'])) {
            $query = "UPDATE pessoas SET 
            pessoa_nome = :pessoa_nome, pessoa_aniversario = :pessoa_aniversario, pessoa_email = :pessoa_email,
            pessoa_telefone = :pessoa_telefone, pessoa_endereco = :pessoa_endereco, pessoa_bairro = :pessoa_bairro, 
            pessoa_municipio = :pessoa_municipio, pessoa_estado = :pessoa_estado, pessoa_cep = :pessoa_cep, 
            pessoa_sexo = :pessoa_sexo, pessoa_facebook = :pessoa_facebook, pessoa_instagram = :pessoa_instagram, 
            pessoa_x = :pessoa_x, pessoa_informacoes = :pessoa_informacoes, pessoa_profissao = :pessoa_profissao, 
            pessoa_cargo = :pessoa_cargo, pessoa_tipo = :pessoa_tipo, pessoa_orgao = :pessoa_orgao, 
            pessoa_foto = :pessoa_foto WHERE pessoa_id = :pessoa_id";
        } else {
            $query = "UPDATE pessoas SET 
            pessoa_nome = :pessoa_nome, pessoa_aniversario = :pessoa_aniversario, pessoa_email = :pessoa_email,
            pessoa_telefone = :pessoa_telefone, pessoa_endereco = :pessoa_endereco, pessoa_bairro = :pessoa_bairro, 
            pessoa_municipio = :pessoa_municipio, pessoa_estado = :pessoa_estado, pessoa_cep = :pessoa_cep, 
            pessoa_sexo = :pessoa_sexo, pessoa_facebook = :pessoa_facebook, pessoa_instagram = :pessoa_instagram, 
            pessoa_x = :pessoa_x, pessoa_informacoes = :pessoa_informacoes, pessoa_profissao = :pessoa_profissao, 
            pessoa_cargo = :pessoa_cargo, pessoa_tipo = :pessoa_tipo, pessoa_orgao = :pessoa_orgao
            WHERE pessoa_id = :pessoa_id";
        }


        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':pessoa_nome', $dados['pessoa_nome']);
        $stmt->bindParam(':pessoa_aniversario', $dados['pessoa_aniversario']);
        $stmt->bindParam(':pessoa_email', $dados['pessoa_email']);
        $stmt->bindParam(':pessoa_telefone', $dados['pessoa_telefone']);
        $stmt->bindParam(':pessoa_endereco', $dados['pessoa_endereco']);
        $stmt->bindParam(':pessoa_bairro', $dados['pessoa_bairro']);
        $stmt->bindParam(':pessoa_municipio', $dados['pessoa_municipio']);
        $stmt->bindParam(':pessoa_estado', $dados['pessoa_estado']);
        $stmt->bindParam(':pessoa_cep', $dados['pessoa_cep']);
        $stmt->bindParam(':pessoa_sexo', $dados['pessoa_sexo']);
        $stmt->bindParam(':pessoa_facebook', $dados['pessoa_facebook']);
        $stmt->bindParam(':pessoa_instagram', $dados['pessoa_instagram']);
        $stmt->bindParam(':pessoa_x', $dados['pessoa_x']);
        $stmt->bindParam(':pessoa_informacoes', $dados['pessoa_informacoes']);
        $stmt->bindParam(':pessoa_profissao', $dados['pessoa_profissao'], PDO::PARAM_INT);
        $stmt->bindParam(':pessoa_cargo', $dados['pessoa_cargo']);
        $stmt->bindParam(':pessoa_tipo', $dados['pessoa_tipo'], PDO::PARAM_INT);
        $stmt->bindParam(':pessoa_orgao', $dados['pessoa_orgao'], PDO::PARAM_INT);
        if (isset($dados['pessoa_foto']) && !empty($dados['pessoa_foto'])) {
            $stmt->bindParam(':pessoa_foto', $dados['pessoa_foto'], PDO::PARAM_STR);
        }
        $stmt->bindParam(':pessoa_id', $pessoa_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function listar($itens, $pagina, $ordem, $ordenarPor, $termo, $filtro) {
        $pagina = (int)$pagina;
        $itens = (int)$itens;
        $offset = ($pagina - 1) * $itens;

        if ($termo === null) {
            if ($filtro) {
                $query = "SELECT view_pessoas.*, 
                                 (SELECT COUNT(*) FROM pessoas WHERE pessoa_id <> 1000 AND pessoa_estado = '".$this->config['deputado']['estado']."') AS total
                          FROM view_pessoas
                          WHERE pessoa_id <> 1000 AND pessoa_estado = '".$this->config['deputado']['estado']."'
                          ORDER BY $ordenarPor $ordem LIMIT :offset, :itens";
            } else {
                $query = "SELECT view_pessoas.*, 
                                 (SELECT COUNT(*) FROM pessoas WHERE pessoa_id <> 1000) AS total
                          FROM view_pessoas
                          WHERE pessoa_id <> 1000
                          ORDER BY $ordenarPor $ordem LIMIT :offset, :itens";
            }
        } else {
            if ($filtro) {
                $query = "SELECT view_pessoas.*, 
                                 (SELECT COUNT(*) FROM pessoas WHERE pessoa_id <> 1000 AND pessoa_nome LIKE :termo AND pessoa_estado = '".$this->config['deputado']['estado']."') AS total
                          FROM view_pessoas
                          WHERE pessoa_id <> 1000 AND pessoa_nome LIKE :termo AND pessoa_estado = '".$this->config['deputado']['estado']."'
                          ORDER BY $ordenarPor $ordem LIMIT :offset, :itens";
                $termo = '%' . $termo . '%';
            } else {
                $query = "SELECT view_pessoas.*, 
                                 (SELECT COUNT(*) FROM pessoas WHERE pessoa_id <> 1000 AND pessoa_nome LIKE :termo) AS total
                          FROM view_pessoas
                          WHERE pessoa_id <> 1000 AND pessoa_nome LIKE :termo
                          ORDER BY $ordenarPor $ordem LIMIT :offset, :itens";
                $termo = '%' . $termo . '%';
            }
        }


        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':itens', $itens, PDO::PARAM_INT);

        if ($termo !== null) {
            $stmt->bindValue(':termo', $termo, PDO::PARAM_STR);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscar($coluna, $valor) {
        $query = "SELECT * FROM view_pessoas WHERE $coluna = :valor";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':valor', $valor, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function apagar($pessoa_id) {
        $query = "DELETE FROM pessoas WHERE pessoa_id = :pessoa_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':pessoa_id', $pessoa_id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
