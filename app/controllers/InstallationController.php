<?php

namespace app\controllers;

use core\Csrf;
use validation\Rules;
use app\models\User;
use app\models\UserRole;
use app\models\Roles;
use database\DB;

class InstallationController extends Controller {

    public function create() {

        $data['rules'] = [];

        return $this->view('admin/installation/index', $data);
    }

    public function store($request) {

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
}