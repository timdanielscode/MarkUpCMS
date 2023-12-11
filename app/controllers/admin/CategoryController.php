<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use app\models\Category;
use app\models\Post;
use app\models\CategoryPage;
use app\models\CategorySub;
use core\Session;
use extensions\Pagination;
use validation\Rules;
use core\http\Response;
use validation\Get;

class CategoryController extends Controller {

    private $_data;

    /**
     * To show 404 page with 404 status code (on not existing category)
     * 
     * @param string $id _POST category id
     * @return object CategoryController
     */ 
    private function ifExists($id) {

        if(empty(Category::ifRowExists($id)) ) {

            return Response::statusCode(404)->view("/404/404")->data() . exit();
        }
    }

    /**
     * To force failed validation message on sub category assignment (and pages are already assigned)
     * 
     * @param string $id _POST category id
     */
    private function checkPostIsAssigned($id) {

        if(!empty(Category::checkPostAssinged($id)) === true) { 
            
            exit();
        }
    }

    /**
     * To force failed validation message on detaching page from category (and page slug is not unique)
     * 
     * @param string $slug page slug
     * @param string $pageId page id
     */
    private function checkUniqueSlugDetach($slug, $pageId) {

        if(empty(Post::checkUniqueSlug($slug, $pageId)) === false) {

            exit();
        }
    }

    /**
     * To force failed validation message on assigning page to category (and page slug is not unique)
     * 
     * @param string $pageId page id
     * @param string $slug page slug
     * @param string $categoryId category id
     */
    private function checkUniqueSlugAttach($pageId, $slug, $categoryId) {

        if(empty(Post::checkUniqueSlugDetach($pageId, $slug, $categoryId)) === false) {

            exit();
        }
    }

    /**
     * To force failed validation message on update category slug (and page slug is not unique)
     * 
     * @param string $slug page slug
     * @param string $id page id
     */
    private function checkUniqueSlugUpdate($slug, $id) {

        if(!empty(Post::checkUniqueSlug($slug, $id)) === true) { 
            
            exit(); 
        }
    }

    /**
     * To show the categories index view
     * 
     * @param array $request _GET search, page
     * @return object CategoryController, Controller
     */
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

    /**
     * To show the categories apply view
     * 
     * @param array $request id (category id)
     * @return object CategoryController, Controller
     */
    public function apply($request) {

        $this->ifExists($request['id']);

        $this->_data['categories'] = Category::allCategoriesButOrdered();
        $this->_data['add'] = $this->getAddData($request['id']);
        $this->_data['categoryId'] = $request['id'];

        return $this->view('admin/categories/apply')->data($this->_data);
    }

    /**
     * To store a new category (on successful validation)
     * 
     * @param array $request _POST title, description
     */
    public function store($request) {

        $rules = new Rules();
        
        if($rules->category($request['title'], $request['description'], Category::whereColumns(['title'], ['title' => $request['title']]))->validated()) {

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

    /**
     * To update category data (on successful validation)
     * 
     * @param array $request id (category id), _POST title, description
     */
    public function update($request) {

        $id = $request['id'];
        $this->ifExists($id);

        $rules = new Rules();

        if($rules->category($request['title'], $request['description'], Category::checkUniqueTitleId($request['title'], $id))->validated()) {

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

    /**
     * To show the categories add view
     * 
     * @param array $request id category id
     * @return object CategoryController, Controller
     */
    public function getAddData($id) {
     
        $this->ifExists($id);

        $this->_data['id'] = $id;
        $this->_data['slug'] = Category::getColumns(['slug'], $id)['slug'];
        $this->_data['assignedPages'] = Category::getPostAssignedIdTitle($id);
        $this->_data['notAssingedPages'] = Category::getNotPostAssignedIdTitle($id);
        $this->_data['assingedSubCategories'] = Category::getSubIdTitleSlug($id);
        $this->_data['notAssingedSubs'] = Category::getNotSubIdTitleSlug(Category::getSubIdTitleSlug($id), $id);

        return $this->_data;
    }

    /**
     * To assign and detach page(s) to a category (checking assign or detach)
     * 
     * @param array $request _POST id (category id), pageid
     */
    public function assignDetachPages($request) {

        $id = $request['id'];
        $this->ifExists($id);
            
        $pageIds = array_filter(explode(',', $request['pageIds']));
        
        if(!empty($pageIds) && $pageIds !== null) {

            foreach($pageIds as $pageId) {

                if(!empty(Category::checkPostAssingedId($request['id'], $pageId)) ) {
                    
                    $this->detachPost($request['id'], Post::getColumns(['id, slug'], $pageId));
                } else if(empty(Category::checkPostAssingedId($request['id'], $pageId)) ) {
                    $this->attachPost($request['id'], Post::getColumns(['id', 'slug'], $pageId));
                } 
            }
        }

        redirect("/admin/categories/$id/apply");
    }

    /**
     * To detach page(s) from a category
     * 
     * @param string $id _POST category id
     * @param array $pageData _POST id, slug
     */
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

    /**
     * To assign page(s) to a category 
     * 
     * @param string $id _POST category id
     * @param array $pageData _POST id, slug
     */
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

    /**
     * To update page slug after assigning to a category
     * 
     * @param string $id _POST category id
     * @param array $pageData _POST id, slug
     */
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

    /**
     * To assign and detach sub category(ies) to a category
     * 
     * @param array $request _POST id (category id), subcategoryid
     */
    public function assignDetachCategories($request) {

        $this->ifExists($request['id']);
        $this->checkPostIsAssigned($request['id']);
        
        $id = $request['id'];

        $categoryIds = array_filter(explode(',', $request['categoryIds']));

        if(!empty($categoryIds) && $categoryIds !== null) {

            foreach($categoryIds as $categoryId) {

                if(!empty(Category::checkSubId($request['id'], $categoryId))) {

                    CategorySub::delete('sub_id', $categoryId);

                } else {

                    CategorySub::insert([
    
                        'sub_id'   => $categoryId,
                        'category_id'   => $request['id']
                    ]);
                } 
            }
        }

        redirect("/admin/categories/$id/apply");
    }

    /**
     * To update category data (slug)
     * 
     * @param array $request _POST id (category id), slug
     */
    public function slug($request) { 

        $id = $request['id'];
        $this->ifExists($id);

        if(!empty($request['slug']) && $request['slug'] !== null) {
            
            $rules = new Rules();
  
            if($rules->category_slug($request['slug'])->validated()) {
         
                if(!empty(Post::getAssignedCategoryIdSlug($id)) && Post::getAssignedCategoryIdSlug($id) !== null) {

                    $this->updateCategoriesPostSlug(Category::getColumns(['slug'], $id), $request, Post::getAssignedCategoryIdSlug($id));
                } 
                
                if(!empty(Post::getAssignedSubCategoryIdSlug($id)) && Post::getAssignedSubCategoryIdSlug($id) !== null) {

                    $this->updateCategoriesPostSlug(Category::getColumns(['slug'], $id), $request, Post::getAssignedSubCategoryIdSlug($id));
                }

                Category::update(['id' => $id], [

                    'slug'  => "/" . $request['slug'],
                    'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
                ]);

                redirect("/admin/categories/$id/apply");
            }
        } 
    }

    /**
     * To update page data (slug)
     * 
     * @param array $currentSlug category slug
     * @param array $request _POST id (category id), slug
     * @param array $pages assigned pages (id, slug)
     */
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

    /**
     * To remove a category
     * 
     * @param array $request _POST (category deleteIds)
     */
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

    /**
     * To update page data (slug) after assinged category is removed
     * 
     * @param string $categoryId category id
     */
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

    /**
     * To update page data (slug) after assinged category is removed
     * 
     * @param string $categoryId category id
     */
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