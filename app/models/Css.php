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

        return DB::try()->all('css')->where('removed', 'IS', NULL)->or('removed', '=', '0')->order('updated_at')->desc()->fetch();
    }

    public function cssFilesOnSearch($searchValue) {

        if(!empty($searchValue) && $searchValue !== null) {

            if($searchValue == 'Thrashcan') {

                return DB::try()->all('css')->where('removed', '=', 1)->fetch();
            }

            return DB::try()->all('css')->where('file_name', 'LIKE', '%'.$searchValue.'%')->or('updated_at', 'LIKE', '%'.$searchValue.'%')->order('updated_at')->desc()->fetch();
        }
    }
}