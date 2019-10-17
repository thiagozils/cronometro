CREATE TABLE Volta (
    id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_competicao INT(10) UNSIGNED NOT NULL,
    id_competidor INT(10) UNSIGNED NOT NULL,
    valida  TINYINT (1) NOT NULL,
    tempo TIME NOT NULL,
    tomada INT(3) NOT NULL,
    tentativa INT(3) NOT NULL,
    data DATETIME NOT NULL,
    FOREIGN KEY (id_competicao) REFERENCES Competicao(id),
    FOREIGN KEY (id_competidor) REFERENCES Competidor(id)
);