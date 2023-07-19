<?php

namespace app\models;

use database\DB;

class Post extends Model {

    public function __construct() {

        self::table("pages");
    }

    public function allPostsWithCategories($searchValue = null) {

        if(!empty($searchValue) && $searchValue !== null) {

            return DB::try()->select('pages.id, pages.title, pages.slug, pages.author, pages.metaTitle, pages.metaDescription, pages.date_created_at, pages.date_updated_at, pages.time_created_at, pages.time_updated_at, categories.title')->from('pages')->joinLeft('category_page')->on('category_page.page_id', '=', 'pages.id')->joinLeft('categories')->on('categories.id', '=', 'category_page.category_id')->where('pages.title', 'LIKE', '%'.$searchValue.'%')->or('pages.author', 'LIKE', '%'.$searchValue.'%')->or('pages.date_created_at', 'LIKE', '%'.$searchValue.'%')->or('pages.time_created_at', 'LIKE', '%'.$searchValue.'%')->or('pages.date_updated_at', 'LIKE', '%'.$searchValue.'%')->or('pages.time_updated_at', 'LIKE', '%'.$searchValue.'%')->fetch();
            
        } else {

            return DB::try()->select('pages.id, pages.title, pages.slug, pages.author, pages.metaTitle, pages.metaDescription, pages.date_created_at, pages.date_updated_at, pages.time_created_at, pages.time_updated_at, categories.title')->from('pages')->joinLeft('category_page')->on('category_page.page_id', '=', 'pages.id')->joinLeft('categories')->on('categories.id', '=', 'category_page.category_id')->fetch();
        }
    }
}