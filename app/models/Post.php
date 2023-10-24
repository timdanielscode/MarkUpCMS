<?php

namespace app\models;

use database\DB;

class Post extends Model {

    public function __construct() {

        self::table("pages");
    }

    public function ifRowExists($id) {

        return DB::try()->select('id')->from('pages')->where('id', '=', $id)->first();
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

    public function getUniqueTitle($title) {

        return DB::try()->select('id, title')->from('pages')->where('title', '=', $title)->fetch();
    }

    public function getUniqueTitleAlsoOnId($title, $id) {

        return DB::try()->select('id, title')->from('pages')->where('title', '=', $title)->and('id', '!=', $id)->fetch();
    } 

    public function getSlug($id) {

        return DB::try()->select('slug')->from('pages')->where('id', '=', $id)->first();
    }

    public function checkUniqueSlugCategory($id, $postSlug, $categoryId) {

        return DB::try()->select('pages.slug')->from('pages')->join('category_page')->on('category_page.page_id', '=', 'pages.id')->where('slug', 'LIKE', '%'.$postSlug)->and('id', '!=', $id)->and('category_id', '=', $categoryId)->first();
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

    public function insertCss($id, $cssId) {

        return DB::try()->insert('css_page', [

            'css_id' => $cssId,
            'page_id' => $id

        ])->where('css_page', '=', $id)->and('css_id', '=', $cssId);
    }

    public function deleteCss($id, $cssId) {

        return DB::try()->delete('css_page')->where('page_id', '=', $id)->and('css_id', '=', $cssId)->run();
    }

    public function checkUniqueSlug($slug, $id) {

        return DB::try()->select('id, slug')->from('pages')->where('slug', '=', $slug)->and('id', '!=', $id)->fetch();
    }

    public function getTitle($id) {

        return DB::try()->select('title')->from('pages')->where('id', '=', $id)->first();
    }

    public function getRemoved($id) {

        return DB::try()->select('removed')->from('pages')->where('id', '=', $id)->first();
    }
}