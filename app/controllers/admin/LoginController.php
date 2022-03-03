<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use app\models\User;
use core\CSRF;
use parts\Session;
use validation\Rules;
use database\DB;
use parts\Auth;

class LoginController extends Controller {

    public function index() {

        $data['errors'] = [];
        return $this->view('admin/login', $data);
    }

    public function auth() {

        $user = new User;

        if(submitted("submit")) {
            
            if(CSRF::validate(CSRF::token('get'), post('token'))) {

                $validate = new Rules();
                $validate->login_rules();

                if($validate->validated()) {
                        
                    if(Auth::authenticate(array('role' => 'admin'))) {
                        Session::delete('csrf_token');
                        redirect('/admin/dashboard');
                    } else {
                        Session::set('auth-failed', 'Username or password does not match.');
                        redirect('/login-admin');   
                    }                  
                } else {
                    $data['errors'] = $validate->login_rules();
                    return $this->view('/admin/login', $data);
                }
            } else {
                Session::set('csrf', 'Cross Site Request Forgery!');
                redirect('/admin/login');
            }
        }
    }



}