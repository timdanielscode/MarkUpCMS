<?php

/** 
 * css table
 * 
 * column id: to use as an unique identifier
 * column file_name: to distinguish css files and to use as a reference for css files
 * column author: to know who created the css file
 * column has_content: to confirm css file does contains data
 * column removed: to not direct permanently delete css files 
 * column created_at: to know when a category is been created
 * column updated_at: to know when a category is been updated
 */

namespace app\models;

use database\DB;

class Css extends Model {

    private static $_table = "css";
    private static $_columns = [];
    private static $_pageIds = [];

    public static function ifRowExists($id) {

        return DB::try()->select('id')->from(self::$_table)->where('id', '=', $id)->first();
    }

    public static function getData($id, $columns) {

        if(!empty($columns) && $columns !== null) {

            $this->_columns = implode(',', $columns);
            return DB::try()->select($this->_columns)->from(self::$_table)->where('id', '=', $id)->first();
        }
    }

    public static function getAll($columns) {

        self::$_columns = implode(',', $columns);
        return DB::try()->select(self::$_columns)->from(self::$_table)->fetch();
    }

    public static function getFilenameExtension($pageId) {

        return DB::try()->select('file_name', 'extension')->from('css')->join('css_page')->on('css_page.css_id', '=', 'css.id')->where('css_page.page_id', '=', $pageId)->fetch();
    }

    public static function allCssButOrderedOnDate() {

        return DB::try()->select('id, file_name, extension, author, has_content, removed, updated_at, created_at')->from(self::$_table)->where('removed', '=', 0)->order('updated_at')->desc()->fetch();
    }

    public static function cssFilesOnSearch($searchValue) {

        if(!empty($searchValue) && $searchValue !== null) {

            if($searchValue == 'Thrashcan') {

                return DB::try()->select('id, file_name, extension, author, has_content, removed, updated_at, created_at')->from(self::$_table)->where('removed', '=', 1)->order('created_at')->desc()->fetch();
            }

            return DB::try()->select('id, file_name, extension, author, has_content, removed, updated_at, created_at')->from(self::$_table)->where('removed', '=', 0)->and('file_name', 'LIKE', '%'.$searchValue.'%')->or('removed', '=', 0)->and('author', 'LIKE', '%'.$searchValue.'%')->order('created_at')->desc()->fetch();
        }
    }

    public static function getAllCss() {

        return DB::try()->select('id, file_name, extension')->from(self::$_table)->where('removed', '!=', 1)->fetch();
    }

    public static function checkUniqueFilename($filename) {

        return DB::try()->select('file_name')->from(self::$_table)->where('file_name', '=', $filename)->fetch();
    }

    public static function checkUniqueFilenameId($filename, $id) {

        return DB::try()->select('file_name')->from(self::$_table)->where('file_name', '=', $filename)->and('id', '!=', $id)->fetch();
    }

    public static function getPageAssignedIdTitle($id) {

        return DB::try()->select('id, title')->from('pages')->join('css_page')->on('pages.id', '=', 'css_page.page_id')->where('css_page.css_id', '=', $id)->and('pages.removed', '!=', 1)->fetch();
    }

    public static function getNotPageAssingedIdTitle($pageAssignedIdTitle) {

        if(!empty($pageAssignedIdTitle) && $pageAssignedIdTitle !== null) {

            foreach($pageAssignedIdTitle as $page) {

                array_push(self::$_pageIds, $page['id']);
            }

            $pageIdsString = implode(',', self::$_pageIds);

            return DB::try()->select('id, title')->from('pages')->whereNotIn('id', $pageIdsString)->and('removed', '!=', 1)->fetch();
        } else {
            return DB::try()->select('id, title')->from('pages')->where('removed', '!=', 1)->fetch();
        }
    }
}