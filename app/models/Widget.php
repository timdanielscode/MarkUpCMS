<?php

namespace app\models;

use database\DB;

class Widget extends Model {

    private $_postApplicableWidgetIds = [];

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

    public function getPostApplicableWidgets($postId) {

        if(!empty($postId) && $postId !== null) {

            return DB::try()->select('id, title')->from('widgets')->join('page_widget')->on('page_widget.widget_id', '=', 'widgets.id')->where('page_widget.page_id', '=', $postId)->and('removed', '!=', 1)->fetch();
        }
    }

    public function getPostInapplicableWidgets($postApplicableWidgets) {

        if(!empty($postApplicableWidgets) && $postApplicableWidgets !== null) {

            foreach($postApplicableWidgets as $postApplicableWidget) {

                array_push($this->_postApplicableWidgetIds, $postApplicableWidget['id']);
            }

            $postWidgetApplicableIdsString = implode(',', $this->_postApplicableWidgetIds);
            return DB::try()->select('id, title')->from('widgets')->whereNotIn('id', $postWidgetApplicableIdsString)->fetch();
        } else {

            return $this->getAllWidgets();
        }
    }

    public function removePostWidget($postId, $widgetId) {

        if(!empty($postId) && $postId !== null && !empty($widgetId) && $widgetId !== null) {

            return DB::try()->delete('page_widget')->where('widget_id', '=', $widgetId)->and('page_id', '=', $postId)->run();
        }
    }
}