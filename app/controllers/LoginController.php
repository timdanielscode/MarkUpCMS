<?php

  namespace app\controllers;
  
  use core\Csrf;
  use extensions\Auth;
  use validation\Rules;
  use core\Session;
                
  class LoginController extends Controller {

    private $_data;
                
    /**
     * To show the login view
     * 
     * @return object LoginController, Controller
     */
    public function index() {    

        $this->_data['failedLoginMessage'] = "";
        $this->_data["rules"] = [];

        return $this->view("login")->data($this->_data);     
    }
                 
    /**
     * To authenticate users to sign in (on successful validation)
     * 
     * @param array $request _POST username, password, token (on successful validation)
     * @return object LoginController, Controller (on failed validation)
     */
    public function authenticateUsers($request) {    
                           
        $rules = new Rules();  
  
        if($rules->login($request['username'], $request['password'], $request['token'], Csrf::get())->validated()) {
  
            $this->authentication();
  
        } else {
              
            $this->_data['failedLoginMessage'] = $this->getFailedLoginAttemptMessages();
            $this->_data["rules"] = $rules->errors;

            return $this->view("login")->data($this->_data);  
        }
    }

    /**
     * To authenticate users to sign in
     * 
     * @return object LoginController, Controller (on failed authentication)
     */
    private function authentication() {

        if(Auth::authenticate() ) {
                   
            redirect("/admin/dashboard"); 
           
        } else {

            $this->_data['failedLoginMessage'] = $this->getFailedLoginAttemptMessages();
            $this->_data["rules"] = [];

            return $this->view("login")->data($this->_data);       
        }
    }

    /**
     * To get failed authentication error messages
     * 
     * @return string $message failed authentication error message
     */
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