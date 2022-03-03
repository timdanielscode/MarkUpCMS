<?php 
/**
 * Use for handling sessions
 * 
 * @author Tim Daniëls
 * @version 1.0
 */

namespace parts;

class Session {

    /**
     * 
     * use to check if session exists with givin name
     * 
     * @param string $name
     * @return bool true|false
     */
    public static function exists($name) {
        if(isset($_SESSION[$name])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 
     * use to set session
     * 
     * @param string $name
     * @param string $value
     * @return global session
     */    
    public static function set($name, $value) {
        
        return $_SESSION[$name] = $value;
    }

    /**
     * 
     * use to get session
     * 
     * @param string $name
     * @return global session
     */     
    public static function get($name) {

        return $_SESSION[$name];
    }    

    /**
     * 
     * use to delete session
     * 
     * @param string $name
     * @return void
     */     
    public static function delete($name) {
        
        if(self::exists($name)) {
            unset($_SESSION[$name]);
        }
    }

}   