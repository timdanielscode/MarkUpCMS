<?php

/** 
 * pages table 
 * 
 * column id: to use as an unique identifier
 * column title: to distinguish pages and to use as a reference for pages
 * column slug: to show pages using the url (navigating through pages by clicking on links, for search engines)
 * column body: to show page body content (html markup)
 * column has_content: to confirm body of page does contains data
 * column author: to know who created the category
 * column metaTitle, metaDescription, metaKeywords: seo 
 * column removed: to not direct permanently delete pages 
 * column created_at: to know when a page is been created
 * column updated_at: to know when a page is been updated
 */

namespace app\models;

use database\DB;

class Page extends Model {

    private static $_table = 'pages';
    private static $_columns = [];
    private static $_metaIds = [], $_cssIds = [], $_jsIds = [], $_widgetIds = [];

    public static function ifRowExists($id) {

        return DB::try()->select('id')->from(self::$_table)->where('id', '=', $id)->first();
    }

    public static function getAll($columns) {

        self::$_columns = implode(',', $columns);
        return DB::try()->select(self::$_columns)->from(self::$_table)->fetch();
    }

    public static function allPagesWithCategories() {

        return DB::try()->select('pages.id, pages.title, pages.slug, pages.author, pages.metaTitle, pages.metaDescription, pages.removed, pages.created_at, pages.updated_at, categories.id AS categoryId, categories.title')->from(self::$_table)->joinLeft('category_page')->on('category_page.page_id', '=', 'pages.id')->joinLeft('categories')->on('categories.id', '=', 'category_page.category_id')->where('removed', '=', 0)->order('updated_at')->desc()->fetch();
    }

    public static function allPagesWithCategoriesOnSearch($searchValue = null) {

        if(!empty($searchValue) && $searchValue !== null) {

            if($searchValue == 'Thrashcan') {
                
                return DB::try()->select('pages.id, pages.title, pages.slug, pages.author, pages.metaTitle, pages.metaDescription, pages.removed, pages.created_at, pages.updated_at, categories.title')->from(self::$_table)->joinLeft('category_page')->on('category_page.page_id', '=', 'pages.id')->joinLeft('categories')->on('categories.id', '=', 'category_page.category_id')->where('pages.removed', '=', 1)->order('created_at')->desc()->fetch();
            }

            return DB::try()->select('pages.id, pages.title, pages.slug, pages.author, pages.metaTitle, pages.metaDescription, pages.removed, pages.created_at, pages.updated_at, categories.title')->from(self::$_table)->joinLeft('category_page')->on('category_page.page_id', '=', 'pages.id')->joinLeft('categories')->on('categories.id', '=', 'category_page.category_id')->where('pages.removed', '=', 0)->and('pages.title', 'LIKE', '%'.$searchValue.'%')->or('pages.removed', '=', 0)->and('pages.author', 'LIKE', '%'.$searchValue.'%')->or('pages.removed', '=', 0)->and('pages.slug', 'LIKE', '%'.$searchValue.'%')->order('created_at')->desc()->fetch();
        } 
    }
    
    public static function checkUniqueTitleId($title, $id) {

        return DB::try()->select('id, title')->from(self::$_table)->where('title', '=', $title)->and('id', '!=', $id)->fetch();
    } 

    public static function checkUniqueSlugCategory($id, $pageSlug, $categoryId) {

        return DB::try()->select('pages.slug')->from(self::$_table)->join('category_page')->on('category_page.page_id', '=', 'pages.id')->where('slug', 'LIKE', '%'.$pageSlug)->and('id', '!=', $id)->and('category_id', '=', $categoryId)->first();
    }

    public static function checkUniqueSlug($slug, $id) {

        return DB::try()->select('id, slug')->from(self::$_table)->where('slug', '=', $slug)->and('id', '!=', $id)->fetch();
    }

    public static function getMetaTitleNotNullEmpty() {

        return DB::try()->select('metaTitle')->from(self::$_table)->where('metaTitle', 'IS NOT', NULL)->and('metaTitle', '!=', '')->fetch();
    }

    public static function getMetaDescriptionNotNullEmpty() {

        return DB::try()->select('metaDescription')->from('pages')->where('metaDescription', 'IS NOT', NULL)->and('metaDescription', '!=', '')->fetch();
    }

    public static function getMetaKeyWordsNotNullEmpty() {

        return DB::try()->select('metaKeywords')->from('pages')->where('metaKeywords', 'IS NOT', NULL)->and('metaKeywords', '!=', '')->fetch();
    }


    public static function checkCategory($id) {

        return DB::try()->select('page_id')->from('category_page')->where('page_id', '=', $id)->fetch();      
    }

    public static function checkUniqueSlugDetach($id, $lastPartSlug) {

        return DB::try()->select('pages.slug')->from(self::$_table)->join('category_page')->on('category_page.page_id', '=', 'pages.id')->where('slug', 'LIKE', '%'.$lastPartSlug)->and('id', '!=', $id)->first();
    }

    public static function getCategoryTitleSlug($pageId) {

        return DB::try()->select('categories.title, categories.slug')->from('categories')->join('category_page')->on('category_page.category_id', '=', 'categories.id')->where('category_page.page_id', '=', $pageId)->first();
    }

    public static function deleteCss($id, $cssId) {

        return DB::try()->delete('css_page')->where('page_id', '=', $id)->and('css_id', '=', $cssId)->run();
    }

    public static function getCssIdFilenameExtension($pageId) {

        return DB::try()->select('id, file_name', 'extension')->from('css')->join('css_page')->on('css_page.css_id', '=', 'css.id')->where('css_page.page_id', '=', $pageId)->and('removed', '!=', 1)->fetch();
    }

    public static function getNotCssIdFilenameExtension($cssIdFilenameExtension) {

        if(!empty($cssIdFilenameExtension) && $cssIdFilenameExtension !== null) {

            foreach($cssIdFilenameExtension as $css) {

                array_push(self::$_cssIds, $css['id']);
            }

            $cssIdsString = implode(',', self::$_cssIds);
            return DB::try()->select('id, file_name, extension')->from('css')->whereNotIn('id', $cssIdsString)->and('removed', '!=', 1)->fetch();
        } else {
            return DB::try()->select('id, file_name, extension')->from('css')->where('removed', '!=', 1)->fetch();
        }
    }

    public static function getJs($id) {

        return DB::try()->select('id, file_name, extension')->from('js')->join('js_page')->on('js_page.js_id', '=', 'js.id')->where('js_page.page_id', '=', $id)->fetch();
    }

    public static function deleteJs($id, $jsId) {

        return DB::try()->delete('js_page')->where('page_id', '=', $id)->and('js_id', '=', $jsId)->run();        
    }

    public static function getJsIdFilenameExtension($id) {

        return DB::try()->select('js.id, file_name', 'extension')->from('js')->join('js_page')->on('js_page.js_id', '=', 'js.id')->where('js_page.page_id', '=', $id)->and('removed', '!=', 1)->fetch();
    }

    public static function getNotJsIdFilenameExtension($jsIdFilenameExtension) {

        if(!empty($jsIdFilenameExtension) && $jsIdFilenameExtension !== null) {

            foreach($jsIdFilenameExtension as $js) {

                array_push(self::$_jsIds, $js['id']);
            }

            $jsIdsString = implode(',', self::$_jsIds);
            return DB::try()->select('id, file_name, extension')->from('js')->whereNotIn('id', $jsIdsString)->and('removed', '!=', 1)->fetch();
        } else {
            return DB::try()->select('id, file_name, extension')->from('js')->where('removed', '!=', 1)->fetch();
        }
    }

    public static function getCdnIdTitle($id) {

        return DB::try()->select('id, title')->from('metas')->join('meta_page')->on("meta_page.meta_id", '=', 'metas.id')->where('meta_page.page_id', '=', $id)->and('removed', '=', 0)->fetch();
    }

    public static function getNotCdnIdTitle($metaIdTitle) {

        if(!empty($metaIdTitle) && $metaIdTitle !== null) {

            foreach($metaIdTitle as $meta) {

                array_push(self::$_metaIds, $meta['id']);
            }

            $metaIdsString = implode(',', self::$_metaIds);
            return DB::try()->select('id, title')->from('metas')->whereNotIn('id', $metaIdsString)->and('removed', '!=', 1)->fetch();
        } else {
            return DB::try()->select('id, title')->from('metas')->where('removed', '!=', 1)->fetch();
        }
    }

    public static function deleteCdn($pageId, $metaId) {

        return DB::try()->delete('meta_page')->where('page_id', '=', $pageId)->and('meta_id', '=', $metaId)->run();
    }

    public static function getApplicableWidgetIdTitle($id) {

        return DB::try()->select('id, title')->from('widgets')->join('page_widget')->on('page_widget.widget_id', '=', 'widgets.id')->where('page_widget.page_id', '=', $id)->and('removed', '!=', 1)->fetch();
    }

    public static function getInapplicableWidgetIdTitle($applicableWidgetIdtitle) {

        if(!empty($applicableWidgetIdtitle) && $applicableWidgetIdtitle !== null) {

            foreach($applicableWidgetIdtitle as $widget) {

                array_push(self::$_widgetIds, $widget['id']);
            }

            $widgetIdsString = implode(',', self::$_widgetIds);
            return DB::try()->select('id, title')->from('widgets')->whereNotIn('id', $widgetIdsString)->and('removed', '!=', 1)->fetch();
        } else {
            return DB::try()->select('id, title')->from('widgets')->where('removed', '!=', 1)->fetch();
        }
    }

    public static function getAssignedSubCategoryIdSlug($categoryId) {

        return DB::try()->select('id, slug')->from('pages')->join('category_page')->on("category_page.page_id",'=','pages.id')->join('category_sub')->on('category_sub.category_id', '=', 'category_page.category_id')->where('category_sub.sub_id', '=', $categoryId)->fetch();
    }

    public static function getAssignedCategoryIdSlug($categoryId) {

        return DB::try()->select('id, slug')->from('pages')->join('category_page')->on("category_page.page_id", '=', 'pages.id')->where('category_page.category_id', '=', $categoryId)->fetch();
    }
}