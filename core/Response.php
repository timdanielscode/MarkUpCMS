<?php
/**
 * Use for handling responses
 * 
 * @author Tim Daniëls
 * @version 1.1
 */
namespace core;

use app\controllers\Controller;

class Response extends Controller {

    /** 
     * @param mixed int|string $code
     * @return void
     */    
    public function set($code) {

        return http_response_code($code);
    }

    /** 
     * 
     * Use in controllers to set response status code
     * 
     * @param mixed int|string $code
     * @return object Response
     */ 
    public static function statusCode($code) {

        http_response_code($code);
        $inst = new Response();

        return $inst;
    }


}