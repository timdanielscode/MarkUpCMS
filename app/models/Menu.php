<?php

namespace app\models;

use database\DB;

class Menu extends Model {

    public function __construct() {

        self::table('menus');
    }

    public function ifRowExists($id) {

        return DB::try()->select('id')->from('menus')->where('id', '=', $id)->first();
    }

    public function allMenusButOrderedOnDate() {

        return DB::try()->all('menus')->where('removed', 'IS', NULL)->or('removed', '=', '0')->order('date_created_at')->fetch();
    }

    public function menusOnSearch($searchValue) {

        if(!empty($searchValue) && $searchValue !== null) {

            if($searchValue == 'removed') {
                
                return DB::try()->all('menus')->where('removed', '=', 1)->fetch();
            }

            return DB::try()->all('menus')->where('title', 'LIKE', '%'.$searchValue.'%')->or('author', 'LIKE', '%'.$searchValue.'%')->or('date_created_at', 'LIKE', '%'.$searchValue.'%')->or('time_created_at', 'LIKE', '%'.$searchValue.'%')->or('date_updated_at', 'LIKE', '%'.$searchValue.'%')->or('time_updated_at', 'LIKE', '%'.$searchValue.'%')->fetch();
        }
    }
}