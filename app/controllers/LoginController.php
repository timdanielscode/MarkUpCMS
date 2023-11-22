<?php

  namespace app\controllers;
  
  use core\Csrf;
  use extensions\Auth;
  use validation\Rules;
  use core\Session;
                
  class LoginController extends Controller {

    private $_data;
                
    public function index() {    

        $this->_data['failedLoginMessage'] = "";
        $this->_data["rules"] = [];

        return $this->view("login")->data($this->_data);     
    }
                  
    public function authenticateUsers($request) {    
                           
        $rules = new Rules();  
  
        if($rules->loginRules($request['username'], $request['password'], $request['token'], Session::get('csrf'),)->validated()) {
  
            $this->authentication();
  
        } else {
              
            $this->_data['failedLoginMessage'] = $this->getFailedLoginAttemptMessages();
            $this->_data["rules"] = $rules->errors;

            return $this->view("login")->data($this->_data);  
        }     
    }

    private function authentication() {

        if(Auth::authenticate() ) {
                   
            redirect("/admin/dashboard"); 
           
        } else {

            $this->_data['failedLoginMessage'] = $this->getFailedLoginAttemptMessages();
            $this->_data["rules"] = [];

            return $this->view("login")->data($this->_data);       
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