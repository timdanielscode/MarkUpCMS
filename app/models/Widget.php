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

        return DB::try()->select('id, title, author, has_content, removed, created_at, updated_at')->from('widgets')->where('removed', '=', 0)->order('updated_at')->desc()->fetch();
    }

    public function widgetsOnSearch($searchValue) {

        if(!empty($searchValue) && $searchValue !== null) {

            if($searchValue == 'Thrashcan') {
                
                return DB::try()->select('id, title, author, has_content, removed, created_at, updated_at')->from('widgets')->where('removed', '=', 1)->order('updated_at')->desc()->fetch();
            }

            return DB::try()->select('id, title, author, has_content, removed, created_at, updated_at')->from('widgets')->where('title', 'LIKE', '%'.$searchValue.'%')->and('removed', '=', 0)->or('author', 'LIKE', '%'.$searchValue.'%')->and('removed', '=', 0)->order('updated_at')->desc()->fetch();
        }
    }
}