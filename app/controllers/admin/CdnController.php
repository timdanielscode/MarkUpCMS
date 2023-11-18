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

    private $_count;

    private function ifExists($id) {

        if(empty(Cdn::ifRowExists($id)) ) {

            return Response::statusCode(404)->view("/404/404") . exit();
        }
    }

    private function redirect($inputName, $path) {

        if(submitted($inputName) === false || Csrf::validate(Csrf::token('get'), post('token')) === false ) { 
            
            redirect($path) . exit(); 
        } 
    }

    public function index() {

        $data['cdns'] = $this->getCdn(Get::validate([get('search')]));
        $data['count'] = $this->_count;
        $data['numberOfPages'] = Pagination::getPageNumbers();

        return $this->view('admin/cdn/index', $data);
    }

    private function getCdn($search) {

        $cdn = Cdn::allCdnsButOrderedByDate();
    
        if(!empty($search)) {
    
            $cdn = Cdn::orderedCdnsOnSearch($search);
        }
    
        $this->_count = count($cdn);
        return Pagination::get($cdn, 10);
    }

    public function create() {

        $data['rules'] = [];

        return $this->view('admin/cdn/create', $data);
    }

    public function store($request) {

        $this->redirect("submit", '/admin/cdn');

        $rules = new Rules();

        if(!empty($request['content']) && $request['content'] !== null) { $hasContent = 1; } else { $hasContent = 0; }

        if($rules->create_cdn(Cdn::whereColumns(['title'], ['title' => $request['title']]))->validated() ) {

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

            $data['rules'] = $rules->errors;
            return $this->view('admin/cdn/create', $data);
        }
    }

    public function read($request) {

        $this->ifExists($request['id']);

        $cdn = Cdn::get($request['id']);
        $data['cdn'] = $cdn;

        return $this->view('admin/cdn/read', $data);
    }

    public function edit($request) {

        $this->ifExists($request['id']);

        $data['cdn'] = Cdn::get($request['id']);
        $data['importedPages'] = Cdn::getPostImportedIdTitle($request['id']);
        $data['pages'] = Cdn::getNotPostImportedIdTitle(Cdn::getPostImportedIdTitle($request['id']));
        $data['rules'] = [];

        return $this->view('admin/cdn/edit', $data);
    }

    public function update($request) {
        
        $id = $request['id'];
        $this->ifExists($request['id']);
        $this->redirect("submit", "/admin/cdn/$id/edit");

        $rules = new Rules();
        
        if(!empty($request['content']) && $request['content'] !== null) { $hasContent = 1; } else { $hasContent = 0; }

        if($rules->edit_cdn(Cdn::checkUniqueTitleId($request['title'], $id))->validated() ) {

            Cdn::update(['id' => $id], [

                'title'     => $request['title'],
                'content' => $request['content'],
                'has_content'   => $hasContent,
                'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
            ]);

            Session::set('success', 'You have successfully updated the cdn!');
            redirect("/admin/cdn/$id/edit");

        } else {

            $data['cdn'] = Cdn::get($request['id']);
            $data['rules'] = $rules->errors;

            return $this->view('admin/cdn/edit', $data);
        }
    }

    public function importPage($request) {

        $id = $request['id'];
        $this->ifExists($request['id']);
        $this->redirect("submit", "/admin/cdn/$id/edit");

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
        $this->redirect("submit", "/admin/cdn/$id/edit");

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
        $this->redirect("submit", "/admin/cdn/$id/edit");

        foreach($request['pages'] as $pageId) {

            Cdn::deleteIdPostId($id, $pageId);
        }

        Session::set('success', 'You have successfully removed the cdn on the page(s)!');
        redirect("/admin/cdn/$id/edit");
    }

    public function exportAll($request) {

        $id = $request['id'];
        $this->ifExists($request['id']);
        $this->redirect("submit", "/admin/cdn/$id/edit");
  
        CdnPage::delete('cdn_id', $request['id']);

        Session::set('success', 'You have successfully removed the cdn on all pages!');
        redirect("/admin/cdn/$id/edit");
    }

    public function recover($request) {

        $this->redirect("recoverIds", "/admin/cdn");

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

        $this->redirect("deleteIds", "/admin/cdn");

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