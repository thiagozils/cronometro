CREATE TABLE Competicao (
    id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome varchar (70) NOT NULL,
    descricao VARCHAR(550) NOT NULL,
    data DATETIME NOT NULL
) ENGINE = innodb;