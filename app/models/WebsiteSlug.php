<?php

/** 
 * websiteSlug table 
 * 
 * column id: to use as an unique identifier
 * column slug: to modify the login slug 
 * column updated_at: to know when a slug has been modified
 */

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