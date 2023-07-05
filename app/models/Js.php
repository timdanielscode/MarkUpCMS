<?php

namespace app\models;

use database\DB;

class Js extends Model {

    public function __construct() {

        self::table('js');
    }

    public function allJsButOrderedOnDate() {

        $allJs = DB::try()->all('js')->order('date_created_at')->fetch();
        return $this->ifDataExists($allJs);
    }

    public function cssFilesOnSearch($searchValue) {

        if(!empty($searchValue) && $searchValue !== null) {

            $jsFiles = DB::try()->all('js')->where('file_name', 'LIKE', '%'.$searchValue.'%')->or('date_created_at', 'LIKE', '%'.$searchValue.'%')->or('time_created_at', 'LIKE', '%'.$searchValue.'%')->or('date_updated_at', 'LIKE', '%'.$searchValue.'%')->or('time_updated_at', 'LIKE', '%'.$searchValue.'%')->fetch();
            return $this->ifDataExists($jsFiles);
        }
    }


}