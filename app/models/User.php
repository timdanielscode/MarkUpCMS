<?php

namespace app\models;

class User {

    public $t = "users",

        $id = 'id', 
        $username = 'username', 
        $email = 'email', 
        $password = 'password', 
        $retype_password = 'retype_password', 
        $created_at = 'created_at', 
        $updated_at = 'updated_at';
}