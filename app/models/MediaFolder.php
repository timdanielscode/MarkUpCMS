<?php

/** 
 * mediaFolders table
 * 
 * column id: to use as an unique identifier
 * column folder_name: for file validation
 */

namespace app\models;

use database\DB;

class MediaFolder extends Model {

    private static $_table = "mediaFolders";
    private static $_columns;

    public static function getAll($columns) {

        self::$_columns = implode(',', $columns);
        return DB::try()->select(self::$_columns)->from(self::$_table)->fetch();
    } 
}