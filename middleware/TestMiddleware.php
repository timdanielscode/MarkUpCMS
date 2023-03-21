<?php

namespace middleware;

use database\DB;
use core\Session;

class TestMiddleware {

    public function __construct() {

        $this->files();
        $this->session();
        $this->appInstances();
        //$this->database();
        //$this->createTestTable();
    }

    public function files() {

        $files = [

            "../core/App.php",
            "../core/routing/Route.php",
            "../core/routing/Router.php",
            "../core/routing/RouteBinder.php",
            "../core/http/Request.php",
            "../core/http/Response.php",
            "../core/validation/Validate.php",
            "../validation/Errors.php",
            "../validation/Rules.php",
            "../core/Csrf.php",
            "../core/Middleware.php",
            "../core/Session.php",
            "../database/DB.php",
            "../functions/functions.php",
            "../routes/routes.php",
        ];

        foreach($files as $file) {

            if(!file_exists($file) ) {

                //echo $this->errors('log dat file niet bestaat in een log bestandje.');
            }
        }
    }

    public function session() {

        if(session_status() === 0 || session_status() === 1) {

            echo $this->errors('log dat session niet gestart is in een log bestandje ofzoeits..');
        } else {
            return true;
        }
    }

    public function appInstances() {

        // nog ff checken welke belangrijk zijn
        //print_r(get_declared_classes());
    }

    public function database() {

        $configIniPath = '../config/database/config.ini';

        if(file_exists($configIniPath) ) {

            $characters = ['?', '}', '{', '|', '&', '~', '!', '[', '(', ')', '^', '"'];
            $configIni = file_get_contents('../config/database/config.ini');

            foreach($characters as $key => $value) {

                if(str_contains($configIni, $value)) {

                    echo $this->errors('Character inside config.ini file is not allowed!');
                } 
            }
        
        } else {

            echo $this->error('File config.ini does not exists!');
        }
    }


    public function createTestTable() {

        $testTable = DB::try()->raw("SHOW TABLES LIKE 'testTable'")->first();

        if(empty($testTable) ) {

            $db = DB::try()->raw("CREATE TABLE testTable(id INT AUTO_INCREMENT PRIMARY KEY, testDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);")->run();
            
            $testTable = DB::try()->raw("SHOW TABLES LIKE 'testTable'")->first();

            if(!empty($testTable) && $testTable[0] === 'testTable') {

                Session::set('testTable', 'TestTable successfully created!');
            
                if(Session::exists('testTable') ) {

                    echo Session::get('testTable');
                    Session::delete('testTable');
                } else {

                    $this->errors("log dat er geen testTable aangemaakt kon worden.");
                }
            } 
        } 
    }

    public function errors($message) {

        echo $message;
    }    


}