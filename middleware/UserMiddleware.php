<?php
                
namespace middleware;
                
use database\DB;

class UserMiddleware {
                
    public function __construct($run) {   

        $users = DB::try()->select("*")->from("users")->fetch();

        if(empty($users) ) {

           return $run();
        } 
    }          
}  