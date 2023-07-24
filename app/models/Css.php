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

        return DB::try()->all('css')->order('date_created_at')->fetch();
    }

    public function cssFilesOnSearch($searchValue) {

        if(!empty($searchValue) && $searchValue !== null) {

            return DB::try()->all('css')->where('file_name', 'LIKE', '%'.$searchValue.'%')->or('date_created_at', 'LIKE', '%'.$searchValue.'%')->or('time_created_at', 'LIKE', '%'.$searchValue.'%')->or('date_updated_at', 'LIKE', '%'.$searchValue.'%')->or('time_updated_at', 'LIKE', '%'.$searchValue.'%')->fetch();
        }
    }
}