<?php
                
namespace middleware;
                
use core\Session; 
use app\models\Table;

class HasDBConnectionMiddleware {
                
    public function __construct($run) {   

        if(file_exists("../config/database/config.ini") === true) {

            $table = new Table();
            $table->create();

            return $run();
        }
    }          
}  