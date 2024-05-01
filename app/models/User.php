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

    public static function getLastUserId() {

        return DB::try()->getLastId(self::$_table)->first();
    }

    public static function ifRowExists($id) {

        return DB::try()->select('id')->from(self::$_table)->where('id', '=', $id)->first();
    }

    public static function getAll($columns) {

        self::$_columns = implode(',', $columns);
        return DB::try()->select(self::$_columns)->from(self::$_table)->fetch();
    }

    public static function allUsersWithRoles($username) {

        return DB::try()->select('*')->from('users')->joinLeft('roles')->on('roles.id', '=', 'users.role_id')->where('users.username','!=', $username)->and('removed', '=', 0)->order('roles.type')->fetch();
    }

    public static function getLoggedInUserAndRole($username) {

        return DB::try()->select('*')->from('users')->joinLeft('roles')->on('roles.id', '=', 'users.role_id')->where('users.username', '=', $username)->first();
    }
                
    public static function allUsersWithRolesOnSearch($searchValue, $username) {

        if($searchValue == 'Thrashcan') {

            return DB::try()->select('*')->from('users')->joinLeft('roles')->on('roles.id', '=', 'users.role_id')->where('users.username','!=', $username)->and('removed', '=', 1)->order('roles.type')->fetch();
        } else {
            return DB::try()->select('users.*', 'roles.type')->from('users')->joinLeft('roles')->on('users.role_id', '=', 'roles.id')->where('users.username', 'LIKE', '%'.$searchValue.'%')->and('removed', '=', 0)->and('users.username','!=', $username)->or('users.email', 'LIKE', '%'.$searchValue.'%')->and('removed', '=', 0)->and('users.username','!=', $username)->or('roles.type', 'LIKE', '%'.$searchValue.'%')->and('users.username','!=', $username)->and('removed', '=', 0)->fetch();
        }
    }

    public static function checkUniqueUsername($username, $id) {

        return DB::try()->select('username')->from(self::$_table)->where('username', '=', $username)->and('id', '!=', $id)->first();
    }

    public static function checkUniqueEmail($email, $id) {

        return DB::try()->select('email')->from(self::$_table)->where('email', '=', $email)->and('id', '!=', $id)->first();
    }

    public static function userAndRole($column, $value) {

        return DB::try()->select('users.*', 'roles.type')->from(self::$_table)->joinLeft('roles')->on('roles.id', '=', 'users.role_id')->where('users.' . $column, '=', $value)->first();
    }

    public static function normalUser($column, $value) {

        return DB::try()->select('users.*', 'roles.type')->from(self::$_table)->joinLeft('roles')->on('roles.id', '=', 'users.role_id')->where('users.' . $column, '=', $value)->and('role_id', 'IS', NULL)->first();
    }

    public static function getIdNormalRoles() {

        return DB::try()->select('id')->from('users')->where('role_id', 'IS', NULL)->fetch();
    }

    public static function getIdAdminRoles() {

        return DB::try()->select('id')->from('users')->where('role_id', '=', '1')->fetch();
    }
}   