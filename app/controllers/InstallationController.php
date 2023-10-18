<?php

namespace app\controllers;

use core\Csrf;
use validation\Rules;
use app\models\User;
use app\models\UserRole;
use app\models\Roles;
use app\models\Table;
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
                    
            if($rules->installationStoreUser()->validated() ) {

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

        $data['rules'] = [];
        return $this->view('admin/installation/database', $data);
    }
    
    public function createConnection($request) {

        if(submitted("submit") && Csrf::validate(Csrf::token("get"), post("token")) ) {

            $rules = new Rules();  
                    
            if($rules->installationDatabase()->validated() ) {

                $this->databaseConfigFile($request);
                
                $table = new Table();
                $table->create();
    
                redirect('/');
            } else {

                $data['rules'] = $rules->errors;
                return $this->view('admin/installation/database', $data);
            }
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