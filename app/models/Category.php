<?php

namespace app\models;

use database\DB;

class Category extends Model {

    private $_columns;
    private $_postIds = [], $_subIds = [];

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

        return DB::try()->select('slug')->from('categories')->join('category_sub')->on('categories.id', '=', 'category_sub.sub_id')->where('category_sub.category_id', '=', $id)->fetch();
    }

    public function checkUniqueTitle($title) {

        return  DB::try()->select('title')->from('categories')->where('title', '=', $title)->fetch();
    }

    public function checkUniqueTitleId($title, $id) {

        return DB::try()->select('title')->from('categories')->where('title', '=', $title)->and('id', '!=', $id)->fetch();
    }

    public function getPostAssignedIdTitle($id) {

        return DB::try()->select('id, title')->from('pages')->join('category_page')->on('pages.id', '=', 'category_page.page_id')->where('category_id', '=', $id)->and('pages.removed', '!=', 1)->fetch();
    }

    public function getNotPostAssignedIdTitle($postAssignedIdTitle) {

        if(!empty($this->getAssignedPostIds()) && !empty($postAssignedIdTitle) ) {

            foreach($postAssignedIdTitle as $post) {

                array_push($this->_postIds, $post['id']);
            }

            $postIdsString = implode(',', $this->_postIds);
            return DB::try()->select('id, title')->from('pages')->whereNotIn('id', $postIdsString)->fetch();

        } else if(!empty($this->getAssignedPostIds()) && empty($postAssignedIdTitle) ) {

            $assignedPostIdsString = implode(',', $this->getAssignedPostIds());
            return DB::try()->select('id, title')->from('pages')->whereNotIn('id', $assignedPostIdsString)->fetch();

        } else {
            
            return DB::try()->select('id, title')->from('pages')->fetch();
        }
    }

    private function getAssignedPostIds() {

        $assignedPostIds = DB::try()->select('page_id')->from('category_page')->fetch();

        foreach($assignedPostIds as $postId) {

            array_push($this->_postIds, $postId['page_id']); 
        }

        return $this->_postIds;
    }

    public function getSubIdTitleSlug($id) {

        return DB::try()->select('categories.id, categories.title, categories.slug')->from('categories')->join('category_sub')->on('category_sub.sub_id', '=', 'categories.id')->where('category_id', '=', $id)->fetch();
    }

    public function getNotSubIdTitleSlug($getSubIdTitleSlug, $id) {

        if(!empty($getSubIdTitleSlug) && $getSubIdTitleSlug !== null) {

            foreach($getSubIdTitleSlug as $sub) {

                array_push($this->_subIds, $sub['id']);
            }

            $sugIdsString = implode(',', $this->_subIds);
            return DB::try()->select('id, title')->from('categories')->whereNotIn('id', $sugIdsString . ',' . $id)->fetch();
        } else {
            return DB::try()->select('id, title')->from('categories')->where('id', '!=', $id)->fetch();
        }
    }

    public function checkPostAssingedId($id, $postId) {

        return DB::try()->select('*')->from('category_page')->where('category_id', '=', $id)->and('page_id', '=', $postId)->first();
    }

    public function checkPostAssinged($id) {

        return DB::try()->select('*')->from('category_page')->where('category_id', '=', $id)->first(); 
    }

    public function deletePost($postId, $categoryId) {

        return DB::try()->delete('category_page')->where('page_id', '=', $postId)->and('category_id', '=', $categoryId)->run();
    }
    
    public function checkSubId($id, $subId) {

        return DB::try()->select('*')->from('category_sub')->where('sub_id', '=', $subId)->and('category_id', '=', $id)->fetch();
    }




}