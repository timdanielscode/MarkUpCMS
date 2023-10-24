<?php

namespace app\models;

use database\DB;

class Category extends Model {

    public function __construct() {

        self::table('categories');
    }

    public function ifRowExists($id) {

        if(!empty($id) && $id !== null) {

            return DB::try()->select('id')->from('categories')->where('id', '=', $id)->first();
        }
    }

    public function allCategoriesButOrdered() {

        return DB::try()->all('categories')->order('created_at')->desc()->fetch();
    }

    public function categoriesFilesOnSearch($searchValue) {

        if(!empty($searchValue) && $searchValue !== null) {

            return DB::try()->all('categories')->where('title', 'LIKE', '%'.$searchValue.'%')->order('created_at')->desc()->fetch();
        }
    }

    public function getLastRegisteredCategoryId() {

        return DB::try()->getLastId('categories')->first();
    }

    public function allCategoriesWithPosts($id) {

        if(!empty($id) && $id !== null) {

            return DB::try()->select('pages.title', 'pages.id')->from('pages')->join('category_page')->on('category_page.page_id', '=', 'pages.id')->join('categories')->on('category_page.category_id', '=', 'categories.id')->where('categories.id', '=', $id)->fetch();
        }
    }

    public function getAllCategories() {

        return DB::try()->select('id, title')->from("categories")->fetch();
    }

    public function ifPageIdExists($postId) {

        if(!empty($postId) && $postId !== null) {

            return DB::try()->select('page_id')->from('category_page')->where('page_id', '=', $postId)->fetch();
        }        
    }

    public function getPostCategory($postId) {

        if(!empty($postId) && $postId !== null) {

            return DB::try()->select('categories.title, categories.slug')->from('categories')->join('category_page')->on('category_page.category_id', '=', 'categories.id')->where('category_page.page_id', '=', $postId)->first();
        }
    }

    public function getSlug($id) {

        return $currentCategorySlug = DB::try()->select('categories.slug')->from('categories')->join('category_page')->on('category_page.category_id', '=', 'categories.id')->where('categories.id', '=', $id)->first();
    }

    public function getSubSlug($id) {

        return DB::try()->select('categories.slug')->from('categories')->join('category_sub')->on('categories.id', '=', 'category_sub.sub_id')->where('category_sub.category_id', '=', $id)->fetch();
    }

}