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

    private $_data;

    private function ifExists($id) {

        if(empty(Category::ifRowExists($id)) ) {

            return Response::statusCode(404)->view("/404/404")->data() . exit();
        }
    }

    private function checkPostIsAssigned($id) {

        if(!empty(Category::checkPostAssinged($id)) === true) { 
            
            exit();
        }
    }

    private function checkUniqueSlugDetach($slug, $pageId) {

        if(empty(Post::checkUniqueSlug($slug, $pageId)) === false) {

            exit();
        }
    }

    private function checkUniqueSlugAttach($pageId, $slug, $categoryId) {

        if(empty(Post::checkUniqueSlugDetach($pageId, $slug, $categoryId)) === false) {

            exit();
        }
    }

    private function checkUniqueSlugUpdate($slug, $id) {

        if(!empty(Post::checkUniqueSlug($slug, $id)) === true) { 
            
            exit(); 
        }
    }

    public function index($request) {

        $categories = Category::allCategoriesButOrdered();

        $this->_data['search'] = '';

        if(!empty($request['search'] ) ) {

            $this->_data['search'] = Get::validate($request['search']);
            $categories = Category::categoriesFilesOnSearch($this->_data['search']);
        }

        $this->_data['categories'] = Pagination::get($request, $categories, 10);
        $this->_data['count'] = count($categories);
        $this->_data['numberOfPages'] = Pagination::getPageNumbers();
       
        return $this->view('admin/categories/index')->data($this->_data);
    }

    public function store($request) {

        $rules = new Rules();
        
        if($rules->create_category($request['title'], $request['description'], Category::whereColumns(['title'], ['title' => $request['title']]))->validated()) {

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

        $rules = new Rules();

        if($rules->edit_category($request['title'], $request['description'], Category::checkUniqueTitleId($request['title'], $id))->validated()) {

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

    public function SHOWADDABLE($request) {

        $id = Get::validate($request['id']);
        $this->ifExists($id);

        $this->_data['id'] = $id;
        $this->_data['slug'] = Category::getColumns(['slug'], $id)['slug'];
        $this->_data['assignedPages'] = Category::getPostAssignedIdTitle($id);
        $this->_data['notAssingedPages'] = Category::getNotPostAssignedIdTitle(Category::getPostAssignedIdTitle($id));
        $this->_data['assingedSubCategories'] = Category::getSubIdTitleSlug($id);
        $this->_data['notAssingedSubs'] = Category::getNotSubIdTitleSlug(Category::getSubIdTitleSlug($id), $id);

        return $this->view('admin/categories/add')->data($this->_data);
    }

    public function ADDPAGE($request) {

        $this->ifExists($request['id']);

        if(!empty($request['pageid']) && $request['pageid'] !== null) {

            foreach($request['pageid'] as $pageId) {

                if(!empty(Category::checkPostAssingedId($request['id'], $pageId)) ) {

                    $this->detachPost($request['id'], Post::getColumns(['id, slug'], $pageId));
                } else if(empty(Category::checkPostAssingedId($request['id'], $pageId)) ) {
                    $this->attachPost($request['id'], Post::getColumns(['id', 'slug'], $pageId));
                } 
            }
        }

        $DATA['pageid'] = $request['pageid'];
        $DATA['categoryid'] = $request['id'];

        echo json_encode($DATA);
    }

    private function detachPost($id, $pageData) {

        $slugParts = explode('/', $pageData['slug']);
        $lastPageSlugKey = array_key_last($slugParts);
        $lastPageSlugValue = "/" . $slugParts[$lastPageSlugKey];

        $this->checkUniqueSlugDetach($lastPageSlugValue, $pageData['id']);

        Post::update(['id' => $pageData['id']], [

            'slug'  => $lastPageSlugValue
        ]);

        Category::deletePost($pageData['id'], $id);
    }

    private function attachPost($id, $pageData) {

        $slug = explode('/', $pageData['slug']);
        $lastKey = array_key_last($slug);

        $this->checkUniqueSlugAttach($pageData['id'], $slug[$lastKey], $id);

        CategoryPage::insert([

            'page_id'   => $pageData['id'],
            'category_id'   => $id
        ]);

        $this->updatePageSlugOnAttach($id, $pageData);
    }

    private function updatePageSlugOnAttach($id, $pageData) {

        if(!empty(Category::getSlugSub($id)) && Category::getSlugSub($id) !== null) {
                
            $subSlugs = [];

            foreach(Category::getSlugSub($id) as $subSlug) {

                array_push($subSlugs, $subSlug['slug']);
            }

            $subSlugsString = implode('', $subSlugs);

            Post::update(['id' => $pageData['id']], [

                'slug'  =>  $subSlugsString . Category::getSlug($id)['slug'] . $pageData['slug']
            ]);

        } else {

            Post::update(['id' => $pageData['id']], [

                'slug'  =>  Category::getSlug($id)['slug'] . $pageData['slug']
            ]);
        }
    }

    public function ADDCATEGORY($request) {

        $this->ifExists($request['id']);
        $this->checkPostIsAssigned($request['id']);

        if(!empty($request['subcategoryid']) && $request['subcategoryid'] !== null) {

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

            if($rules->slug_category($request['slug'])->validated()) {

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

                $this->checkUniqueSlugUpdate($slug, $page['id']);

                Post::update(['id' => $page['id']], [
        
                    'slug'  => $slug
                ]);
            }
        } 
    }

    public function delete($request) {

        $deleteIds = explode(',', $request['deleteIds']);

        foreach($deleteIds as $id) {

            $this->ifExists($id);

            $this->updateSlugAssingedCategory($id); 
            $this->updateSlugAssingedSubCategories($id);

            Session::set('success', 'You have successfully removed the catgory(s)!');
            Category::delete('id', $id);
            CategoryPage::delete('category_id', $id);
            CategorySub::delete('category_id', $id);
        
            redirect("/admin/categories");
        }
    }

    private function updateSlugAssingedCategory($categoryId) {

        if(!empty(Post::getAssignedCategoryIdSlug($categoryId)) && Post::getAssignedCategoryIdSlug($categoryId) !== null) {
            
            foreach(Post::getAssignedCategoryIdSlug($categoryId) as $page) {
    
                $slugParts = explode('/', $page['slug']);
                $lastPageSlugKey = array_key_last($slugParts);
                $lastPageSlugValue = "/" . $slugParts[$lastPageSlugKey];
    
                Post::update(['id' => $page['id']], [
                
                    'slug'  => $lastPageSlugValue
                ]);
            } 
        }
    }

    private function updateSlugAssingedSubCategories($categoryId) {

        if(!empty(Post::getAssignedSubCategoryIdSlug($categoryId)) && Post::getAssignedSubCategoryIdSlug($categoryId) !== null) {

            foreach(Post::getAssignedSubCategoryIdSlug($categoryId) as $page) {
        
                $slugParts = explode('/', $page['slug']);
                $categorySlugKey = array_search(substr(Category::getColumns(['slug'], $categoryId)['slug'], 1), $slugParts);
                unset($slugParts[$categorySlugKey]);
                $slugMinusSubCategorySlug = implode('/', $slugParts);
            
                Post::update(['id' => $page['id']], [
                
                    'slug'  => $slugMinusSubCategorySlug
                ]);
            } 
        }
    }
}