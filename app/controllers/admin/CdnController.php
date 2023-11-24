<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use app\models\Cdn;
use app\models\Post;
use extensions\Pagination;
use app\models\CdnPage;
use core\Csrf;
use validation\Rules;
use core\Session;
use core\http\Response;
use validation\Get;

class CdnController extends Controller {

    private $_data;

    private function ifExists($id) {

        if(empty(Cdn::ifRowExists($id)) ) {

            return Response::statusCode(404)->view("/404/404")->data() . exit();
        }
    }

    public function index($request) {

        $cdn = Cdn::allCdnsButOrderedByDate();

        $this->_data['search'] = '';

        if(!empty($request['search'] ) ) {

            $this->_data['search'] = Get::validate($request['search']);
            $cdn = Cdn::orderedCdnsOnSearch($this->_data['search']);
        }

        $this->_data['cdns'] = Pagination::get($request, $cdn, 10);
        $this->_data['count'] = count($cdn);
        $this->_data['numberOfPages'] = Pagination::getPageNumbers();

        return $this->view('admin/cdn/index')->data($this->_data);
    }

    public function create() {

        $this->_data['rules'] = [];

        return $this->view('admin/cdn/create')->data($this->_data);
    }

    public function store($request) {

        $rules = new Rules();

        if(!empty($request['content']) && $request['content'] !== null) { $hasContent = 1; } else { $hasContent = 0; }

        if($rules->cdn($request['title'], Cdn::whereColumns(['title'], ['title' => $request['title']]))->validated() ) {

            Cdn::insert([

                'title' => $request['title'],
                'content' => $request['content'],
                'has_content' => $hasContent,
                'removed'   => 0,
                'author' => Session::get('username'),
                'created_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
                'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
            ]);

            Session::set('success', 'You have successfully created a new cdn!');
            redirect('/admin/cdn');
               
        } else {

            $this->_data['rules'] = $rules->errors;
            $this->_data['title'] = $request['title'];

            return $this->view('admin/cdn/create')->data($this->_data);
        }
    }

    public function read($request) {

        $this->ifExists($request['id']);

        $this->_data['cdn'] = Cdn::get($request['id']);

        return $this->view('admin/cdn/read')->data($this->_data);
    }

    public function edit($request) {

        $this->ifExists($request['id']);

        $this->_data['cdn'] = Cdn::get($request['id']);
        $this->_data['importedPages'] = Cdn::getPostImportedIdTitle($request['id']);
        $this->_data['pages'] = Cdn::getNotPostImportedIdTitle(Cdn::getPostImportedIdTitle($request['id']));
        $this->_data['rules'] = [];

        return $this->view('admin/cdn/edit')->data($this->_data);
    }

    public function update($request) {
        
        $id = $request['id'];
        $this->ifExists($request['id']);

        $rules = new Rules();
        
        if(!empty($request['content']) && $request['content'] !== null) { $hasContent = 1; } else { $hasContent = 0; }

        if($rules->cdn($request['title'], Cdn::checkUniqueTitleId($request['title'], $id))->validated() ) {

            Cdn::update(['id' => $id], [

                'title'     => $request['title'],
                'content' => $request['content'],
                'has_content'   => $hasContent,
                'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
            ]);

            Session::set('success', 'You have successfully updated the cdn!');
            redirect("/admin/cdn/$id/edit");

        } else {

            $this->_data['cdn'] = Cdn::get($request['id']);
            $this->_data['importedPages'] = Cdn::getPostImportedIdTitle($request['id']);
            $this->_data['pages'] = Cdn::getNotPostImportedIdTitle(Cdn::getPostImportedIdTitle($request['id']));
            $this->_data['rules'] = $rules->errors;

            return $this->view('admin/cdn/edit')->data($this->_data);
        }
    }

    public function importPage($request) {

        $id = $request['id'];
        $this->ifExists($request['id']);

        foreach($request['pages'] as $pageId) {

            CdnPage::insert([

                'page_id' => $pageId,
                'cdn_id' => $request['id']
            ]);
        }

        Session::set('success', 'You have successfully imported the cdn on the page(s)!');
        redirect("/admin/cdn/$id/edit");
    }

    public function importAll($request) {

        $id = $request['id'];
        $this->ifExists($request['id']);

        CdnPage::delete('cdn_id', $id);

        foreach(Post::getAll(['id']) as $pageId ) {

            CdnPage::insert([

                'page_id' => $pageId['id'],
                'cdn_id'    => $id
            ]);
        }
        
        Session::set('success', 'You have successfully imported the cdn on all pages!');
        redirect("/admin/cdn/$id/edit");
    }

    public function exportPage($request) {

        $id = $request['id'];
        $this->ifExists($request['id']);

        foreach($request['pages'] as $pageId) {

            Cdn::deleteIdPostId($id, $pageId);
        }

        Session::set('success', 'You have successfully removed the cdn on the page(s)!');
        redirect("/admin/cdn/$id/edit");
    }

    public function exportAll($request) {

        $id = $request['id'];
        $this->ifExists($request['id']);
  
        CdnPage::delete('cdn_id', $request['id']);

        Session::set('success', 'You have successfully removed the cdn on all pages!');
        redirect("/admin/cdn/$id/edit");
    }

    public function recover($request) {

        $recoverIds = explode(',', $request['recoverIds']);
            
        foreach($recoverIds as $request['id'] ) {

            $this->ifExists($request['id']);

            Cdn::update(['id' => $request['id']], [

                'removed'  => 0
            ]);
        }
        
        Session::set('success', 'You have successfully recovered the cdn(s)!');
        redirect("/admin/cdn");
    }

    public function delete($request) {

        $deleteIds = explode(',', $request['deleteIds']);

        if(!empty($deleteIds) && !empty($deleteIds[0])) {

            foreach($deleteIds as $id) {

                $this->ifExists($id);
    
                if(Cdn::getColumns(['removed'], $id)['removed'] !== 1) {

                    Cdn::update(['id' => $id], [

                        'removed'  => 1
                    ]);

                    Session::set('success', 'You have successfully moved the cdn(s) to the trashcan!');

                } else if(Cdn::getColumns(['removed'], $id)['removed'] === 1) {

                    Cdn::delete("id", $id);
                    Session::set('success', 'You have successfully removed the cdn(s)!');
                }
            }
        }

        redirect("/admin/cdn");
    }
}