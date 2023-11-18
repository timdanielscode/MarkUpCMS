<?php
                
/**        
 * Model: app/models/User.php
 */ 
namespace app\models;

use database\DB;
use core\Session;
   
class User extends Model {

    private static $_table = "users";

    public static function getCredentials($inputType, $inputValue) {

        return DB::try()->select('users.id', 'users.'.$inputType, 'users.password','roles.name')->from('users')->join('user_role')->on('users.id', '=','user_role.user_id')->join('roles')->on('user_role.role_id', '=', 'roles.id')->where('users'.'.'.$inputType, '=', $inputValue)->first();
    }

    public static function getCredentialsAndRole($inputType, $inputValue, $role) {

        return DB::try()->select('users.id', 'users.'.$inputType, 'users.password','roles.name')->from('users')->join('user_role')->on('users.id', '=','user_role.user_id')->join('roles')->on('user_role.role_id', '=', 'roles.id')->where('users'.'.'.$inputType, '=', $inputValue)->and('roles.name', '=', $role)->first();
    }

    public static function ifRowExists($value) {

        return DB::try()->select('username')->from(self::$_table)->where('username', '=', $value)->or('id', '=', $value)->first();
    }

    public static function allUsersWithRoles() {

        return DB::try()->select('users.*', 'roles.name')->from(self::$_table)->join('user_role')->on('users.id', '=', 'user_role.user_id')->join('roles')->on('user_role.role_id', '=', 'roles.id')->where('users.username','!=', Session::get('username'))->and('removed', '=', 0)->order('roles.name')->fetch();
    }
                
    public static function allUsersWithRolesOnSearch($searchValue = null) {

        if($searchValue == 'Thrashcan') {

            return DB::try()->select('users.*', 'roles.name')->from(self::$_table)->join('user_role')->on('users.id', '=', 'user_role.user_id')->join('roles')->on('user_role.role_id', '=', 'roles.id')->where('removed', '=', 1)->fetch();
        } else {
            return DB::try()->select('users.*', 'roles.name')->from(self::$_table)->join('user_role')->on('users.id', '=', 'user_role.user_id')->join('roles')->on('user_role.role_id', '=', 'roles.id')->where('users.username', 'LIKE', '%'.$searchValue.'%')->and('removed', '=', 0)->or('users.email', 'LIKE', '%'.$searchValue.'%')->and('removed', '=', 0)->or('roles.name', 'LIKE', '%'.$searchValue.'%')->and('users.username','!=', Session::get('username'))->and('removed', '=', 0)->fetch();
        }
    }

    public static function userAndRole($username) {

        return DB::try()->select('users.*', 'roles.name')->from(self::$_table)->join('user_role')->on('user_id', '=', 'users.id')->join('roles')->on('role_id', '=', 'roles.id')->where('users.username', '=', $username)->first();
    }

    public static function getLastRegisteredUserId() {

        return DB::try()->getLastId(self::$_table)->first();
    }

    public static function checkUniqueUsername($f_username, $id) {

        return DB::try()->select('username')->from(self::$_table)->where('username', '=', $f_username)->and('id', '!=', $id)->first();
    }

    public static function checkUniqueEmail($email, $id) {

        return DB::try()->select('email')->from(self::$_table)->where('email', '=', $email)->and('id', '!=', $id)->first();
    }

    public static function getLoggedInUserAndRole($username) {

        return DB::try()->select('users.id, users.username, users.email, roles.name')->from('users')->join('user_role')->on('user_role.user_id', '=', 'users.id')->join('roles')->on('roles.id', '=', 'user_role.role_id')->where('users.username', '=', $username)->first();
    }
}   