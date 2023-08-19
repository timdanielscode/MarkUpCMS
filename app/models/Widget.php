<?php

namespace app\models;

use database\DB;

class Widget extends Model {

    public function __construct() {

        self::table('widgets');
    }
}