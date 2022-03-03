<?php

namespace app\controllers;

use app\models\User;
use core\CSRF;
use parts\Session;
use validation\Rules;
use parts\Auth;

class LoginController extends Controller {

    public function index() {

        $data['errors'] = [];
        return $this->view('login', $data);
    }

    public function auth() {

        $user = new User;

        if(submitted("submit")) {
            
            if(CSRF::validate(CSRF::token('get'), post('token'))) {

                $rules = new Rules();
                $rules->login_rules();

                if($rules->validated()) {

                    if(Auth::authenticate(array('role' => 'normal'))) {
                        
                        redirect('/profile/'.Session::get("username")); 
                    } else {
                        Session::set('auth_failed', 'Username and password does not match.');
                        redirect('/login');                        
                    }

                } else {
                    $data['errors'] = $rules->login_rules();
                    return $this->view('login', $data);
                }
            } else {
                Session::set('csrf', 'Cross Site Request Forgery!');
                redirect('/login');
            }
        }
    }

}