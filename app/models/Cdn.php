<?php

namespace app\models;

use database\DB;

class Cdn extends Model {

    public function __construct() {

        self::table('cdn');
    }

    public function ifRowExists($id) {

        return DB::try()->select('id')->from('cdn')->where('id', '=', $id)->first();
    }

    public function allCdnsButOrderedByDate() {

        return DB::try()->select('id, title, content, has_content, removed, author, created_at, updated_at')->from('cdn')->where('removed', '=', 0)->order('updated_at')->desc()->fetch();
    }

    public function orderedCdnsOnSearch($searchValue = null) {

        if(!empty($searchValue) && $searchValue !== null) {

            if($searchValue == 'Thrashcan') {

                return DB::try()->select('id, title, author, removed, updated_at, created_at')->from('cdn')->where('removed', '=', 1)->order('updated_at')->desc()->fetch();
            } 

            return DB::try()->select('id, title, author, removed, updated_at, created_at')->from('cdn')->where('title', 'LIKE', '%'.$searchValue.'%')->or('author', 'LIKE', '%'.$searchValue.'%')->and('removed', '!=', 1)->order('updated_at')->desc()->fetch();
        } 
    }
}