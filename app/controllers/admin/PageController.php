<?php

namespace app\controllers\admin;

use validation\Rules;
use app\models\Page;
use app\models\CategoryPage;
use app\models\Category;
use app\models\PageMeta;
use app\models\Css;
use app\models\Js;
use app\models\Meta;
use app\models\Widget;
use app\models\PageWidget;
use app\models\Menu;
use app\models\CssPage;
use app\models\JsPage;
use core\Session;
use extensions\Pagination;
use core\http\Response;
use validation\Get;

class PageController extends \app\controllers\Controller {

    private $_data;

    /**
     * To show 404 page with 404 status code (on not existing page)
     * 
     * @param string $id _POST page id
     * @return object PageController
     */ 
    private function ifExists($id) {

        if(empty(Page::ifRowExists($id)) ) {

            return Response::statusCode(404)->view("/404/404")->data() . exit();
        }
    }

    /**
     * To show the page index view
     * 
     * @param array $request _GET search, page
     * @return object PageController, Controller
     */
    public function index($request) {
        
        $pages = Page::allPagesWithCategories();

        $this->_data['search'] = '';

        if(!empty($request['search'] ) ) {

            $this->_data['search'] = Get::validate($request['search']);
            $pages = Page::allPagesWithCategoriesOnSearch($this->_data['search']);
        }

        $this->_data["pages"] = Pagination::get($request, $pages, 10);
        $this->_data["count"] = count($pages);
        $this->_data['numberOfPages'] = Pagination::getPageNumbers();

        return $this->view('admin/pages/index')->data($this->_data);
    }

    /**
     * To show the page create view
     * 
     * @return object PageController, Controller
     */
    public function create() {
        
        $this->_data['rules'] = [];
        return $this->view('admin/pages/create')->data($this->_data);
    }

    /**
     * To store a new page (on successful validation)
     * 
     * @param array $request _POST title, body
     * @return object PageController, Controller (on failed validation)
     */
    public function store($request) {

        $rules = new Rules();
    
        if($rules->page($request, Page::whereColumns(['id', 'title'], ['title' => $request['title']]))->validated()) {
                        
            $slug = "/" . strtolower($request['title']);
            $slug = str_replace(" ", "-", $slug);

            if(!empty($request['body']) ) { $hasContent = 1; } else { $hasContent = 0; }

            Page::insert([
    
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
            redirect('/admin/pages');
        } else {

            $this->_data['body'] = $request['body'];
            $this->_data['title'] = $request['title'];
            $this->_data['rules'] = $rules->errors;

            return $this->view('admin/pages/create')->data($this->_data);
        }
    }

    /**
     * To show the page read view
     * 
     * @param array $request id (page id), _GET search, page
     * @return object PageController, Controller
     */
    public function read($request) {

        $this->ifExists($request['id']);

        $this->_data['page'] = Page::get($request['id']);

        if(!empty(Widget::getPageWidgets($this->_data['page']['id'])) ) {

            foreach(Widget::getPageWidgets($this->_data['page']['id']) as $widget) {

                $widgetId = $widget['widget_id'];
                $regex = '/@widget\[' . $widgetId . '\];/';
                $content = Widget::whereColumns(['content'], ['id' => $widgetId]);
                $this->_data['page']['body'] = preg_replace($regex, $content[0]['content'], $this->_data['page']['body']);
            }
        }

        $this->_data['cssFiles'] = Page::getCssIdFilenameExtension($request['id']);
        $this->_data['jsFiles'] = Page::getJs($request['id']);
        $this->_data['metas'] = Meta::getContent($request['id']);
        $this->_data['menusTop'] = Menu::getTopMenus();
        $this->_data['menusBottom'] =  Menu::getBottomMenus();

        return $this->view('/admin/pages/read')->data($this->_data);
    }

    /**
     * To show the page edit view
     * 
     * @param array $request id (page id), _GET search, page
     * @return object PageController, Controller
     */
    public function edit($request) {

        $this->ifExists($request['id']);
        $this->setDefaultSession('slug');
        
        $this->_data = $this->getAllData($request['id']);
        $this->_data['rules'] = [];

        return $this->view('admin/pages/edit')->data($this->_data);
    }

    /**
     * To update page data (on successful validation)
     * 
     * @param array $request id (page id), _POST title, body
     * @return object PageController, Controller (on failed validation)
     */
    public function update($request) {

        $id = $request['id'];
        $this->unsetSessions(['cdn', 'widget', 'category', 'css', 'js', 'meta']);
        $this->ifExists($id);

        $rules = new Rules();

        if($rules->page($request, Page::checkUniqueTitleId($request['title'], $id))->validated()) {
                
            if(!empty($request['body']) ) { $hasContent = 1; } else { $hasContent = 0; }

            Page::update(['id' => $id], [

                'title' => $request["title"],
                'body' => $request["body"],
                'has_content' => $hasContent,
                'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
            ]);

            Session::set('success', 'You have successfully updated the page!'); 
            redirect("/admin/pages/$id/edit");
                
        } else {

            $this->_data = $this->getAllData($id, $request);
            $this->_data['rules'] = $rules->errors;

            return $this->view('admin/pages/edit')->data($this->_data);
        }
    }

    /**
     * To unset sessions on session name
     * 
     * @param array $names session names
     */
    private function unsetSessions($names) {

        if(!empty($names) && $names !== null) {

            foreach($names as $name) {

                Session::delete($name);
            }
        }
    }

    /**
     * To only show a certain 'section' on page edit view after clicking on sidebar
     * 
     * @param string $slug slug
     */
    private function setDefaultSession($slug) {

        $names = ['cdn', 'widget', 'category', 'css', 'js', 'meta'];

        foreach($names as $name) {

            if(Session::exists($name)) {

                return;
            }
        }

        Session::set($slug, true);
    }

    /**
     * To get all necessary data on failed validation or page edit view
     * 
     * @param string $id page id
     * @param array optional $requestData request data 
     */
    private function getAllData($id, $requestData = null) {

        $pageData = Page::get($id);
        $pageSlug = explode('/', $pageData['slug']);
        $pageSlug = "/" . $pageSlug[array_key_last($pageSlug)];

        $this->_data['data'] = $pageData;

        if(!empty($requestData['body']) && $requestData['body'] !== null) {

            $this->_data['data']['body'] = $requestData['body'];
        }

        $this->_data['data']['pageSlug'] = $pageSlug;
        $this->_data['data']['linkedCssFiles'] = Page::getCssIdFilenameExtension($id);
        $this->_data['data']['notLinkedCssFiles'] = Page::getNotCssIdFilenameExtension(Page::getCssIdFilenameExtension($id));
        $this->_data['data']['linkedJsFiles'] = Page::getJsIdFilenameExtension($id);
        $this->_data['data']['notLinkedJsFiles'] = Page::getNotJsIdFilenameExtension(Page::getJsIdFilenameExtension($id));

        if(empty(Page::checkCategory($id))) {

            $this->_data['data']['categories'] = Category::getAll(['id', 'title']);
        } else {
            $this->_data['data']['category'] = Page::getCategoryTitleSlug($id);
        }

        $this->_data['data']['applicableWidgets'] = Page::getApplicableWidgetIdTitle($id);
        $this->_data['data']['inapplicableWidgets'] = Page::getInapplicableWidgetIdTitle(Page::getApplicableWidgetIdTitle($id));
        $this->_data['data']['exportCdns'] = Page::getCdnIdTitle($id);
        $this->_data['data']['importCdns'] = Page::getNotCdnIdTitle(Page::getCdnIdTitle($id));

        return $this->_data;
    }

    /** 
     * To import meta(s) on page
     * 
     * @param array $request id (page id), _POST cdns
     */
    public function importCdns($request) {

        $id = $request['id'];
        $this->unsetSessions(['slug', 'widget', 'category', 'css', 'js', 'meta']);
        Session::set('cdn', true); 

        foreach($request['cdns'] as $cdnId) {

            PageMeta::insert([

                'page_id' => $id,
                'meta_id' => $cdnId
            ]);
        }

        Session::set('success', 'You have successfully imported the meta(s) on this page!'); 
        redirect("/admin/pages/$id/edit");
    }

    /** 
     * To export meta(s) from page
     * 
     * @param array $request id (page id), _POST cdns
     */
    public function exportCdns($request) {

        $id = $request['id'];
        $this->unsetSessions(['slug', 'widget', 'category', 'css', 'js', 'meta']);
        Session::set('cdn', true);

        foreach($request['cdns'] as $cdnId) {

            Page::deleteCdn($id, $cdnId);
        }

        Session::set('success', 'You have successfully removed the meta(s) on this page!'); 
        redirect("/admin/pages/$id/edit");
    }

    /**
     * To make widget(s) applicable on page
     * 
     * @param array $request id (page id), _POST widgets
     */
    public function addWidget($request) {

        $id = $request['id'];
        $this->ifExists($id);
        $this->unsetSessions(['slug', 'cdn', 'category', 'css', 'js', 'meta']);
        Session::set('widget', true);

        if(!empty($request['widgets']) && $request['widgets'] !== null) {

            foreach($request['widgets'] as $widgetId) {

                PageWidget::insert([

                    'page_id' => $id,
                    'widget_id' => $widgetId
                ]);
            }
        }

        Session::set('success', 'You have successfully made the widget(s) applicable for this page!'); 
        redirect("/admin/pages/$id/edit");
    }

    /**
     * To make widget(s) inapplicable on page
     * 
     * @param array $request id (page id), _POST widgets
     */
    public function removeWidget($request) {

        $id = $request['id'];
        $this->ifExists($id);
        $this->unsetSessions(['slug', 'cdn', 'category', 'css', 'js', 'meta']);
        Session::set('widget', true);

        if(!empty($request['widgets']) && $request['widgets'] !== null) {

            foreach($request['widgets'] as $widgetId) {

                Widget::removePagewidget($id, $widgetId);
            }
        }

        Session::set('success', 'You have successfully made the widget(s) inapplicable for this page!'); 
        redirect("/admin/pages/$id/edit");
    }

    /**
     * To assign category on page (on successful validation)
     * 
     * @param array $request id (page id), _POST categories
     * @return object PageController, Controller (on failed validation)
     */
    public function assignCategory($request) {

        $id = $request['id'];
        $this->ifExists($id);
        $this->unsetSessions(['slug', 'widget', 'cdn', 'css', 'js', 'meta']);
        Session::set('category', true);

        $rules = new Rules();

        $slug = explode('/', Page::getColumns(['slug'], $id)['slug']);
        $lastKey = array_key_last($slug);
        $categoryId = $request['categories'];

        if($rules->page_update_category($request, Page::checkUniqueSlugCategory($id, $slug[$lastKey], $categoryId))->validated()) {

            CategoryPage::insert([

                'category_id' => $categoryId,
                'page_id'    => $id
            ]);

            $this->updateSlugCategory($id, $categoryId);

            Session::set('success', 'You have successfully assigned the category on this page!'); 
            redirect("/admin/pages/$id/edit");

        } else {

            $this->_data = $this->getAllData($id);
            $this->_data['rules'] = $rules->errors;

            return $this->view('admin/pages/edit')->data($this->_data);
        }
    }

    /**
     * To update page data (slug) after assigning a category
     * 
     * @param string $id page id 
     * @param string $categoryId _POST category id
     */
    private function updateSlugCategory($id, $categoryId) {

        if(!empty(Category::getSlugSub($categoryId))) {

            $subCategorySlugs = [];

            foreach(Category::getSlugSub($categoryId) as $subCategorySlug) {

                array_push($subCategorySlugs, $subCategorySlug['slug']);
            }

            $subCategorySlugsString = implode('', $subCategorySlugs);
    
            Page::update(['id' => $id], [

                'slug'  =>  $subCategorySlugsString . Category::getSlug($categoryId)['slug'] . Page::getColumns(['slug'], $id)['slug']
            ]);

        } else {

            Page::update(['id' => $id], [
    
                'slug'  => Category::getSlug($categoryId)['slug'] . Page::getColumns(['slug'], $id)['slug']
            ]);
        }
    }

    /**
     * To exclude js file(s) on page
     * 
     * @param array $request id (page id), _POST linkedJsFiles
     */
    public function removeJs($request) {

        $id = $request['id'];
        $this->ifExists($id);
        $this->unsetSessions(['slug', 'widget', 'cdn', 'category', 'css', 'meta']);
        Session::set('js', true);

        foreach($request['linkedJsFiles'] as $linkedJsId) {

            Page::deleteJs($id, $linkedJsId);
        }
            
        Session::set('success', 'You have successfully removed the js file(s) on this page!');
        redirect("/admin/pages/$id/edit");
    }

    /**
     * To include js file(s) on page
     * 
     * @param array $request id (page id), _POST jsFiles
     */
    public function includeJs($request) {

        $id = $request['id'];
        $this->ifExists($id);
        $this->unsetSessions(['slug', 'widget', 'cdn', 'category', 'css', 'meta']);
        Session::set('js', true);

        foreach($request['jsFiles'] as $jsId) {

            JsPage::insert([

                'js_id' => $jsId,
                'page_id' => $id
            ]);
        }

        Session::set('success', 'You have successfully included the js file(s) on this page!');
        redirect("/admin/pages/$id/edit");
    }

    /**
     * To link css file(s) on page
     * 
     * @param array $request id (page id), _POST cssFiles
     */
    public function linkCss($request) {

        $id = $request['id'];
        $this->ifExists($id);
        $this->unsetSessions(['slug', 'widget', 'cdn', 'category', 'js', 'meta']);
        Session::set('css', true);

        foreach($request['cssFiles'] as $cssId) {

            CssPage::insert([

                'css_id' => $cssId,
                'page_id' => $id
            ]);
        };

        Session::set('success', 'You have successfully linked the css file(s) on this page!');
        redirect("/admin/pages/$id/edit");
    }

    /**
     * To unlink css file(s) on page
     * 
     * @param array $request id (page id), _POST linkedCssFiles
     */
    public function unLinkCss($request) {

        $id = $request['id'];
        $this->ifExists($id);
        $this->unsetSessions(['slug', 'widget', 'cdn', 'category', 'js', 'meta']);
        Session::set('css', true);

        foreach($request['linkedCssFiles'] as $cssId) {

            Page::deleteCss($id, $cssId);
        }

        Session::set('success', 'You have successfully removed the css file(s) on this page!');
        redirect("/admin/pages/$id/edit");
    }

    /**
     * To update page data (slug) (on successful validation)
     * 
     * @param array $request id (page id), _POST pageSlug
     * @return object PageController, Controller (on failed validation)
     */
    public function updateSlug($request) {

        $id = $request['id'];
        $this->ifExists($id);
        $this->unsetSessions(['widget', 'cdn', 'category', 'js', 'meta', 'css']);
        Session::set('slug', true);

        $rules = new Rules();
            
        $slug = explode('/', $request['slug']);
        $lastKey = array_key_last($slug);

        $slug[$lastKey] = $request['pageSlug'];
        $fullPageSlug = implode('/', $slug);

        if($rules->page_slug($request, Page::checkUniqueSlug($fullPageSlug, $id))->validated()) {

            $slug = explode('/', "/" . $request['slug']);
            $slug[array_key_last($slug)] = substr("/" . $request['pageSlug'], 1);
            $slug = implode('/', array_filter($slug));

            Page::update(['id' => $id], [

                'slug' => "/" . $slug,
                'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
            ]);

        } else {

            $this->_data = $this->getAllData($id);
            $this->_data['rules'] = $rules->errors;

            return $this->view('admin/pages/edit')->data($this->_data);
        }

        Session::set('success', 'You have successfully updated the slug on this page!');
        redirect("/admin/pages/$id/edit");
    }

    /**
     * To update page data (meta title, meta description, meta keywords) (on successful validation)
     * 
     * @param array $request id (page id), _POST metaTitle, metaDescription, metaKeywords
     * @return object PageController, Controller (on failed validation)
     */
    public function updateMetadata($request) {

        $id = $request['id'];
        $this->ifExists($id);
        $this->unsetSessions(['slug', 'widget', 'cdn', 'category', 'css', 'js']);
        Session::set('meta', true);

        $rules = new Rules();

        if($rules->seo($request)->validated()) {

            Page::update(['id' => $id], [

                'metaTitle' => $request['metaTitle'],
                'metaDescription' => $request['metaDescription'],
                'metaKeywords' => $request['metaKeywords'],
                'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
            ]);

        } else {

            $this->_data = $this->getAllData($id);
            $this->_data['rules'] = $rules->errors;

            return $this->view('admin/pages/edit')->data($this->_data);
        }

        Session::set('success', 'You have successfully updated the meta data on this page!');
        redirect("/admin/pages/$id/edit");
    }

    /**
     * To detach category from page and update page data (slug) (on successful validation)
     * 
     * @param array $request id (page id), _POST slug
     * @return object PageController, Controller (on failed validation)
     */
    public function detachCategory($request) {

        $id = $request['id'];
        $this->ifExists($request['id']);
        $this->unsetSessions(['slug', 'widget', 'cdn', 'css', 'js', 'meta']);
        Session::set('category', true);

        $rules = new Rules();

        $slugParts = explode('/', Page::getColumns(['slug'], $id)['slug']);
        $lastPageSlugKey = array_key_last($slugParts);
        $lastPageSlugValue = "/" . $slugParts[$lastPageSlugKey];

        if($rules->page_remove_category($request, Page::checkUniqueSlug($lastPageSlugValue, $id))->validated()) {
            
            Page::update(['id' => $id], [

                'slug'  => $lastPageSlugValue,
            ]);
    
            CategoryPage::delete('page_id', $id);
    
            Session::set('success', 'You have successfully removed the category on this page!');
            redirect("/admin/pages/$id/edit");

        } else {
                
            $this->_data = $this->getAllData($id);
            $this->_data['rules'] = $rules->errors;

            return $this->view('admin/pages/edit')->data($this->_data);
        }
    }

    /**
     * To remove page(s) from thrashcan
     * 
     * @param array $request _POST recoverIds (page recoverIds) 
     */
    public function recover($request) {

        $recoverIds = explode(',', $request['recoverIds']);
                
        foreach($recoverIds as $id) {

            $this->ifExists($id);
            
            Page::update(['id' => $id], [
            
                'removed'  => 0,
                'slug' => "/" . Page::getColumns(['title'], $id)['title']
            ]);
        }
        
        Session::set('success', 'You have successfully recovered the page(s)!');
        redirect("/admin/pages");
    }

    /**
     * To remove page(s) permanently or move to thrashcan
     * 
     * @param array $request _POST deleteIds (page deleteIds)
     */
    public function delete($request) {

        $deleteIds = explode(',', $request['deleteIds']);

        if(!empty($deleteIds) && !empty($deleteIds[0])) {

            foreach($deleteIds as $id) {

                $this->ifExists($id);

                if(Page::getColumns(['removed'], $id)['removed'] !== 1) {
            
                    Page::update(['id' => $id], [
            
                        'removed'  => 1,
                        'slug'  => ''
                    ]);

                    CategoryPage::delete('page_id', $id);

                    Session::set('success', 'You have successfully moved the page(s) to the trashcan!');
            
                } else if(Page::getColumns(['removed'], $id)['removed'] === 1) {
            
                    Page::delete("id", $id);
                    CategoryPage::delete('page_id', $id);

                    Session::set('success', 'You have successfully removed the page(s)!');
                }
            }
        }

        redirect("/admin/pages");
    }
}