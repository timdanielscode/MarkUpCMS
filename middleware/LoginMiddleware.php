<?php
/**
 * Create your own middleware project/middleware/myMiddleware
 * This login middleware is an example
 * 
 * @author Tim Daniëls
 * @version 1.0
 */

namespace middleware;

use parts\Session;

class LoginMiddleware {

    /**
     * 
     * $next is not optional for middleware to work properly
     * 
     * @return object $next
     */     
    public function __invoke(callable $next) {

        return $next();
    }

    /**
     * @return bool
     */     
    public static function logged_in() {
        
        if(Session::exists('logged_in') === true) {
            return true;
        }
    }

}