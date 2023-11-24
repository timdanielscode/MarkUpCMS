<?php
                
namespace middleware;
                
use core\Session; 

class HasNotDBConnectionMiddleware {
                
    public function __construct($run) {   

        if(file_exists("../config/database/config.ini") === false) {

            return $run();
        }
    }          
}  