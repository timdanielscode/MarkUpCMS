<?php
             
namespace app\controllers;

    use core\Session;
                
    class LogoutController extends Controller {
                
    public function logout() {    
                     
        Session::delete("logged_in");
        Session::delete("username");
        Session::delete('user_role');
        Session::delete('cdn');
        Session::delete('widget');
        Session::delete('category');
        Session::delete('css');
        Session::delete('js');
        Session::delete('meta');
        Session::delete('slug');
        
        redirect("/");
    }
}  