<?php

namespace app\models;

use database\DB;

class Category extends Model {

    private $_columns;

    public function __construct() {

        self::table('categories');
    }

    public function ifRowExists($id) {

        return DB::try()->select('id')->from('categories')->where('id', '=', $id)->first();
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

        return DB::try()->select('pages.title', 'pages.id')->from('pages')->join('category_page')->on('category_page.page_id', '=', 'pages.id')->join('categories')->on('category_page.category_id', '=', 'categories.id')->where('categories.id', '=', $id)->fetch();
    }

    public function getAll($columns) {

        if(!empty($columns) && $columns !== null) {

            $this->_columns = implode(',', $columns);
            return DB::try()->select($this->_columns)->from("categories")->fetch();
        }
    }

    public function getSlug($id) {

        return DB::try()->select('categories.slug')->from('categories')->join('category_page')->on('category_page.category_id', '=', 'categories.id')->where('categories.id', '=', $id)->first();
    }

    public function getSlugSub($id) {

        return DB::try()->select('categories.slug')->from('categories')->join('category_sub')->on('categories.id', '=', 'category_sub.sub_id')->where('category_sub.category_id', '=', $id)->fetch();
    }

}