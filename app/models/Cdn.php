<?php

namespace app\models;

class Cdn extends Model {

    public function __construct() {

        self::table('cdn');
    }
}