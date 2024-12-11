/*-----------------------TABELA DE USUÁRIOS-----------------------*/
CREATE TABLE usuarios (
    usuario_id int NOT NULL AUTO_INCREMENT,
    usuario_nome varchar(255) NOT NULL,
    usuario_email varchar(255) NOT NULL UNIQUE,
    usuario_telefone varchar(20) NOT NULL,
    usuario_senha varchar(255) NOT NULL,
    usuario_nivel int NOT NULL,
    usuario_ativo tinyint(1) NOT NULL,
    usuario_aniversario varchar(255) NOT NULL,
    usuario_foto varchar(255) DEFAULT NULL,
    usuario_criado_em timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    usuario_atualizado_em timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (usuario_id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

INSERT INTO usuarios (usuario_id, usuario_nome, usuario_email, usuario_telefone, usuario_senha, usuario_nivel, usuario_ativo, usuario_aniversario) VALUES (1000,'USUÁRIO SISTEMA', 'email@email.com', '000000', 'sd9fasdfasd9fasd89fsad9f8', 1, 1, '2000-01-01');


/*-----------------------TABELAS DE ÓRGÃOS-----------------------*/
CREATE TABLE orgaos_tipos (
    orgao_tipo_id int NOT NULL AUTO_INCREMENT,
    orgao_tipo_nome varchar(255) NOT NULL UNIQUE,
    orgao_tipo_descricao text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
    orgao_tipo_criado_em timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    orgao_tipo_criado_por int NOT NULL,
    orgao_tipo_atualizado_em timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (orgao_tipo_id),
    CONSTRAINT fk_orgao_tipo_criado_por FOREIGN KEY (orgao_tipo_criado_por) REFERENCES usuarios(usuario_id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

INSERT INTO orgaos_tipos (orgao_tipo_id, orgao_tipo_nome, orgao_tipo_descricao, orgao_tipo_criado_por) VALUES (1000, 'Tipo não informado', 'Sem tipo definido', 1000);
INSERT INTO orgaos_tipos (orgao_tipo_id, orgao_tipo_nome, orgao_tipo_descricao, orgao_tipo_criado_por) VALUES (1001, 'Ministério', 'Órgão responsável por uma área específica do governo federal', 1000);
INSERT INTO orgaos_tipos (orgao_tipo_id, orgao_tipo_nome, orgao_tipo_descricao, orgao_tipo_criado_por) VALUES (1002, 'Autarquia Federal', 'Órgão com autonomia administrativa e financeira', 1000);
INSERT INTO orgaos_tipos (orgao_tipo_id, orgao_tipo_nome, orgao_tipo_descricao, orgao_tipo_criado_por) VALUES (1003, 'Empresa Pública Federal', 'Órgão que realiza atividades econômicas como públicos, correios, eletrobras..', 1000);
INSERT INTO orgaos_tipos (orgao_tipo_id, orgao_tipo_nome, orgao_tipo_descricao, orgao_tipo_criado_por) VALUES (1004, 'Universidade Federal', 'Instituição de ensino superior federal', 1000);
INSERT INTO orgaos_tipos (orgao_tipo_id, orgao_tipo_nome, orgao_tipo_descricao, orgao_tipo_criado_por) VALUES (1005, 'Polícia Federal', 'Órgão responsável pela segurança e investigação em âmbito federal', 1000);
INSERT INTO orgaos_tipos (orgao_tipo_id, orgao_tipo_nome, orgao_tipo_descricao, orgao_tipo_criado_por) VALUES (1020, 'Governo Estadual', 'Órgão executivo estadual responsável pela administração de um estado', 1000);
INSERT INTO orgaos_tipos (orgao_tipo_id, orgao_tipo_nome, orgao_tipo_descricao, orgao_tipo_criado_por) VALUES (1021, 'Assembleia Legislativa Estadual', 'Órgão legislativo estadual responsável pela criação de leis estaduais', 1000);
INSERT INTO orgaos_tipos (orgao_tipo_id, orgao_tipo_nome, orgao_tipo_descricao, orgao_tipo_criado_por) VALUES (1025, 'Prefeitura', 'Órgão executivo municipal responsável pela administração local', 1000);
INSERT INTO orgaos_tipos (orgao_tipo_id, orgao_tipo_nome, orgao_tipo_descricao, orgao_tipo_criado_por) VALUES (1026, 'Câmara Municipal', 'Órgão legislativo municipal responsável pela criação de leis municipais', 1000);
INSERT INTO orgaos_tipos (orgao_tipo_id, orgao_tipo_nome, orgao_tipo_descricao, orgao_tipo_criado_por) VALUES (1030, 'Entidade Civil', 'Organização sem fins lucrativos que atua em prol de causas sociais', 1000);
INSERT INTO orgaos_tipos (orgao_tipo_id, orgao_tipo_nome, orgao_tipo_descricao, orgao_tipo_criado_por) VALUES (1031, 'Escola estadual', 'Escolas estaduais', 1000);
INSERT INTO orgaos_tipos (orgao_tipo_id, orgao_tipo_nome, orgao_tipo_descricao, orgao_tipo_criado_por) VALUES (1032, 'Escola municipal', 'Escolas municipais', 1000);
INSERT INTO orgaos_tipos (orgao_tipo_id, orgao_tipo_nome, orgao_tipo_descricao, orgao_tipo_criado_por) VALUES (1033, 'Escola Federal', 'Escolas federais', 1000);
INSERT INTO orgaos_tipos (orgao_tipo_id, orgao_tipo_nome, orgao_tipo_descricao, orgao_tipo_criado_por) VALUES (1034, 'Partido Político', 'Partido Político', 1000);
INSERT INTO orgaos_tipos (orgao_tipo_id, orgao_tipo_nome, orgao_tipo_descricao, orgao_tipo_criado_por) VALUES (1035, 'Câmara Federal', 'Câmara Federal', 1000);
INSERT INTO orgaos_tipos (orgao_tipo_id, orgao_tipo_nome, orgao_tipo_descricao, orgao_tipo_criado_por) VALUES (1036, 'Senado Federal', 'Senado Federal', 1000);
INSERT INTO orgaos_tipos (orgao_tipo_id, orgao_tipo_nome, orgao_tipo_descricao, orgao_tipo_criado_por) VALUES (1038, 'Presidência da Repúlica', 'Presidência da Repúlica', 1000);


CREATE TABLE orgaos (
    orgao_id int NOT NULL AUTO_INCREMENT,
    orgao_nome text NOT NULL,
    orgao_email varchar(255) NOT NULL UNIQUE,
    orgao_telefone varchar(255) DEFAULT NULL,
    orgao_endereco text,
    orgao_bairro text,
    orgao_municipio varchar(255) NOT NULL,
    orgao_estado varchar(255) NOT NULL,
    orgao_cep varchar(255) DEFAULT NULL,
    orgao_tipo int NOT NULL,
    orgao_informacoes text,
    orgao_site text,
    orgao_criado_em timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    orgao_atualizado_em timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    orgao_criado_por int NOT NULL,
    PRIMARY KEY (orgao_id),
    CONSTRAINT fk_orgao_criado_por FOREIGN KEY (orgao_criado_por) REFERENCES usuarios(usuario_id),
    CONSTRAINT fk_orgao_tipo FOREIGN KEY (orgao_tipo) REFERENCES orgaos_tipos(orgao_tipo_id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

INSERT INTO orgaos (orgao_id, orgao_nome, orgao_email, orgao_municipio, orgao_estado, orgao_tipo, orgao_criado_por) VALUES (1000, 'Órgão não informado', 'email@email', 'municipio', 'estado', 1000, 1000);


/*-----------------------TABELAS DE PESSOAS-----------------------*/
CREATE TABLE pessoas_tipos (
    pessoa_tipo_id int NOT NULL AUTO_INCREMENT,
    pessoa_tipo_nome varchar(255) NOT NULL UNIQUE,
    pessoa_tipo_descricao text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
    pessoa_tipo_criado_em timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    pessoa_tipo_criado_por int NOT NULL,
    PRIMARY KEY (pessoa_tipo_id),
    CONSTRAINT fk_pessoa_tipo_criado_por FOREIGN KEY (pessoa_tipo_criado_por) REFERENCES usuarios(usuario_id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

INSERT INTO pessoas_tipos (pessoa_tipo_id, pessoa_tipo_nome, pessoa_tipo_descricao, pessoa_tipo_criado_por) VALUES (1000, 'Sem tipo definido', 'Sem tipo definido', 1000);
/*INSERT INTO pessoas_tipos (pessoa_tipo_id, pessoa_tipo_nome, pessoa_tipo_descricao, pessoa_tipo_criado_por) VALUES (1001, 'Autoridades políticas', 'Prefeitos, vereadores, Ministros...', 1000);*/
INSERT INTO pessoas_tipos (pessoa_tipo_id, pessoa_tipo_nome, pessoa_tipo_descricao, pessoa_tipo_criado_por) VALUES (1002, 'Familiares', 'Familiares do deputado', 1000);
INSERT INTO pessoas_tipos (pessoa_tipo_id, pessoa_tipo_nome, pessoa_tipo_descricao, pessoa_tipo_criado_por) VALUES (1003, 'Empresários', 'Donos de empresa', 1000);
INSERT INTO pessoas_tipos (pessoa_tipo_id, pessoa_tipo_nome, pessoa_tipo_descricao, pessoa_tipo_criado_por) VALUES (1004, 'Eleitores', 'Eleitores em geral', 1000);
INSERT INTO pessoas_tipos (pessoa_tipo_id, pessoa_tipo_nome, pessoa_tipo_descricao, pessoa_tipo_criado_por) VALUES (1005, 'Imprensa', 'Jornalistas, diretores de jornais, assessoria', 1000);
INSERT INTO pessoas_tipos (pessoa_tipo_id, pessoa_tipo_nome, pessoa_tipo_descricao, pessoa_tipo_criado_por) VALUES (1006, 'Site', 'Pessoas registradas no site', 1000);
INSERT INTO pessoas_tipos (pessoa_tipo_id, pessoa_tipo_nome, pessoa_tipo_descricao, pessoa_tipo_criado_por) VALUES (1007, 'Amigos', 'Amigos pessoais do deputado', 1000);
INSERT INTO pessoas_tipos (pessoa_tipo_id, pessoa_tipo_nome, pessoa_tipo_descricao, pessoa_tipo_criado_por) VALUES (1008, 'Deputado Federal', 'Deputado Federal', 1000);
INSERT INTO pessoas_tipos (pessoa_tipo_id, pessoa_tipo_nome, pessoa_tipo_descricao, pessoa_tipo_criado_por) VALUES (1009, 'Senador', 'Senador', 1000);



CREATE TABLE pessoas_profissoes (
    pessoas_profissoes_id int NOT NULL AUTO_INCREMENT,
    pessoas_profissoes_nome varchar(255) NOT NULL UNIQUE,
    pessoas_profissoes_descricao text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
    pessoas_profissoes_criado_em timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    pessoas_profissoes_criado_por int NOT NULL,
    PRIMARY KEY (pessoas_profissoes_id),
    CONSTRAINT fk_pessoas_profissoes_criado_por FOREIGN KEY (pessoas_profissoes_criado_por) REFERENCES usuarios(usuario_id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

INSERT INTO pessoas_profissoes (pessoas_profissoes_id, pessoas_profissoes_nome, pessoas_profissoes_descricao,pessoas_profissoes_criado_por) VALUES (1000, 'Profissão não informada', 'Profissão não informada', 1000);
INSERT INTO pessoas_profissoes (pessoas_profissoes_id, pessoas_profissoes_nome, pessoas_profissoes_descricao, pessoas_profissoes_criado_por) 
VALUES 
(1001, 'Médico', 'Profissional responsável por diagnosticar e tratar doenças', 1000),
(1002, 'Engenheiro de Software', 'Profissional especializado em desenvolvimento e manutenção de sistemas de software', 1000),
(1003, 'Advogado', 'Profissional que oferece consultoria e representação legal', 1000),
(1004, 'Professor', 'Profissional responsável por ministrar aulas e orientar estudantes', 1000),
(1005, 'Enfermeiro', 'Profissional da saúde que cuida e monitoriza pacientes', 1000),
(1006, 'Arquiteto', 'Profissional que projeta e planeja edifícios e espaços urbanos', 1000),
(1007, 'Contador', 'Profissional que gerencia contas e prepara relatórios financeiros', 1000),
(1008, 'Designer Gráfico', 'Profissional especializado em criação visual e design', 1000),
(1009, 'Jornalista', 'Profissional que coleta, escreve e distribui notícias', 1000),
(1010, 'Chef de Cozinha', 'Profissional que planeja, dirige e prepara refeições em restaurantes', 1000);
INSERT INTO pessoas_profissoes (pessoas_profissoes_id, pessoas_profissoes_nome, pessoas_profissoes_descricao, pessoas_profissoes_criado_por) 
VALUES 
(1011, 'Psicólogo', 'Profissional que realiza avaliações psicológicas e oferece terapia', 1000),
(1012, 'Fisioterapeuta', 'Profissional que ajuda na reabilitação física de pacientes', 1000),
(1013, 'Veterinário', 'Profissional responsável pelo cuidado e tratamento de animais', 1000),
(1014, 'Fotógrafo', 'Profissional que captura e edita imagens fotográficas', 1000),
(1015, 'Tradutor', 'Profissional que converte textos de um idioma para outro', 1000),
(1016, 'Administrador', 'Profissional que gerencia operações e processos em uma organização', 1000),
(1017, 'Biólogo', 'Profissional que estuda organismos vivos e seus ecossistemas', 1000),
(1018, 'Economista', 'Profissional que analisa dados econômicos e desenvolve modelos de previsão', 1000),
(1019, 'Programador', 'Profissional que escreve e testa códigos de software', 1000),
(1020, 'Cientista de Dados', 'Profissional que analisa e interpreta grandes volumes de dados', 1000);
INSERT INTO pessoas_profissoes (pessoas_profissoes_id, pessoas_profissoes_nome, pessoas_profissoes_descricao, pessoas_profissoes_criado_por) 
VALUES 
(1021, 'Analista de Marketing', 'Profissional que desenvolve e implementa estratégias de marketing', 1000),
(1022, 'Engenheiro Civil', 'Profissional que projeta e constrói infraestrutura como pontes e edifícios', 1000),
(1023, 'Cozinheiro', 'Profissional que prepara e cozinha alimentos em ambientes como restaurantes', 1000),
(1024, 'Social Media', 'Profissional que gerencia e cria conteúdo para redes sociais', 1000),
(1025, 'Auditor', 'Profissional que examina e avalia registros financeiros e operacionais', 1000),
(1026, 'Técnico em Informática', 'Profissional que presta suporte técnico e manutenção de hardware e software', 1000),
(1027, 'Líder de Projeto', 'Profissional que coordena e supervisiona projetos para garantir a conclusão bem-sucedida', 1000),
(1028, 'Químico', 'Profissional que realiza pesquisas e experimentos químicos', 1000),
(1029, 'Gerente de Recursos Humanos', 'Profissional responsável pela gestão de pessoal e políticas de recursos humanos', 1000),
(1030, 'Engenheiro Eletricista', 'Profissional que projeta e implementa sistemas elétricos e eletrônicos', 1000);
INSERT INTO pessoas_profissoes (pessoas_profissoes_id, pessoas_profissoes_nome, pessoas_profissoes_descricao, pessoas_profissoes_criado_por) 
VALUES 
(1031, 'Designer de Moda', 'Profissional que cria e desenvolve roupas e acessórios', 1000),
(1032, 'Engenheiro Mecânico', 'Profissional que projeta e desenvolve sistemas mecânicos e máquinas', 1000),
(1033, 'Web Designer', 'Profissional que cria e mantém layouts e interfaces de sites', 1000),
(1034, 'Geólogo', 'Profissional que estuda a composição e estrutura da Terra', 1000),
(1035, 'Segurança da Informação', 'Profissional que protege sistemas e dados contra ameaças e ataques', 1000),
(1036, 'Consultor Financeiro', 'Profissional que oferece orientação sobre gestão e planejamento financeiro', 1000),
(1037, 'Artista Plástico', 'Profissional que cria obras de arte em diversos meios e materiais', 1000),
(1038, 'Logístico', 'Profissional que coordena e gerencia operações de logística e cadeia de suprimentos', 1000),
(1039, 'Fonoaudiólogo', 'Profissional que avalia e trata problemas de comunicação e linguagem', 1000),
(1040, 'Corretor de Imóveis', 'Profissional que facilita a compra, venda e aluguel de propriedades', 1000);



CREATE TABLE pessoas (
    pessoa_id int NOT NULL AUTO_INCREMENT,
    pessoa_nome varchar(255) NOT NULL,
    pessoa_aniversario varchar(255) NOT NULL,
    pessoa_email varchar(255) NOT NULL UNIQUE,
    pessoa_telefone varchar(255) DEFAULT NULL,
    pessoa_endereco text DEFAULT NULL,
    pessoa_bairro text,
    pessoa_municipio varchar(255) NOT NULL,
    pessoa_estado varchar(255) NOT NULL,
    pessoa_cep varchar(255) DEFAULT NULL,
    pessoa_sexo varchar(255) DEFAULT NULL,
    pessoa_facebook varchar(255) DEFAULT NULL,
    pessoa_instagram varchar(255) DEFAULT NULL,
    pessoa_x varchar(255) DEFAULT NULL,
    pessoa_informacoes text DEFAULT NULL,
    pessoa_profissao int NOT NULL,
    pessoa_cargo varchar(255) DEFAULT NULL,
    pessoa_tipo int NOT NULL,
    pessoa_orgao int NOT NULL,
    pessoa_foto text DEFAULT NULL,
    pessoa_criada_em timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    pessoa_atualizada_em timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    pessoa_criada_por int NOT NULL,
    PRIMARY KEY (pessoa_id),
    CONSTRAINT fk_pessoa_criada_por FOREIGN KEY (pessoa_criada_por) REFERENCES usuarios(usuario_id),
    CONSTRAINT fk_pessoa_tipo FOREIGN KEY (pessoa_tipo) REFERENCES pessoas_tipos(pessoa_tipo_id),
    CONSTRAINT fk_pessoa_profissao FOREIGN KEY (pessoa_profissao) REFERENCES pessoas_profissoes(pessoas_profissoes_id),
    CONSTRAINT fk_pessoa_orgao FOREIGN KEY (pessoa_orgao) REFERENCES orgaos(orgao_id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;


/*-----------------------TABELA NOTAS TÉCNICAS-----------------------*/
CREATE TABLE notas_tecnicas (
    nota_id int NOT NULL AUTO_INCREMENT,
    nota_proposicao int NOT NULL UNIQUE,
    nota_titulo varchar(255) NOT NULL,
    nota_resumo text NOT NULL,
    nota_texto text NOT NULL,
    nota_criada_por int NOT NULL,
    nota_criada_em timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    nota_atualizada_em timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (nota_id),
    CONSTRAINT fk_nota_criada_por FOREIGN KEY (nota_criada_por) REFERENCES usuarios(usuario_id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;


/*-----------------------TABELA CLIPPING-----------------------*/
CREATE TABLE clipping_tipos (
    clipping_tipo_id int NOT NULL AUTO_INCREMENT,
    clipping_tipo_nome varchar(255) NOT NULL UNIQUE,
    clipping_tipo_descricao text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
    clipping_tipo_criado_em timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    clipping_tipo_criado_por int NOT NULL,
    PRIMARY KEY (clipping_tipo_id),
    CONSTRAINT fk_clipping_tipo_criado_por FOREIGN KEY (clipping_tipo_criado_por) REFERENCES usuarios(usuario_id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

INSERT INTO clipping_tipos (clipping_tipo_id, clipping_tipo_nome, clipping_tipo_descricao, clipping_tipo_criado_por) VALUES (1000, 'Sem tipo definido', 'Sem tipo definido', 1000);
INSERT INTO clipping_tipos (clipping_tipo_id, clipping_tipo_nome, clipping_tipo_descricao, clipping_tipo_criado_por) VALUES (1001, 'Notícia Jornalística', 'Matéria Jornalistica de site, revista, blog...', 1000);
INSERT INTO clipping_tipos (clipping_tipo_id, clipping_tipo_nome, clipping_tipo_descricao, clipping_tipo_criado_por) VALUES (1002, 'Post de rede social', 'Post de instagram, facebook....', 1000);

CREATE TABLE clipping (
    clipping_id INT NOT NULL AUTO_INCREMENT,
    clipping_resumo TEXT NOT NULL,
    clipping_titulo TEXT NOT NULL,
    clipping_link VARCHAR(255) NOT NULL UNIQUE,
    clipping_orgao INT NOT NULL,
    clipping_arquivo VARCHAR(255),
    clipping_tipo INT NOT NULL, 
    clipping_criado_por INT NOT NULL,
    clipping_criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    clipping_atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (clipping_id),
    CONSTRAINT fk_clipping_criado_por FOREIGN KEY (clipping_criado_por) REFERENCES usuarios(usuario_id),
    CONSTRAINT fk_clipping_orgao FOREIGN KEY (clipping_orgao) REFERENCES orgaos(orgao_id),
    CONSTRAINT fk_clipping_tipo FOREIGN KEY (clipping_tipo) REFERENCES clipping_tipos(clipping_tipo_id)
)ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;


/*-----------------------TABELA OFICIOS-----------------------*/
CREATE TABLE oficios(
    oficio_id INT NOT NULL AUTO_INCREMENT,
    oficio_titulo VARCHAR(255) NOT NULL UNIQUE,
    oficio_resumo text,
    oficio_arquivo text,
    oficio_ano int,
    oficio_orgao INT NOT NULL,
    oficio_criado_por INT NOT NULL,
    oficio_criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    oficio_atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY(oficio_id),
    CONSTRAINT fk_oficio_criado_por FOREIGN KEY (oficio_criado_por) REFERENCES usuarios(usuario_id),
    CONSTRAINT fk_oficio_orgao FOREIGN KEY (oficio_orgao) REFERENCES orgaos(orgao_id)
)ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

/*-----------------------TABELAS POSTAGENS-----------------------*/
CREATE TABLE postagem_status(
    postagem_status_id INT NOT NULL AUTO_INCREMENT,
    postagem_status_nome VARCHAR(255) NOT NULL UNIQUE,
    postagem_status_descricao TEXT NULL,
    postagem_status_criado_por INT NOT NULL,
    postagem_status_criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    postagem_status_atualizada_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY(postagem_status_id),
    CONSTRAINT fk_postagem_status_criado_por FOREIGN KEY (postagem_status_criado_por) REFERENCES usuarios(usuario_id)
)ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

INSERT INTO postagem_status (postagem_status_id, postagem_status_nome, postagem_status_descricao,postagem_status_criado_por) VALUES (1000, 'Iniciada', 'Iniciada uma postagem', 1000);
INSERT INTO postagem_status (postagem_status_id, postagem_status_nome, postagem_status_descricao,postagem_status_criado_por) VALUES (1001, 'Em produção', 'Postagem em fase de produção', 1000);
INSERT INTO postagem_status (postagem_status_id, postagem_status_nome, postagem_status_descricao,postagem_status_criado_por) VALUES (1002, 'Em aprovação', 'Postagem em fase de aprovação', 1000);
INSERT INTO postagem_status (postagem_status_id, postagem_status_nome, postagem_status_descricao,postagem_status_criado_por) VALUES (1003, 'Aprovada', 'Postagem aprovada', 1000);
INSERT INTO postagem_status (postagem_status_id, postagem_status_nome, postagem_status_descricao,postagem_status_criado_por) VALUES (1004, 'Postada', 'Postagem postada', 1000);

CREATE TABLE postagens(
    postagem_id INT NOT NULL AUTO_INCREMENT,
    postagem_titulo VARCHAR(255) NOT NULL UNIQUE,
    postagem_data TIMESTAMP NULL,
    postagem_pasta TEXT, 
    postagem_informacoes TEXT,
    postagem_midias TEXT,  
    postagem_status INT,
    postagem_criada_por INT NOT NULL,
    postagem_criada_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    postagem_atualizada_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY(postagem_id),
    CONSTRAINT fk_postagem_criada_por FOREIGN KEY (postagem_criada_por) REFERENCES usuarios(usuario_id),
    CONSTRAINT fk_postagem_status FOREIGN KEY (postagem_status) REFERENCES postagem_status(postagem_status_id)
)ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;


CREATE TABLE proposicoes (
    proposicao_id INT NOT NULL,
    proposicao_numero INT NOT NULL,
    proposicao_titulo VARCHAR(255) NOT NULL,
    proposicao_ano INT NOT NULL,
    proposicao_tipo VARCHAR(10) NOT NULL,
    proposicao_ementa TEXT NOT NULL,
    proposicao_apresentacao DATETIME NULL DEFAULT NULL,
    proposicao_arquivada TINYINT(1) NOT NULL DEFAULT 0,
    proposicao_aprovada TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (proposicao_id),
    INDEX (proposicao_titulo)
) ENGINE=InnoDB 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_general_ci;


CREATE TABLE proposicoes_autores (
    proposicao_id INT NOT NULL,
    proposicao_autor_id INT NOT NULL,
    proposicao_autor_nome TEXT NOT NULL,
    proposicao_autor_partido VARCHAR(255) DEFAULT NULL,
    proposicao_autor_estado VARCHAR(255) DEFAULT NULL,
    proposicao_autor_proponente INT NOT NULL,
    proposicao_autor_assinatura INT NOT NULL,
    proposicao_autor_ano INT NOT NULL,
    INDEX (proposicao_id, proposicao_autor_id)
) ENGINE=InnoDB 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_general_ci;

/*-----------------------VIEWS-----------------------*/
CREATE VIEW view_orgaos AS SELECT orgaos.*, orgaos_tipos.orgao_tipo_nome, usuarios.usuario_nome FROM orgaos INNER JOIN orgaos_tipos ON orgaos.orgao_tipo = orgaos_tipos.orgao_tipo_id INNER JOIN usuarios ON orgaos.orgao_criado_por = usuarios.usuario_id;
CREATE VIEW view_pessoas AS SELECT pessoas.*, pessoas_profissoes.pessoas_profissoes_nome,  pessoas_tipos.pessoa_tipo_nome, orgaos.orgao_nome, usuarios.usuario_nome FROM pessoas INNER JOIN pessoas_tipos ON pessoas.pessoa_tipo = pessoas_tipos.pessoa_tipo_id INNER JOIN orgaos ON pessoas.pessoa_orgao = orgaos.orgao_id INNER JOIN pessoas_profissoes ON pessoas.pessoa_profissao = pessoas_profissoes.pessoas_profissoes_id INNER JOIN usuarios ON pessoas.pessoa_criada_por = usuarios.usuario_id;
CREATE VIEW view_clipping AS SELECT clipping.*, clipping_tipos.clipping_tipo_nome, orgaos.orgao_nome, usuarios.usuario_nome FROM clipping INNER JOIN orgaos ON clipping.clipping_orgao = orgaos.orgao_id INNER JOIN usuarios ON clipping_criado_por = usuarios.usuario_id INNER JOIN clipping_tipos ON clipping.clipping_tipo = clipping_tipos.clipping_tipo_id;
CREATE VIEW view_clipping_tipos AS SELECT * FROM clipping_tipos INNER JOIN usuarios ON clipping_tipos.clipping_tipo_criado_por = usuarios.usuario_id;
CREATE VIEW view_oficios AS SELECT oficios.*, orgaos.orgao_nome, orgaos.orgao_id, usuarios.usuario_nome FROM oficios INNER JOIN orgaos ON oficios.oficio_orgao = orgaos.orgao_id INNER JOIN usuarios ON oficios.oficio_criado_por = usuarios.usuario_id;
CREATE VIEW view_notas_tecnicas AS SELECT notas_tecnicas.*, usuarios.usuario_nome FROM notas_tecnicas INNER JOIN usuarios ON notas_tecnicas.nota_criada_por = usuarios.usuario_id;
CREATE VIEW view_orgaos_tipos AS SELECT orgaos_tipos.*, usuarios.usuario_nome FROM orgaos_tipos INNER JOIN usuarios on orgaos_tipos.orgao_tipo_criado_por = usuarios.usuario_id;
CREATE VIEW view_pessoas_tipos AS SELECT pessoas_tipos.*, usuarios.usuario_nome FROM pessoas_tipos INNER JOIN usuarios ON pessoas_tipos.pessoa_tipo_criado_por = usuarios.usuario_id ORDER BY pessoas_tipos.pessoa_tipo_nome ASC;
CREATE VIEW view_profissoes AS SELECT pessoas_profissoes.*, usuarios.usuario_nome FROM pessoas_profissoes INNER JOIN usuarios ON pessoas_profissoes.pessoas_profissoes_criado_por = usuarios.usuario_id ORDER BY pessoas_profissoes.pessoas_profissoes_nome ASC;
CREATE VIEW view_postagens AS SELECT postagens.*, usuarios.usuario_nome, postagem_status.postagem_status_id, postagem_status.postagem_status_nome, postagem_status.postagem_status_descricao FROM postagens INNER JOIN usuarios ON postagens.postagem_criada_por = usuarios.usuario_id INNER JOIN postagem_status ON postagens.postagem_status = postagem_status.postagem_status_id;
CREATE VIEW view_postagens_status AS SELECT postagem_status.*, usuarios.usuario_nome FROM postagem_status INNER JOIN usuarios ON postagem_status.postagem_status_criado_por = usuarios.usuario_id ORDER BY postagem_status.postagem_status_nome ASC;
CREATE VIEW view_tipo_clipping AS SELECT clipping_tipos.*, usuarios.usuario_nome FROM clipping_tipos INNER JOIN usuarios ON clipping_tipos.clipping_tipo_criado_por = usuarios.usuario_id;
CREATE VIEW view_proposicoes AS SELECT proposicoes_autores.proposicao_id AS proposicao_id, proposicoes_autores.proposicao_autor_id AS proposicao_autor_id, proposicoes_autores.proposicao_autor_nome AS proposicao_autor_nome, proposicoes_autores.proposicao_autor_partido AS proposicao_autor_partido, proposicoes_autores.proposicao_autor_estado AS proposicao_autor_estado, proposicoes_autores.proposicao_autor_proponente AS proposicao_autor_proponente, proposicoes_autores.proposicao_autor_assinatura AS proposicao_autor_assinatura, proposicoes.proposicao_numero AS proposicao_numero, proposicoes.proposicao_titulo AS proposicao_titulo, proposicoes.proposicao_tipo AS proposicao_tipo, proposicoes.proposicao_ementa AS proposicao_ementa, proposicoes.proposicao_ano AS proposicao_ano, proposicoes.proposicao_apresentacao AS proposicao_apresentacao, proposicoes.proposicao_arquivada AS proposicao_arquivada, proposicoes.proposicao_aprovada AS proposicao_aprovada FROM proposicoes_autores JOIN proposicoes ON proposicoes_autores.proposicao_id = proposicoes.proposicao_id;
