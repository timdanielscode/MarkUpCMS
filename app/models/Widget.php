<?php

/** 
 * widgets table 
 * 
 * column id: to use as an unique identifier
 * column title: to distinguish widgets and to use as a reference for widgets
 * column content: to show widget contents (html markup)
 * column has_content: to confirm widget content does contains data
 * column author: to know who created the widget
 * column removed: to not direct permanently delete a widget 
 * column created_at: to know when a widget is been created
 * column updated_at: to know when a widget is been updated
 */

namespace app\models;

use database\DB;

class Widget extends Model {

    private static $_table = "widgets";
    private static $_columns = [];

    public static function ifRowExists($id) {

        return DB::try()->select('id')->from(self::$_table)->where('id', '=', $id)->first();
    }

    public static function getData($id, $columns) {

        if(!empty($columns) && $columns !== null) {

            $this->_columns = implode(',', $columns);
            return DB::try()->select($this->_columns)->from(self::$_table)->where('id', '=', $id)->first();
        }
    }

    public static function getAll($columns) {

        self::$_columns = implode(',', $columns);
        return DB::try()->select(self::$_columns)->from(self::$_table)->fetch();
    }

    public static function getPageWidgets($pageId) {

        return DB::try()->select('widget_id')->from('page_widget')->where('page_id', '=', $pageId)->fetch();
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

    public static function getPageWidgetIdTitleNotRemoved($pageId) {

        if(!empty($pageId) && $pageId !== null) {

            return DB::try()->select('widgets.id, widgets.title')->from(self::$_table)->join('page_widget')->on('page_widget.widget_id', '=', 'widgets.id')->where('page_widget.page_id', '=', $pageId)->and('widgets.removed', '!=', 1)->fetch();
        }
    }

    public static function getAllWidgets() {

        return DB::try()->select('id, title')->from(self::$_table)->where('removed', '!=', 1)->fetch();
    }

    public static function removePageWidget($pageId, $widgetId) {

        return DB::try()->delete('page_widget')->where('widget_id', '=', $widgetId)->and('page_id', '=', $pageId)->run();
    }

    public static function checkUniqueTitleId($title, $id) {

        return DB::try()->select('title')->from(self::$_table)->where('title', '=', $title)->and('id', '!=', $id)->fetch();
    }
}