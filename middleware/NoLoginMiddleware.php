<?php
            
namespace middleware;
            
use core\Session; 

class NoLoginMiddleware {
            
    /** 
     * To check user is not logged in to restrict routes
     * 
     * @return object $run App, Closure Object
     */  
    public function __construct($run) {    

        if(Session::exists("logged_in") === false) {

            return $run();
        }    
    }          
}  