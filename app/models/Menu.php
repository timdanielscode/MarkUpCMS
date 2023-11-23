<?php

namespace app\models;

use database\DB;

class Menu extends Model {

    private static $_table = "menus";
    private static $_columns = [];

    public static function ifRowExists($id) {

        return DB::try()->select('id')->from(self::$_table)->where('id', '=', $id)->first();
    }

    public static function allMenusButOrderedOnDate() {

        return DB::try()->select('id, title, has_content, position, ordering, author, removed, updated_at, created_at')->from(self::$_table)->where('removed', '=', 0)->order('updated_at')->desc()->fetch();
    }

    public static function getAll($columns) {

        self::$_columns = implode(',', $columns);
        return DB::try()->select(self::$_columns)->from(self::$_table)->fetch();
    }

    public static function menusOnSearch($searchValue) {

        if(!empty($searchValue) && $searchValue !== null) {

            if($searchValue == 'Thrashcan') {
                
                return DB::try()->select('id, title, has_content, position, ordering, author, removed, updated_at, created_at')->from(self::$_table)->where('removed', '=', 1)->order('created_at')->desc()->fetch();
            }

            return DB::try()->select('id, title, has_content, position, ordering, author, removed, updated_at, created_at')->from(self::$_table)->where('removed', '=', 0)->and('title', 'LIKE', '%'.$searchValue.'%')->or('author', 'LIKE', '%'.$searchValue.'%')->and('removed', '=', 0)->or('ordering', 'LIKE', '%'.$searchValue.'%')->and('removed', '=', 0)->or('position', 'LIKE', '%'.$searchValue.'%')->and('removed', '=', 0)->order('created_at')->desc()->fetch();
        }
    }

    public static function getTopMenus() {

        return DB::try()->all(self::$_table)->where('position', '=', 'top')->and('removed', '!=', 1)->order('ordering')->fetch();
    }

    public static function getBottomMenus() {

        return DB::try()->all(self::$_table)->where('position', '=', 'bottom')->and('removed', '!=', 1)->order('ordering')->fetch();
    }

    public static function checkUniqueTitleId($title, $id) {

        return DB::try()->select('title')->from(self::$_table)->where('title', '=', $title)->and('id', '!=', $id)->fetch();
    }

    public static function getPositionNotUnset() {

        return DB::try()->select('id')->from(self::$_table)->where('position', '!=', 'unset')->fetch();
    }

    public static function getOrderingIsNotNull() {

        return DB::try()->select('id')->from(self::$_table)->where('ordering', 'IS NOT', NULL)->fetch();
    }
}