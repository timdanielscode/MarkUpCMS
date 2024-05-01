<?php

namespace middleware;

use core\Session;
use core\http\Request;
use database\DB;

class AuthMiddleware {

    /** 
     * To restrict routes
     * 
     * @param object $run App, Closure Object
     * @param string $role role type
     */
    public function __construct($run, $role = null) {

        if(Session::exists("logged_in") === true && Session::get("logged_in") === true) {
        
            $this->checkRoleType($role, $run);
        }
    }
  
    /** 
     * To check type of user role 
     * 
     * @param string $role role type
     * @param object $run App, Closure Object
     */ 
    private function checkRoleType($role, $run) {
  
        if($role === null) {
        
            $this->checkRoleTypeNull($run);
        
        } else if($role === "admin") {
        
            $this->checkRoleTypeAdmin($run, $role);
        }
    }
  
    /** 
     * To check if type of role is null
     * 
     * @param object $run App, Closure Object
     */ 
    private function checkRoleTypeNull($run) {
    
        if(Session::get("user_role") === null && Session::get("username") ) {
        
            $run();
        }
    }

    /** 
     * To check if type of role is admin
     * 
     * @param string $role role type
     * @param object $run App, Closure Object
     */ 
    private function checkRoleTypeAdmin($run, $role) {
  
        $data = DB::try()->select("type")->from("roles")->where("id", "=", Session::get("user_role"))->first();
    
        if(!empty($data) && $data["type"] === $role && Session::get("username") ) {
    
            $run();
        }
    }
  
    /** 
     * To get the route key value
     * 
     * @param int $position uri part position
     * @return string $uri route key value
     */ 
    /*private function getRouteKeyValue($position) {
  
        $request = new Request();
        $uri = explode("/", $request->getUri());
    
        if(!empty($uri[$position]) && $uri[$position] !== null) {
        
            return $uri[$position];
        }
    }*/
}