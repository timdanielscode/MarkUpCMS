<?php
/**
 * Auth 
 * 
 * @author Tim Daniëls
 */
namespace extensions;

use core\http\Request;
use core\Session;
use app\models\User;

class Auth {

    private static $_userCredentialInputName, $_userCredentialInputValue, $_password;

    /**
     * Authenticate & authorize users
     * 
     * @param array $roleType expects 'role' as key and value type of admin|normal
     * @example authenticate(array('role' => 'normal')) || authenticate(array('role' => 'admin'))
     * @return bool true|false
     */
    public static function authenticate($userRole = null) {

        self::setUserCredentials(new Request());

        if($userRole !== null) {

            $sql = User::getCredentialsAndRole(self::$_userCredentialInputName, self::$_userCredentialInputValue, $userRole['role']);
        } else {
            $sql = User::getCredentials(self::$_userCredentialInputName, self::$_userCredentialInputValue);
        }

        return self::verifyPassword($sql);
    }

    /**
     * Setting user credentials
     * 
     * @param object $request 
     * @return bool
     */
    public static function setUserCredentials($request) {

        self::$_password = $request->get()['password'];

        if(!empty($request->get()['email']) && $request->get()['email'] !== null) {

            self::$_userCredentialInputName = 'email';
            self::$_userCredentialInputValue = $request->get()['email'];

        } else if(!empty($request->get()['username']) && $request->get()['username'] !== null) {

            self::$_userCredentialInputName = 'username';
            self::$_userCredentialInputValue = $request->get()['username'];
        }
    }

    /**
     * Verify user password
     * 
     * @param array $sql user database record
     * @param string $password html input password value
     * @return bool
     */
    public static function verifyPassword($sql) {

        if(password_verify(self::$_password, $sql['password']) && Session::exists('failed_login_attempts_timestamp') === false) {

            Session::set('logged_in', true);
            Session::set('user_role', $sql['name']);
            Session::set('username', $sql['username']);

            return true;
        } 

        self::loginAttempt();
    }

    /**
     * Counting failded login attempts
     */
    public static function loginAttempt() {

        self::checkTooManyLoginAttempts();

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
     * Setting timeout based on failed login attempts
     */
    public static function checkTooManyLoginAttempts() {

        if(Session::exists('failed_login_attempts_timestamp') === true) {

            $timeoutInSeconds = 300;
            $currentTime = time();
            $timestampFailedLoginAttempts = Session::get('failed_login_attempts_timestamp');
        
            if($currentTime - $timestampFailedLoginAttempts > $timeoutInSeconds) {
    
                Session::delete('failed_login_attempts_timestamp');
                Session::set('failed_login_attempt', 0);
            } 
        }
    }
}