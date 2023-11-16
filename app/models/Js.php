<?php

namespace app\models;

use database\DB;

class Js extends Model {

    private static $_table = "js";
    private static $_postIds = [];

    public static function ifRowExists($id) {

        return DB::try()->select('id')->from(self::$_table)->where('id', '=', $id)->first();
    }

    public static function allJsButOrderedOnDate() {

        return DB::try()->select('id, file_name, extension, author, has_content, removed, updated_at, created_at')->from(self::$_table)->where('removed', '=', 0)->order('updated_at')->desc()->fetch();
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

    public static function getPostAssignedIdTitle($id) {

        return DB::try()->select('id, title')->from('pages')->join('js_page')->on('pages.id', '=', 'js_page.page_id')->where('js_page.js_id', '=', $id)->and('pages.removed', '!=', 1)->fetch();
    }

    public static function getNotPostAssingedIdTitle($postAssignedIdTitle) {

        if(!empty($postAssignedIdTitle) && $postAssignedIdTitle !== null) {

            foreach($postAssignedIdTitle as $post) {

                array_push(self::$_postIds, $post['id']);
            }

            $postIdsString = implode(',', self::$_postIds);

            return DB::try()->select('id, title')->from('pages')->whereNotIn('id', $postIdsString)->and('pages.removed', '!=', 1)->fetch();
        } else {
            return DB::try()->select('id, title')->from('pages')->where('pages.removed', '!=', 1)->fetch();
        }
    }
}