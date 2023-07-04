<?php

namespace app\models;

class UserRole extends Model {

    public function __construct() {

        self::table("user_role");
    }

    public $t = 'user_role',

        $user_id = 'user_id', 
        $role_id = 'role_id';
}