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

class JsController extends Controller {

    private $_fileExtension = ".js";
    private $_folderLocation = "website/assets/js/";

    public function index() {

        $js = new Js();
        $jsFiles = $js->allJsButOrderedOnDate();
        
        $search = get('search');

        if(!empty($search) ) {

            $jsFiles = $js->cssFilesOnSearch($search);
        }
        
        $count = count($jsFiles);

        $jsFiles = Pagination::get($jsFiles, 3);
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

        $file = Js::get($request['id']);
        $code = $this->getFileContent($file['file_name']);

        $data['jsFile'] = $file;
        $data['code'] = $code;

        return $this->view('admin/js/read', $data);
    }

    public function store($request) {

        if(submitted('submit') && Csrf::validate(Csrf::token('get'), post('token') ) === true) {

            $rules = new Rules();

            $uniqueFilename = DB::try()->select('file_name')->from('js')->where('file_name', '=', $request['filename'])->and('id', '!=', $request['id'])->fetch();

            if($rules->js($uniqueFilename)->validated()) {
                    
                $filename = "/".post('filename');
                $filename = str_replace(" ", "-", $filename);

                $code = post('code');
            
                $file = fopen("website/assets/js/" . $filename . ".js", "w");
                fwrite($file, $code);
                fclose($file);
                
                Js::insert([

                    'file_name' => $request['filename'],
                    'extension' => '.js',
                    'date_created_at'   => date('d/m/Y'),
                    'time_created_at'   => date('H:i'),
                    'date_updated_at'   => date('d/m/Y'),
                    'time_updated_at'   => date('H:i')
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

        $file = Js::where('id', '=', $request['id'])[0];
        $code = $this->getFileContent($file['file_name']);

        $assingedPages = DB::try()->select('id, title')->from('pages')->join('js_page')->on('pages.id', '=', 'js_page.page_id')->where('js_page.js_id', '=', $request['id'])->fetch();
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

            $pages = DB::try()->select('id, title')->from('pages')->whereNotIn('id', $listAssingedPageIdString)->fetch();
        } else {
            $pages = DB::try()->select('id, title')->from('pages')->fetch();
        }

        return $pages;
    }

    public function update($request) {

        if(!empty($request['updatePage']) && $request['updatePage'] !== null) {

            return $this->updatePage($request);
        } else if(!empty($request['removePage']) && $request['removePage'] !== null) {

            return $this->removePage($request);
        } else if(!empty($request['includeAll']) && $request['includeAll'] !== null) {

            return $this->includeAll($request);
        } else if(!empty($request['removeAll']) && $request['removeAll'] !== null) {

            return $this->removeAll($request);
        } else if(!empty($request['submit']) && $request['submit'] !== null) {

            return $this->updateJs($request);
        } else {
            return;
        }
    }

    public function updateJs($request) {

        if(submitted('submit') && Csrf::validate(Csrf::token('get'), post('token'))) {
                
            $id = $request['id'];
            $filename = str_replace(" ", "-", $request["filename"]);
            $currentJsFileName = Js::where('id', '=', $id)[0]['file_name'];

            $rules = new Rules();

            $uniqueFilename = DB::try()->select('file_name')->from('js')->where('file_name', '=', $request['filename'])->and('id', '!=', $request['id'])->fetch();

            if($rules->Js($uniqueFilename)->validated()) {

                rename($this->_folderLocation . $currentJsFileName . $this->_fileExtension, $this->_folderLocation . $filename . $this->_fileExtension);

                Js::update(['id' => $id], [

                    'file_name'     => $filename,
                    'date_updated_at'   => date("d/m/Y"),
                    'time_updated_at'   => date("H:i")
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

    public function updatePage($request) {

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

    public function removePage($request) {

        $id = $request['id'];
        $pageIds = $request['pages'];

        if(!empty($pageIds) && $pageIds !== null) {

            foreach($pageIds as $pageId) {

                JsPage::delete('page_id', $pageId);
            }
        }

        redirect("/admin/js/$id/edit");
    }

    public function includeAll($request) {

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

    public function removeAll($request) {

        $id = $request['id'];

        JsPage::delete('js_id', $id);
        redirect("/admin/js/$id/edit");
    }

    public function delete($request) {

        $filename = Js::where('id', '=', $request['id'])[0]['file_name'];
        $path = "website/assets/js/" . $filename . ".js";
        unlink($path);

        Js::delete('id', $request['id']);

        redirect("/admin/js");
    }

}