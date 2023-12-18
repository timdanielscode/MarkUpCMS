<?php
                
namespace middleware;
                
use core\Session; 

class HasNotDBConnectionMiddleware {
                
    /** 
     * To check config.ini file exists to restrict routes
     * 
     * @return object $run App, Closure Object
     */    
    public function __construct($run) {   

        if(file_exists("../config/database/config.ini") === false) {

            return $run();
        }
    }          
}  