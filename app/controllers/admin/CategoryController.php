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
use core\http\Response;
use validation\Get;

class CategoryController extends Controller {

    private function ifExists($id) {

        $category = new Category();

        if(empty($category->ifRowExists($id)) ) {

            return Response::statusCode(404)->view("/404/404") . exit();
        }
    }

    private function redirect($inputName, $path, $csrf = null) {

        if($csrf === true && Csrf::validate(Csrf::token('get'), post('token')) === false || submitted($inputName) === false) {

            redirect($path) . exit(); 
        }
    }

    public function index() {

        $category = new Category();
        $categories = $category->allCategoriesButOrdered();

        $search = Get::validate([get('search')]);

        if(!empty($search) ) {

            $categories = $category->categoriesFilesOnSearch($search);
        }

        $count = count($categories);

        $categories = Pagination::get($categories, 10);
        $numberOfPages = Pagination::getPageNumbers();

        $data['categories'] = $categories;
        $data['numberOfPages'] = $numberOfPages;
        $data['search'] = $search;
        $data['count'] = $count;

        return $this->view('admin/categories/index', $data);
    }

    public function store($request) {

        $this->redirect("submit", "/admin/categories");

        $rules = new Rules();
        $category = new Category();
        
        if($rules->create_category($category->checkUniqueTitle($request['title']))->validated()) {

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
        $category = new Category();

        if($rules->edit_category($category->checkUniqueTitleId($request['title'], $id))->validated()) {

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

        $slug = DB::try()->select('slug')->from('categories')->where('id', '=', $id)->first();

        $category = new Category();
        $assignedPages = $category->getPostAssignedIdTitle($id);
        $notAssignedPages = $category->getNotPostAssignedIdTitle($category->getPostAssignedIdTitle($id));
        $assingedSubCategories = $category->getSubIdTitleSlug($id);
        $notAssingedSubs = $category->getNotSubIdTitleSlug($category->getSubIdTitleSlug($id), $id);

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

                $category = new Category();
                $post = new Post();

                $pageSlug = $post->getData($pageId, ['slug']);

                if(!empty($category->checkPostAssingedId($request['id'], $pageId)) ) {

                    $slugParts = explode('/', $pageSlug['slug']);
                    $lastPageSlugKey = array_key_last($slugParts);
                    $lastPageSlugValue = "/" . $slugParts[$lastPageSlugKey];

                    if(empty($post->checkUniqueSlug($lastPageSlugValue, $pageId)) ) {

                        Post::update(['id' => $pageId], [

                            'slug'  => $lastPageSlugValue
                        ]);

                        $category->deletePost($pageId, $request['id']);

                    } else { return; }

                } else if(empty($category->checkPostAssingedId($request['id'], $pageId)) ) {

                    $slug = explode('/', $pageSlug['slug']);
                    $lastKey = array_key_last($slug);
      
                    if(empty($post->checkUniqueSlugDetach($pageId, $slug[$lastKey], $request['id'])) ) {

                        CategoryPage::insert([
    
                            'page_id'   => $pageId,
                            'category_id'   => $request['id']
                        ]);

                        if(!empty($category->getSlugSub($request['id'])) && $category->getSlugSub($request['id']) !== null) {
                            
                            $subSlugs = [];

                            foreach($category->getSlugSub($request['id']) as $subSlug) {

                                array_push($subSlugs, $subSlug['slug']);
                            }

                            $subSlugsString = implode('', $subSlugs);

                            Post::update(['id' => $pageId], [
    
                                'slug'  =>  $subSlugsString . $category->getSlug($request['id'])['slug'] . $pageSlug['slug']
                            ]);

                        } else {

                            Post::update(['id' => $pageId], [
    
                                'slug'  =>  $category->getSlug($request['id'])['slug'] . $pageSlug['slug']
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

            $category = new Category();

            if(!empty($category->checkPostAssinged($request['id'])) ) { return; }

            foreach($request['subcategoryid'] as $subCategoryId) {

                if(!empty($category->checkSubId($request['id'], $subCategoryId))) {

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

                $slug = Category::where('id', '=', $request['id'])[0];
                $post = new Post();
                
                if(!empty($post->getAssignedCategoryIdSlug($request['id'])) && $post->getAssignedCategoryIdSlug($request['id']) !== null) {

                    $this->updateCategoriesPostSlug($slug, $request, $post->getAssignedCategoryIdSlug($request['id']));
                } 
                
                if(!empty($post->getAssignedSubCategoryIdSlug($request['id'])) && $post->getAssignedSubCategoryIdSlug($request['id']) !== null) {
                    $this->updateCategoriesPostSlug($slug, $request, $post->getAssignedSubCategoryIdSlug($request['id']));
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

                $post = new Post();
                $unique = $post->checkUniqueSlug($slug, $page['id']);

                if(!empty($unique)) { exit(); }
        
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

            $currentSlug = Category::where('id', '=', $request['id'])[0];
            $post = new Post();

            if(!empty($post->getAssignedCategoryIdSlug($request['id'])) && $post->getAssignedCategoryIdSlug($request['id']) !== null) {
            
                foreach($post->getAssignedCategoryIdSlug($request['id']) as $page) {
        
                    $slugParts = explode('/', $page['slug']);
                    $lastPageSlugKey = array_key_last($slugParts);
                    $lastPageSlugValue = "/" . $slugParts[$lastPageSlugKey];
        
                    Post::update(['id' => $page['id']], [
                    
                        'slug'  => $lastPageSlugValue
                    ]);
                } 
            }
        
            if(!empty($post->getAssignedSubCategoryIdSlug($request['id'])) && $post->getAssignedSubCategoryIdSlug($request['id']) !== null) {
            
                foreach($post->getAssignedSubCategoryIdSlug($request['id']) as $page) {
            
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