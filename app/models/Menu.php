<?php

namespace app\models;

use database\DB;

class Menu extends Model {

    public function __construct() {

        self::table('menus');
    }

    public function allMenusButOrderedOnDate() {

        $menus = DB::try()->all('menus')->order('date_created_at')->fetch();
        return $this->ifDataExists($menus);
    }

    public function menusOnSearch($searchValue) {

        if(!empty($searchValue) && $searchValue !== null) {

            $menus = DB::try()->all('menus')->where('title', 'LIKE', '%'.$searchValue.'%')->or('author', 'LIKE', '%'.$searchValue.'%')->or('date_created_at', 'LIKE', '%'.$searchValue.'%')->or('time_created_at', 'LIKE', '%'.$searchValue.'%')->or('date_updated_at', 'LIKE', '%'.$searchValue.'%')->or('time_updated_at', 'LIKE', '%'.$searchValue.'%')->fetch();
            return $this->ifDataExists($menus);
        }
    }

}