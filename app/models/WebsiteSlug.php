<?php

namespace app\models;

use database\DB;

class WebsiteSlug extends Model {

    private static $_table = "websiteSlug";
    private static $_columns = [];

    public static function getData($columns) {

        if(!empty($columns) && $columns !== null) {

            $columns = implode(',', $columns);
            return DB::try()->select($columns)->from(self::$_table)->first();
        }
    }
}