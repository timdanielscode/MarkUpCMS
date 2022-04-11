<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use app\models\Category;
use app\models\Post;
use app\models\CategoryPage;
use database\DB;
use parts\Session;
use parts\Pagination;
use core\Csrf;
use validation\Rules;

class CategoryController extends Controller {

    public function index() {

        $category = new Category();
        $categories = DB::try()->all($category->t)->order('date_created_at')->fetch();
        if(empty($categories) ) {
            $categories = array(["id" => "?","title" => "no category created", "extension" => "","date_created_at" => "-", "time_created_at" => "", "date_updated_at" => "-", "time_updated_at" => ""]);
        }
        $count = count($categories);
        $search = get('search');

        if(!empty($search) ) {
            $categories = DB::try()->all($category->t)->where($category->file_name, 'LIKE', '%'.$search.'%')->or($category->date_created_at, 'LIKE', '%'.$search.'%')->or($category->time_created_at, 'LIKE', '%'.$search.'%')->or($category->date_updated_at, 'LIKE', '%'.$search.'%')->or($category->time_updated_at, 'LIKE', '%'.$search.'%')->fetch();
            if(empty($categories) ) {
                $categories = array(["id" => "?","file_name" => "not found", "date_created_at" => "-", "time_created_at" => "", "date_updated_at" => "-", "time_updated_at" => ""]);
            }
        }

        $categories = Pagination::set($categories, 20);
        $numberOfPages = Pagination::getPages();

        $data['categories'] = $categories;
        $data['numberOfPages'] = $numberOfPages;
        $data['count'] = $count;

        return $this->view('admin/categories/index', $data);
    }

    public function fetchTable() {

        $category = new Category();
        $categories = DB::try()->all($category->t)->order('date_created_at')->fetch();

        if(empty($categories) ) {
            $categories = array(["id" => "?","title" => "no category created", "extension" => "","date_created_at" => "-", "time_created_at" => "", "date_updated_at" => "-", "time_updated_at" => ""]);
        }

        $data['categories'] = $categories;

        return $this->view('admin/categories/table', $data);
    }

    public function categoryModalFetch($request) {

        $id = $request['id'];

        $category = new Category();
        $category = DB::try()->select($category->title, $category->category_description)->from($category->t)->where($category->id, '=', $id)->first();

        $categoryTitle = $category['title'];
        $categoryDescription = $category['category_description'];

        $data['categoryTitle'] = $categoryTitle;
        $data['categoryDescription'] = $categoryDescription;
        $data['id'] = $id;

        return $this->view('admin/categories/modal', $data);
    }

    public function previewCategoryPages($request) {

        $id = $request['id'];

        $category = new Category();
        $page = new Post();
        $categoryPage = new CategoryPage();

        $pages = DB::try()->select($page->t.'.'.$page->title, $page->t.'.'.$page->id)->from($page->t)->join($categoryPage->t)->on($page->t.'.'.$page->id, '=', $categoryPage->t.'.'.$categoryPage->page_id)->where($categoryPage->t.'.'.$categoryPage->category_id, '=', $id)->fetch();

        $data['pages'] = $pages;

        return $this->view('admin/categories/previewPages', $data);
    }

    public function create() {

        $page = new Post();
        $pages = DB::try()->select($page->id, $page->title)->from($page->t)->fetch();

        $data['rules'] = [];
        $data['pages'] = $pages;
        return $this->view('admin/categories/create', $data);
    }

    public function store() {

        if(submitted('submit')) {
            
            if(Csrf::validate(Csrf::token('get'), post('token') ) === true) {

                $rules = new Rules();
                $category = new Category();

                if($rules->create_category()->validated()) {

                    $slug = post('title');
                    $slug = str_replace(" ", "-", $slug);
                         
                    DB::try()->insert($category->t, [

                        $category->title => post('title'),
                        $category->slug => $slug,
                        $category->category_description => post('description'),
                        $category->date_created_at => date("d/m/Y"),
                        $category->time_created_at => date("H:i"),
                        $category->date_updated_at => date("d/m/Y"),
                        $category->time_updated_at => date("H:i")
                    ]);

                    $categoryId = DB::try()->getLastId()->first();

                    $categoryPage = new CategoryPage();

                    foreach(post('page') as $pageId) {
                        
                        DB::try()->insert($categoryPage->t, [

                            $categoryPage->category_id => $categoryId[0],
                            $categoryPage->page_id => $pageId,
                        ]);
                    }

                    Session::set('create', 'You have successfully created a new post!');            
                    redirect('/admin/categories');

                } else {

                    $data['rules'] = $rules->errors;
                    return $this->view('admin/categories/create', $data);
                }
            } else {
                Session::set('csrf', 'Cross site request forgery!');
                redirect('/admin/categories/create');
            }
        }
    }

    public function updateSlug($request) { 

        if(!empty($request['slug']) && $request['slug'] !== null) {

            $data['id'] = $request['id'];
            $data['slug'] = $request['slug'];
    
            $rules = new Rules();
    
            //if($rules->update_media_filename()->validated()) {
    
                $category = new Category();
      
                DB::try()->update($category->t)->set([
                    $category->slug => $data['slug']
                ])->where($category->id, '=', $data['id'])->run(); 
    
                echo json_encode($data);
            //}

        } else if(!empty($request['title']) && $request['title'] !== null) {

            $data['id'] = $request['id'];
            $data['title'] = $request['title'];
            $data['description'] = $request['description'];

            $category = new Category();

            DB::try()->update($category->t)->set([
                $category->title => $data['title'],
                $category->category_description => $data['description']
            ])->where($category->id, '=', $data['id'])->run(); 

            echo json_encode($data);
        }
    }

    public function delete($request) {

        $id = $request['id'];

        $category = new Category();
        $category = DB::try()->delete($category->t)->where($category->id, "=", $id)->run();

        redirect("/admin/categories");
    }

}