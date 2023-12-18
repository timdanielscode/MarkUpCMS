<?php
            
namespace middleware;
            
use core\Session; 

class LoginMiddleware {
            
    /** 
     * To check user is logged in to restrict routes
     * 
     * @return object $run App, Closure Object
     */  
    public function __construct($run) {   

        if(Session::exists("logged_in") === true ) {

            return $run();
        }    
    }          
}  