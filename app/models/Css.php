<?php

namespace app\models;

class Css extends Model {

    public function __construct() {

        self::table('css');
    }


    public $t = "css",

        $id = 'id', 
        $file_name = 'file_name', 
        $extension = 'extension', 
        $date_created_at = 'date_created_at', 
        $time_created_at = 'time_created_at', 
        $date_updated_at = 'date_updated_at', 
        $time_updated_at = 'time_updated_at';
}