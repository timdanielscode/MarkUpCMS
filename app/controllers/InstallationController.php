<?php

namespace app\controllers;

use core\Csrf;
use validation\Rules;
use app\models\User;
use app\models\UserRole;

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

                if(empty($users) ) {

                    $user = new User(); 
                    $userRole = new UserRole();  

                    User::insert([
                    
                        $user->username => $request["username"], 
                        $user->email => $request["email"], 
                        $user->password => password_hash($request["password"], PASSWORD_DEFAULT),
                        $user->retypePassword => password_hash($request["retypePassword"], PASSWORD_DEFAULT),
                        $user->created_at => date("Y-m-d H:i:s"), 
                        $user->updated_at => date("Y-m-d H:i:s")
                                 
                    ]); 

                    UserRole::insert([
    
                        $userRole->user_id => 1,
                        $userRole->role_id => 2
                    ]);

                    redirect('/admin/dashboard');
                } 


    
            } else {
                         
                $data["rules"] = $rules->errors;
                return $this->view("admin/installation/index", $data);
            }
        }
    }







}