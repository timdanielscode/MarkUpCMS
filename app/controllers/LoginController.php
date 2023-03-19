<?php

  namespace app\controllers;
  
  use core\Csrf;
  use extensions\Auth;
  use validation\Rules;
  use core\Session;
                
  class LoginController extends Controller {
                
    public function index() {    
                  
      $data["rules"] = [];
      return $this->view("login", $data);     
    }
                  
                
    public function authenticateUsers() {    
                  
        if(submitted("submit") && Csrf::validate(Csrf::token("get"), post("token")) ) {
                     
          $rules = new Rules();  
                  
          if($rules->loginRules()->validated()) {
  
            if(Auth::authenticate() ) {
                   
              redirect("/profile/" . Session::get("username") ); 
                   
            } else {
                              
              redirect("/login");      
            }
  
          } else {
              
            $data["rules"] = $rules->errors;
            return $this->view("login", $data);  
          }   
        }     
      }
  }  