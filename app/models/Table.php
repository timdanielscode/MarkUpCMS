<?php 

/** 
 * To create necessary tables (during installation process)
 */

namespace app\models;

use database\DB;

class Table {

    public static function create() {

        DB::try()->raw('CREATE TABLE IF NOT EXISTS users (
            
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(30) NOT NULL,
            email VARCHAR(50) NOT NULL,
            password VARCHAR(255) NOT NULL,
            removed TINYINT NOT NULL,
            created_at DATE NOT NULL,
            updated_at DATE NOT NULL

        )')->run();

        DB::try()->raw('CREATE TABLE IF NOT EXISTS roles (
                            
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(30) NOT NULL

        )')->run();

        DB::try()->raw('CREATE TABLE IF NOT EXISTS user_role (
                                    
            user_id INT(11) NOT NULL,
            role_id INT(11) NOT NULL

        )')->run();

        DB::try()->raw('CREATE TABLE IF NOT EXISTS pages (
                    
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(50) NOT NULL,
            slug VARCHAR(255) NOT NULL,
            body MEDIUMTEXT NOT NULL,
            has_content TINYINT NOT NULL,
            author VARCHAR(255) NOT NULL,
            metaTitle VARCHAR(255),
            metaDescription VARCHAR(255),
            metaKeywords VARCHAR(255),
            removed TINYINT NOT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL

        )')->run();

        DB::try()->raw('CREATE TABLE IF NOT EXISTS category_page (
                            
            category_id INT(11) NOT NULL,
            page_id INT(11) NOT NULL

        )')->run();

        DB::try()->raw('CREATE TABLE IF NOT EXISTS meta_page (
                                    
            page_id INT(11) NOT NULL,
            meta_id INT(11) NOT NULL

        )')->run();

        DB::try()->raw('CREATE TABLE IF NOT EXISTS css_page (
                                            
            page_id INT(11) NOT NULL,
            css_id INT(11) NOT NULL

        )')->run();

        DB::try()->raw('CREATE TABLE IF NOT EXISTS js_page (
                                                    
            page_id INT(11) NOT NULL,
            js_id INT(11) NOT NULL

        )')->run();

        DB::try()->raw('CREATE TABLE IF NOT EXISTS page_widget (
                                                            
            page_id INT(11) NOT NULL,
            widget_id INT(11) NOT NULL

        )')->run();

        DB::try()->raw('CREATE TABLE IF NOT EXISTS categories (
                            
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(50) NOT NULL,
            slug VARCHAR(50) NOT NULL,
            category_description VARCHAR(100) NOT NULL,
            author VARCHAR(255) NOT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL

        )')->run();

        DB::try()->raw('CREATE TABLE IF NOT EXISTS category_sub (
                                    
            category_id INT(11) NOT NULL,
            sub_id INT(11) NOT NULL

        )')->run();

        DB::try()->raw('CREATE TABLE IF NOT EXISTS metas (
                                    
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(50) NOT NULL,
            content MEDIUMTEXT NOT NULL,
            has_content TINYINT NOT NULL,
            removed TINYINT NOT NULL,
            author VARCHAR(50) NOT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL

        )')->run();

        DB::try()->raw('CREATE TABLE IF NOT EXISTS css (
                                            
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            file_name VARCHAR(30) NOT NULL,
            extension VARCHAR(30) NOT NULL,
            author VARCHAR(50) NOT NULL,
            has_content TINYINT NOT NULL,
            removed TINYINT NOT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL

        )')->run();

        DB::try()->raw('CREATE TABLE IF NOT EXISTS js (
                                                    
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            file_name VARCHAR(30) NOT NULL,
            extension VARCHAR(30) NOT NULL,
            author VARCHAR(50) NOT NULL,
            has_content TINYINT NOT NULL,
            removed TINYINT NOT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL

        )')->run();

        DB::try()->raw('CREATE TABLE IF NOT EXISTS media (
                                                            
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            media_filename VARCHAR(50) NOT NULL,
            media_folder VARCHAR(50) NOT NULL,
            media_filetype VARCHAR(15) NOT NULL,
            media_filesize VARCHAR(8) NOT NULL,
            media_description VARCHAR(100) NOT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL

        )')->run();

        DB::try()->raw('CREATE TABLE IF NOT EXISTS mediaFolders (
                                                                    
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            folder_name VARCHAR(50) NOT NULL

        )')->run();

        DB::try()->raw('CREATE TABLE IF NOT EXISTS menus (
                                                            
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(50) NOT NULL,
            content MEDIUMTEXT NOT NULL,
            has_content TINYINT NOT NULL,
            position VARCHAR(10) NOT NULL,
            ordering INT(11) NOT NULL,
            author VARCHAR(50) NOT NULL,
            removed TINYINT NOT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL

        )')->run();

        DB::try()->raw('CREATE TABLE IF NOT EXISTS websiteSlug (
                                                                    
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            slug VARCHAR(50) NOT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL

        )')->run();

        DB::try()->raw('CREATE TABLE IF NOT EXISTS widgets (
                                                                    
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(50) NOT NULL,
            content MEDIUMTEXT NOT NULL,
            has_content TINYINT NOT NULL,
            author VARCHAR(50) NOT NULL,
            removed TINYINT NOT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL

        )')->run();
    }
}