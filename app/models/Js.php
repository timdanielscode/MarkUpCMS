<?php

namespace app\models;

use database\DB;

class Js extends Model {

    public function __construct() {

        self::table('js');
    }

    public function allJsButOrderedOnDate() {

        return DB::try()->all('js')->order('date_created_at')->fetch();
    }

    public function cssFilesOnSearch($searchValue) {

        if(!empty($searchValue) && $searchValue !== null) {

            return DB::try()->all('js')->where('file_name', 'LIKE', '%'.$searchValue.'%')->or('date_created_at', 'LIKE', '%'.$searchValue.'%')->or('time_created_at', 'LIKE', '%'.$searchValue.'%')->or('date_updated_at', 'LIKE', '%'.$searchValue.'%')->or('time_updated_at', 'LIKE', '%'.$searchValue.'%')->fetch();
        }
    }
}