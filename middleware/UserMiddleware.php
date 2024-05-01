<?php
                
namespace middleware;
                
use database\DB;

class UserMiddleware {

    private $_database = '';
                
    /** 
     * To restrict routes
     * 
     * @return object $run App, Closure Object
     */  
    public function __construct($run) {   

        $this->getDatabase();

        if($this->checkTables('users') === false) {

            return $run();
        } 
    }       

    /** 
     * To get the database name
     */  
    private function getDatabase() {

        if(file_exists("../config/database/config.ini") === true) {

            $ini = parse_ini_file('../config/database/config.ini');
            $this->_database = $ini['db'];
        }
    }
    
    /** 
     * To check if users table exists
     * 
     * @param string $table users
     * @return bool false
     */ 
    private function checkTables($table) {
        
        $tableName = DB::try()->raw("SELECT table_name FROM information_schema.tables WHERE table_schema = '$this->_database' AND table_name = '$table';")->fetch();

        if(empty($tableName) || $tableName === null) {

            return false;
        }
    }
}  