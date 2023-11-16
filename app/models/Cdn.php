<?php

namespace app\models;

use database\DB;

class Cdn extends Model {

    private static $_table = "cdn";
    private static $_postIds = [];

    public static function ifRowExists($id) {

        return DB::try()->select('id')->from(self::$_table)->where('id', '=', $id)->first();
    }

    public static function allCdnsButOrderedByDate() {

        return DB::try()->select('id, title, content, has_content, removed, author, created_at, updated_at')->from(self::$_table)->where('removed', '=', 0)->order('created_at')->desc()->fetch();
    }

    public static function orderedCdnsOnSearch($searchValue = null) {

        if(!empty($searchValue) && $searchValue !== null) {

            if($searchValue == 'Thrashcan') {

                return DB::try()->select('id, title, author, removed, updated_at, created_at')->from(self::$_table)->where('removed', '=', 1)->order('created_at')->desc()->fetch();
            } 

            return DB::try()->select('id, title, author, removed, updated_at, created_at')->from(self::$_table)->where('removed', '=', 0)->and('title', 'LIKE', '%'.$searchValue.'%')->or('author', 'LIKE', '%'.$searchValue.'%')->and('removed', '=', 0)->order('created_at')->desc()->fetch();
        } 
    }

    public static function getAllCdn() {

        return DB::try()->select('id, title')->from(self::$_table)->where('removed', '!=', 1)->fetch();
    }

    public static function checkUniqueTitleId($title, $id) {

        return DB::try()->select('id')->from(self::$_table)->where('title', '=', $title)->and('id', '!=', $id)->fetch();
    }

    public static function getPostImportedIdTitle($id) {

        return DB::try()->select('id, title')->from('pages')->join('cdn_page')->on('cdn_page.page_id', '=', 'pages.id')->where('cdn_page.cdn_id', '=', $id)->and('pages.removed', '!=', 1)->fetch();
    }

    public static function getNotPostImportedIdTitle($postImportedIdTitle) {

        if(!empty($postImportedIdTitle) && $postImportedIdTitle !== null) {

            foreach($postImportedIdTitle as $post) {

                array_push(self::$_postIds, $post['id']);
            }

            $postIdsString = implode(',', self::$_postIds);

            return DB::try()->select('id, title')->from('pages')->whereNotIn('id', $postIdsString)->and('removed', '!=', 1)->fetch();
        } else {
            return DB::try()->select('id, title')->from('pages')->where('removed', '!=', 1)->fetch();
        }
    }

    public static function deleteIdPostId($id, $postId) {
        
        return DB::try()->delete('cdn_page')->where('cdn_page.cdn_id', '=', $id)->and('cdn_page.page_id', '=', $postId)->run();
    }
}