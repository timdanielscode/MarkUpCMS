<?php
                
namespace middleware;
                
use core\Session; 
use database\DB;

class UserMiddleware {
                
    public function __construct($run) {   

        if(file_exists("../config/database/config.ini") === true) {

            $users = DB::try()->select("*")->from("users")->fetch();

            if(empty($users) ) {
    
                return $run();
            }
        }
    }          
}  