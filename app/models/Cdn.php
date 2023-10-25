<?php

namespace app\models;

use database\DB;

class Cdn extends Model {

    private $_postIds = [];

    public function __construct() {

        self::table('cdn');
    }

    public function ifRowExists($id) {

        return DB::try()->select('id')->from('cdn')->where('id', '=', $id)->first();
    }

    public function getData($id, $columns) {

        if(!empty($columns) && $columns !== null) {

            $this->_columns = implode(',', $columns);
            return DB::try()->select($this->_columns)->from('cdn')->where('id', '=', $id)->first();
        }
    }

    public function allCdnsButOrderedByDate() {

        return DB::try()->select('id, title, content, has_content, removed, author, created_at, updated_at')->from('cdn')->where('removed', '=', 0)->order('created_at')->desc()->fetch();
    }

    public function orderedCdnsOnSearch($searchValue = null) {

        if(!empty($searchValue) && $searchValue !== null) {

            if($searchValue == 'Thrashcan') {

                return DB::try()->select('id, title, author, removed, updated_at, created_at')->from('cdn')->where('removed', '=', 1)->order('created_at')->desc()->fetch();
            } 

            return DB::try()->select('id, title, author, removed, updated_at, created_at')->from('cdn')->where('removed', '=', 0)->and('title', 'LIKE', '%'.$searchValue.'%')->or('author', 'LIKE', '%'.$searchValue.'%')->and('removed', '=', 0)->order('created_at')->desc()->fetch();
        } 
    }

    public function getAllCdn() {

        return DB::try()->select('id, title')->from('cdn')->where('removed', '!=', 1)->fetch();
    }

    public function checkUniqueTitle($title) {

        return DB::try()->select('id')->from('cdn')->where('title', '=', $title)->fetch();
    }

    public function checkUniqueTitleId($title, $id) {

        return DB::try()->select('id')->from('cdn')->where('title', '=', $title)->and('id', '!=', $id)->fetch();
    }

    public function getPostImportedIdTitle($id) {

        return DB::try()->select('id, title')->from('pages')->join('cdn_page')->on('cdn_page.page_id', '=', 'pages.id')->where('cdn_page.cdn_id', '=', $id)->and('pages.removed', '!=', 1)->fetch();
    }

    public function getNotPostImportedIdTitle($postImportedIdTitle) {

        if(!empty($postImportedIdTitle) && $postImportedIdTitle !== null) {

            foreach($postImportedIdTitle as $post) {

                array_push($this->_postIds, $post['id']);
            }

            $postIdsString = implode(',', $this->_postIds);

            return DB::try()->select('id, title')->from('pages')->whereNotIn('id', $postIdsString)->and('removed', '!=', 1)->fetch();
        } else {
            return DB::try()->select('id, title')->from('pages')->where('removed', '!=', 1)->fetch();
        }
    }

    public function deleteIdPostId($id, $postId) {
        
        return DB::try()->delete('cdn_page')->where('cdn_page.cdn_id', '=', $id)->and('cdn_page.page_id', '=', $postId)->run();
    }
}