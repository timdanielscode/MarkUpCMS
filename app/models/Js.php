<?php

namespace app\models;

use database\DB;

class Js extends Model {

    public function __construct() {

        self::table('js');
    }

    public function ifRowExists($id) {

        return DB::try()->select('id')->from('js')->where('id', '=', $id)->first();
    }

    public function allJsButOrderedOnDate() {

        return DB::try()->select('id, file_name, extension, has_content, removed, updated_at, created_at')->from('js')->where('removed', '=', 0)->order('updated_at')->desc()->fetch();
    }

    public function cssFilesOnSearch($searchValue) {

        if(!empty($searchValue) && $searchValue !== null) {

            if($searchValue == 'Thrashcan') {

                return DB::try()->select('id, file_name, extension, has_content, removed, updated_at, created_at')->from('js')->where('removed', '=', 1)->fetch();
            }

            return DB::try()->select('id, file_name, extension, has_content, removed, updated_at, created_at')->from('js')->where('removed', '=', 0)->and('file_name', 'LIKE', '%'.$searchValue.'%')->fetch();
        }
    }
}