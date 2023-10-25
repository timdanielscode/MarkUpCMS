<?php

namespace app\models;

use database\DB;

class Menu extends Model {

    private $_columns;

    public function __construct() {

        self::table('menus');
    }

    public function ifRowExists($id) {

        return DB::try()->select('id')->from('menus')->where('id', '=', $id)->first();
    }

    public function getData($id, $columns) {

        if(!empty($columns) && $columns !== null) {

            $this->_columns = implode(',', $columns);
            return DB::try()->select($this->_columns)->from('menus')->where('id', '=', $id)->first();
        }
    }

    public function allMenusButOrderedOnDate() {

        return DB::try()->select('id, title, has_content, position, ordering, author, removed, updated_at, created_at')->from('menus')->where('removed', '=', 0)->order('updated_at')->desc()->fetch();
    }

    public function menusOnSearch($searchValue) {

        if(!empty($searchValue) && $searchValue !== null) {

            if($searchValue == 'Thrashcan') {
                
                return DB::try()->select('id, title, has_content, position, ordering, author, removed, updated_at, created_at')->from('menus')->where('removed', '=', 1)->order('updated_at')->desc()->fetch();
            }

            return DB::try()->select('id, title, has_content, position, ordering, author, removed, updated_at, created_at')->from('menus')->where('removed', '=', 0)->and('title', 'LIKE', '%'.$searchValue.'%')->or('author', 'LIKE', '%'.$searchValue.'%')->and('removed', '=', 0)->or('ordering', 'LIKE', '%'.$searchValue.'%')->and('removed', '=', 0)->or('position', 'LIKE', '%'.$searchValue.'%')->and('removed', '=', 0)->order('updated_at')->desc()->fetch();
        }
    }

    public function getTopMenus() {

        return DB::try()->all('menus')->where('position', '=', 'top')->and('removed', '!=', 1)->order('ordering')->fetch();
    }

    public function getBottomMenus() {

        return DB::try()->all('menus')->where('position', '=', 'bottom')->and('removed', '!=', 1)->order('ordering')->fetch();
    }

    public function checkUniqueTitle($title) {

        return DB::try()->select('title')->from('menus')->where('title', '=', $title)->fetch();
    }

    public function checkUniqueTitleId($title, $id) {

        return DB::try()->select('title')->from('menus')->where('title', '=', $title)->and('id', '!=', $id)->fetch();
    }
}