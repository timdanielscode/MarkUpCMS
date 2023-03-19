<?php
            
namespace middleware;
                
use core\Session; 

class AuthMiddleware {
                
    public function __construct($run, $role) {    
                 
        if(Session::exists("logged_in") === true && Session::get("user_role") === $role) {

            return $run();
        }    
    }          
}  