<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use app\models\Js;
use app\models\JsPage;
use app\models\Post;
use database\DB;
use core\Csrf;
use validation\Rules;
use core\Session;
use extensions\Pagination;
use core\http\Response;
use validation\Get;

class JsController extends Controller {

    private $_fileExtension = ".js";
    private $_folderLocation = "website/assets/js/";

    private function ifExists($id) {

        if(empty(Js::ifRowExists($id)) ) {

            return Response::statusCode(404)->view("/404/404") . exit();
        }
    }

    private function redirect($inputName, $path) {

        if(submitted($inputName) === false || Csrf::validate(Csrf::token('get'), post('token')) === false ) { 
            
            redirect($path) . exit(); 
        } 
    }

    public function index() {
        
        $data['jsFiles'] = $this->getJs(Get::validate([get('search')]));
        $data['count'] = $this->_count;
        $data['numberOfPages'] = Pagination::getPageNumbers();

        return $this->view('admin/js/index', $data);
    }

    private function getJs($search) {

        $js = Js::allJsButOrderedOnDate();
    
        if(!empty($search)) {
    
            $js = Js::jsFilesOnSearch($search);
        }
    
        $this->_count = count($js);
        return Pagination::get($js, 10);
    }

    public function create() {

        $data['rules'] = [];
        return $this->view('admin/js/create', $data);
    }

    public function read($request) {

        $this->ifExists($request['id']);

        $file = Js::get($request['id']);
        $code = $this->getFileContent($file['file_name']);

        $data['jsFile'] = $file;
        $data['code'] = $code;

        return $this->view('admin/js/read', $data);
    }

    public function store($request) {

        $this->redirect("submit", '/admin/js');

        $rules = new Rules();

        if($rules->js(Js::whereColumns(['file_name'], ['file_name' => $request['filename']]))->validated()) {
                    
            $filename = "/".$request['filename'];
            $filename = str_replace(" ", "-", $filename);

            $file = fopen("website/assets/js/" . $filename . ".js", "w");
            fwrite($file, $request['code']);
            fclose($file);

            if(!empty($request['code']) ) { $hasContent = 1; } else { $hasContent = 0; }
                
            Js::insert([

                'file_name' => $request['filename'],
                'extension' => '.js',
                'author'    => Session::get('username'),
                'has_content' => $hasContent,
                'removed'   => 0,
                'created_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
                'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
            ]);

            Session::set('success', 'You have successfully created a new js file!');          
            redirect('/admin/js');

        } else {

            $data['rules'] = $rules->errors;
            return $this->view('admin/js/create', $data);
        }
    }

    private function getFileContent($filename) {

        if(!empty($filename) && $filename !== null) {

            $filePath = $this->_folderLocation . $filename . $this->_fileExtension;

            if(file_exists($filePath) ) {
    
                $content = file_get_contents($filePath);
                return $content;
            } 
        }
    }

    public function edit($request) {

        $this->ifExists($request['id']);

        $file = Js::get($request['id']);
        if($file['removed'] === 1) { return Response::statusCode(404)->view("/404/404") . exit(); }

        $code = $this->getFileContent($file['file_name']);
        
        $data['data'] = $file;
        $data['data']['pages'] = Js::getNotPostAssingedIdTitle(Js::getPostAssignedIdTitle($request['id']));
        $data['data']['assingedPages'] = Js::getPostAssignedIdTitle($request['id']); 
        $data['data']['code'] = $code;
        $data['rules'] = [];

        return $this->view('admin/js/edit', $data);
    }

    public function update($request) {

        $id = $request['id'];
        $this->ifExists($id);
        $this->redirect("submit", "/admin/js/$id/edit");
                
        $filename = str_replace(" ", "-", $request["filename"]);
        $currentJsFileName = Js::getColumns(['file_name'], $id);

        $rules = new Rules();

        if($rules->Js(Js::checkUniqueFilenameId($request['filename'], $id))->validated()) {

            rename($this->_folderLocation . $currentJsFileName . $this->_fileExtension, $this->_folderLocation . $filename . $this->_fileExtension);

            if(!empty($request['code']) ) { $hasContent = 1; } else { $hasContent = 0; }

            Js::update(['id' => $id], [

                'file_name'     => $filename,
                'has_content' => $hasContent,
                'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
            ]);
	
            $file = fopen("website/assets/js/" . $filename . $this->_fileExtension, "w");
            fwrite($file, $request["code"]);
            fclose($file);

            Session::set('success', 'You have successfully updated the js file!');
            redirect("/admin/js/$id/edit");
                    
        } else {
                
            $filePath = $this->_folderLocation . $currentJsFileName . $this->_fileExtension; 
            $code = file_get_contents($filePath);
                
            $data['data'] = Js::get($id);
            $data['data']['assingedPages'] = Js::getPostAssignedIdTitle($request['id']); 
            $data['data']['pages'] = Js::getNotPostAssingedIdTitle(Js::getPostAssignedIdTitle($request['id']));
            $data['data']['code'] = $code;
            $data['rules'] = $rules->errors;
                
            return $this->view("/admin/js/edit", $data);
        }
    }

    public function includePages($request) {

        $id = $request['id'];
        $this->ifExists($id);
        $this->redirect("submit", "/admin/js/$id/edit");
            
        if(!empty($request['pages']) && $request['pages'] !== null) {

            foreach($request['pages'] as $pageId) {

                JsPage::insert([

                    'page_id' => $pageId,
                    'js_id' => $id
                ]);
            }
        }

        Session::set('success', 'You have successfully included the js file the page(s)!');
        redirect("/admin/js/$id/edit");
    }

    public function removePages($request) {

        $id = $request['id'];
        $this->ifExists($request['id']);
        $this->redirect("submit", "/admin/js/$id/edit");

        if(!empty($request['pages']) && $request['pages'] !== null) {

            foreach($request['pages'] as $pageId) {

                JsPage::delete('page_id', $pageId);
            }
        }

        Session::set('success', 'You have successfully removed the js file the page(s)!');
        redirect("/admin/js/$id/edit");
    }

    public function includeAll($request) {

        $id = $request['id'];
        $this->ifExists($request['id']);
        $this->redirect("submit", "/admin/js/$id/edit");

        JsPage::delete('js_id', $id);

        if(!empty(Post::getAll(['id'])) && Post::getAll(['id']) !== null) {

            foreach(Post::getAll(['id']) as $pageId) {

                JsPage::insert([

                    'page_id' => $pageId['id'],
                    'js_id' => $id
                ]);
            }
        }

        Session::set('success', 'You have successfully included the js file on all pages!');
        redirect("/admin/js/$id/edit");
    }

    public function removeAll($request) {

        $id = $request['id'];
        $this->ifExists($request['id']);
        $this->redirect("submit", "/admin/js/$id/edit");

        JsPage::delete('js_id', $id);

        Session::set('success', 'You have successfully removed the js file on all pages!');
        redirect("/admin/js/$id/edit");
    }

    public function recover($request) {

        $this->redirect("recoverIds", "/admin/js");

        $recoverIds = explode(',', $request['recoverIds']);
            
        foreach($recoverIds as $request['id'] ) {

            $this->ifExists($request['id']);

            Js::update(['id' => $request['id']], [

                'removed'  => 0
            ]);
        }
        
        Session::set('success', 'You have successfully recovered the js file(s)!');
        redirect("/admin/js");
    }

    public function delete($request) {

        $this->redirect("deleteIds", "/admin/js");

        $deleteIds = explode(',', $request['deleteIds']);

        if(!empty($deleteIds) && !empty($deleteIds[0])) {

            foreach($deleteIds as $id) {

                $this->ifExists($id);
            
                if(Js::getColumns(['removed'], $id)['removed'] !== 1) {

                    Js::update(['id' => $id], [

                        'removed'  => 1
                    ]);

                    Session::set('success', 'You have successfully moved the js file(s) to the trashcan!');

                } else if(Js::getColumns(['removed'], $id)['removed'] === 1) {

                    $path = "website/assets/js/" . Js::getColumns(['file_name'], $id)['file_name'] . ".js";
                        
                    unlink($path);

                    Js::delete("id", $id);
                    Session::set('success', 'You have successfully removed the js file(s)!');
                }
            }
        }

        redirect("/admin/js");
    }
}