<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use app\models\Category;
use app\models\Post;
use app\models\CategoryPage;
use database\DB;
use core\Session;
use extensions\Pagination;
use core\Csrf;
use validation\Rules;

class CategoryController extends Controller {

    public function index() {

        /*$category = new Category();

        $categories = $category->allCategoriesButOrdered();

        if(empty($categories) ) {
            $categories = array(["id" => "?","title" => "no category created", "extension" => "","date_created_at" => "-", "time_created_at" => "", "date_updated_at" => "-", "time_updated_at" => ""]);
        }
        $count = count($categories);
        $search = get('search');

        if(!empty($search) ) {

            $categories = $category->categoriesFilesOnSearch($search);

            if(empty($categories) ) {
                $categories = array(["id" => "?","file_name" => "not found", "date_created_at" => "-", "time_created_at" => "", "date_updated_at" => "-", "time_updated_at" => ""]);
            }
        }

        $categories = Pagination::get($categories, 7);
        $numberOfPages = Pagination::getPageNumbers();

        $data['categories'] = $categories;
        $data['numberOfPages'] = $numberOfPages;
        $data['count'] = $count;*/


        $category = new Category();

        $categories = $category->allCategoriesButOrdered();

        $count = count($categories);
        $data['count'] = $count;


        return $this->view('admin/categories/index', $data);
    }

    public function table() {

        $category = new Category();
        $categories = $category->allCategoriesButOrdered();

        if(empty($categories) ) {
            $categories = array(["id" => "?","title" => "no category created", "extension" => "","date_created_at" => "-", "time_created_at" => "", "date_updated_at" => "-", "time_updated_at" => ""]);
        }

        $data['categories'] = $categories;
        return $this->view('admin/categories/table', $data);
    }

    public function edit($request) {

        $category = Category::where('id', '=', $request['id']);

        $data['categoryTitle'] = $category['title'];
        $data['categoryDescription'] = $category['category_description'];
        $data['id'] = $request['id'];

        return $this->view('admin/categories/edit', $data);
    }

    public function add($request) {

        $assignedPages = DB::try()->select('id, title')->from('pages')->join('category_page')->on('pages.id', '=', 'category_page.page_id')->where('category_id', '=', $request['id'])->fetch();
        $assignedPageIds = DB::try()->select('id')->from('pages')->join('category_page')->on('pages.id', '=', 'category_page.page_id')->where('category_id', '=', $request['id'])->fetch();

        $pageIds = DB::try()->select('id')->from('pages')->fetch();

        $listAssingedPageIds = [];

        foreach($assignedPageIds as $assignedPageId) {

            array_push($listAssingedPageIds, $assignedPageId['id']);
        }

        $listAssingedPageIds = implode(',', $listAssingedPageIds);

        $notAssignedPages = DB::try()->select('id, title')->from('pages')->whereNotIn('id', $listAssingedPageIds)->fetch();


        $data['notAssingedPages'] = $notAssignedPages;
        $data['assignedPages'] = $assignedPages;

        return $this->view('admin/categories/add', $data);
    }

    public function read($request) {

        $category = new Category();
        $pages = $category->allCategoriesWithPosts($request['id']);

        $data['pages'] = $pages;

        return $this->view('admin/categories/read', $data);
    }

    public function create() {

        $pages = Post::all();

        $data['rules'] = [];
        $data['pages'] = $pages;

        return $this->view('admin/categories/create', $data);
    }

    public function store($request) {
            
        if(submitted('submit') && Csrf::validate(Csrf::token('get'), post('token') ) === true) {

            $rules = new Rules();
           
            if($rules->create_category()->validated()) {

                $slug = post('title');
                $slug = str_replace(" ", "-", $slug);
                         
                Category::insert([

                    'title' => $request['title'],
                    'slug'  => $slug,
                    'category_description'  => $request['description'],
                    'date_created_at'   => date("d/m/Y"),
                    'time_created_at'   => date("H:i"),
                    'date_updated_at'   => date("d/m/Y"),
                    'time_updated_at'   => date("H:i")
                ]);

                $category = new Category();
                $categoryId = $category->getLastRegisteredCategoryId()[0];

                foreach($request['page'] as $pageId) {

                    $page = Post::get($pageId)['slug'];

                    CategoryPage::insert([

                        'category_id'   => $categoryId,
                        'page_id'   => $pageId
                    ]);

                    Post::update(['id' => $pageId],[

                        'slug'  =>  '/' . $slug . $page
                    ]);
                }

                Session::set('create', 'You have successfully created a new post!');            
                redirect('/admin/categories');

            } else {

                $data['rules'] = $rules->errors;
                return $this->view('admin/categories/create', $data);
            }
        } 
    }

    public function slug($request) { 

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

        Category::delete('id', $request['id']);
        CategoryPage::delete('category_id', $request['id']);

        redirect("/admin/categories");
    }

}