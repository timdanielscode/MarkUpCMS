<?php

namespace app\models;

use database\DB;

class Meta extends Model {

    private static $_table = "metas";
    private static $_pageIds = [];

    public static function ifRowExists($id) {

        return DB::try()->select('id')->from(self::$_table)->where('id', '=', $id)->first();
    }

    public static function allMetaButOrderedByDate() {

        return DB::try()->select('id, title, content, has_content, removed, author, created_at, updated_at')->from(self::$_table)->where('removed', '=', 0)->order('updated_at')->desc()->fetch();
    }

    public static function getContent($pageId) {

        return DB::try()->select('content')->from(self::$_table)->join('meta_page')->on('metas.id', '=', 'meta_page.meta_id')->where('meta_page.page_id', '=', $pageId)->and('removed', '!=', 1)->fetch();
    }

    public static function orderedMetaOnSearch($searchValue = null) {

        if(!empty($searchValue) && $searchValue !== null) {

            if($searchValue == 'Thrashcan') {

                return DB::try()->select('id, title, author, removed, updated_at, created_at')->from(self::$_table)->where('removed', '=', 1)->order('created_at')->desc()->fetch();
            } 

            return DB::try()->select('id, title, author, removed, updated_at, created_at')->from(self::$_table)->where('removed', '=', 0)->and('title', 'LIKE', '%'.$searchValue.'%')->or('author', 'LIKE', '%'.$searchValue.'%')->and('removed', '=', 0)->order('created_at')->desc()->fetch();
        } 
    }

    public static function getAllMeta() {

        return DB::try()->select('id, title')->from(self::$_table)->where('removed', '!=', 1)->fetch();
    }

    public static function checkUniqueTitleId($title, $id) {

        return DB::try()->select('id')->from(self::$_table)->where('title', '=', $title)->and('id', '!=', $id)->fetch();
    }

    public static function getPageImportedIdTitle($id) {

        return DB::try()->select('id, title')->from('pages')->join('meta_page')->on('meta_page.page_id', '=', 'pages.id')->where('meta_page.meta_id', '=', $id)->and('pages.removed', '!=', 1)->fetch();
    }

    public static function getNotPageImportedIdTitle($pageImportedIdTitle) {

        if(!empty($pageImportedIdTitle) && $pageImportedIdTitle !== null) {

            foreach($pageImportedIdTitle as $page) {

                array_push(self::$_pageIds, $page['id']);
            }

            $pageIdsString = implode(',', self::$_pageIds);

            return DB::try()->select('id, title')->from('pages')->whereNotIn('id', $pageIdsString)->and('removed', '!=', 1)->fetch();
        } else {
            return DB::try()->select('id, title')->from('pages')->where('removed', '!=', 1)->fetch();
        }
    }

    public static function deleteIdPageId($id, $pageId) {
        
        return DB::try()->delete('meta_page')->where('meta_page.meta_id', '=', $id)->and('meta_page.page_id', '=', $pageId)->run();
    }
}