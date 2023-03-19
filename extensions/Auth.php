<?php
/**
 * Auth 
 * 
 * @author Tim Daniëls
 */
namespace extensions;

use app\models\User;
use app\models\UserRole;
use app\models\Roles;
use core\http\Request;
use database\DB;
use core\Session;

class Auth {
    
    /**
     * Authenticate & authorize users
     * 
     * @param array $roleType expects 'role' as key and value type of admin|normal
     * @example authenticate(array('role' => 'normal')) || authenticate(array('role' => 'admin'))
     * @return bool true|false
     */
    public static function authenticate($userRole = null) {

        $user = new User();

        $request = new Request();
        $username = $request->get()['username'];
        $password = $request->get()['password'];

        if($userRole !== null) {

            $user_role = new UserRole();
            $role = new Roles();

            $userRole = $userRole['role'];
            $sql = DB::try()->select($user->t.'.'.$user->id, $user->t.'.'.$user->username, $user->t.'.'.$user->password, $role->t.'.'.$role->name)->from($user->t)->join($user_role->t)->on($user->t.'.'.$user->id, '=', $user_role->t.'.'.$user_role->user_id)->join($role->t)->on($user_role->t.'.'.$user_role->role_id, '=', $role->t.'.'.$role->id)->where($user->username, '=', $username)->and($role->name, '=', $userRole)->first();
        } else {

            $sql = DB::try()->select($user->t.'.'.$user->username, $user->t.'.'.$user->password)->from($user->t)->where($user->username, '=', $username)->first();
            $sql['name'] = '';
        }

        if(!empty($sql) && $sql !== null) {

            $fetched_password = $sql['password'];
            
            if(!password_verify($password, $fetched_password)) {

                return false;
            } else {

                Session::set('logged_in', true);
                Session::set('user_role', $sql['name']);
                Session::set('username', $sql['username']);
                return true;
            }
        }
    }
}