<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use core\Csrf;
use validation\Rules;
use app\models\Post;
use app\models\CategoryPage;
use app\models\Category;
use app\models\CdnPage;
use app\models\Css;
use app\models\Js;
use app\models\Widget;
use app\models\PageWidget;
use app\models\Menu;
use app\models\Cdn;
use core\Session;
use extensions\Pagination;
use core\http\Response;
use validation\Get;

class PostController extends Controller {

    private $_data;

    private function ifExists($id) {

        if(empty(Post::ifRowExists($id)) ) {

            return Response::statusCode(404)->view("/404/404")->data() . exit();
        }
    }

    private function redirect($inputName, $path) {

        //if(submitted($inputName) === false || Csrf::validate(Csrf::token('get'), post('token')) === false ) { 
            
        //    redirect($path) . exit(); 
        //} 
    }

    public function index($request) {

        $posts = Post::allPostsWithCategories();

        $this->_data['search'] = '';

        if(!empty($request['search'] ) ) {

            $this->_data['search'] = Get::validate($request['search']);
            $posts = Post::allPostsWithCategoriesOnSearch($this->_data['search']);
        }

        $this->_data["posts"] = Pagination::get($posts, 10);
        $this->_data["count"] = count($posts);
        $this->_data['numberOfPages'] = Pagination::getPageNumbers();

        return $this->view('admin/posts/index')->data($this->_data);
    }

    public function create() {
        
        $this->_data['rules'] = [];
        return $this->view('admin/posts/create')->data($this->_data);
    }

    public function store($request) {


        //$this->redirect("submit", '/admin/posts/create');

        $rules = new Rules();
    
        if($rules->create_post($request['title'], Post::whereColumns(['id', 'title'], ['title' => $request['title']]))->validated()) {
                        
            $slug = "/" . $request['title'];
            $slug = str_replace(" ", "-", $slug);

            if(!empty($request['body']) ) { $hasContent = 1; } else { $hasContent = 0; }

            Post::insert([
    
                'title' => $request['title'],
                'slug' => $slug,
                'body' => $request['body'],
                'has_content' => $hasContent,
                'author' => Session::get('username'),
                'removed' => 0,
                'created_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
                'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
            ]);
    
            Session::set('success', 'You have successfully created a new page!');            
            redirect('/admin/posts');
        } else {

            $this->_data['title'] = $request['title'];
            $this->_data['rules'] = $rules->errors;

            return $this->view('admin/posts/create')->data($this->_data);
        }
    }

    public function read($request) {

        $this->ifExists($request['id']);

        $this->_data['post'] = Post::get($request['id']);
        $this->_data['cssFiles'] = Post::getCssIdFilenameExtension($request['id']);
        $this->_data['jsFiles'] = Post::getJs($request['id']);
        $this->_data['menusTop'] = Menu::getTopMenus();
        $this->_data['menusBottom'] =  Menu::getBottomMenus();

        return $this->view('/admin/posts/read')->data($this->_data);
    }

    public function edit($request) {

        $this->ifExists($request['id']);
        $this->setDefaultSession('slug');
        
        $this->_data = $this->getAllData($request['id']);
        $this->_data['rules'] = [];

        return $this->view('admin/posts/edit')->data($this->_data);
    }

    public function update($request) {

        $id = $request['id'];
        $this->unsetSessions(['cdn', 'widget', 'category', 'css', 'js', 'meta']);
        $this->ifExists($id);
        //$this->redirect("submit", "/admin/posts/$id/edit");

        $rules = new Rules();

        if($rules->update_post($request['title'], Post::checkUniqueTitleId($request['title'], $id))->validated()) {
                
            if(!empty($request['body']) ) { $hasContent = 1; } else { $hasContent = 0; }

            Post::update(['id' => $id], [

                'title' => $request["title"],
                'body' => $request["body"],
                'has_content' => $hasContent,
                'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
            ]);

            Session::set('success', 'You have successfully updated the page!'); 
            redirect("/admin/posts/$id/edit");
                
        } else {

            $this->_data = $this->getAllData($id, $request);
            $this->_data['rules'] = $rules->errors;

            return $this->view('admin/posts/edit')->data($this->_data);
        }
    }

    private function unsetSessions($names) {

        if(!empty($names) && $names !== null) {

            foreach($names as $name) {

                Session::delete($name);
            }
        }
    }

    private function setDefaultSession($slug) {

        $names = ['cdn', 'widget', 'category', 'css', 'js', 'meta'];

        foreach($names as $name) {

            if(Session::exists($name)) {

                return;
            }
        }

        Session::set($slug, true);
    }

    private function getAllData($id, $requestData = null) {

        $postData = Post::get($id);
        $postSlug = explode('/', $postData['slug']);
        $postSlug = "/" . $postSlug[array_key_last($postSlug)];

        $this->_data['data'] = $postData;

        if(!empty($requestData['body']) && $requestData['body'] !== null) {

            $this->_data['data']['body'] = $requestData['body'];
        }

        $this->_data['data']['postSlug'] = $postSlug;
        $this->_data['data']['linkedCssFiles'] = Post::getCssIdFilenameExtension($id);
        $this->_data['data']['notLinkedCssFiles'] = Post::getNotCssIdFilenameExtension(Post::getCssIdFilenameExtension($id));
        $this->_data['data']['linkedJsFiles'] = Post::getJsIdFilenameExtension($id);
        $this->_data['data']['notLinkedJsFiles'] = Post::getNotJsIdFilenameExtension(Post::getJsIdFilenameExtension($id));

        if(empty(Post::checkCategory($id))) {

            $this->_data['data']['categories'] = Category::getAll(['id', 'title']);
        } else {
            $this->_data['data']['category'] = Post::getCategoryTitleSlug($id);
        }

        $this->_data['data']['applicableWidgets'] = Post::getApplicableWidgetIdTitle($id);
        $this->_data['data']['inapplicableWidgets'] = Post::getInapplicableWidgetIdTitle(Post::getApplicableWidgetIdTitle($id));
        $this->_data['data']['exportCdns'] = Post::getCdnIdTitle($id);
        $this->_data['data']['importCdns'] = Post::getNotCdnIdTitle(Post::getCdnIdTitle($id));

        return $this->_data;
    }

    public function importCdns($request) {

        $id = $request['id'];
        $this->unsetSessions(['slug', 'widget', 'category', 'css', 'js', 'meta']);
        Session::set('cdn', true);

        //if(submitted("submit") === true && Csrf::validate(Csrf::token('get'), post('token')) === true ) {

            foreach($request['cdns'] as $cdnId) {

                CdnPage::insert([

                    'page_id' => $id,
                    'cdn_id' => $cdnId
                ]);
            }

            Session::set('success', 'You have successfully imported the cdn(s) on this page!'); 
            redirect("/admin/posts/$id/edit");
       // }
    }

    public function exportCdns($request) {

        $id = $request['id'];
        ///$this->redirect("submit", "/admin/posts/$id/edit");
        $this->unsetSessions(['slug', 'widget', 'category', 'css', 'js', 'meta']);
        Session::set('cdn', true);

        foreach($request['cdns'] as $cdnId) {

            Post::deleteCdn($id, $cdnId);
        }

        Session::set('success', 'You have successfully removed the cdn(s) on this page!'); 
        redirect("/admin/posts/$id/edit");
    }

    public function addWidget($request) {

        $id = $request['id'];
        //$this->redirect("submit", "/admin/posts/$id/edit");
        $this->ifExists($id);
        $this->unsetSessions(['slug', 'cdn', 'category', 'css', 'js', 'meta']);
        Session::set('widget', true);

        $widgetIds = $request['widgets'];

        if(!empty($widgetIds) && $widgetIds !== null) {

            foreach($widgetIds as $widgetId) {

                PageWidget::insert([

                    'page_id' => $id,
                    'widget_id' => $widgetId
                ]);
            }
        }

        Session::set('success', 'You have successfully made the widget(s) applicable for this page!'); 
        redirect("/admin/posts/$id/edit");
    }

    public function removeWidget($request) {

        $id = $request['id'];
        $this->ifExists($id);
        //$this->redirect("submit", "/admin/posts/$id/edit");
        $this->unsetSessions(['slug', 'cdn', 'category', 'css', 'js', 'meta']);
        Session::set('widget', true);

        if(!empty($request['widgets']) && $request['widgets'] !== null) {

            foreach($request['widgets'] as $widgetId) {

                Widget::removePostwidget($id, $widgetId);
            }
        }

        Session::set('success', 'You have successfully made the widget(s) inapplicable for this page!'); 
        redirect("/admin/posts/$id/edit");
    }

    public function assignCategory($request) {

        $id = $request['id'];
        $this->ifExists($id);
        //$this->redirect("submit", "/admin/posts/$id/edit");
        $this->unsetSessions(['slug', 'widget', 'cdn', 'css', 'js', 'meta']);
        Session::set('category', true);

        $rules = new Rules();

        $slug = explode('/', Post::getColumns(['slug'], $id)['slug']);
        $lastKey = array_key_last($slug);
        $categoryId = $request['categories'];

        if($rules->update_post_category($categoryId, Post::checkUniqueSlugCategory($id, $slug[$lastKey], $categoryId))->validated()) {

            CategoryPage::insert([

                'category_id' => $categoryId,
                'page_id'    => $id
            ]);

            $this->updateSlugCategory($id, $categoryId);

            Session::set('success', 'You have successfully assigned the category on this page!'); 
            redirect("/admin/posts/$id/edit");

        } else {

            $this->_data = $this->getAllData($id);
            $this->_data['rules'] = $rules->errors;

            return $this->view('admin/posts/edit')->data($this->_data);
        }
    }

    private function updateSlugCategory($id, $categoryId) {

        if(!empty(Category::getSlugSub($categoryId))) {

            $subCategorySlugs = [];

            foreach(Category::getSlugSub($categoryId) as $subCategorySlug) {

                array_push($subCategorySlugs, $subCategorySlug['slug']);
            }

            $subCategorySlugsString = implode('', $subCategorySlugs);
    
            Post::update(['id' => $id], [

                'slug'  =>  $subCategorySlugsString . Category::getSlug($categoryId)['slug'] . Post::getColumns(['slug'], $id)['slug']
            ]);

        } else {

            Post::update(['id' => $id], [
    
                'slug'  => Category::getSlug($categoryId)['slug'] . Post::getColumns(['slug'], $id)['slug']
            ]);
        }
    }

    public function removeJs($request) {

        $id = $request['id'];
        $this->ifExists($id);
        //$this->redirect("submit", "/admin/posts/$id/edit");
        $this->unsetSessions(['slug', 'widget', 'cdn', 'category', 'css', 'meta']);
        Session::set('js', true);

        foreach($request['linkedJsFiles'] as $linkedJsId) {

            Post::deleteJs($id, $linkedJsId);
        }
            
        Session::set('success', 'You have successfully removed the js file(s) on this page!');
        redirect("/admin/posts/$id/edit");
    }

    public function includeJs($request) {

        $id = $request['id'];
        $this->ifExists($id);
        //$this->redirect("submit", "/admin/posts/$id/edit");
        $this->unsetSessions(['slug', 'widget', 'cdn', 'category', 'css', 'meta']);
        Session::set('js', true);

        foreach($request['jsFiles'] as $jsId) {

            Post::insertJs($id, $jsId);
        }

        Session::set('success', 'You have successfully included the js file(s) on this page!');
        redirect("/admin/posts/$id/edit");
    }

    public function linkCss($request) {

        $id = $request['id'];
        $this->ifExists($id);
       // $this->redirect("submit", "/admin/posts/$id/edit");
        $this->unsetSessions(['slug', 'widget', 'cdn', 'category', 'js', 'meta']);
        Session::set('css', true);

        foreach($request['cssFiles'] as $cssId) {

            Post::insertCss($id, $cssId);
        }

        Session::set('success', 'You have successfully linked the css file(s) on this page!');
        redirect("/admin/posts/$id/edit");
    }

    public function unLinkCss($request) {

        $id = $request['id'];
        $this->ifExists($id);
        //$this->redirect("submit", "/admin/posts/$id/edit");
        $this->unsetSessions(['slug', 'widget', 'cdn', 'category', 'js', 'meta']);
        Session::set('css', true);

        foreach($request['linkedCssFiles'] as $cssId) {

            Post::deleteCss($id, $cssId);
        }

        Session::set('success', 'You have successfully removed the css file(s) on this page!');
        redirect("/admin/posts/$id/edit");
    }

    public function updateSlug($request) {

        $id = $request['id'];
        $this->ifExists($id);
        //$this->redirect("submit", "/admin/posts/$id/edit");
        $this->unsetSessions(['widget', 'cdn', 'category', 'js', 'meta', 'css']);
        Session::set('slug', true);

        $rules = new Rules();
            
        $slug = explode('/', $request['slug']);
        $lastKey = array_key_last($slug);

        $slug[$lastKey] = $request['postSlug'];
        $fullPostSlug = implode('/', $slug);

        if($rules->update_post_slug($request['postSlug'], Post::checkUniqueSlug($fullPostSlug, $id))->validated()) {

            $slug = explode('/', "/" . $request['slug']);
            $slug[array_key_last($slug)] = substr("/" . $request['postSlug'], 1);
            $slug = implode('/', array_filter($slug));

            Post::update(['id' => $id], [

                'slug' => "/" . $slug,
                'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
            ]);

        } else {

            $this->_data = $this->getAllData($id);
            $this->_data['rules'] = $rules->errors;

            return $this->view('admin/posts/edit')->data($this->_data);
        }

        Session::set('success', 'You have successfully updated the slug on this page!');
        redirect("/admin/posts/$id/edit");
    }

    public function updateMetadata($request) {

        $id = $request['id'];
        $this->ifExists($id);
        //$this->redirect("submit", "/admin/posts/$id/edit");
        $this->unsetSessions(['slug', 'widget', 'cdn', 'category', 'css', 'js']);
        Session::set('meta', true);

        $rules = new Rules();

        if($rules->update_metadata($request['metaTitle'], $request['metaDescription'], $request['metaKeywords'])->validated()) {

            Post::update(['id' => $id], [

                'metaTitle' => $request['metaTitle'],
                'metaDescription' => $request['metaDescription'],
                'metaKeywords' => $request['metaKeywords'],
                'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
            ]);

        } else {

            $this->_data = $this->getAllData($id);
            $this->_data['rules'] = $rules->errors;

            return $this->view('admin/posts/edit')->data($this->_data);
        }

        Session::set('success', 'You have successfully updated the meta data on this page!');
        redirect("/admin/posts/$id/edit");
    }

    public function detachCategory($request) {

        $id = $request['id'];
        $this->ifExists($request['id']);
        //$this->redirect("submit", "/admin/posts/$id/edit");
        $this->unsetSessions(['slug', 'widget', 'cdn', 'css', 'js', 'meta']);
        Session::set('category', true);

        $rules = new Rules();

        $slugParts = explode('/', Post::getColumns(['slug'], $id)['slug']);
        $lastPageSlugKey = array_key_last($slugParts);
        $lastPageSlugValue = "/" . $slugParts[$lastPageSlugKey];
            
        if($rules->remove_post_category($request['submit'], Post::checkUniqueSlug($lastPageSlugValue, $id))->validated()) {
            
            Post::update(['id' => $id], [

                'slug'  => $lastPageSlugValue,
            ]);
    
            CategoryPage::delete('page_id', $id);
    
            Session::set('success', 'You have successfully removed the category on this page!');
            redirect("/admin/posts/$id/edit");

        } else {
                
            $this->_data = $this->getAllData($id);
            $this->_data['rules'] = $rules->errors;

            return $this->view('admin/posts/edit')->data($this->_data);
        }
    }

    public function recover($request) {

        $id = $request['id'];
        //$this->redirect("recoverIds", "/admin/posts");
        $recoverIds = explode(',', $request['recoverIds']);
                
        foreach($recoverIds as $request['id'] ) {

            $this->ifExists($request['id']);
            
            Post::update(['id' => $request['id']], [
            
                'removed'  => 0,
                'slug' => "/" . Post::getColumns(['title'], $request['id'])['title']
            ]);
        }
        
        Session::set('success', 'You have successfully recovered the page(s)!');
        redirect("/admin/posts");
    }

    public function delete($request) {

       // $this->redirect("deleteIds", "/admin/posts");
        $deleteIds = explode(',', $request['deleteIds']);

        if(!empty($deleteIds) && !empty($deleteIds[0])) {

            foreach($deleteIds as $id) {

                $this->ifExists($id);

                if(Post::getColumns(['removed'], $id)['removed'] !== 1) {
            
                    Post::update(['id' => $id], [
            
                        'removed'  => 1,
                        'slug'  => ''
                    ]);

                    Session::set('success', 'You have successfully moved the page(s) to the trashcan!');
            
                } else if(Post::getColumns(['removed'], $id)['removed'] === 1) {
            
                    Post::delete("id", $id);
                    CategoryPage::delete('page_id', $id);

                    Session::set('success', 'You have successfully removed the page(s)!');
                }
            }
        }

        redirect("/admin/posts");
    }
}