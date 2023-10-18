<?php
                
namespace middleware;
                
use core\Session; 

class HasDBConnectionMiddleware {
                
    public function __construct($run) {   

        if(file_exists("../config/database/config.ini") === true) {

            return $run();
        }
    }          
}  