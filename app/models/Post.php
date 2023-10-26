<?php

namespace app\models;

use database\DB;

class Post extends Model {

    private $_columns;
    private $_cdnIds = [], $_cssIds = [], $_jsIds = [], $_widgetIds = [];

    public function __construct() {

        self::table("pages");
    }

    public function ifRowExists($id) {

        return DB::try()->select('id')->from('pages')->where('id', '=', $id)->first();
    }

    public function getData($id, $columns) {

        if(!empty($columns) && $columns !== null) {

            $this->_columns = implode(',', $columns);
            return DB::try()->select($this->_columns)->from('pages')->where('id', '=', $id)->first();
        }
    }

    public function getAll($columns) {

        if(!empty($columns) && $columns !== null) {

            $this->_columns = implode(',', $columns);
            return DB::try()->select($this->_columns)->from('pages')->fetch();
        }
    }

    public function allPostsWithCategories() {

        return DB::try()->select('pages.id, pages.title, pages.slug, pages.author, pages.metaTitle, pages.metaDescription, pages.removed, pages.created_at, pages.updated_at, categories.title')->from('pages')->joinLeft('category_page')->on('category_page.page_id', '=', 'pages.id')->joinLeft('categories')->on('categories.id', '=', 'category_page.category_id')->where('removed', '=', 0)->order('created_at')->desc()->fetch();
    }

    public function allPostsWithCategoriesOnSearch($searchValue = null) {

        if(!empty($searchValue) && $searchValue !== null) {

            if($searchValue == 'Thrashcan') {
                
                return DB::try()->select('pages.id, pages.title, pages.slug, pages.author, pages.metaTitle, pages.metaDescription, pages.removed, pages.created_at, pages.updated_at, categories.title')->from('pages')->joinLeft('category_page')->on('category_page.page_id', '=', 'pages.id')->joinLeft('categories')->on('categories.id', '=', 'category_page.category_id')->where('pages.removed', '=', 1)->order('created_at')->desc()->fetch();
            }

            return DB::try()->select('pages.id, pages.title, pages.slug, pages.author, pages.metaTitle, pages.metaDescription, pages.removed, pages.created_at, pages.updated_at, categories.title')->from('pages')->joinLeft('category_page')->on('category_page.page_id', '=', 'pages.id')->joinLeft('categories')->on('categories.id', '=', 'category_page.category_id')->where('pages.removed', '=', 0)->and('pages.title', 'LIKE', '%'.$searchValue.'%')->or('pages.removed', '=', 0)->and('pages.author', 'LIKE', '%'.$searchValue.'%')->order('created_at')->desc()->fetch();
        } 
    }
    
    public function checkUniqueTitle($title) {

        return DB::try()->select('id, title')->from('pages')->where('title', '=', $title)->fetch();
    }

    public function checkUniqueTitleId($title, $id) {

        return DB::try()->select('id, title')->from('pages')->where('title', '=', $title)->and('id', '!=', $id)->fetch();
    } 

    public function checkUniqueSlugCategory($id, $postSlug, $categoryId) {

        return DB::try()->select('pages.slug')->from('pages')->join('category_page')->on('category_page.page_id', '=', 'pages.id')->where('slug', 'LIKE', '%'.$postSlug)->and('id', '!=', $id)->and('category_id', '=', $categoryId)->first();
    }

    public function checkUniqueSlug($slug, $id) {

        return DB::try()->select('id, slug')->from('pages')->where('slug', '=', $slug)->and('id', '!=', $id)->fetch();
    }


    public function checkCategory($id) {

        return DB::try()->select('page_id')->from('category_page')->where('page_id', '=', $id)->fetch();      
    }

    public function checkUniqueSlugDetach($id, $lastPartSlug, $categoryId) {

        return DB::try()->select('pages.slug')->from('pages')->join('category_page')->on('category_page.page_id', '=', 'pages.id')->where('category_page.category_id', '=', $categoryId)->and('slug', 'LIKE', '%'.$lastPartSlug)->and('id', '!=', $id)->first();
    }

    public function getCategoryTitleSlug($postId) {

        return DB::try()->select('categories.title, categories.slug')->from('categories')->join('category_page')->on('category_page.category_id', '=', 'categories.id')->where('category_page.page_id', '=', $postId)->first();
    }

    public function deleteCss($id, $cssId) {

        return DB::try()->delete('css_page')->where('page_id', '=', $id)->and('css_id', '=', $cssId)->run();
    }

    public function getCssIdFilenameExtension($postId) {

        return DB::try()->select('id, file_name', 'extension')->from('css')->join('css_page')->on('css_page.css_id', '=', 'css.id')->where('css_page.page_id', '=', $postId)->and('removed', '!=', 1)->fetch();
    }

    public function getNotCssIdFilenameExtension($cssIdFilenameExtension) {

        if(!empty($cssIdFilenameExtension) && $cssIdFilenameExtension !== null) {

            foreach($cssIdFilenameExtension as $css) {

                array_push($this->_cssIds, $css['id']);
            }

            $cssIdsString = implode(',', $this->_cssIds);
            return DB::try()->select('id, file_name, extension')->from('css')->whereNotIn('id', $cssIdsString)->fetch();
        } else {
            return DB::try()->select('id, file_name, extension')->from('css')->where('removed', '!=', 1)->fetch();
        }
    }

    public function deleteJs($id, $jsId) {

        return DB::try()->delete('js_page')->where('page_id', '=', $id)->and('js_id', '=', $jsId)->run();        
    }

    public function insertJs($id, $jsId) {

        return DB::try()->insert('js_page', [

            'js_id' => $jsId,
            'page_id' => $id

        ])->where('js_page', '=', $id)->and('js_id', '=', $jsId);
    }

    public function getJsIdFilenameExtension($id) {

        return DB::try()->select('js.id, file_name', 'extension')->from('js')->join('js_page')->on('js_page.js_id', '=', 'js.id')->where('js_page.page_id', '=', $id)->and('removed', '!=', 1)->fetch();
    }

    public function getNotJsIdFilenameExtension($jsIdFilenameExtension) {

        if(!empty($jsIdFilenameExtension) && $jsIdFilenameExtension !== null) {

            foreach($jsIdFilenameExtension as $js) {

                array_push($this->_jsIds, $js['id']);
            }

            $jsIdsString = implode(',', $this->_jsIds);
            return DB::try()->select('id, file_name, extension')->from('js')->whereNotIn('id', $jsIdsString)->fetch();
        } else {
            return DB::try()->select('id, file_name, extension')->from('js')->where('removed', '!=', 1)->fetch();
        }
    }

    public function insertCss($id, $cssId) {

        return DB::try()->insert('css_page', [

            'css_id' => $cssId,
            'page_id' => $id

        ])->where('css_page', '=', $id)->and('css_id', '=', $cssId);
    }

    public function getCdnIdTitle($id) {

        return DB::try()->select('id, title')->from('cdn')->join('cdn_page')->on("cdn_page.cdn_id", '=', 'cdn.id')->where('cdn_page.page_id', '=', $id)->and('removed', '!=', 1)->fetch();
    }

    public function getNotCdnIdTitle($cdnIdTitle) {

        if(!empty($cdnIdTitle) && $cdnIdTitle !== null) {

            foreach($cdnIdTitle as $cdn) {

                array_push($this->_cdnIds, $cdn['id']);
            }

            $cdnIdsString = implode(',', $this->_cdnIds);
            return DB::try()->select('id, title')->from('cdn')->whereNotIn('id', $cdnIdsString)->fetch();
        } else {
            return DB::try()->select('id, title')->from('cdn')->where('removed', '!=', 1)->fetch();
        }
    }

    public function deleteCdn($postId, $cdnId) {

        return DB::try()->delete('cdn_page')->where('page_id', '=', $postId)->and('cdn_id', '=', $cdnId)->run();
    }

    public function getApplicableWidgetIdTitle($id) {

        return DB::try()->select('id, title')->from('widgets')->join('page_widget')->on('page_widget.widget_id', '=', 'widgets.id')->where('page_widget.page_id', '=', $id)->and('removed', '!=', 1)->fetch();
    }

    public function getInapplicableWidgetIdTitle($applicableWidgetIdtitle) {

        if(!empty($applicableWidgetIdtitle) && $applicableWidgetIdtitle !== null) {

            foreach($applicableWidgetIdtitle as $widget) {

                array_push($this->_widgetIds, $widget['id']);
            }

            $widgetIdsString = implode(',', $this->_widgetIds);
            return DB::try()->select('id, title')->from('widgets')->whereNotIn('id', $widgetIdsString)->fetch();
        } else {
            return DB::try()->select('id, title')->from('widgets')->where('removed', '!=', 1)->fetch();
        }
    }

    public function getAssignedSubCategoryIdSlug($categoryId) {

        return DB::try()->select('id, slug')->from('pages')->join('category_page')->on("category_page.page_id",'=','pages.id')->join('category_sub')->on('category_sub.category_id', '=', 'category_page.category_id')->where('category_sub.sub_id', '=', $categoryId)->fetch();
    }

    public function getAssignedCategoryIdSlug($categoryId) {

        return DB::try()->select('id, slug')->from('pages')->join('category_page')->on("category_page.page_id", '=', 'pages.id')->where('category_page.category_id', '=', $categoryId)->fetch();
    }
}