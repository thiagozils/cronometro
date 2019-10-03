CREATE TABLE Competicao (
    id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome varchar (70) NOT NULL,
    descricao VARCHAR(550) NOT NULL,
    data DATETIME NOT NULL
);


CREATE TABLE Competidor (
    id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome varchar (70) NOT NULL
);


CREATE TABLE CompeticaoCompetidor (
    id int(10) unsigned NOT NULL AUTO_INCREMENT,
    competicao_id INT(10) NOT NULL,
    competidor_id INT(10) NOT NULL,
    PRIMARY KEY (id),
    KEY competicao_id (competicao_id),
    KEY competidor_id (competidor_id)
);


CREATE TABLE Volta (
    id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    competicao_id INT(10) NOT NULL,
    competidor_id INT(10) NOT NULL,
    valida  TINYINT (1) NOT NULL,
    tempo TIME NOT NULL,
    data DATETIME NOT NULL,
    KEY competicao_id (competicao_id),
    KEY competidor_id (competidor_id)
);
