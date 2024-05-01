<?php

namespace middleware;

use core\Session;

class LoginMiddleware {

    /** 
     * To restrict routes
     * 
     * @param object $run App, Closure Object
     * @param string $bool true | false
     */
    public function __construct($run, $bool = null) {
  
        if(Session::exists("logged_in") === true && Session::get("logged_in") === true && $bool === true) {
        
            $run();
        
        } else if(Session::exists("logged_in") === false && $bool === false) {
    
            $run(); 
        }
    }
}