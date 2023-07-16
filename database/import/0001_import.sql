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
ALTER TABLE posts ADD slug varchar(255) AFTER title;
ALTER TABLE posts ADD author varchar(255) AFTER body;
ALTER TABLE posts ADD date_created_at varchar(10) AFTER metaDescription;
ALTER TABLE posts ADD time_created_at varchar(5) AFTER date_created_at;
ALTER TABLE posts ADD date_updated_at varchar(10) AFTER time_created_at;
ALTER TABLE posts ADD time_updated_at varchar(5) AFTER date_updated_at;


CREATE TABLE css (
    id int(11) AUTO_INCREMENT PRIMARY KEY,
    file_name VARCHAR(30) NOT NULL,
    extension VARCHAR(30) NOT NULL,
    date_created_at VARCHAR(10) NOT NULL,
    time_created_at VARCHAR(5) NOT NULL,
    date_updated_at VARCHAR(10) NOT NULL,
    time_updated_at VARCHAR(5) NOT NULL
);

CREATE TABLE js (
    id int(11) AUTO_INCREMENT PRIMARY KEY,
    file_name VARCHAR(30) NOT NULL,
    extension VARCHAR(30) NOT NULL,
    date_created_at VARCHAR(10) NOT NULL,
    time_created_at VARCHAR(5) NOT NULL,
    date_updated_at VARCHAR(10) NOT NULL,
    time_updated_at VARCHAR(5) NOT NULL
);

CREATE TABLE media (
    id int(11) AUTO_INCREMENT PRIMARY KEY,
    media_title VARCHAR(50) NOT NULL,
    media_description VARCHAR(100) NOT NULL,
    media_filename VARCHAR(50) NOT NULL,
    media_filetype VARCHAR(15) NOT NULL,
    media_filesize VARCHAR(8) NOT NULL,
    date_created_at VARCHAR(10) NOT NULL,
    time_created_at VARCHAR(5) NOT NULL,
    date_updated_at VARCHAR(10) NOT NULL,
    time_updated_at VARCHAR(5) NOT NULL
);

RENAME TABLE posts TO pages;

CREATE TABLE menus (
    id int(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(50) NOT NULL,
    content MEDIUMTEXT NOT NULL,
    position VARCHAR(10) NOT NULL,
    date_created_at VARCHAR(10) NOT NULL,
    time_created_at VARCHAR(5) NOT NULL,
    date_updated_at VARCHAR(10) NOT NULL,
    time_updated_at VARCHAR(5) NOT NULL
);

ALTER TABLE menus ADD author varchar(50) AFTER position;

CREATE TABLE categories (
    id int(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(50) NOT NULL,
    slug VARCHAR(50) NOT NULL,
    category_description VARCHAR(100) NOT NULL,
    date_created_at VARCHAR(10) NOT NULL,
    time_created_at VARCHAR(5) NOT NULL,
    date_updated_at VARCHAR(10) NOT NULL,
    time_updated_at VARCHAR(5) NOT NULL
);

CREATE TABLE category_page (
    category_id int(11) NOT NULL,
    page_id int(11) NOT NULL
);

ALTER TABLE menus ADD ordering int(11) AFTER position;

ALTER TABLE users RENAME COLUMN retype_password TO retypePassword;

ALTER TABLE pages DROP COLUMN created_at;
ALTER TABLE pages DROP COLUMN updated_at;

CREATE TABLE category_sub (
    category_id int(11) NOT NULl,
    sub_id int(11) NOT NULL
);

ALTER TABLE pages ADD metaKeywords varchar(255) AFTER metaDescription;