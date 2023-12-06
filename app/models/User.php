<?php
                
/** 
 * users table 
 * 
 * column id: to use as an unique identifier
 * column username, email: to distinguish users and to authenticate
 * column password: to authenticate
 * column removed: to not direct permanently delete a user 
 * column created_at: to know when a page is been created
 * column updated_at: to know when a page is been updated
 */
namespace app\models;

use database\DB;
   
class User extends Model {

    private static $_table = "users";
    private static $_columns = [];

    public static function getCredentials($inputType, $inputValue) {

        return DB::try()->select('users.id', 'users.'.$inputType, 'users.password','roles.name')->from('users')->join('user_role')->on('users.id', '=','user_role.user_id')->join('roles')->on('user_role.role_id', '=', 'roles.id')->where('users'.'.'.$inputType, '=', $inputValue)->first();
    }

    public static function getCredentialsAndRole($inputType, $inputValue, $role) {

        return DB::try()->select('users.id', 'users.'.$inputType, 'users.password','roles.name')->from('users')->join('user_role')->on('users.id', '=','user_role.user_id')->join('roles')->on('user_role.role_id', '=', 'roles.id')->where('users'.'.'.$inputType, '=', $inputValue)->and('roles.name', '=', $role)->first();
    }

    public static function getLastUserId() {

        return DB::try()->getLastId(self::$_table)->first();
    }

    public static function ifRowExists($value) {

        return DB::try()->select('username')->from(self::$_table)->where('username', '=', $value)->or('id', '=', $value)->first();
    }

    public static function getAll($columns) {

        self::$_columns = implode(',', $columns);
        return DB::try()->select(self::$_columns)->from(self::$_table)->fetch();
    }

    public static function allUsersWithRoles($username) {

        return DB::try()->select('users.*', 'roles.name')->from(self::$_table)->join('user_role')->on('users.id', '=', 'user_role.user_id')->join('roles')->on('user_role.role_id', '=', 'roles.id')->where('users.username','!=', $username)->and('removed', '=', 0)->order('roles.name')->fetch();
    }
                
    public static function allUsersWithRolesOnSearch($searchValue, $username) {

        if($searchValue == 'Thrashcan') {

            return DB::try()->select('users.*', 'roles.name')->from(self::$_table)->join('user_role')->on('users.id', '=', 'user_role.user_id')->join('roles')->on('user_role.role_id', '=', 'roles.id')->where('removed', '=', 1)->and('username', '!=', $username)->fetch();
        } else {
            return DB::try()->select('users.*', 'roles.name')->from(self::$_table)->join('user_role')->on('users.id', '=', 'user_role.user_id')->join('roles')->on('user_role.role_id', '=', 'roles.id')->where('users.username', 'LIKE', '%'.$searchValue.'%')->and('removed', '=', 0)->and('users.username','!=', $username)->or('users.email', 'LIKE', '%'.$searchValue.'%')->and('removed', '=', 0)->and('users.username','!=', $username)->or('roles.name', 'LIKE', '%'.$searchValue.'%')->and('users.username','!=', $username)->and('removed', '=', 0)->fetch();
        }
    }

    public static function userAndRole($column, $value) {

        return DB::try()->select('users.*', 'roles.name')->from(self::$_table)->join('user_role')->on('user_id', '=', 'users.id')->join('roles')->on('role_id', '=', 'roles.id')->where('users.' . $column, '=', $value)->first();
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

    public static function getIdNormalRoles() {

        return DB::try()->select('users.id')->from('users')->join('user_role')->on('user_role.user_id', '=', 'users.id')->join('roles')->on('user_role.role_id', '=', 'roles.id')->where('roles.name', '=', 'normal')->fetch();
    }

    public static function getIdAdminRoles() {

        return DB::try()->select('users.id')->from('users')->join('user_role')->on('user_role.user_id', '=', 'users.id')->join('roles')->on('user_role.role_id', '=', 'roles.id')->where('roles.name', '=', 'admin')->fetch();
    }
}   