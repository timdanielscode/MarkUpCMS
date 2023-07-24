<?php
                
/**        
 * Model: app/models/User.php
 */ 
namespace app\models;

use database\DB;
use core\Session;
   
class User extends Model {

    public function __construct() {

        self::table("users");
    }

    public function ifRowExists($username) {

        return DB::try()->select('username')->from('users')->where('username', '=', $username)->first();
    }
                
    public function allUsersWithRoles($searchValue = null) {

        if(!empty($searchValue) && $searchValue !== null) {

            return DB::try()->select('users.*', 'roles.name')->from('users')->join('user_role')->on('users.id', '=', 'user_role.user_id')->join('roles')->on('user_role.role_id', '=', 'roles.id')->where('users.username', 'LIKE', '%'.$searchValue.'%')->or('users.email', 'LIKE', '%'.$searchValue.'%')->or('roles.name', 'LIKE', '%'.$searchValue.'%')->and('users.username','!=', Session::get('username'))->fetch();
        } else {
            return DB::try()->select('users.*', 'roles.name')->from('users')->join('user_role')->on('users.id', '=', 'user_role.user_id')->join('roles')->on('user_role.role_id', '=', 'roles.id')->where('users.username','!=', Session::get('username'))->order('roles.name')->fetch();
        }
    }

    public function userAndRole($username) {

        $user = DB::try()->select('users.*', 'roles.name')->from('users')->join('user_role')->on('user_id', '=', 'users.id')->join('roles')->on('role_id', '=', 'roles.id')->where('users.username', '=', $username)->first();
        return $user;
    }

    public function getLastRegisteredUserId() {

        $user = DB::try()->getLastId('users')->first();
        return $user;
    }


}   