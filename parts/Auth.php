<?php
/**
 * Use to display bootstrap css alerts on views
 * 
 * @author Tim Daniëls
 * @version 1.0
 */
namespace parts;

use app\models\User;
use app\models\UserRole;
use app\models\Roles;
use core\Request;
use database\DB;
use core\QueryBuilder;

class Auth {
    
    /**
     * Use to authenticate user type
     * 
     * @param array $roleType expects to be admin|normal
     * @example Auth::authenticate(array('role' => 'admin'))
     * @return bool true|false
     * 
     */
    public static function authenticate($userRole = null) {

        $user = new User();
        $user_role = new UserRole();
        $role = new Roles();
        $request = new Request();

        $userRole = $userRole['role'];

        $username = $request->get()['username'];
        $password = $request->get()['password'];

        $sql = DB::try()->select($user->t.'.'.$user->id, $user->t.'.'.$user->username, $user->t.'.'.$user->password, $role->t.'.'.$role->name)->from($user->t)->join($user_role->t)->on($user->t.'.'.$user->id, '=', $user_role->t.'.'.$user_role->user_id)->join($role->t)->on($user_role->t.'.'.$user_role->role_id, '=', $role->t.'.'.$role->id)->where($user->username, '=', $username)->and($role->name, '=', $userRole)->first();
        
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