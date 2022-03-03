<?php
/**
 * Create your own middleware project/middleware/myMiddleware
 * This authentication middleware is an example
 * Middleware can be used on routes.php file
 * 
 * @author Tim Daniëls
 * @version 1.0
 */
namespace middleware;

use parts\Session;

class AuthMiddleware {

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
     * 
     * @param string $role like admin|normal
     * 
     * @return bool 
     */    
    public static function auth($role) {

        if(Session::exists('logged_in') === true) {
            
            if(Session::get('user_role') == $role) {
                return true;
            }
        } 
    }

}