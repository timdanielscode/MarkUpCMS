/*
    Use to add tables in the database
*/
CREATE TABLE users (
    id int(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    email VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    retype_password VARCHAR(255) NOT NULL,
    created_at DATE NOT NULL,
    updated_at DATE NOT NULL
);

CREATE TABLE roles (
    id int(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

CREATE TABLE user_role (
    id int(11) AUTO_INCREMENT PRIMARY KEY,
    user_id int(11) NOT NULL,
    role_id int(11) NOT NULL
);

INSERT INTO roles (name) VALUES ('normal');
INSERT INTO roles (name) VALUES ('admin');

CREATE TABLE posts (
    id int(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(50) NOT NULL,
    body MEDIUMTEXT NOT NULL,
    created_at DATE NOT NULL,
    updated_at DATE NOT NULL
);

ALTER TABLE posts ADD metaTitle varchar(255) AFTER body;
ALTER TABLE posts ADD metaDescription varchar(255) AFTER metaTitle;