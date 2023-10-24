<?php

namespace app\models;

use database\DB;

class Js extends Model {

    private $_postJsIds = [];

    public function __construct() {

        self::table('js');
    }

    public function ifRowExists($id) {

        return DB::try()->select('id')->from('js')->where('id', '=', $id)->first();
    }

    public function allJsButOrderedOnDate() {

        return DB::try()->select('id, file_name, extension, author, has_content, removed, updated_at, created_at')->from('js')->where('removed', '=', 0)->order('updated_at')->desc()->fetch();
    }

    public function cssFilesOnSearch($searchValue) {

        if(!empty($searchValue) && $searchValue !== null) {

            if($searchValue == 'Thrashcan') {

                return DB::try()->select('id, file_name, extension, author, has_content, removed, updated_at, created_at')->from('js')->where('removed', '=', 1)->order('updated_at')->desc()->fetch();
            }

            return DB::try()->select('id, file_name, extension, author, has_content, removed, updated_at, created_at')->from('js')->where('removed', '=', 0)->and('file_name', 'LIKE', '%'.$searchValue.'%')->or('removed', '=', 0)->and('author', 'LIKE', '%'.$searchValue.'%')->order('updated_at')->desc()->fetch();
        }
    }

    public function getAllJs() {

        return DB::try()->select('id, file_name, extension')->from('js')->where('removed', '!=', 1)->fetch();
    }

    public function getPostJs($postId) {

        if(!empty($postId) && $postId !== null) {

            return DB::try()->select('js.id, file_name', 'extension')->from('js')->join('js_page')->on('js_page.js_id', '=', 'js.id')->where('js_page.page_id', '=', $postId)->and('removed', '!=', 1)->fetch();
        }
    }

    public function getNotPostJs($postJs) {

        if(!empty($postJs) && $postJs !== null) {

            foreach($postJs as $js) {

                array_push($this->_postJsIds, $js['id']);
            }

            $postJsIdsString = implode(',', $this->_postJsIds);
            return DB::try()->select('id, file_name, extension')->from('js')->whereNotIn('id', $postJsIdsString)->fetch();
        } else {

            return $this->getAllJs();
        }
    }


}