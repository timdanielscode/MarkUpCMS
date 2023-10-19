<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use app\models\Js;
use app\models\JsPage;
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

        $js = new Js();

        if(empty($js->ifRowExists($id)) ) {

            return Response::statusCode(404)->view("/404/404") . exit();
        }
    }

    public function index() {

        $js = new Js();
        $jsFiles = $js->allJsButOrderedOnDate();
        
        $search = Get::validate([get('search')]);

        if(!empty($search) ) {

            $jsFiles = $js->cssFilesOnSearch($search);
        }
        
        $count = count($jsFiles);

        $jsFiles = Pagination::get($jsFiles, 10);
        $numberOfPages = Pagination::getPageNumbers();

        $data['jsFiles'] = $jsFiles;
        $data['count'] = $count;
        $data['numberOfPages'] = $numberOfPages;

        return $this->view('admin/js/index', $data);
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

        if(submitted('submit') && Csrf::validate(Csrf::token('get'), post('token') ) === true) {

            $rules = new Rules();

            $uniqueFilename = DB::try()->select('file_name')->from('js')->where('file_name', '=', $request['filename'])->fetch();

            if($rules->js($uniqueFilename)->validated()) {
                    
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

                Session::set('create', 'You have successfully created a new post!');            
                redirect('/admin/js');

            } else {

                $data['rules'] = $rules->errors;
                return $this->view('admin/js/create', $data);
            }
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

        $file = Js::where('id', '=', $request['id'])[0];
        if($file['removed'] === 1) { return Response::statusCode(404)->view("/404/404") . exit(); }

        $code = $this->getFileContent($file['file_name']);

        $assingedPages = DB::try()->select('id, title')->from('pages')->join('js_page')->on('pages.id', '=', 'js_page.page_id')->where('js_page.js_id', '=', $request['id'])->and('pages.removed', '!=', 1)->fetch();
        $pages = $this->getPages($assingedPages);

        $data['data'] = $file;
        $data['data']['pages'] = $pages;
        $data['data']['assingedPages'] = $assingedPages;
        $data['data']['code'] = $code;
        $data['rules'] = [];

        return $this->view('admin/js/edit', $data);
    }

    public function getPages($assingedPages) {

        $listAssingedPageIds = [];

        if(!empty($assingedPages) && $assingedPages !== null) {

            foreach($assingedPages as $assingedPage) {

                array_push($listAssingedPageIds, $assingedPage['id']);
            }

            $listAssingedPageIdString = implode(',', $listAssingedPageIds);

            $pages = DB::try()->select('id, title')->from('pages')->whereNotIn('id', $listAssingedPageIdString)->and('pages.removed', '!=', 1)->fetch();
        } else {
            $pages = DB::try()->select('id, title')->from('pages')->where('pages.removed', '!=', 1)->fetch();
        }

        return $pages;
    }

    public function update($request) {

        $this->ifExists($request['id']);

        if(submitted('submit') && Csrf::validate(Csrf::token('get'), post('token'))) {
                
            $id = $request['id'];
            $filename = str_replace(" ", "-", $request["filename"]);
            $currentJsFileName = Js::where('id', '=', $id)[0]['file_name'];

            $rules = new Rules();

            $uniqueFilename = DB::try()->select('file_name')->from('js')->where('file_name', '=', $request['filename'])->and('id', '!=', $request['id'])->fetch();

            if($rules->Js($uniqueFilename)->validated()) {

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

                Session::set('updated', 'User updated successfully!');
                redirect("/admin/js/$id/edit");
                    
            } else {
                
                $filePath = $this->_folderLocation . $currentJsFileName . $this->_fileExtension; 
                $code = file_get_contents($filePath);
                
                $data['data'] = Js::where('id', '=', $id)[0];
                $data['data']['assingedPages'] = DB::try()->select('id, title')->from('pages')->join('js_page')->on('pages.id', '=', 'js_page.page_id')->where('js_page.js_id', '=', $request['id'])->fetch();
                $data['data']['pages'] = $this->getPages($data['data']['assingedPages']);
                $data['data']['code'] = $code;
                $data['rules'] = $rules->errors;
                
                return $this->view("/admin/css/edit", $data);
            }
        }
    }

    public function includePages($request) {

        $this->ifExists($request['id']);

        if(submitted("submit") === true && Csrf::validate(Csrf::token('get'), post('token')) === true ) {

            $id = $request['id'];
            $pageIds = $request['pages'];
            
            if(!empty($pageIds) && $pageIds !== null) {

                foreach($pageIds as $pageId) {

                    JsPage::insert([

                        'page_id' => $pageId,
                        'js_id' => $id
                    ]);
                }
            }

            redirect("/admin/js/$id/edit");
        }
    }

    public function removePages($request) {

        $this->ifExists($request['id']);

        if(submitted("submit") === true && Csrf::validate(Csrf::token('get'), post('token')) === true ) {

            $id = $request['id'];
            $pageIds = $request['pages'];

            if(!empty($pageIds) && $pageIds !== null) {

                foreach($pageIds as $pageId) {

                    JsPage::delete('page_id', $pageId);
                }
            }

            redirect("/admin/js/$id/edit");
        }
    }

    public function includeAll($request) {

        $this->ifExists($request['id']);

        if(submitted("submit") === true && Csrf::validate(Csrf::token('get'), post('token')) === true ) {

            $id = $request['id'];
            $pageIds = DB::try()->select('id')->from('pages')->fetch();

            JsPage::delete('js_id', $id);

            if(!empty($pageIds) && $pageIds !== null) {

                foreach($pageIds as $pageId) {

                    JsPage::insert([

                        'page_id' => $pageId['id'],
                        'js_id' => $id
                    ]);
                }
            }
            redirect("/admin/js/$id/edit");
        }
    }

    public function removeAll($request) {

        $this->ifExists($request['id']);

        if(submitted("submit") === true && Csrf::validate(Csrf::token('get'), post('token')) === true ) {

            $id = $request['id'];

            JsPage::delete('js_id', $id);
            redirect("/admin/js/$id/edit");
        }
    }

    public function recover($request) {

        if(!empty($request['recoverIds']) && $request['recoverIds'] !== null) {

            $recoverIds = explode(',', $request['recoverIds']);
            
            foreach($recoverIds as $request['id'] ) {

                $this->ifExists($request['id']);

                $js = DB::try()->select('removed')->from('js')->where('id', '=', $request['id'])->first();

                Js::update(['id' => $request['id']], [

                    'removed'  => 0
                ]);
            }
        }

        redirect("/admin/js");
    }

    public function delete($request) {

        $deleteIds = explode(',', $request['deleteIds']);

        if(!empty($deleteIds) && !empty($deleteIds[0])) {

            foreach($deleteIds as $request['id']) {

                $this->ifExists($request['id']);

                $js = DB::try()->select('removed')->from('js')->where('id', '=', $request['id'])->first();

                if($js['removed'] !== 1) {

                    Js::update(['id' => $request['id']], [

                        'removed'  => 1
                    ]);

                } else if($js['removed'] === 1) {

                    $filename = Js::where('id', '=', $request['id'])[0]['file_name'];
                    $path = "website/assets/js/" . $filename . ".js";
                    
                    unlink($path);

                    Js::delete("id", $request['id']);
                }
            }
        }

        redirect("/admin/js");
    }
}