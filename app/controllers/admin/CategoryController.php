<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use app\models\Category;
use app\models\Post;
use app\models\CategoryPage;
use app\models\CategorySub;
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
                    'slug'  => "/" . $slug,
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

    public function TABLE() {

        $category = new Category();
        $categories = $category->allCategoriesButOrdered();

        if(empty($categories) ) {
            $categories = array(["id" => "?","title" => "no category created", "extension" => "","date_created_at" => "-", "time_created_at" => "", "date_updated_at" => "-", "time_updated_at" => ""]);
        }

        $data['categories'] = $categories;

        return $this->view('admin/categories/table', $data);
    }

    public function EDIT($request) {

        $category = Category::where('id', '=', $request['id'])[0];

        $data['id'] = $request['id'];
        $data['title'] = $category['title'];
        $data['description'] = $category['category_description'];
        
        return $this->view('admin/categories/edit', $data);
    }


    public function UPDATE($request) {

        Category::update(['id' => $request['id']], [

            'title'   => $request['title'],
            'category_description' => $request['description']

        ]);
  
        $DATA['id'] = $request['id'];
        $DATA['title'] = $request['title'];
        $DATA['description'] = $request['description'];

        echo json_encode($DATA);
    }

    public function SHOWADDABLE($request) {

        $slug = DB::try()->select('slug')->from('categories')->where('id', '=', $request['id'])->first();

        $assignedPages = DB::try()->select('id, title')->from('pages')->join('category_page')->on('pages.id', '=', 'category_page.page_id')->where('category_id', '=', $request['id'])->fetch();
        $assingedSubCategories = DB::try()->select('categories.id, categories.title, categories.slug')->from('categories')->join('category_sub')->on('category_sub.sub_id', '=', 'categories.id')->where('category_id', '=', $request['id'])->fetch();

        $notAssignedPages = $this->getNotAssingedPages($assignedPages);
        $notAssingedSubs = $this->getNotAssingedSubCategories($assingedSubCategories, $request['id']);

        $data['id'] = $request['id'];
        $data['slug'] = $slug['slug'];

        $data['assignedPages'] = $assignedPages;
        $data['notAssingedPages'] = $notAssignedPages;
        
        $data['assingedSubCategories'] = $assingedSubCategories;
        $data['notAssingedSubs'] = $notAssingedSubs;

        return $this->view('admin/categories/add', $data);
    }

    public function getNotAssingedPages($assingedPages) {

        $listAssingedPageIds = [];

        foreach($assingedPages as $assignedPage) {
    
            array_push($listAssingedPageIds, $assignedPage['id']);
        }
    
        $listAssingedPageIds = implode(',', $listAssingedPageIds);
    
        if(!empty($listAssingedPageIds) && $listAssingedPageIds !== null) {
    
            $notAssignedPages = DB::try()->select('id, title')->from('pages')->whereNotIn('id', $listAssingedPageIds)->fetch();
        } else {
            $notAssignedPages = DB::try()->select('id, title')->from('pages')->fetch();
        }

        return $notAssignedPages;
    }

    public function getNotAssingedSubCategories($assingedCategories, $id) {

        if(!empty($assingedCategories) && $assingedCategories !== null) {

            $listAssingedSubIds = [$id];

            foreach($assingedCategories as $assingedSubCategory) {

                array_push($listAssingedSubIds, $assingedSubCategory['id']);
            }
   
            $listAssingedSubIds = implode(',', $listAssingedSubIds);

            if(!empty($listAssingedSubIds) && $listAssingedSubIds !== null) {

                $notAssingedSubs = DB::try()->select('id, title')->from('categories')->whereNotIn('id', $listAssingedSubIds)->fetch();
            } 

        } else {

            $notAssingedSubs = DB::try()->select('id, title')->from('categories')->whereNot('id', '=', $id)->fetch();
        }

        return $notAssingedSubs;
    }

    public function ADDPAGE($request) {

        if(!empty($request['pageid']) && $request['pageid'] !== null) {

            foreach($request['pageid'] as $pageId) {

                $ifAssingedOnCategory = DB::try()->select('*')->from('category_page')->where('category_id', '=', $request['id'])->and('page_id', '=', $pageId)->first();
                $ifAlreadyAssinged = DB::try()->select('*')->from('category_page')->where('page_id', '=', $pageId)->first();

                if(!empty($ifAssingedOnCategory) ) {

                    $this->updatePageSlugOnCategoryDetach($pageId, $request['id']);

                    DB::try()->delete('category_page')->where('page_id', '=', $pageId)->and('category_id', '=', $request['id'])->run();

                } else if(empty($ifAssingedOnCategory) && empty($ifAlreadyAssinged)) {

                    CategoryPage::insert([
    
                        'page_id'   => $pageId,
                        'category_id'   => $request['id']
                    ]);

                    $this->updatePageSlugOnCategoryAssign($pageId, $request['id']);
                } else if(!empty($ifAlreadyAssinged) ) {
                    
                    return;
                }
            }
        }

        $DATA['pageid'] = $request['pageid'];
        $DATA['categoryid'] = $request['id'];

        echo json_encode($DATA);
    }

    public function updatePageSlugOnCategoryDetach($pageId, $categoryId) {

        $postSlug = DB::try()->select('slug')->from('pages')->where('id', '=', $pageId)->first();
        
        $slugParts = explode('/', $postSlug['slug']);
        $lastPageSlugKey = array_key_last($slugParts);
        $lastPageSlugValue = "/" . $slugParts[$lastPageSlugKey];

        Post::update(['id' => $pageId], [

            'slug'  => $lastPageSlugValue
        ]);
    }

    public function updatePageSlugOnCategoryAssign($pageId, $catgoryId) {

        $currentCategorySlug = DB::try()->select('categories.slug')->from('categories')->join('category_page')->on('category_page.category_id', '=', 'categories.id')->where('categories.id', '=', $catgoryId)->first();
        $currentSlug = DB::try()->select('slug')->from('pages')->where('id', '=', $pageId)->first();

        Post::update(['id' => $pageId], [

            'slug'  => $currentCategorySlug['slug'] . $currentSlug['slug']
        ]);
    }

    public function ADDCATEGORY($request) {

        if(!empty($request['subcategoryid']) && $request['subcategoryid'] !== null) {

            foreach($request['subcategoryid'] as $subCategoryId) {

                $ifAlreadyAssinged = DB::try()->select('*')->from('category_sub')->where('sub_id', '=', $subCategoryId)->fetch();

                if(!empty($ifAlreadyAssinged) && $ifAlreadyAssinged !== null ) {

                    $subCategorySlugs = DB::try()->select('slug')->from('categories')->join('category_sub')->on('categories.id', '=', 'category_sub.sub_id')->where('category_sub.sub_id', '=', $subCategoryId)->fetch();
                    $postSlug = DB::try()->select('pages.id, pages.slug')->from('pages')->join('category_page')->on('pages.id', '=', 'category_page.page_id')->where('category_page.category_id', '=', $request['id'])->first();

                    foreach($subCategorySlugs as $subCategorySlug) {

                        $slugParts = explode('/', $postSlug['slug']);
                        $subCategorySlugKey = array_search(substr($subCategorySlug['slug'], 1), $slugParts);
                        unset($slugParts[$subCategorySlugKey]);
                        $slugMinusSubCategorySlug = implode('/', $slugParts);
                
                        Post::update(['id' => $postSlug['id']], [
                
                            'slug'  => $slugMinusSubCategorySlug
                        ]);
                    }

                    CategorySub::delete('sub_id', $subCategoryId);

                } else {

                    CategorySub::insert([
    
                        'sub_id'   => $subCategoryId,
                        'category_id'   => $request['id']
                    ]);

                    $postSlug = DB::try()->select('pages.id, pages.slug')->from('pages')->join('category_page')->on('pages.id', '=', 'category_page.page_id')->where('category_page.category_id', '=', $request['id'])->first();

                    if(!empty($postSlug) ) {

                        $assingedSubCategorySlugs = DB::try()->select('categories.slug')->from('categories')->join('category_sub')->on('categories.id', '=', 'category_sub.sub_id')->where('category_sub.category_id', '=', $request['id'])->fetch();

                        foreach($assingedSubCategorySlugs as $assingedSubCategorySlug) {
    
                            Post::update(['id' => $postSlug['id']], [
    
                                'slug'  => $assingedSubCategorySlug['slug'] . $postSlug['slug']
                            ]);
    
                        }
                    }
                }
            }
        }

        $DATA['subcategoryid'] = $request['subcategoryid'];
        $DATA['categoryid'] = $request['id'];

        echo json_encode($DATA);
    }

    public function READ($request) {

        $category = new Category();
        $pages = $category->allCategoriesWithPosts($request['id']);

        $data['pages'] = $pages;

        return $this->view('admin/categories/read', $data);
    }

    public function SLUG($request) { 

        if(!empty($request['slug']) && $request['slug'] !== null) {
    
            $currentSlug = Category::where('id', '=', $request['id']);
            $postsWithSlug = DB::try()->select('pages.id, pages.slug')->from('pages')->join('category_page')->on('pages.id', '=', 'category_page.page_id')->where('category_page.category_id', '=', $currentSlug[0]['id'])->fetch();
    
            if(!empty($postsWithSlug) && $postsWithSlug !== null) {
    
                foreach($postsWithSlug as $postWithSlug) {
    
                    $slugParts = explode('/', $postWithSlug['slug']);
                    $categorySlugKey = array_search($currentSlug[0]['slug'], $slugParts);
                    $slugParts[$categorySlugKey] = $request['slug'];
                    $slug = implode('/', $slugParts);
    
                    Post::update(['id' => $postWithSlug['id']], [
    
                        'slug'  => $slug
                    ]);
                } 
            }

            Category::update(['id' => $request['id']], [

                'slug'  => $request['slug']
            ]);

            $data['id'] = $request['id'];
            $data['slug'] = $request['slug'];
    
            echo json_encode($data);
        } 
    }

    public function delete($request) {

        Category::delete('id', $request['id']);
        CategoryPage::delete('category_id', $request['id']);

        redirect("/admin/categories");
    }

}