<?php

  namespace app\controllers;
  
  use core\Csrf;
  use extensions\Auth;
  use validation\Rules;
  use core\Session;
                
  class LoginController extends Controller {
                
    public function index() {    

        $data['failedLoginMessage'] = "";
        $data["rules"] = [];

        return $this->view("login", $data);     
    }
                  
    public function authenticateUsers() {    
                           
        $rules = new Rules();  
                  
        if($rules->loginRules()->validated()) {
  
            $this->authentication();
  
        } else {
              
            $data['failedLoginMessage'] = $this->getFailedLoginAttemptMessages();
            $data["rules"] = $rules->errors;

            return $this->view("login", $data);  
        }     
    }

    private function authentication() {

        if(Auth::authenticate() ) {
                   
            redirect("/admin/dashboard"); 
           
        } else {

            $data['failedLoginMessage'] = $this->getFailedLoginAttemptMessages();
            $data["rules"] = [];

            return $this->view("login", $data);       
        }
    }

    private function getFailedLoginAttemptMessages() {

        if(Session::exists('failed_login_attempt') === true && Session::exists('failed_login_attempts_timestamp') === false) {

            $message = "Incorrect login credentials.";
        } else if(Session::exists('failed_login_attempt') === true && Session::exists('failed_login_attempts_timestamp') === true) {
            $message = "Too many failed login attempts.";
        } else {
            $message = "";
        }

        return $message;
    }
}  