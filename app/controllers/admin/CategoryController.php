<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use app\models\Category;
use app\models\Post;
use app\models\CategoryPage;
use app\models\CategorySub;
use core\Session;
use extensions\Pagination;
use core\Csrf;
use validation\Rules;
use core\http\Response;
use validation\Get;

class CategoryController extends Controller {

    private $_count;

    private function ifExists($id) {

        if(empty(Category::ifRowExists($id)) ) {

            return Response::statusCode(404)->view("/404/404") . exit();
        }
    }

    private function redirect($inputName, $path, $csrf = null) {

        if($csrf === true && Csrf::validate(Csrf::token('get'), post('token')) === false || submitted($inputName) === false) {

            redirect($path) . exit(); 
        }
    }

    public function index() {

        $data['categories'] = $this->getCategories(Get::validate([get('search')]));
        $data['numberOfPages'] = Pagination::getPageNumbers();
        $data['count'] = $this->_count;

        return $this->view('admin/categories/index', $data);
    }

    private function getCategories($search) {

        $categories = Category::allCategoriesButOrdered();

        if(!empty($search)) {

            $categories = Category::categoriesFilesOnSearch($search);
        }

        $this->_count = count($categories);
        return Pagination::get($categories, 10);
    }

    public function store($request) {

        $this->redirect("submit", "/admin/categories");

        $rules = new Rules();
        
        if($rules->create_category(Category::whereColumns(['title'], ['title' => $request['title']]))->validated()) {

            Category::insert([

                'title' => $request['title'],
                'slug'  => "/" . $request['title'],
                'category_description'  => $request['description'],
                'author'    => Session::get('username'),
                'created_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
                'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
            ]);

            Session::set('success', 'You have successfully created a new category!');
        } else {
            Session::set('failed', "Title can't be empty, must be unique, max 49 characters, no special characters! Description max 99 characters, no special characters!");
        }

        redirect('/admin/categories');
    }

    public function update($request) {

        $id = $request['id'];
        $this->ifExists($id);
        $this->redirect("submit", "/admin/categories");

        $rules = new Rules();

        if($rules->edit_category(Category::checkUniqueTitleId($request['title'], $id))->validated()) {

            Category::update(['id' => $request['id']], [

                'title'   => $request['title'],
                'category_description' => $request['description'],
                'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
            ]);

            Session::set('success', 'You have successfully created a new category!');
        } else {
            Session::set('failed', "Title can't be empty, must be unique, max 49 characters, no special characters! Description max 99 characters, no special characters!");
        }

        redirect('/admin/categories');
    }

    public function SHOWADDABLE() {

        $id = Get::validate([get('id')]);
        $this->ifExists($id);

        $slug = Category::getColumns(['slug'], $id);

        $assignedPages = Category::getPostAssignedIdTitle($id);
        $notAssignedPages = Category::getNotPostAssignedIdTitle(Category::getPostAssignedIdTitle($id));
        $assingedSubCategories = Category::getSubIdTitleSlug($id);
        $notAssingedSubs = Category::getNotSubIdTitleSlug(Category::getSubIdTitleSlug($id), $id);

        $data['id'] = $id;
        $data['slug'] = $slug['slug'];
        $data['assignedPages'] = $assignedPages;
        $data['notAssingedPages'] = $notAssignedPages;
        $data['assingedSubCategories'] = $assingedSubCategories;
        $data['notAssingedSubs'] = $notAssingedSubs;

        return $this->view('admin/categories/add', $data);
    }

    public function ADDPAGE($request) {

        $this->ifExists($request['id']);

        if(!empty($request['pageid']) && $request['pageid'] !== null) {

            foreach($request['pageid'] as $pageId) {

                $pageSlug = Post::getColumns(['slug'], $pageId);

                if(!empty(Category::checkPostAssingedId($request['id'], $pageId)) ) {

                    $slugParts = explode('/', $pageSlug['slug']);
                    $lastPageSlugKey = array_key_last($slugParts);
                    $lastPageSlugValue = "/" . $slugParts[$lastPageSlugKey];

                    if(empty(Post::checkUniqueSlug($lastPageSlugValue, $pageId)) ) {

                        Post::update(['id' => $pageId], [

                            'slug'  => $lastPageSlugValue
                        ]);

                        Category::deletePost($pageId, $request['id']);

                    } else { return; }

                } else if(empty(Category::checkPostAssingedId($request['id'], $pageId)) ) {

                    $slug = explode('/', $pageSlug['slug']);
                    $lastKey = array_key_last($slug);
      
                    if(empty(Post::checkUniqueSlugDetach($pageId, $slug[$lastKey], $request['id'])) ) {

                        CategoryPage::insert([
    
                            'page_id'   => $pageId,
                            'category_id'   => $request['id']
                        ]);

                        if(!empty(Category::getSlugSub($request['id'])) && Category::getSlugSub($request['id']) !== null) {
                            
                            $subSlugs = [];

                            foreach(Category::getSlugSub($request['id']) as $subSlug) {

                                array_push($subSlugs, $subSlug['slug']);
                            }

                            $subSlugsString = implode('', $subSlugs);

                            Post::update(['id' => $pageId], [
    
                                'slug'  =>  $subSlugsString . Category::getSlug($request['id'])['slug'] . $pageSlug['slug']
                            ]);

                        } else {

                            Post::update(['id' => $pageId], [
    
                                'slug'  =>  Category::getSlug($request['id'])['slug'] . $pageSlug['slug']
                            ]);
                        }

                    } else { return; }
                } 
            }
        }

        $DATA['pageid'] = $request['pageid'];
        $DATA['categoryid'] = $request['id'];

        echo json_encode($DATA);
    }

    public function ADDCATEGORY($request) {

        $this->ifExists($request['id']);

        if(!empty($request['subcategoryid']) && $request['subcategoryid'] !== null) {

            if(!empty(Category::checkPostAssinged($request['id'])) ) { return; }

            foreach($request['subcategoryid'] as $subCategoryId) {

                if(!empty(Category::checkSubId($request['id'], $subCategoryId))) {

                    CategorySub::delete('sub_id', $subCategoryId);

                } else {

                    CategorySub::insert([
    
                        'sub_id'   => $subCategoryId,
                        'category_id'   => $request['id']
                    ]);
                } 
            }
        }

        $DATA['subcategoryid'] = $request['subcategoryid'];
        $DATA['categoryid'] = $request['id'];

        echo json_encode($DATA);
    }

    public function SLUG($request) { 

        $this->ifExists($request['id']);

        if(!empty($request['slug']) && $request['slug'] !== null) {
    
            $rules = new Rules();

            if($rules->slug_category()->validated()) {

                if(!empty(Post::getAssignedCategoryIdSlug($request['id'])) && Post::getAssignedCategoryIdSlug($request['id']) !== null) {

                    $this->updateCategoriesPostSlug(Category::getColumns(['slug'], $request['id']), $request, Post::getAssignedCategoryIdSlug($request['id']));
                } 
                
                if(!empty(Post::getAssignedSubCategoryIdSlug($request['id'])) && Post::getAssignedSubCategoryIdSlug($request['id']) !== null) {

                    $this->updateCategoriesPostSlug(Category::getColumns(['slug'], $request['id']), $request, Post::getAssignedSubCategoryIdSlug($request['id']));
                }

                Category::update(['id' => $request['id']], [

                    'slug'  => "/" . $request['slug'],
                    'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
                ]);

                $data['id'] = $request['id'];
                $data['slug'] = $request['slug'];
        
                echo json_encode($data);
            }
        } 
    }

    private function updateCategoriesPostSlug($currentSlug, $request, $pages) {

        foreach($pages as $page) {
        
            $slugParts = explode('/', $page['slug']);
            $categorySlugKey = array_search(substr($currentSlug['slug'], 1), $slugParts);
            
            if(!empty($categorySlugKey) && $categorySlugKey !== null) {

                $slugParts[$categorySlugKey] = substr("/" . $request['slug'], 1);
                $slug = implode('/', $slugParts);

                if(!empty(Post::checkUniqueSlug($slug, $page['id']))) { exit(); }
        
                Post::update(['id' => $page['id']], [
        
                    'slug'  => $slug
                ]);
            }
        } 
    }

    public function delete($request) {

        $this->redirect("deleteIds", "/admin/categories", true);

        $deleteIds = explode(',', $request['deleteIds']);

        foreach($deleteIds as $request['id']) {

            $this->ifExists($request['id']);

            $currentSlug = Category::getColumns(['slug'], $request['id']);

            if(!empty(Post::getAssignedCategoryIdSlug($request['id'])) && Post::getAssignedCategoryIdSlug($request['id']) !== null) {
            
                foreach(Post::getAssignedCategoryIdSlug($request['id']) as $page) {
        
                    $slugParts = explode('/', $page['slug']);
                    $lastPageSlugKey = array_key_last($slugParts);
                    $lastPageSlugValue = "/" . $slugParts[$lastPageSlugKey];
        
                    Post::update(['id' => $page['id']], [
                    
                        'slug'  => $lastPageSlugValue
                    ]);
                } 
            }
        
            if(!empty(Post::getAssignedSubCategoryIdSlug($request['id'])) && Post::getAssignedSubCategoryIdSlug($request['id']) !== null) {
            
                foreach(Post::getAssignedSubCategoryIdSlug($request['id']) as $page) {
            
                    $slugParts = explode('/', $page['slug']);
                    $categorySlugKey = array_search(substr($currentSlug['slug'], 1), $slugParts);
                    unset($slugParts[$categorySlugKey]);
                    $slugMinusSubCategorySlug = implode('/', $slugParts);
                
                    Post::update(['id' => $page['id']], [
                    
                        'slug'  => $slugMinusSubCategorySlug
                    ]);
                } 
            }
        
            Session::set('success', 'You have successfully removed the catgory(s)!');
            Category::delete('id', $request['id']);
            CategoryPage::delete('category_id', $request['id']);
            CategorySub::delete('category_id', $request['id']);
        
            redirect("/admin/categories");
        }
    }
}