<?php
/**
 * 
 * @author Tim Daniëls
 * @version 1.0
 */
namespace core;

use parts\Session;

class Csrf {

    /**
     * Generates Csrf token 
     * 
     * @return string token
     */
    public static function token($arg) {

        if(!Session::exists('Csrf_token')) {
            Session::set('Csrf_token', bin2hex(random_bytes(32)));
        }
        $token = hash_hmac('sha256', 'hash me please!', Session::get('Csrf_token'));
        if($arg == 'get') {
            return $token;
        } else if($arg == 'add') {
            echo $token;
        }
    }

    /**
     * Validates Csrf token 
     * 
     * @return bool true|false
     */    
    public static function validate($token, $postToken) {

        if(hash_equals($token, $postToken)) {
            return true;
        } 
        return false;
    }

}