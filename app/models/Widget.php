<?php

namespace app\models;

use database\DB;

class Widget extends Model {

    private $_columns;

    public function __construct() {

        self::table('widgets');
    }

    public function ifRowExists($id) {

        return DB::try()->select('id')->from('widgets')->where('id', '=', $id)->first();
    }

    public function getData($id, $columns) {

        if(!empty($columns) && $columns !== null) {

            $this->_columns = implode(',', $columns);
            return DB::try()->select($this->_columns)->from('widgets')->where('id', '=', $id)->first();
        }
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

    public function getAllWidgetsNotRemoved() {

        return DB::try()->select('id, title')->from('widgets')->where('removed', '!=', 1)->fetch();
    }

    public function getPostWidgetIdTitleNotRemoved($postId) {

        if(!empty($postId) && $postId !== null) {

            return DB::try()->select('widgets.id, widgets.title')->from('widgets')->join('page_widget')->on('page_widget.widget_id', '=', 'widgets.id')->where('page_widget.page_id', '=', $postId)->and('widgets.removed', '!=', 1)->fetch();
        }
    }

    public function getAllWidgets() {

        return DB::try()->select('id, title')->from('widgets')->where('removed', '!=', 1)->fetch();
    }

    public function removePostWidget($postId, $widgetId) {

        if(!empty($postId) && $postId !== null && !empty($widgetId) && $widgetId !== null) {

            return DB::try()->delete('page_widget')->where('widget_id', '=', $widgetId)->and('page_id', '=', $postId)->run();
        }
    }

    public function checkUniqueTitle($title) {

        return DB::try()->select('title')->from('widgets')->where('title', '=', $title)->fetch();
    }

    public function checkUniqueTitleId($title, $id) {

        return DB::try()->select('title')->from('widgets')->where('title', '=', $title)->and('id', '!=', $id)->fetch();
    }
}