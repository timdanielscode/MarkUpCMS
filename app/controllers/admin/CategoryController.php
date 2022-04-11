<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use app\models\Category;
use database\DB;
use parts\Session;
use parts\Pagination;
use core\Csrf;
use validation\Rules;

class CategoryController extends Controller {

    public function index() {

        $category = new Category();
        $categories = DB::try()->all($category->t)->fetch();
        //$categories = DB::try()->all($category->t)->order('date_created_at')->fetch();
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

    public function create() {

        $data['rules'] = [];
        return $this->view('admin/categories/create', $data);
    }

    public function store() {

        if(submitted('submit')) {

            if(Csrf::validate(Csrf::token('get'), post('token') ) === true) {

                $rules = new Rules();
                $category = new Category();

                if($rules->create_category()->validated()) {
                         
                    DB::try()->insert($category->t, [

                        $category->title => post('title')
                    ]);

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

}