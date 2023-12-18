<?php
            
namespace middleware;
                
use core\Session; 

class AuthMiddleware {
                
    /** 
     * To check type of user to restrict routes
     * 
     * @return object $run App, Closure Object
     */    
    public function __construct($run, $role) {

        if(Session::exists("logged_in") === true && Session::get("user_role") === $role) {

            return $run();
        }    
    }          
}  