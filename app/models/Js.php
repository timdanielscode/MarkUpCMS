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

        return DB::try()->all('js')->where('removed', 'IS', NULL)->or('removed', '=', '0')->order('updated_at')->desc()->fetch();
    }

    public function cssFilesOnSearch($searchValue) {

        if(!empty($searchValue) && $searchValue !== null) {

            if($searchValue == 'removed') {

                return DB::try()->all('js')->where('removed', '=', 1)->order('updated_at')->desc()->fetch();
            }

            return DB::try()->all('js')->where('file_name', 'LIKE', '%'.$searchValue.'%')->or('updated_at', 'LIKE', '%'.$searchValue.'%')->order('updated_at')->desc()->fetch();
        }
    }
}