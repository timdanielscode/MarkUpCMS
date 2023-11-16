<?php

namespace app\models;

use database\DB;

class Widget extends Model {

    private static $_table = "widgets";

    public static function ifRowExists($id) {

        return DB::try()->select('id')->from(self::$_table)->where('id', '=', $id)->first();
    }

    public static function getData($id, $columns) {

        if(!empty($columns) && $columns !== null) {

            $this->_columns = implode(',', $columns);
            return DB::try()->select($this->_columns)->from(self::$_table)->where('id', '=', $id)->first();
        }
    }

    public static function allWidgetsButOrderedOnDate() {

        return DB::try()->select('id, title, author, has_content, removed, created_at, updated_at')->from(self::$_table)->where('removed', '=', 0)->order('updated_at')->desc()->fetch();
    }

    public static function widgetsOnSearch($searchValue) {

        if(!empty($searchValue) && $searchValue !== null) {

            if($searchValue == 'Thrashcan') {
                
                return DB::try()->select('id, title, author, has_content, removed, created_at, updated_at')->from(self::$_table)->where('removed', '=', 1)->order('updated_at')->desc()->fetch();
            }

            return DB::try()->select('id, title, author, has_content, removed, created_at, updated_at')->from(self::$_table)->where('title', 'LIKE', '%'.$searchValue.'%')->and('removed', '=', 0)->or('author', 'LIKE', '%'.$searchValue.'%')->and('removed', '=', 0)->order('updated_at')->desc()->fetch();
        }
    }

    public static function getAllWidgetsNotRemoved() {

        return DB::try()->select('id, title')->from(self::$_table)->where('removed', '!=', 1)->fetch();
    }

    public static function getPostWidgetIdTitleNotRemoved($postId) {

        if(!empty($postId) && $postId !== null) {

            return DB::try()->select('widgets.id, widgets.title')->from(self::$_table)->join('page_widget')->on('page_widget.widget_id', '=', 'widgets.id')->where('page_widget.page_id', '=', $postId)->and('widgets.removed', '!=', 1)->fetch();
        }
    }

    public static function getAllWidgets() {

        return DB::try()->select('id, title')->from(self::$_table)->where('removed', '!=', 1)->fetch();
    }

    public static function removePostWidget($postId, $widgetId) {

        return DB::try()->delete('page_widget')->where('widget_id', '=', $widgetId)->and('page_id', '=', $postId)->run();
    }

    public static function checkUniqueTitleId($title, $id) {

        return DB::try()->select('title')->from(self::$_table)->where('title', '=', $title)->and('id', '!=', $id)->fetch();
    }
}