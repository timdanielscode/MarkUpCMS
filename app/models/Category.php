<?php

/** 
 * categories table
 * 
 * column id: to use as an unique identifier
 * column title: to distinguish categories and to use as a reference for categories
 * column slug: to extend page slugs
 * column category_description: to add a short description (could be anything)
 * column author: to know who created the category
 * column created_at: to know when a category is been created
 * column updated_at: to know when a category is been updated
 */

namespace app\models;

use database\DB;

class Category extends Model {

    private static $_table = "categories";
    private static $_columns = [];
    private static $_postIds = [], $_subIds = [];

    public static function ifRowExists($id) {

        return DB::try()->select('id')->from(self::$_table)->where('id', '=', $id)->first();
    }

    public static function allCategoriesButOrdered() {

        return DB::try()->all(self::$_table)->order('updated_at')->desc()->fetch();
    }

    public static function categoriesFilesOnSearch($searchValue) {

        return DB::try()->all(self::$_table)->where('title', 'LIKE', '%'.$searchValue.'%')->or('slug', 'LIKE', '%'.$searchValue.'%')->or('author', 'LIKE', '%'.$searchValue.'%')->order('created_at')->desc()->fetch();
    }

    public static function getLastRegisteredCategoryId() {

        return DB::try()->getLastId(self::$_table)->first();
    }

    public function allCategoriesWithPosts($id) {

        return DB::try()->select('pages.title', 'pages.id')->from('pages')->join('category_page')->on('category_page.page_id', '=', 'pages.id')->join(self::$_table)->on('category_page.category_id', '=', 'categories.id')->where('categories.id', '=', $id)->fetch();
    }

    public static function getAll($columns) {

        self::$_columns = implode(',', $columns);
        return DB::try()->select(self::$_columns)->from(self::$_table)->fetch();
    }

    public static function getSlug($id) {

        return DB::try()->select('categories.slug')->from(self::$_table)->join('category_page')->on('category_page.category_id', '=', 'categories.id')->where('categories.id', '=', $id)->first();
    }

    public static function getSlugSub($id) {

        return DB::try()->select('slug')->from(self::$_table)->join('category_sub')->on('categories.id', '=', 'category_sub.sub_id')->where('category_sub.category_id', '=', $id)->fetch();
    }

    public function checkUniqueTitle($title) {

        return  DB::try()->select('title')->from(self::$_table)->where('title', '=', $title)->fetch();
    }

    public static function checkUniqueTitleId($title, $id) {

        return DB::try()->select('title')->from(self::$_table)->where('title', '=', $title)->and('id', '!=', $id)->fetch();
    }

    public static function getPostAssignedIdTitle($id) {

        return DB::try()->select('id, title')->from('pages')->join('category_page')->on('pages.id', '=', 'category_page.page_id')->where('category_id', '=', $id)->and('pages.removed', '!=', 1)->fetch();
    }

    public static function getNotPostAssignedIdTitle() {

        if(!empty(self::getAssignedPostIds())  ) {

            $postIdsString = implode(',', self::getAssignedPostIds());
            return DB::try()->select('id, title')->from('pages')->whereNotIn('id', $postIdsString)->and('removed', '!=', 1)->fetch();

        } else {
            return DB::try()->select('id, title')->from('pages')->where('removed', '!=', 1)->fetch();
        }
    }

    private static function getAssignedPostIds() {

        $assignedPostIds = DB::try()->select('page_id')->from('category_page')->fetch();

        foreach($assignedPostIds as $postId) {

            array_push(self::$_postIds, $postId['page_id']); 
        }

        return self::$_postIds;
    }

    public static function getSubIdTitleSlug($id) {

        return DB::try()->select('categories.id, categories.title, categories.slug')->from(self::$_table)->join('category_sub')->on('category_sub.sub_id', '=', 'categories.id')->where('category_id', '=', $id)->fetch();
    }

    public static function getNotSubIdTitleSlug($getSubIdTitleSlug, $id) {

        if(!empty($getSubIdTitleSlug) && $getSubIdTitleSlug !== null) {

            foreach($getSubIdTitleSlug as $sub) {

                array_push(self::$_subIds, $sub['id']);
            }

            $sugIdsString = implode(',', self::$_subIds);
            return DB::try()->select('id, title')->from(self::$_table)->whereNotIn('id', $sugIdsString . ',' . $id)->fetch();
        } else {
            return DB::try()->select('id, title')->from(self::$_table)->where('id', '!=', $id)->fetch();
        }
    }

    public static function checkPostAssingedId($id, $postId) {

        return DB::try()->select('*')->from('category_page')->where('category_id', '=', $id)->and('page_id', '=', $postId)->first();
    }

    public static function checkPostAssinged($id) {

        return DB::try()->select('*')->from('category_page')->where('category_id', '=', $id)->first(); 
    }

    public static function deletePost($postId, $categoryId) {

        return DB::try()->delete('category_page')->where('page_id', '=', $postId)->and('category_id', '=', $categoryId)->run();
    }
    
    public static function checkSubId($id, $subId) {

        return DB::try()->select('*')->from('category_sub')->where('sub_id', '=', $subId)->and('category_id', '=', $id)->fetch();
    }
}