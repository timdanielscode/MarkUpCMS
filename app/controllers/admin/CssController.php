<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use app\models\Css;
use app\models\CssPage;
use app\models\Post;
use core\Csrf;
use validation\Rules;
use core\Session;
use extensions\Pagination;
use core\http\Response;
use validation\Get;

class CssController extends Controller {

    private $_data;
    private $_fileExtension = ".css";
    private $_folderLocation = "website/assets/css/";

    private function ifExists($id) {

        if(empty(Css::ifRowExists($id)) ) {

            return Response::statusCode(404)->view("/404/404") . exit();
        }
    }

    private function redirect($inputName, $path) {

        if(submitted($inputName) === false || Csrf::validate(Csrf::token('get'), post('token')) === false ) { 
            
            redirect($path) . exit(); 
        } 
    }

    public function index($request) {

        $css = Css::allCssButOrderedOnDate();

        $this->_data['search'] = '';

        if(!empty($request['search'] ) ) {

            $this->_data['search'] = Get::validate($request['search']);
            $css = Css::cssFilesOnSearch($this->_data['search']);
        }

        $this->_data['cssFiles'] = Pagination::get($css, 10);
        $this->_data['count'] = count($css);
        $this->_data['numberOfPages'] = Pagination::getPageNumbers();

        return $this->view('admin/css/index')->data($this->_data);
    }

    public function create() {

        $this->_data['rules'] = [];
        return $this->view('admin/css/create')->data($this->_data);
    }

    public function store($request) {

        ///$this->redirect("submit", '/admin/css');

        $rules = new Rules();
        
        if($rules->css($request['filename'], Css::whereColumns(['file_name'], ['file_name' => $request['filename']]))->validated()) {
                    
            $filename = "/".$request['filename'];
            $filename = str_replace(" ", "-", $filename);
            $code = $request['code'];
            $file = fopen("website/assets/css" . $filename . ".css", "w");

            fwrite($file, $code);
            fclose($file);
                    
            if(!empty($request['code']) ) { $hasContent = 1; } else { $hasContent = 0; }

            Css::insert([

                'file_name' => $request['filename'],
                'extension' => '.css',
                'author'    => Session::get('username'),
                'has_content' => $hasContent,
                'removed' => 0,
                'created_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
                'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
            ]);

            Session::set('success', 'You have successfully created a new css file!');         
            redirect('/admin/css');

        } else {

            $this->_data['rules'] = $rules->errors;
            $this->_data['filename'] = $request['filename'];

            return $this->view('admin/css/create')->data($this->_data);
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

    public function read($request) {

        $this->ifExists($request['id']);

        $this->_data['file'] = Css::get($request['id']);
        $this->_data['code'] = $this->getFileContent(Css::get($request['id'])['file_name']);

        return $this->view('admin/css/read')->data($this->_data);
    }

    public function edit($request) {

        $this->ifExists($request['id']);

        //if($cssFile['removed'] === 1) { return Response::statusCode(404)->view("/404/404") . exit(); }

        $this->_data['data'] = Css::get($request['id']);
        $this->_data['data']['code'] = $this->getFileContent(Css::get($request['id'])['file_name']);
        $this->_data['data']['pages'] = Css::getNotPostAssingedIdTitle(Css::getPostAssignedIdTitle($request['id']));
        $this->_data['data']['assingedPages'] = Css::getPostAssignedIdTitle($request['id']);
        $this->_data['rules'] = [];

        return $this->view('admin/css/edit')->data($this->_data);
    }

    public function update($request) {

        $id = $request['id'];
        $this->ifExists($id);
        ///$this->redirect("submit", "/admin/css/$id/edit");

        $filename = str_replace(" ", "-", $request["filename"]);
        $currentCssFileName = Css::getColumns(['file_name'], $id)['file_name'];

        $rules = new Rules();
        
        if($rules->css($request['filename'], Css::checkUniqueFilenameId($request['filename'], $id))->validated()) {

            rename($this->_folderLocation . $currentCssFileName . $this->_fileExtension, $this->_folderLocation . $filename . $this->_fileExtension);

            if(!empty($request['code']) ) { $hasContent = 1; } else { $hasContent = 0; }

            Css::update(['id' => $id], [

                'file_name'     => $filename,
                'has_content' => $hasContent,
                'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
            ]);
	
            $file = fopen("website/assets/css/" . $filename . $this->_fileExtension, "w");
            fwrite($file, $request["code"]);
            fclose($file);

            Session::set('success', 'You have successfully updated the css file!');
            redirect("/admin/css/$id/edit");
                    
        } else {
                
            $filePath = $this->_folderLocation . $currentCssFileName . $this->_fileExtension; 
            $code = file_get_contents($filePath);

            $this->_data['data'] = Css::get($id);
            $this->_data['data']['assingedPages'] = Css::getPostAssignedIdTitle($request['id']);
            $this->_data['data']['pages'] = Css::getNotPostAssingedIdTitle(Css::getPostAssignedIdTitle($request['id']));         
            $this->_data['data']['code'] = $code;
            $this->_data['rules'] = $rules->errors;
                
            return $this->view("/admin/css/edit")->data($this->_data);
        }
    }

    public function linkAll($request) {

        $id = $request['id'];
        $this->ifExists($request['id']);
        //$this->redirect("submit", "/admin/css/$id/edit");

        CssPage::delete('css_id', $id);

        if(!empty(Post::getAll(['id'])) && Post::getAll(['id']) !== null) {

            foreach(Post::getAll(['id']) as $pageId) {

                CssPage::insert([

                    'page_id' => $pageId['id'],
                    'css_id' => $id
                ]);
            }
        }

        Session::set('success', 'You have successfully linked the css file on all pages!');
        redirect("/admin/css/$id/edit");
    }

    public function unlinkAll($request) {

        $id = $request['id'];
        $this->ifExists($id);
        //$this->redirect("submit", "/admin/css/$id/edit");

        CssPage::delete('css_id', $id);

        Session::set('success', 'You have successfully removed the css file on all pages!');
        redirect("/admin/css/$id/edit");
    }

    public function unlinkPages($request) {

        $id = $request['id'];
        $this->ifExists($id);
        //$this->redirect("submit", "/admin/css/$id/edit");

        if(!empty($request['pages']) && $request['pages'] !== null) {

            foreach($request['pages'] as $pageId) {

                CssPage::delete('page_id', $pageId);
            }
        }

        Session::set('success', 'You have successfully removed the css file on the page(s)!');
        redirect("/admin/css/$id/edit");
    }

    public function linkPages($request) {

        $id = $request['id'];
        $this->ifExists($id);
        //$this->redirect("submit", "/admin/css/$id/edit");

        if(!empty($request['pages']) && $request['pages'] !== null) {

            foreach($request['pages'] as $pageId) {

                CssPage::insert([

                    'page_id' => $pageId,
                    'css_id' => $id
                ]);
            }
        }

        Session::set('success', 'You have successfully linked the css file on the page(s)!');
        redirect("/admin/css/$id/edit");
    }

    public function recover($request) {

        //$this->redirect("recoverIds", "/admin/css");

        $recoverIds = explode(',', $request['recoverIds']);
            
        foreach($recoverIds as $request['id'] ) {

            $this->ifExists($request['id']);

            Css::update(['id' => $request['id']], [

                'removed'  => 0
            ]);
        }

        Session::set('success', 'You have successfully recovered the css file(s)!');
        redirect("/admin/css");
    }

    public function delete($request) {

        //$this->redirect("deleteIds", "/admin/css");
        $deleteIds = explode(',', $request['deleteIds']);

        if(!empty($deleteIds) && !empty($deleteIds[0])) {

            foreach($deleteIds as $id) {

                $this->ifExists($id);
                
                if(Css::getColumns(['removed'], $id)['removed'] !== 1) {

                    Css::update(['id' => $id], [

                        'removed'  => 1
                    ]);

                    Session::set('success', 'You have successfully moved the css file(s) to the trashcan!');

                } else if(Css::getColumns(['removed'], $id)['removed'] === 1) {

                    $filename = $filename = Css::getColumns(['file_name'], $id);
                    $path = "website/assets/css/" . $filename['file_name'] . ".css";
            
                    unlink($path);
                    Css::delete("id", $id);
                    Session::set('success', 'You have successfully removed the css file(s)!');
                }
            }
        }

        redirect("/admin/css");
    }
}