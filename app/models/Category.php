<?php

namespace app\models;

use database\DB;

class Category extends Model {

    public function __construct() {

        self::table('categories');
    }

    public function allCategoriesButOrdered() {

        $categories = DB::try()->all('categories')->order('date_created_at')->fetch();
        return $this->ifDataExists($categories);
    }

    public function categoriesFilesOnSearch($searchValue) {

        $categories = DB::try()->all('categories')->where('title', 'LIKE', '%'.$searchValue.'%')->or('date_created_at', 'LIKE', '%'.$searchValue.'%')->or('time_created_at', 'LIKE', '%'.$searchValue.'%')->or('date_updated_at', 'LIKE', '%'.$searchValue.'%')->or('time_updated_at', 'LIKE', '%'.$searchValue.'%')->fetch();
        return $this->ifDataExists($categories);
    }

    public function getLastRegisteredCategoryId() {

        $category = DB::try()->getLastId('categories')->first();
        return $this->ifDataExists($category);
    }

    public function allCategoriesWithPosts($id) {

        if(!empty($id) && $id !== null) {

            $categories = DB::try()->select('pages.title', 'pages.id')->from('pages')->join('category_page')->on('category_page.page_id', '=', 'pages.id')->join('categories')->on('category_page.category_id', '=', 'categories.id')->where('categories.id', '=', $id)->fetch();
            return $this->ifDataExists($categories);
        }
    }
}