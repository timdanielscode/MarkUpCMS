<?php

/** 
 * media table 
 * 
 * column id: to use as an unique identifier
 * column media_filename: to load files correctly and distinguish media files and to use as a reference for media files
 * column media_folder, media_filetype: to load files correctly
 * column media_filesize: to know size of file
 * column media_description: to know who created the category
 * column created_at: to know when a page is been created
 * column updated_at: to know when a page is been updated
 */

namespace app\models;

use database\DB;

class Media extends Model {

    private static $_table = "media";
    private static $_columns = [];

    public static function ifRowExists($id) {

        return DB::try()->select('id')->from(self::$_table)->where('id', '=', $id)->first();
    }

    public static function allMediaButOrdered() {

        return DB::try()->all(self::$_table)->order('updated_at')->desc()->fetch();
    }
    
    public static function mediaFilesOnSearch($searchValue) {

        return DB::try()->all(self::$_table)->where('media_filetype', 'LIKE', '%'.$searchValue.'%')->or('media_filename', 'LIKE', '%'.$searchValue.'%')->or('media_description', 'LIKE', '%'.$searchValue.'%')->order('created_at')->desc()->fetch();
    }

    public static function getAll($columns) {

        self::$_columns = implode(',', $columns);
        return DB::try()->select(self::$_columns)->from(self::$_table)->fetch();
    }

    public static function checkMediaFilenameOnId($filename, $id) {

        return DB::try()->select('media_filename')->from(self::$_table)->where('media_filename', '=', $filename)->and('id', '!=', $id)->fetch();
    }

    public static function getOnType($sql) {

        return DB::try()->raw($sql)->fetch();
    }
}