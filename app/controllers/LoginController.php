<?php

namespace app\controllers;
  
use core\Csrf;
use extensions\Auth;
use validation\Rules;
                
class LoginController extends Controller {

    private $_data;
                
    /**
     * To show the login view
     * 
     * @return object LoginController, Controller
     */
    public function index() {    

        $this->_data["rules"] = [];
        $this->_data["username"] = "";

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
  
        if($rules->login($request, Csrf::get())->validated() && Auth::success(["username" => $request]) ) {
  
            redirect("/admin/dashboard");
  
        } else {
              
            $this->_data["username"] = $request["username"];
            $this->_data["rules"] = $rules->errors;

            return $this->view("login")->data($this->_data);  
        }
    }
}  