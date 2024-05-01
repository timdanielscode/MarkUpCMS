<?php

namespace extensions;

use core\Session;
use database\DB;

class Auth {

    /**
     * To get user credentials
     * 
     * @param array $roleType type of role
     */
    public static function success($request, $role = null) {

        if(!empty($role) && $role !== null) {

            $sql = DB::try()->select('*')->from('users')->join('roles')->on('users.role_id', '=', 'roles.id')->where(key($request), '=', $request[key($request)][key($request)])->and('type', '=', $role['role'])->first();
        } else {

            $sql = DB::try()->select('*')->from('users')->where(key($request), '=', $request[key($request)][key($request)])->first();
        }

        return self::verifyPassword($sql, $request[key($request)]['password']);
    }

    /**
     * To verify user password
     * 
     * @param array $sql user database record
     * @param string $password html input password value
     * @return bool
     */
    private static function verifyPassword($sql, $password) {

        self::checkTooManyLoginAttempts();
  
        if(!empty($sql) && password_verify($password, $sql['password']) && Session::exists('failed_login_attempts_timestamp') === false) {

            Session::set('logged_in', true);
            Session::set('user_role', $sql['role_id']);
            Session::set('username', $sql['username']);

            return true;
        } 

        self::loginAttempt();
    }

    /**
     * To count failded login attempts to show failed login attempt messages
     */
    private static function loginAttempt() {

        if(Session::exists('failed_login_attempt') === true) {

            $attempt = Session::get('failed_login_attempt');
            $attempt++;

            if(Session::get('failed_login_attempt') > 2) {
                    
                return Session::set('failed_login_attempts_timestamp', time());
            } else {
                return Session::set('failed_login_attempt', $attempt);
            }
        } 

        Session::set('failed_login_attempt', 1);
    }

    /**
     * To set a timeout to prevent brute force
     */
    private static function checkTooManyLoginAttempts() {

        if(Session::exists('failed_login_attempts_timestamp') === true) {

            $timeoutInSeconds = 60;
            $currentTime = time();
            $timestampFailedLoginAttempts = Session::get('failed_login_attempts_timestamp');
        
            if($currentTime - $timestampFailedLoginAttempts > $timeoutInSeconds) {
    
                Session::delete('failed_login_attempts_timestamp');
                Session::set('failed_login_attempt', 0);
            } 
        }
    }

    /**
     * To get failed login messages
     */
    public static function getFailedMessages() {

        if(Session::exists("failed_login_attempt") === true && Session::get("failed_login_attempt") > 0 && Session::exists("failed_login_attempts_timestamp") === false) { 
      
            return 'Incorrect login credentials!';
        
        } else if(Session::exists("failed_login_attempts_timestamp") === true && time() - Session::get("failed_login_attempts_timestamp") < 60) { 
            
            return 'Too many failed login attempts!';
        }
    }
}