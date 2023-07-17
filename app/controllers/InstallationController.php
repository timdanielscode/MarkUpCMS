<?php

namespace app\controllers;

use core\Csrf;
use validation\Rules;
use app\models\User;
use app\models\UserRole;
use app\models\Post;


class InstallationController extends Controller {

    public function create() {

        $data['rules'] = [];

        return $this->view('admin/installation/index', $data);
    }

    public function store($request) {

        if(submitted("submit") && Csrf::validate(Csrf::token("get"), post("token")) ) {
                
            $rules = new Rules();  
                    
            if($rules->installationRules()->validated() ) {
                     
                $users = User::all();

                $user = new User(); 
                $userRole = new UserRole();  
                $post = new Post();

                User::insert([
                    
                    'username' => $request["username"], 
                    'email' => $request["email"], 
                    'password' => password_hash($request["password"], PASSWORD_DEFAULT),
                    'retypePassword' => password_hash($request["retypePassword"], PASSWORD_DEFAULT),
                    'created_at' => date("Y-m-d H:i:s"), 
                    'updated_at' => date("Y-m-d H:i:s")
                                 
                ]); 

                UserRole::insert([
    
                    'user_id' => 1,
                    'role_id' => 2
                ]);

                redirect('/login');

            } else {
                         
                $data["rules"] = $rules->errors;
                return $this->view("admin/installation/index", $data);
            }
        }
    }
}