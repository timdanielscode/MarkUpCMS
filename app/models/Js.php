<?php

/** 
 * js table
 * 
 * column id: to use as an unique identifier
 * column file_name: to distinguish js files and to use as a reference for js files
 * column author: to know who created the js file
 * column has_content: to confirm js file does contains data
 * column removed: to not direct permanently delete js files 
 * column created_at: to know when a category is been created
 * column updated_at: to know when a category is been updated
 */

namespace app\models;

use database\DB;

class Js extends Model {

    private static $_table = "js";
    private static $_columns = [];
    private static $_pageIds = [];

    public static function ifRowExists($id) {

        return DB::try()->select('id')->from(self::$_table)->where('id', '=', $id)->first();
    }

    public static function allJsButOrderedOnDate() {

        return DB::try()->select('id, file_name, extension, author, has_content, removed, updated_at, created_at')->from(self::$_table)->where('removed', '=', 0)->order('updated_at')->desc()->fetch();
    }

    public static function getAll($columns) {

        self::$_columns = implode(',', $columns);
        return DB::try()->select(self::$_columns)->from(self::$_table)->fetch();
    }

    public static function getFilenameExtension($pageId) {

        return DB::try()->select('file_name', 'extension')->from('js')->join('js_page')->on('js_page.js_id', '=', 'js.id')->where('js_page.page_id', '=', $pageId)->fetch();
    }

    public static function jsFilesOnSearch($searchValue) {

        if(!empty($searchValue) && $searchValue !== null) {

            if($searchValue == 'Thrashcan') {

                return DB::try()->select('id, file_name, extension, author, has_content, removed, updated_at, created_at')->from(self::$_table)->where('removed', '=', 1)->order('updated_at')->desc()->fetch();
            }

            return DB::try()->select('id, file_name, extension, author, has_content, removed, updated_at, created_at')->from(self::$_table)->where('removed', '=', 0)->and('file_name', 'LIKE', '%'.$searchValue.'%')->or('removed', '=', 0)->and('author', 'LIKE', '%'.$searchValue.'%')->order('updated_at')->desc()->fetch();
        }
    }

    public static function getAllJs() {

        return DB::try()->select('id, file_name, extension')->from(self::$_table)->where('removed', '!=', 1)->fetch();
    }

    public static function checkUniqueFilenameId($filename, $id) {

        return DB::try()->select('file_name')->from(self::$_table)->where('file_name', '=', $filename)->and('id', '!=', $id)->fetch();
    }

    public static function getPageAssignedIdTitle($id) {

        return DB::try()->select('id, title')->from('pages')->join('js_page')->on('pages.id', '=', 'js_page.page_id')->where('js_page.js_id', '=', $id)->and('pages.removed', '!=', 1)->fetch();
    }

    public static function getNotPageAssingedIdTitle($pageAssignedIdTitle) {

        if(!empty($pageAssignedIdTitle) && $pageAssignedIdTitle !== null) {

            foreach($pageAssignedIdTitle as $page) {

                array_push(self::$_pageIds, $page['id']);
            }

            $pageIdsString = implode(',', self::$_pageIds);

            return DB::try()->select('id, title')->from('pages')->whereNotIn('id', $pageIdsString)->and('pages.removed', '!=', 1)->fetch();
        } else {
            return DB::try()->select('id, title')->from('pages')->where('pages.removed', '!=', 1)->fetch();
        }
    }
}