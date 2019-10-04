CREATE TABLE CompeticaoCompetidor (
    id_competicao INT(10) UNSIGNED ,
    id_competidor INT(10) UNSIGNED ,
    FOREIGN KEY (id_competicao) REFERENCES Competicao(id),
    FOREIGN KEY (id_competidor) REFERENCES Competidor(id),
    PRIMARY KEY(id_competicao, id_competidor)
)ENGINE = innodb;