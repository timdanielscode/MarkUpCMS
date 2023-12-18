<?php
                
namespace middleware;
                
use database\DB;

class UserMiddleware {
                
    /** 
     * To check any user exists to restrict routes
     * 
     * @return object $run App, Closure Object
     */  
    public function __construct($run) {   

        if(file_exists("../config/database/config.ini") === true) {

            $users = DB::try()->select("*")->from("users")->fetch();

            if(empty($users) ) {
    
                return $run();
            }
        }
    }          
}  