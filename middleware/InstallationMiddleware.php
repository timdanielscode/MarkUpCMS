<?php

namespace middleware;

use core\http\Route;
use database\DB;

class InstallationMiddleware {

    public function __construct() {

        $this->setDatabaseRoutes();
        $this->setUserRoutes();
    }

    private function checkConfigFile() {

        if(file_exists("../config/database/config.ini") === true) {

            return true;
        } 

        return false;
    }

    private function checkUser() {

        $users = DB::try()->select("*")->from("users")->fetch();

        if(empty($users) ) {

            return false;
        }

        return true;
    }

    private function setDatabaseRoutes() {

        if($this->checkConfigFile() === false) {

            new Route(['GET' => '/'], ['InstallationController' => 'databaseSetup']);
            new Route(['POST' => '/'], ['InstallationController' => 'createConnection']);

            exit();
        }
    }

    private function setUserRoutes() {

        if($this->checkConfigFile() === true && $this->checkUser() === false) {

            new Route(['GET' => '/'], ['InstallationController' => 'createUser']);
            new Route(['POST' => '/'], ['InstallationController' => 'storeUser']);

            exit();
        }
    }
}