<?php
            
namespace middleware;
                
use core\Session; 

class AuthMiddleware {
                
    public function __construct($run, $value = null) {
 
        if($value === 'admin') {

            $this->checkLoggedInAndUserRole($run, $value);

        } else if ($value === 'not') {

            $this->checkNotLoggedIn($run);
        } else {
            $this->checkLoggedIn($run);
        }
    }  

    /** 
     * To check user is logged in and type of user to restrict routes
     * 
     * @param object $run App, Closure Object
     * @param string $role type of user role
     * @return object $run App, Closure Object
     */ 
    private function checkLoggedInAndUserRole($run, $role) {

        if(Session::exists("logged_in") === true && Session::get("user_role") === $role) {

            return $run();
        }   
    }
    
    /** 
     * To check user is logged in to restrict routes
     * 
     * @param object $run App, Closure Object
     * @param string $role type of user role
     * @return object $run App, Closure Object
     */ 
    private function checkLoggedIn($run) {
        
        if(Session::exists("logged_in") === true) {

            return $run();
        }
    }

    /** 
     * To check user is not logged in to restrict routes
     * 
     * @param object $run App, Closure Object
     * @return object $run App, Closure Object
     */ 
    private function checkNotLoggedIn($run) {

        if(Session::exists("logged_in") === false) {
            
            return $run();
        }
    }
}  