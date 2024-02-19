CREATE DATABASE processo_seletivo;
use processo_seletivo;
CREATE TABLE estoque (
	id INT UNSIGNED auto_increment NOT NULL,
	produto varchar(100) NOT NULL,
	cor varchar(100) NOT NULL,
	tamanho varchar(100) NOT NULL,
	deposito varchar(100) NOT NULL,
	data_disponibilidade DATE NOT NULL,
	quantidade INT UNSIGNED NOT NULL,
	CONSTRAINT estoque_pk PRIMARY KEY (id),
	CONSTRAINT estoque_un UNIQUE KEY (produto,cor,tamanho,deposito,data_disponibilidade)
);
ALTER TABLE estoque ENGINE=InnoDB;
ALTER TABLE estoque DEFAULT CHARSET=utf8mb3;
ALTER TABLE estoque COLLATE=utf8mb3_general_ci;
