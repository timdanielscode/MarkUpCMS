<?php

namespace app\controllers\admin;

use app\models\Js;
use app\models\JsPage;
use app\models\Page;
use validation\Rules;
use core\Session;
use extensions\Pagination;
use core\http\Response;
use validation\Get;

class JsController extends \app\controllers\Controller {

    private $_data;
    private $_fileExtension = ".js";
    private $_folderLocation = "website/assets/js/";

    /**
     * To show 404 page with 404 status code (on not existing js)
     * 
     * @param string $id _POST js id
     * @return object JsController
     */ 
    private function ifExists($id) {

        if(empty(Js::ifRowExists($id)) ) {

            return Response::statusCode(404)->view("/404/404")->data() . exit();
        }
    }

    /**
     * To show the js index view
     * 
     * @param array $request _GET search, page
     * @return object JsController, Controller
     */
    public function index($request) {

        $js = Js::allJsButOrderedOnDate();

        $this->_data['search'] = '';

        if(!empty($request['search'] ) ) {

            $this->_data['search'] = Get::validate($request['search']);
            $js = Js::jsFilesOnSearch($this->_data['search']);
        }

        $this->_data['jsFiles'] = Pagination::get($request, $js, 10);
        $this->_data['count'] = count($js);
        $this->_data['numberOfPages'] = Pagination::getPageNumbers();

        return $this->view('admin/js/index')->data($this->_data);
    }

    /**
     * To show the js create view
     * 
     * @return object JsController, Controller
     */
    public function create() {

        $this->_data['rules'] = [];
        return $this->view('admin/js/create')->data($this->_data);
    }

    /**
     * To show the js read view
     * 
     * @param array $request id (js id)
     * @return object JsController, Controller
     */
    public function read($request) {

        $this->ifExists($request['id']);

        $this->_data['jsFile'] = Js::get($request['id']);
        $this->_data['code'] = $this->getFileContent(Js::get($request['id'])['file_name']);

        return $this->view('admin/js/read')->data($this->_data);
    }

    /**
     * To store new js data and to create a new js file (on successful validaton)
     * 
     * @param array $request _POST filename, code
     * @return object JsController, Controller (on failed validation)
     */
    public function store($request) {

        $rules = new Rules();

        if($rules->js($request, Js::whereColumns(['file_name'], ['file_name' => $request['filename']]))->validated()) {
                    
            $filename = "/".$request['filename'];
            $filename = str_replace(" ", "-", $filename);

            $file = fopen("website/assets/js/" . $filename . ".js", "w");
            fwrite($file, htmlspecialchars_decode($request['code']));
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

            $this->_data['filename'] = $request['filename'];
            $this->_data['code'] = $request['code'];
            $this->_data['rules'] = $rules->errors;

            return $this->view('admin/js/create')->data($this->_data);
        }
    }

    /**
     * To get contents of js file
     * 
     * @param string $filename js filename
     * @return string js file content
     */
    private function getFileContent($filename) {

        if(!empty($filename) && $filename !== null) {

            $filePath = $this->_folderLocation . $filename . $this->_fileExtension;

            if(file_exists($filePath) ) {
    
                $content = file_get_contents($filePath);
                return $content;
            } 
        }
    }

    /**
     * To show the js edit view
     * 
     * @param array $request id (page id)
     * @return object JsController, Controller
     */
    public function edit($request) {

        $this->ifExists($request['id']);

        $this->_data['data'] = Js::get($request['id']);
        $this->_data['data']['pages'] = Js::getNotPageAssingedIdTitle(Js::getPageAssignedIdTitle($request['id']));
        $this->_data['data']['assingedPages'] = Js::getPageAssignedIdTitle($request['id']); 
        $this->_data['data']['code'] = $this->getFileContent(Js::get($request['id'])['file_name']);
        $this->_data['rules'] = [];

        return $this->view('admin/js/edit')->data($this->_data);
    }

    /**
     * To update js data and to update the js file (on successful validation)
     * 
     * @param array $request id (js id), _POST filename, code
     * @return object JsController, Controller (on failed validation)
     */
    public function update($request) {

        $id = $request['id'];
        $this->ifExists($id);
                
        $filename = str_replace(" ", "-", $request["filename"]);
        $currentJsFileName = Js::getColumns(['file_name'], $id);

        $rules = new Rules();

        if($rules->Js($request, Js::checkUniqueFilenameId($request['filename'], $id))->validated()) {

            rename($this->_folderLocation . $currentJsFileName['file_name'] . $this->_fileExtension, $this->_folderLocation . $filename . $this->_fileExtension);

            if(!empty($request['code']) ) { $hasContent = 1; } else { $hasContent = 0; }

            Js::update(['id' => $id], [

                'file_name'     => $filename,
                'has_content' => $hasContent,
                'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
            ]);
	
            $file = fopen("website/assets/js/" . $filename . $this->_fileExtension, "w");
            fwrite($file, htmlspecialchars_decode($request["code"]));
            fclose($file);

            Session::set('success', 'You have successfully updated the js file!');
            redirect("/admin/js/$id/edit");
                    
        } else {
                
            $this->_data['data'] = Js::get($id);
            $this->_data['data']['assingedPages'] = Js::getPageAssignedIdTitle($request['id']); 
            $this->_data['data']['pages'] = Js::getNotPageAssingedIdTitle(Js::getPageAssignedIdTitle($request['id']));
            $this->_data['data']['code'] = file_get_contents($this->_folderLocation . $currentJsFileName . $this->_fileExtension);
            $this->_data['rules'] = $rules->errors;
                
            return $this->view("/admin/js/edit")->data($this->_data);
        }
    }

    /**
     * To include a js file on page(s)
     * 
     * @param array $request id (js id), _POST pages
     */
    public function includePages($request) {

        $id = $request['id'];
        $this->ifExists($id);
            
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

    /**
     * To exclude a js file on page(s)
     * 
     * @param array $request id (js id), _POST pages
     */
    public function removePages($request) {

        $id = $request['id'];
        $this->ifExists($request['id']);

        if(!empty($request['pages']) && $request['pages'] !== null) {

            foreach($request['pages'] as $pageId) {

                Page::deleteJs($pageId, $request['id']);
            }
        }

        Session::set('success', 'You have successfully removed the js file the page(s)!');
        redirect("/admin/js/$id/edit");
    }

    /**
     * To include a js file on all pages
     * 
     * @param array $request id (js id)
     */
    public function includeAll($request) {

        $id = $request['id'];
        $this->ifExists($request['id']);

        JsPage::delete('js_id', $id);

        if(!empty(Page::getAll(['id'])) && Page::getAll(['id']) !== null) {

            foreach(Page::getAll(['id']) as $pageId) {

                JsPage::insert([

                    'page_id' => $pageId['id'],
                    'js_id' => $id
                ]);
            }
        }

        Session::set('success', 'You have successfully included the js file on all pages!');
        redirect("/admin/js/$id/edit");
    }

    /**
     * To exclude a js file on all pages
     * 
     * @param array $request _POST id (js id)
     */
    public function removeAll($request) {

        $id = $request['id'];
        $this->ifExists($request['id']);

        JsPage::delete('js_id', $id);

        Session::set('success', 'You have successfully removed the js file on all pages!');
        redirect("/admin/js/$id/edit");
    }

    /**
     * To remove js file(s) from thrashcan
     * 
     * @param array $request _POST recoverIds (js recoverIds)
     */
    public function recover($request) {

        $recoverIds = explode(',', $request['recoverIds']);
            
        foreach($recoverIds as $id ) {

            $this->ifExists($id);

            Js::update(['id' => $id], [

                'removed'  => 0
            ]);
        }
        
        Session::set('success', 'You have successfully recovered the js file(s)!');
        redirect("/admin/js");
    }

    /**
     * To remove js file(s) permanently or move to thrashcan
     * 
     * @param array $request _POST deleteIds (js deleteIds)
     */
    public function delete($request) {

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