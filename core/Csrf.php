<?php
/**
 * Csrf
 * 
 * @author Tim Daniëls
 */
namespace core;

use core\Session;

class Csrf {

    /**
     * Setting token
     */
    public static function token() {

        if(Session::exists('csrf') === false) {

            Session::set('csrf', bin2hex(random_bytes(32)));
        }

        echo Session::get('csrf');
    }
}