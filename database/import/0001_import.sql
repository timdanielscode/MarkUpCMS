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

CREATE TABLE css_page (
    page_id int(11) NOT NULl,
    css_id int(11) NOT NULL
);

CREATE TABLE js_page (
    page_id int(11) NOT NULl,
    js_id int(11) NOT NULL
);

ALTER TABLE pages ADD has_content bit AFTER body;
ALTER TABLE pages DROP COLUMN has_content;
ALTER TABLE pages ADD has_content TINYINT AFTER body;
ALTER TABLE menus ADD has_content TINYINT AFTER content;
ALTER TABLE css ADD has_content TINYINT AFTER extension;
ALTER TABLE js ADD has_content TINYINT AFTER extension;

ALTER TABLE pages ADD removed TINYINT AFTER metaKeywords;
ALTER TABLE menus ADD removed TINYINT AFTER author;
ALTER TABLE css ADD removed TINYINT AFTER has_content;
ALTER TABLE js ADD removed TINYINT AFTER has_content;

ALTER TABLE media ADD media_folder VARCHAR(50) AFTER media_filename;
ALTER TABLE media DROP COLUMN media_title;
ALTER TABLE media DROP COLUMN media_description;
ALTER TABLE media ADD media_description VARCHAR(100) AFTER media_filesize; 

CREATE TABLE mediaFolders (
    id int(11) AUTO_INCREMENT PRIMARY KEY,
    folder_name varchar(49) NOT NULL
);

ALTER TABLE pages ADD date_time DATETIME AFTER removed;

ALTER TABLE pages ADD created_at DATETIME AFTER removed;
ALTER TABLE pages ADD updated_at DATETIME AFTER removed;

ALTER TABLE pages DROP COLUMN date_created_at;
ALTER TABLE pages DROP COLUMN time_created_at;
ALTER TABLE pages DROP COLUMN date_updated_at;
ALTER TABLE pages DROP COLUMN time_updated_at;
ALTER TABLE pages DROP COLUMN date_time;

ALTER TABLE menus DROP COLUMN date_created_at;
ALTER TABLE menus DROP COLUMN time_created_at;
ALTER TABLE menus DROP COLUMN date_updated_at;
ALTER TABLE menus DROP COLUMN time_updated_at;

ALTER TABLE menus ADD created_at DATETIME AFTER removed;
ALTER TABLE menus ADD updated_at DATETIME AFTER removed;

ALTER TABLE css DROP COLUMN date_created_at;
ALTER TABLE css DROP COLUMN time_created_at;
ALTER TABLE css DROP COLUMN date_updated_at;
ALTER TABLE css DROP COLUMN time_updated_at;

ALTER TABLE css ADD created_at DATETIME AFTER removed;
ALTER TABLE css ADD updated_at DATETIME AFTER removed;

ALTER TABLE js DROP COLUMN date_created_at;
ALTER TABLE js DROP COLUMN time_created_at;
ALTER TABLE js DROP COLUMN date_updated_at;
ALTER TABLE js DROP COLUMN time_updated_at;

ALTER TABLE js ADD created_at DATETIME AFTER removed;
ALTER TABLE js ADD updated_at DATETIME AFTER removed;

ALTER TABLE media DROP COLUMN date_created_at;
ALTER TABLE media DROP COLUMN time_created_at;
ALTER TABLE media DROP COLUMN date_updated_at;
ALTER TABLE media DROP COLUMN time_updated_at;

ALTER TABLE media ADD created_at DATETIME AFTER media_description;
ALTER TABLE media ADD updated_at DATETIME AFTER media_description;

ALTER TABLE categories DROP COLUMN date_created_at;
ALTER TABLE categories DROP COLUMN time_created_at;
ALTER TABLE categories DROP COLUMN date_updated_at;
ALTER TABLE categories DROP COLUMN time_updated_at;

ALTER TABLE categories ADD created_at DATETIME AFTER category_description;
ALTER TABLE categories ADD updated_at DATETIME AFTER category_description;

CREATE TABLE widgets (
    id int(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(50) NOT NULL,
    content MEDIUMTEXT NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
);

ALTER TABLE widgets ADD has_content TINYINT AFTER content;
ALTER TABLE widgets ADD removed TINYINT AFTER has_content;
ALTER TABLE widgets ADD author varchar(50) AFTER removed;

CREATE TABLE page_widget (
    page_id int(11) NOT NULl,
    widget_id int(11) NOT NULL
);

CREATE TABLE websiteSlug (
    id int(11) AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(50) NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
);

CREATE TABLE cdn (
    id int(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(50) NOT NULL,
    content MEDIUMTEXT NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
);

CREATE TABLE cdn_page (
    page_id int(11) NOT NULl,
    cdn_id int(11) NOT NULL
);

ALTER TABLE cdn ADD has_content TINYINT AFTER content;
ALTER TABLE cdn ADD removed TINYINT AFTER has_content;
ALTER TABLE cdn ADD author varchar(50) AFTER removed;

ALTER TABLE categories ADD author VARCHAR(50) AFTER category_description;
ALTER TABLE css ADD author VARCHAR(50) AFTER extension;
ALTER TABLE js ADD author VARCHAR(50) AFTER extension;