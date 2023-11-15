<?php
                
/**        
 * Model: app/models/User.php
 */ 
namespace app\models;

use database\DB;
use core\Session;
   
class User extends Model {

    public function ifRowExists($value) {

        return DB::try()->select('username')->from('users')->where('username', '=', $value)->or('id', '=', $value)->first();
    }

    public function allUsersWithRoles() {

        return DB::try()->select('users.*', 'roles.name')->from('users')->join('user_role')->on('users.id', '=', 'user_role.user_id')->join('roles')->on('user_role.role_id', '=', 'roles.id')->where('users.username','!=', Session::get('username'))->and('removed', '=', 0)->order('roles.name')->fetch();
    }
                
    public function allUsersWithRolesOnSearch($searchValue = null) {

        if($searchValue == 'Thrashcan') {

            return DB::try()->select('users.*', 'roles.name')->from('users')->join('user_role')->on('users.id', '=', 'user_role.user_id')->join('roles')->on('user_role.role_id', '=', 'roles.id')->where('removed', '=', 1)->fetch();
        } else {
            return DB::try()->select('users.*', 'roles.name')->from('users')->join('user_role')->on('users.id', '=', 'user_role.user_id')->join('roles')->on('user_role.role_id', '=', 'roles.id')->where('users.username', 'LIKE', '%'.$searchValue.'%')->and('removed', '=', 0)->or('users.email', 'LIKE', '%'.$searchValue.'%')->and('removed', '=', 0)->or('roles.name', 'LIKE', '%'.$searchValue.'%')->and('users.username','!=', Session::get('username'))->and('removed', '=', 0)->fetch();
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