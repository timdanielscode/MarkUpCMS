<?php

namespace app\models;

use database\DB;

class Cdn extends Model {

    private $_postCdnIds = [];

    public function __construct() {

        self::table('cdn');
    }

    public function ifRowExists($id) {

        return DB::try()->select('id')->from('cdn')->where('id', '=', $id)->first();
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

    public function getPostCdn($postId) {

        if(!empty($postId) && $postId !== null) {

            return DB::try()->select('id, title')->from('cdn')->join('cdn_page')->on("cdn_page.cdn_id", '=', 'cdn.id')->where('cdn_page.page_id', '=', $postId)->and('removed', '!=', 1)->fetch();
        }
    }

    public function getNotPostCdn($postCdn) {

        if(!empty($postCdn) && $postCdn !== null) {

            foreach($postCdn as $cdn) {

                array_push($this->_postCdnIds, $cdn['id']);
            }

            $postCdnIdsString = implode(',', $this->_postCdnIds);
            return DB::try()->select('id, title')->from('cdn')->whereNotIn('id', $postCdnIdsString)->fetch();
        } else {

            return $this->getAllCdn();
        }
    }

    public function removePostCdn($postId, $cdnId) {

        if(!empty($postId) && $postId !== null && !empty($cdnId) && $cdnId !== null) {

            return DB::try()->delete('cdn_page')->where('page_id', '=', $postId)->and('cdn_id', '=', $cdnId)->run();
        }
    }
}