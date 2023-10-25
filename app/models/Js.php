<?php

namespace app\models;

use database\DB;

class Js extends Model {

    private $_postIds = [];

    public function __construct() {

        self::table('js');
    }

    public function ifRowExists($id) {

        return DB::try()->select('id')->from('js')->where('id', '=', $id)->first();
    }

    public function getData($id, $columns) {

        if(!empty($columns) && $columns !== null) {

            $this->_columns = implode(',', $columns);
            return DB::try()->select($this->_columns)->from('js')->where('id', '=', $id)->first();
        }
    }

    public function allJsButOrderedOnDate() {

        return DB::try()->select('id, file_name, extension, author, has_content, removed, updated_at, created_at')->from('js')->where('removed', '=', 0)->order('updated_at')->desc()->fetch();
    }

    public function jsFilesOnSearch($searchValue) {

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

    public function checkUniqueFilename($filename) {

        return  DB::try()->select('file_name')->from('js')->where('file_name', '=', $filename)->fetch();
    }

    public function checkUniqueFilenameId($filename, $id) {

        return DB::try()->select('file_name')->from('js')->where('file_name', '=', $filename)->and('id', '!=', $id)->fetch();
    }

    public function getPostAssignedIdTitle($id) {

        return DB::try()->select('id, title')->from('pages')->join('js_page')->on('pages.id', '=', 'js_page.page_id')->where('js_page.js_id', '=', $id)->and('pages.removed', '!=', 1)->fetch();
    }

    public function getNotPostAssingedIdTitle($postAssignedIdTitle) {

        if(!empty($postAssignedIdTitle) && $postAssignedIdTitle !== null) {

            foreach($postAssignedIdTitle as $post) {

                array_push($this->_postIds, $post['id']);
            }

            $postIdsString = implode(',', $this->_postIds);

            return DB::try()->select('id, title')->from('pages')->whereNotIn('id', $postIdsString)->and('pages.removed', '!=', 1)->fetch();
        } else {
            return DB::try()->select('id, title')->from('pages')->where('pages.removed', '!=', 1)->fetch();
        }
    }
}