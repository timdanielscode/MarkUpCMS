<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use app\models\Meta;
use app\models\Post;
use extensions\Pagination;
use app\models\PageMeta;
use validation\Rules;
use core\Session;
use core\http\Response;
use validation\Get;

class MetaController extends Controller {

    private $_data;

    /**
     * To show 404 page with 404 status code (on not existing meta)
     * 
     * @param string $id _POST meta id
     * @return object MetaController
     */ 
    private function ifExists($id) {

        if(empty(Meta::ifRowExists($id)) ) {

            return Response::statusCode(404)->view("/404/404")->data() . exit();
        }
    }

    /**
     * To show the meta index view
     * 
     * @param array $request _GET search, page
     * @return object MetaController, Controller
     */
    public function index($request) {

        $cdn = Meta::allMetaButOrderedByDate();

        $this->_data['search'] = '';

        if(!empty($request['search'] ) ) {

            $this->_data['search'] = Get::validate($request['search']);
            $cdn = Meta::orderedMetaOnSearch($this->_data['search']);
        }

        $this->_data['cdns'] = Pagination::get($request, $cdn, 10);
        $this->_data['count'] = count($cdn);
        $this->_data['numberOfPages'] = Pagination::getPageNumbers();

        return $this->view('admin/meta/index')->data($this->_data);
    }

    /**
     * To show the meta create view
     * 
     * @return object MetaController, Controller
     */
    public function create() {

        $this->_data['rules'] = [];

        return $this->view('admin/meta/create')->data($this->_data);
    }

    /**
     * To store a new meta (on successful validation)
     * 
     * @param array $request _POST title, content
     * @return object MetaController, Controller (on failed validation)
     */
    public function store($request) {

        $rules = new Rules();

        if(!empty($request['content']) && $request['content'] !== null) { $hasContent = 1; } else { $hasContent = 0; }

        if($rules->cdn($request['title'], Meta::whereColumns(['title'], ['title' => $request['title']]))->validated() ) {

            Meta::insert([

                'title' => $request['title'],
                'content' => $request['content'],
                'has_content' => $hasContent,
                'removed'   => 0,
                'author' => Session::get('username'),
                'created_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
                'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
            ]);

            Session::set('success', 'You have successfully created a new cdn!');
            redirect('/admin/meta');
               
        } else {

            $this->_data['title'] = $request['title'];
            $this->_data['content'] = $request['content'];
            $this->_data['rules'] = $rules->errors;
            
            return $this->view('admin/meta/create')->data($this->_data);
        }
    }

    /**
     * To show the meta read view
     * 
     * @param array $request id (meta id)
     * @return object MetaController, Controller
     */
    public function read($request) {

        $this->ifExists($request['id']);

        $this->_data['cdn'] = Meta::get($request['id']);

        return $this->view('admin/meta/read')->data($this->_data);
    }

    /**
     * To show the meta edit view
     * 
     * @param array $request id (meta id)
     * @return object MetaController, Controller
     */
    public function edit($request) {

        $this->ifExists($request['id']);

        $this->_data['cdn'] = Meta::get($request['id']);
        $this->_data['importedPages'] = Meta::getPostImportedIdTitle($request['id']);
        $this->_data['pages'] = Meta::getNotPostImportedIdTitle(Meta::getPostImportedIdTitle($request['id']));
        $this->_data['rules'] = [];

        return $this->view('admin/meta/edit')->data($this->_data);
    }

    /**
     * To update meta data (on successful validation)
     * 
     * @param array $request id (meta id), _POST title, content
     * @return object MetaController, Controller (on failed validation)
     */
    public function update($request) {
        
        $id = $request['id'];
        $this->ifExists($request['id']);

        $rules = new Rules();
        
        if(!empty($request['content']) && $request['content'] !== null) { $hasContent = 1; } else { $hasContent = 0; }

        if($rules->cdn($request['title'], Meta::checkUniqueTitleId($request['title'], $id))->validated() ) {

            Meta::update(['id' => $id], [

                'title'     => $request['title'],
                'content' => $request['content'],
                'has_content'   => $hasContent,
                'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
            ]);

            Session::set('success', 'You have successfully updated the cdn!');
            redirect("/admin/meta/$id/edit");

        } else {

            $this->_data['cdn'] = Meta::get($request['id']);
            $this->_data['importedPages'] = Meta::getPostImportedIdTitle($request['id']);
            $this->_data['pages'] = Meta::getNotPostImportedIdTitle(Meta::getPostImportedIdTitle($request['id']));
            $this->_data['rules'] = $rules->errors;

            return $this->view('admin/meta/edit')->data($this->_data);
        }
    }

    /**
     * To import a meta on page(s)
     * 
     * @param array $request id (meta id), _POST pages
     */
    public function importPage($request) {

        $id = $request['id'];
        $this->ifExists($request['id']);

        foreach($request['pages'] as $pageId) {

            PageMeta::insert([

                'page_id' => $pageId,
                'cdn_id' => $request['id']
            ]);
        }

        Session::set('success', 'You have successfully imported the cdn on the page(s)!');
        redirect("/admin/meta/$id/edit");
    }

    /**
     * To import a meta on all pages
     * 
     * @param array $request id (meta id)
     */
    public function importAll($request) {

        $id = $request['id'];
        $this->ifExists($request['id']);

        PageMeta::delete('cdn_id', $id);

        foreach(Post::getAll(['id']) as $pageId ) {

            PageMeta::insert([

                'page_id' => $pageId['id'],
                'cdn_id'    => $id
            ]);
        }
        
        Session::set('success', 'You have successfully imported the cdn on all pages!');
        redirect("/admin/meta/$id/edit");
    }

    /**
     * To export a meta from page(s)
     * 
     * @param array $request id (meta id), _POST pages
     */
    public function exportPage($request) {

        $id = $request['id'];
        $this->ifExists($request['id']);

        foreach($request['pages'] as $pageId) {

            Meta::deleteIdPostId($id, $pageId);
        }

        Session::set('success', 'You have successfully removed the cdn on the page(s)!');
        redirect("/admin/meta/$id/edit");
    }

    /**
     * To export a meta from all pages
     * 
     * @param array $request id (meta id)
     */
    public function exportAll($request) {

        $id = $request['id'];
        $this->ifExists($request['id']);
  
        PageMeta::delete('cdn_id', $request['id']);

        Session::set('success', 'You have successfully removed the cdn on all pages!');
        redirect("/admin/meta/$id/edit");
    }

    /**
     * To remove meta(s) from thrashcan
     * 
     * @param array $request _POST recoverIds (meta recoverIds)
     */
    public function recover($request) {

        $recoverIds = explode(',', $request['recoverIds']);
            
        foreach($recoverIds as $id ) {

            $this->ifExists($id);

            Meta::update(['id' => $id], [

                'removed'  => 0
            ]);
        }
        
        Session::set('success', 'You have successfully recovered the cdn(s)!');
        redirect("/admin/meta");
    }

    /**
     * To remove meta(s) permanently or move to thrashcan
     * 
     * @param array $request _POST deleteIds (meta deleteIds)
     */
    public function delete($request) {

        $deleteIds = explode(',', $request['deleteIds']);

        if(!empty($deleteIds) && !empty($deleteIds[0])) {

            foreach($deleteIds as $id) {

                $this->ifExists($id);
    
                if(Meta::getColumns(['removed'], $id)['removed'] !== 1) {

                    Meta::update(['id' => $id], [

                        'removed'  => 1
                    ]);

                    Session::set('success', 'You have successfully moved the cdn(s) to the trashcan!');

                } else if(Meta::getColumns(['removed'], $id)['removed'] === 1) {

                    Meta::delete("id", $id);
                    Session::set('success', 'You have successfully removed the cdn(s)!');
                }
            }
        }

        redirect("/admin/meta");
    }
}