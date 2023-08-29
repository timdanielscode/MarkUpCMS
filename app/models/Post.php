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

        return DB::try()->select('pages.id, pages.title, pages.slug, pages.author, pages.metaTitle, pages.metaDescription, pages.removed, pages.created_at, pages.updated_at, categories.title')->from('pages')->joinLeft('category_page')->on('category_page.page_id', '=', 'pages.id')->joinLeft('categories')->on('categories.id', '=', 'category_page.category_id')->where('removed', '=', 0)->order('updated_at')->desc()->fetch();
    }

    public function allPostsWithCategoriesOnSearch($searchValue = null) {

        if(!empty($searchValue) && $searchValue !== null) {

            if($searchValue == 'Thrashcan') {
                
                return DB::try()->select('pages.id, pages.title, pages.slug, pages.author, pages.metaTitle, pages.metaDescription, pages.removed, pages.created_at, pages.updated_at, categories.title')->from('pages')->joinLeft('category_page')->on('category_page.page_id', '=', 'pages.id')->joinLeft('categories')->on('categories.id', '=', 'category_page.category_id')->where('pages.removed', '=', 1)->order('updated_at')->desc()->fetch();
            }

            return DB::try()->select('pages.id, pages.title, pages.slug, pages.author, pages.metaTitle, pages.metaDescription, pages.removed, pages.created_at, pages.updated_at, categories.title')->from('pages')->joinLeft('category_page')->on('category_page.page_id', '=', 'pages.id')->joinLeft('categories')->on('categories.id', '=', 'category_page.category_id')->where('pages.removed', '=', 0)->and('pages.title', 'LIKE', '%'.$searchValue.'%')->or('pages.removed', '=', 0)->and('pages.author', 'LIKE', '%'.$searchValue.'%')->order('updated_at')->desc()->fetch();
        } 
    }
}