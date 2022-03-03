<?php

namespace app\controllers;

use parts\Session; 

class LogoutController extends Controller {

    public function logout() {

        Session::delete('logged_in');
        Session::delete('username');
        redirect('/');
    }
}