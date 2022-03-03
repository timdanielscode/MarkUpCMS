<?php
/**
 * Use to create mid layer rules
 * 
 * @author Tim Daniëls
 * @version 1.0
 */

namespace core;

class Middleware {

    protected $start;

    public function __construct() {

        $this->start = function() {
            #application is running after middlewares 
        };
    }

    /**
     * 
     * @param object $middleware expecting a sql query
     * 
     * @return object Middleware
     */     
    public function add($middleware) {

        $next = $this->start;
        $this->start = function() use ($middleware, $next){
            return $middleware($next);
        };
    }

    /**
     * @return void
     */ 
    public function handle() {
        
        return call_user_func($this->start);
    }

}