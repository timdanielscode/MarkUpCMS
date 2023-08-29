<?php

namespace app\models;

use database\DB;

class Css extends Model {

    public function __construct() {

        self::table('css');
    }

    public function ifRowExists($id) {

        return DB::try()->select('id')->from('css')->where('id', '=', $id)->first();
    }

    public function allCssButOrderedOnDate() {

        return DB::try()->select('id, file_name, extension, has_content, removed, updated_at, created_at')->from('css')->where('removed', '=', 0)->order('updated_at')->desc()->fetch();
    }

    public function cssFilesOnSearch($searchValue) {

        if(!empty($searchValue) && $searchValue !== null) {

            if($searchValue == 'Thrashcan') {

                return DB::try()->select('id, file_name, extension, has_content, removed, updated_at, created_at')->from('css')->where('removed', '=', 1)->order('updated_at')->desc()->fetch();
            }

            return DB::try()->select('id, file_name, extension, has_content, removed, updated_at, created_at')->from('css')->where('removed', '=', 0)->and('file_name', 'LIKE', '%'.$searchValue.'%')->order('updated_at')->desc()->fetch();
        }
    }
}