<?php

namespace app\controllers;

use app\models\User;
use app\models\UserRole;
use core\Csrf;
use parts\Session;
use validation\Rules;
use database\DB;

class RegisterController extends Controller {

    public function create() {

        $data['rules'] = [];
        return $this->view('register', $data);
    }

    public function store() {
 
        if(submitted('submit')) {
            
            if(Csrf::validate(Csrf::token('get'), post('token') ) === true) {

                $rules = new Rules();
                $user = new User();
                $userRole = new UserRole();
                
                $uniqueUsername = DB::try()->select($user->username)->from($user->t)->where($user->username, '=', post('username'))->first();
                $uniqueEmail = DB::try()->select($user->email)->from($user->t)->where($user->email, '=', post('email'))->fetch();

                if($rules->register_rules($uniqueUsername, $uniqueEmail)->validated()) {
                    
                    DB::try()->insert($user->t, [

                        $user->username => post('username'),
                        $user->email => post('email'),
                        $user->password => password_hash(post('password'), PASSWORD_DEFAULT),
                        $user->retype_password => password_hash(post('password_confirm '), PASSWORD_DEFAULT),
                        $user->created_at => date("Y-m-d H:i:s"),
                        $user->updated_at => date("Y-m-d H:i:s")
                    ]);

                    $lastRegisteredUser = DB::try()->select($user->id)->from($user->t)->order($user->id)->desc(1)->first();              

                    DB::try()->insert($userRole->t, [
                        $userRole->user_id => $lastRegisteredUser['id'],
                        $userRole->role_id => 1 
                    ]); 

                    Session::set('registered', 'You have been successfully registered!');            
                    redirect('/login');

                } else {

                    $data['rules'] = $rules->errors;
                    return $this->view('register', $data);
                }
            } else {
                Session::set('csrf', 'Cross site request forgery!');
                redirect('/register');
            }
        }
    }
}