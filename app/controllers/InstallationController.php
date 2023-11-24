<?php

namespace app\controllers;

use core\Csrf;
use validation\Rules;
use app\models\User;
use app\models\UserRole;
use app\models\Roles;
use app\models\Table;
use app\models\WebsiteSlug;
use database\DB;

class InstallationController extends Controller {

    private $_data;

    private $_configDatabasePath = "../config/database/config.ini";

    public function createUser() {

        $this->_data['rules'] = [];

        return $this->view('admin/installation/user')->data($this->_data);
    }

    public function storeUser($request) {

        $rules = new Rules();  
                    
        if($rules->installation_user($request['username'], $request['email'], $request['password'], $request['retypePassword'], $request['token'], Csrf::get())->validated() ) {

            User::insert([
                    
                'username' => $request["username"], 
                'email' => $request["email"], 
                'password' => password_hash($request["password"], PASSWORD_DEFAULT),
                'removed'   => 0,
                'created_at' => date("Y-m-d H:i:s"), 
                'updated_at' => date("Y-m-d H:i:s")
            ]); 

            UserRole::insert([
    
                'user_id' => User::getLastUserId()['id'],
                'role_id' => 2
            ]);

            Roles::insert(['name'  => 'normal']);
            Roles::insert(['name'  => 'admin']);

            redirect('/login');

        } else {
                         
            $this->_data["rules"] = $rules->errors;
            return $this->view("admin/installation/user")->data($this->_data);
        }
    }

    public function databaseSetup() {

        $this->_data['rules'] = [];
        return $this->view('admin/installation/database')->data($this->_data);
    }
    
    public function createConnection($request) {

        $rules = new Rules();  
                    
        if($rules->installation_database($request['host'], $request['database'], $request['username'], $request['password'], $request['retypePassword'], $request['token'], Csrf::get())->validated() ) {

            $this->databaseConfigFile($request);
                
            Table::create();
            WebsiteSlug::insert([

                'slug' => '/login',
                'created_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
                'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
            ]);
    
            redirect('/');
        } else {

            $this->_data['rules'] = $rules->errors;
            return $this->view('admin/installation/database')->data($this->_data);
        }
    }

    private function databaseConfigFile($request) {

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
    }
}