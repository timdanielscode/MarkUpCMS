<?php

namespace app\models;

class UserRole extends Model {

    public function __construct() {

        self::table("user_role");
    }
}