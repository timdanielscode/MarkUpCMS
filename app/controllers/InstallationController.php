<?php

namespace app\controllers;

use core\Csrf;
use validation\Rules;
use app\models\User;
use app\models\UserRole;
use app\models\Roles;
use database\DB;

class InstallationController extends Controller {

    private $_configDatabasePath = "../config/database/config.ini";

    public function createUser() {

        $data['rules'] = [];

        return $this->view('admin/installation/index', $data);
    }

    public function storeUser($request) {

        if(submitted("submit") && Csrf::validate(Csrf::token("get"), post("token")) ) {
                
            $rules = new Rules();  
                    
            if($rules->installationRules()->validated() ) {

                $this->insertRoles(1, 'normal');
                $this->insertRoles(2, 'admin');

                User::insert([
                    
                    'username' => $request["username"], 
                    'email' => $request["email"], 
                    'password' => password_hash($request["password"], PASSWORD_DEFAULT),
                    'created_at' => date("Y-m-d H:i:s"), 
                    'updated_at' => date("Y-m-d H:i:s")
                ]); 

                $lastId = DB::try()->getLastId('users')->first();

                UserRole::insert([
    
                    'user_id' => $lastId['id'],
                    'role_id' => 2
                ]);

                redirect('/login');

            } else {
                         
                $data["rules"] = $rules->errors;
                return $this->view("admin/installation/index", $data);
            }
        }
    }

    private function insertRoles($id, $roleType) {

        $role = DB::try()->select('id')->from('roles')->where('id', '=', $id)->and('name', '=', $roleType)->first();

        if(empty($role)) {

            Roles::insert(['id' => $id, 'name'  =>  $roleType]);
        } 
    }

    public function databaseSetup() {

        return $this->view('admin/installation/database');
    }
    
    public function createConnection($request) {

        if(file_exists($this->_configDatabasePath) === false) {

            $file = fopen($this->_configDatabasePath, "w");

            $content = 
                "host=" . $request['host'] . "\r\n" .
                "db=" . $request['database'] . "\r\n" .
                "user=" . $request['username'] . "\r\n" .
                "password=" . $request['password'] . "\r\n";
    
            fwrite($file, $content);
            fclose($file);
        } 

        $this->createTables();

        redirect('/');
    }

    private function createTables() {

        DB::try()->raw('CREATE TABLE IF NOT EXISTS users (
            
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(30) NOT NULL,
            email VARCHAR(50) NOT NULL,
            password VARCHAR(255) NOT NULL,
            created_at DATE NOT NULL,
            updated_at DATE NOT NULL

        )')->run();

        DB::try()->raw('CREATE TABLE IF NOT EXISTS roles (
                    
            id INT(11) NOT NULL,
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
            metaTitle VARCHAR(255) NOT NULL,
            metaDescription VARCHAR(255) NOT NULL,
            metaKeywords VARCHAR(255) NOT NUll,
            removed TINYINT NOT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL

        )')->run();

        DB::try()->raw('CREATE TABLE IF NOT EXISTS category_page (
                            
            category_id INT(11) NOT NULL,
            page_id INT(11) NOT NULL

        )')->run();

        DB::try()->raw('CREATE TABLE IF NOT EXISTS cdn_page (
                                    
            page_id INT(11) NOT NULL,
            cdn_id INT(11) NOT NULL

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

        DB::try()->raw('CREATE TABLE IF NOT EXISTS cdn (
                                    
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