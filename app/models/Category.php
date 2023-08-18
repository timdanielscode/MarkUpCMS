<?php

namespace app\models;

use database\DB;

class Category extends Model {

    public function __construct() {

        self::table('categories');
    }

    public function ifRowExists($id) {

        return DB::try()->select('id')->from('categories')->where('id', '=', $id)->first();
    }

    public function allCategoriesButOrdered() {

        $categories = DB::try()->all('categories')->order('updated_at')->desc()->fetch();
        return $categories;
    }

    public function categoriesFilesOnSearch($searchValue) {

        $categories = DB::try()->all('categories')->where('title', 'LIKE', '%'.$searchValue.'%')->or('created_at', 'LIKE', '%'.$searchValue.'%')->or('updated_at', 'LIKE', '%'.$searchValue.'%')->order('updated_at')->desc()->fetch();
        return $categories;
    }

    public function getLastRegisteredCategoryId() {

        $category = DB::try()->getLastId('categories')->first();
        return $category;
    }

    public function allCategoriesWithPosts($id) {

        if(!empty($id) && $id !== null) {

            $categories = DB::try()->select('pages.title', 'pages.id')->from('pages')->join('category_page')->on('category_page.page_id', '=', 'pages.id')->join('categories')->on('category_page.category_id', '=', 'categories.id')->where('categories.id', '=', $id)->fetch();
            return $categories;
        }
    }
}