<?php

namespace app\models;

use database\DB;

class Css extends Model {

    private $_postIds = [];

    public function ifRowExists($id) {

        return DB::try()->select('id')->from('css')->where('id', '=', $id)->first();
    }

    public function getData($id, $columns) {

        if(!empty($columns) && $columns !== null) {

            $this->_columns = implode(',', $columns);
            return DB::try()->select($this->_columns)->from('css')->where('id', '=', $id)->first();
        }
    }

    public function allCssButOrderedOnDate() {

        return DB::try()->select('id, file_name, extension, author, has_content, removed, updated_at, created_at')->from('css')->where('removed', '=', 0)->order('created_at')->desc()->fetch();
    }

    public function cssFilesOnSearch($searchValue) {

        if(!empty($searchValue) && $searchValue !== null) {

            if($searchValue == 'Thrashcan') {

                return DB::try()->select('id, file_name, extension, author, has_content, removed, updated_at, created_at')->from('css')->where('removed', '=', 1)->order('created_at')->desc()->fetch();
            }

            return DB::try()->select('id, file_name, extension, author, has_content, removed, updated_at, created_at')->from('css')->where('removed', '=', 0)->and('file_name', 'LIKE', '%'.$searchValue.'%')->or('removed', '=', 0)->and('author', 'LIKE', '%'.$searchValue.'%')->order('created_at')->desc()->fetch();
        }
    }

    public function getAllCss() {

        return DB::try()->select('id, file_name, extension')->from('css')->where('removed', '!=', 1)->fetch();
    }

    public function checkUniqueFilename($filename) {

        return DB::try()->select('file_name')->from('css')->where('file_name', '=', $filename)->fetch();
    }

    public function checkUniqueFilenameId($filename, $id) {

        return DB::try()->select('file_name')->from('css')->where('file_name', '=', $filename)->and('id', '!=', $id)->fetch();
    }

    public function getPostAssignedIdTitle($id) {

        return DB::try()->select('id, title')->from('pages')->join('css_page')->on('pages.id', '=', 'css_page.page_id')->where('css_page.css_id', '=', $id)->and('pages.removed', '!=', 1)->fetch();
    }

    public function getNotPostAssingedIdTitle($postAssignedIdTitle) {

        if(!empty($postAssignedIdTitle) && $postAssignedIdTitle !== null) {

            foreach($postAssignedIdTitle as $post) {

                array_push($this->_postIds, $post['id']);
            }

            $postIdsString = implode(',', $this->_postIds);

            return DB::try()->select('id, title')->from('pages')->whereNotIn('id', $postIdsString)->and('removed', '!=', 1)->fetch();
        } else {
            return DB::try()->select('id, title')->from('pages')->where('removed', '!=', 1)->fetch();
        }
    }

}