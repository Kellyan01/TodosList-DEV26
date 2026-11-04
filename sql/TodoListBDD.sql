CREATE DATABASE IF NOT EXISTS todos CHARSET utf8mb4;
USE todos;
CREATE TABLE IF NOT EXISTS roles(
	id_role INT PRIMARY KEY AUTO_INCREMENT,
    `role` VARCHAR(50) NOT NULL UNIQUE
)ENGINE=innodb;

INSERT INTO roles(`role`) VALUES ('Administrateur'),('Utilisateur');

CREATE TABLE IF NOT EXISTS users(
	id_user INT PRIMARY KEY AUTO_INCREMENT,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(100) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT (CURRENT_TIMESTAMP),
    id_role INT NOT NULL DEFAULT 2,
    CONSTRAINT fk_users_roles FOREIGN KEY (id_role) REFERENCES roles(id_role)
)ENGINE=innodb;

INSERT INTO users(firstname, lastname, email, `password`, id_role) 
	VALUES ('Yoann','Depriester','yoyo@gmail.fr','$2y$12$Fg5.wHHMMOY4ysV8LKcZteoHn/s5NcDyFwnLM0g9R5vEgOoVHvzbK',1);

CREATE TABLE IF NOT EXISTS categories(
	id_category INT PRIMARY KEY AUTO_INCREMENT,
    category VARCHAR(50) NOT NULL UNIQUE
)ENGINE=innodb;

INSERT INTO categories(category) VALUES ("Formation"),("Jeux de Rôle"),("Quotidien"),("Code");

CREATE TABLE IF NOT EXISTS todos(
	id_todo INT PRIMARY KEY AUTO_INCREMENT,
    todo VARCHAR(100) NOT NULL,
    `description`VARCHAR(255),
    created_at DATETIME NOT NULL DEFAULT (CURRENT_TIMESTAMP),
    date_limit DATETIME,
    id_user INT NOT NULL,
    CONSTRAINT fk_todos_users FOREIGN KEY (id_user) REFERENCES users(id_user)
)ENGINE=innodb;

CREATE TABLE IF NOT EXISTS todos_categories(
	id_todo INT NOT NULL,
	id_category INT NOT NULL,
    CONSTRAINT fk_todos_categories_todos FOREIGN KEY (id_todo) REFERENCES todos(id_todo),
    CONSTRAINT fk_todos_categories_categories FOREIGN KEY (id_category) REFERENCES categories(id_category),
    PRIMARY KEY(id_todo, id_category)
)ENGINE=innodb;