<?php

namespace app\models;

class Roles extends Model {

    public function __construct() {

        self::table('roles');
    }
}