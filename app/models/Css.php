<?php

namespace app\models;

use database\DB;

class Css extends Model {

    public function __construct() {

        self::table('css');
    }

    public function allCssButOrderedOnDate() {

        $allCss = DB::try()->all('css')->order('date_created_at')->fetch();
        return $allCss;
    }

    public function cssFilesOnSearch($searchValue) {

        if(!empty($searchValue) && $searchValue !== null) {

            $cssFiles = DB::try()->all('css')->where('file_name', 'LIKE', '%'.$searchValue.'%')->or('date_created_at', 'LIKE', '%'.$searchValue.'%')->or('time_created_at', 'LIKE', '%'.$searchValue.'%')->or('date_updated_at', 'LIKE', '%'.$searchValue.'%')->or('time_updated_at', 'LIKE', '%'.$searchValue.'%')->fetch();
            return $allCss;
        }
    }
}