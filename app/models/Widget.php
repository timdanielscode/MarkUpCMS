<?php

namespace app\models;

use database\DB;

class Widget extends Model {

    public function __construct() {

        self::table('widgets');
    }

    public function ifRowExists($id) {

        return DB::try()->select('id')->from('widgets')->where('id', '=', $id)->first();
    }

    public function allWidgetsButOrderedOnDate() {

        return DB::try()->all('widgets')->where('removed', 'IS', NULL)->or('removed', '=', '0')->order('updated_at')->desc()->fetch();
    }

    public function widgetsOnSearch($searchValue) {

        if(!empty($searchValue) && $searchValue !== null) {

            if($searchValue == 'Thrashcan') {
                
                return DB::try()->all('widgets')->where('removed', '=', 1)->order('updated_at')->desc()->fetch();
            }

            return DB::try()->all('widgets')->where('title', 'LIKE', '%'.$searchValue.'%')->or('author', 'LIKE', '%'.$searchValue.'%')->or('updated_at', 'LIKE', '%'.$searchValue.'%')->order('updated_at')->desc()->fetch();
        }
    }
}