<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use app\models\Css;
use app\models\CssPage;
use app\models\Post;
use database\DB;
use core\Csrf;
use validation\Rules;
use core\Session;
use extensions\Pagination;
use core\http\Response;
use validation\Get;

class CssController extends Controller {

    private $_fileExtension = ".css";
    private $_folderLocation = "website/assets/css/";

    private function ifExists($id) {

        $css = new Css();

        if(empty($css->ifRowExists($id)) ) {

            return Response::statusCode(404)->view("/404/404") . exit();
        }
    }

    private function redirect($inputName, $path) {

        if(submitted($inputName) === false || Csrf::validate(Csrf::token('get'), post('token')) === false ) { 
            
            redirect($path) . exit(); 
        } 
    }

    public function index() {

        $css = new Css();
        $cssFiles = $css->allCssButOrderedOnDate();

        $search = Get::validate([get('search')]);

        if(!empty($search) ) {

            $cssFiles = $css->cssFilesOnSearch($search);
        }

        $count = count($cssFiles);

        $cssFiles = Pagination::get($cssFiles, 10);
        $numberOfPages = Pagination::getPageNumbers();

        $data['count'] = $count;
        $data['cssFiles'] = $cssFiles;
        $data['numberOfPages'] = $numberOfPages;

        return $this->view('admin/css/index', $data);
    }

    public function create() {

        $data['rules'] = [];
        return $this->view('admin/css/create', $data);
    }

    public function store($request) {

        $this->redirect("submit", '/admin/css');

        $rules = new Rules();
        $css = new Css();
        
        if($rules->css($css->checkUniqueFilename($request['filename']))->validated()) {
                    
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

            $data['rules'] = $rules->errors;
            return $this->view('admin/css/create', $data);
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

        $file = Css::get($request['id']);
        $code = $this->getFileContent($file['file_name']);

        $data['file'] = $file;
        $data['code'] = $code;

        return $this->view('admin/css/read', $data);
    }

    public function edit($request) {

        $this->ifExists($request['id']);

        $cssFile = Css::where('id', '=', $request['id'])[0];
        if($cssFile['removed'] === 1) { return Response::statusCode(404)->view("/404/404") . exit(); }

        $code = $this->getFileContent($cssFile['file_name']);

        $css = new Css();
        $assingedPages = $css->getPostAssignedIdTitle($request['id']);
        $notAssignedPages = $css->getNotPostAssingedIdTitle($css->getPostAssignedIdTitle($request['id']));

        $data['data'] = $cssFile;
        $data['data']['code'] = $code;
        $data['data']['pages'] = $notAssignedPages;
        $data['data']['assingedPages'] = $assingedPages;
        
        $data['rules'] = [];

        return $this->view('admin/css/edit', $data);
    }

    public function update($request) {

        $id = $request['id'];
        $this->ifExists($id);
        $this->redirect("submit", "/admin/css/$id/edit");

        $filename = str_replace(" ", "-", $request["filename"]);
        $currentCssFileName = Css::where('id', '=', $id)[0]['file_name'];

        $rules = new Rules();
        $css = new Css();
        
        if($rules->css($css->checkUniqueFilenameId($request['filename'], $id))->validated()) {

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

            $data['data'] = Css::where('id', '=', $id)[0];
            $data['data']['assingedPages'] = $css->getPostAssignedIdTitle($request['id']);
            $data['data']['pages'] = $css->getNotPostAssingedIdTitle($css->getPostAssignedIdTitle($request['id']));         
            $data['data']['code'] = $code;
            $data['rules'] = $rules->errors;
                
            return $this->view("/admin/css/edit", $data);
        }
    }

    public function linkAll($request) {

        $id = $request['id'];
        $this->ifExists($request['id']);
        $this->redirect("submit", "/admin/css/$id/edit");

        $post = new Post();
        CssPage::delete('css_id', $id);

        if(!empty($post->getAll(['id'])) && $post->getAll(['id']) !== null) {

            foreach($post->getAll(['id']) as $pageId) {

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
        $this->redirect("submit", "/admin/css/$id/edit");

        CssPage::delete('css_id', $id);

        Session::set('success', 'You have successfully removed the css file on all pages!');
        redirect("/admin/css/$id/edit");
    }

    public function unlinkPages($request) {

        $id = $request['id'];
        $this->ifExists($id);
        $this->redirect("submit", "/admin/css/$id/edit");

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
        $this->redirect("submit", "/admin/css/$id/edit");

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

        $this->redirect("recoverIds", "/admin/css");

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

        $this->redirect("deleteIds", "/admin/css");
        $deleteIds = explode(',', $request['deleteIds']);

        if(!empty($deleteIds) && !empty($deleteIds[0])) {

            foreach($deleteIds as $id) {

                $this->ifExists($id);
                $css = new Css();
                
                if($css->getData($id, ['removed'])['removed']!== 1) {

                    Css::update(['id' => $id], [

                        'removed'  => 1
                    ]);

                    Session::set('success', 'You have successfully moved the css file(s) to the trashcan!');

                } else if($css->getData($id, ['removed'])['removed'] === 1) {

                    $filename = Css::where('id', '=', $id)[0]['file_name'];
                    $path = "website/assets/css/" . $filename . ".css";
            
                    unlink($path);
                    Css::delete("id", $id);
                    Session::set('success', 'You have successfully removed the css file(s)!');
                }
            }
        }

        redirect("/admin/css");
    }
}