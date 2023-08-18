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

        return DB::try()->all('menus')->where('removed', 'IS', NULL)->or('removed', '=', '0')->order('updated_at')->desc()->fetch();
    }

    public function menusOnSearch($searchValue) {

        if(!empty($searchValue) && $searchValue !== null) {

            if($searchValue == 'Thrashcan') {
                
                return DB::try()->all('menus')->where('removed', '=', 1)->order('updated_at')->desc()->fetch();
            }

            return DB::try()->all('menus')->where('title', 'LIKE', '%'.$searchValue.'%')->or('author', 'LIKE', '%'.$searchValue.'%')->or('updated_at', 'LIKE', '%'.$searchValue.'%')->order('updated_at')->desc()->fetch();
        }
    }
}