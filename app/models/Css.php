<?php

namespace app\models;

use database\DB;

class Css extends Model {

    private static $_table = "css";
    private static $_postIds = [];

    public static function ifRowExists($id) {

        return DB::try()->select('id')->from(self::$_table)->where('id', '=', $id)->first();
    }

    public static function getData($id, $columns) {

        if(!empty($columns) && $columns !== null) {

            $this->_columns = implode(',', $columns);
            return DB::try()->select($this->_columns)->from(self::$_table)->where('id', '=', $id)->first();
        }
    }

    public static function getFilenameExtension($postId) {

        return DB::try()->select('file_name', 'extension')->from('css')->join('css_page')->on('css_page.css_id', '=', 'css.id')->where('css_page.page_id', '=', $postId)->fetch();
    }

    public static function allCssButOrderedOnDate() {

        return DB::try()->select('id, file_name, extension, author, has_content, removed, updated_at, created_at')->from(self::$_table)->where('removed', '=', 0)->order('created_at')->desc()->fetch();
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

    public static function getPostAssignedIdTitle($id) {

        return DB::try()->select('id, title')->from('pages')->join('css_page')->on('pages.id', '=', 'css_page.page_id')->where('css_page.css_id', '=', $id)->and('pages.removed', '!=', 1)->fetch();
    }

    public static function getNotPostAssingedIdTitle($postAssignedIdTitle) {

        if(!empty($postAssignedIdTitle) && $postAssignedIdTitle !== null) {

            foreach($postAssignedIdTitle as $post) {

                array_push(self::$_postIds, $post['id']);
            }

            $postIdsString = implode(',', self::$_postIds);

            return DB::try()->select('id, title')->from('pages')->whereNotIn('id', $postIdsString)->and('removed', '!=', 1)->fetch();
        } else {
            return DB::try()->select('id, title')->from('pages')->where('removed', '!=', 1)->fetch();
        }
    }

}