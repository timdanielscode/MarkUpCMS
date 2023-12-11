<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use app\models\Css;
use app\models\CssPage;
use app\models\Post;
use validation\Rules;
use core\Session;
use extensions\Pagination;
use core\http\Response;
use validation\Get;

class CssController extends Controller {

    private $_data;
    private $_fileExtension = ".css";
    private $_folderLocation = "website/assets/css/";

    /**
     * To show 404 page with 404 status code (on not existing css)
     * 
     * @param string $id css id
     * @return object CssController
     */ 
    private function ifExists($id) {

        if(empty(Css::ifRowExists($id)) ) {

            return Response::statusCode(404)->view("/404/404")->data() . exit();
        }
    }

    /**
     * To show the css index view
     * 
     * @param array $request _GET (search)
     * @return object CssController, Controller
     */
    public function index($request) {

        $css = Css::allCssButOrderedOnDate();

        $this->_data['search'] = '';

        if(!empty($request['search'] ) ) {

            $this->_data['search'] = Get::validate($request['search']);
            $css = Css::cssFilesOnSearch($this->_data['search']);
        }

        $this->_data['cssFiles'] = Pagination::get($request, $css, 10);
        $this->_data['count'] = count($css);
        $this->_data['numberOfPages'] = Pagination::getPageNumbers();

        return $this->view('admin/css/index')->data($this->_data);
    }

    /**
     * To show the css create view
     * 
     * @return object CssController, Controller
     */
    public function create() {

        $this->_data['rules'] = [];
        return $this->view('admin/css/create')->data($this->_data);
    }

    /**
     * To store new css data and to create a new css file (on successful validation)
     * 
     * @param array $request _POST filename, code 
     * @return object CssController, Controller (on failed validation)
     */
    public function store($request) {

        $rules = new Rules();
        
        if($rules->css($request['filename'], Css::whereColumns(['file_name'], ['file_name' => $request['filename']]))->validated()) {
                    
            $filename = "/".$request['filename'];
            $filename = str_replace(" ", "-", $filename);
            $code = $request['code'];
            $file = fopen("website/assets/css" . $filename . ".css", "w");

            fwrite($file, htmlspecialchars_decode($code));
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

            $this->_data['filename'] = $request['filename'];
            $this->_data['code'] = $request['code'];
            $this->_data['rules'] = $rules->errors;

            return $this->view('admin/css/create')->data($this->_data);
        }
    }

    /**
     * To get contents of css file
     * 
     * @param string $filename css filename
     * @return string css file content
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
     * To show the css read view
     * 
     * @param array $request id (css id)
     * @return object CssController, Controller
     */
    public function read($request) {

        $this->ifExists($request['id']);

        $this->_data['file'] = Css::get($request['id']);
        $this->_data['code'] = $this->getFileContent(Css::get($request['id'])['file_name']);

        return $this->view('admin/css/read')->data($this->_data);
    }

    /**
     * To show the css edit view
     * 
     * @param array $request id (css id)
     * @return object CssController, Controller
     */
    public function edit($request) {

        $this->ifExists($request['id']);

        $this->_data['data'] = Css::get($request['id']);
        $this->_data['data']['code'] = $this->getFileContent(Css::get($request['id'])['file_name']);
        $this->_data['data']['pages'] = Css::getNotPostAssingedIdTitle(Css::getPostAssignedIdTitle($request['id']));
        $this->_data['data']['assingedPages'] = Css::getPostAssignedIdTitle($request['id']);
        $this->_data['rules'] = [];

        return $this->view('admin/css/edit')->data($this->_data);
    }

    /**
     * To update css data and to update the css file (on successful validation)
     * 
     * @param array $request id (css id), _POST filename, code
     * @return object CssController, Controller (on failed validation)
     */
    public function update($request) {

        $id = $request['id'];
        $this->ifExists($id);

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
            fwrite($file, htmlspecialchars_decode($request["code"]));
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

    /**
     * To link a css file on all pages
     * 
     * @param array $request id (css id)
     */
    public function linkAll($request) {

        $id = $request['id'];
        $this->ifExists($request['id']);

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

    /**
     * To unlink a css file on all pages
     * 
     * @param array $request id (css id)
     */
    public function unlinkAll($request) {

        $id = $request['id'];
        $this->ifExists($id);

        CssPage::delete('css_id', $id);

        Session::set('success', 'You have successfully removed the css file on all pages!');
        redirect("/admin/css/$id/edit");
    }

    /**
     * To unlink a css file on page(s)
     * 
     * @param array $request id (css id) _POST pages
     */
    public function unlinkPages($request) {

        $id = $request['id'];
        $this->ifExists($id);

        if(!empty($request['pages']) && $request['pages'] !== null) {

            foreach($request['pages'] as $pageId) {

                CssPage::delete('page_id', $pageId);
            }
        }

        Session::set('success', 'You have successfully removed the css file on the page(s)!');
        redirect("/admin/css/$id/edit");
    }

    /**
     * To link a css file on page(s)
     * 
     * @param array $request id (css id), _POST pages
     */
    public function linkPages($request) {

        $id = $request['id'];
        $this->ifExists($id);

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

    /**
     * To remove css file(s) from thrashcan
     * 
     * @param array $request _POST recoverIds (css recoverIds)
     */
    public function recover($request) {

        $recoverIds = explode(',', $request['recoverIds']);
            
        foreach($recoverIds as $id) {

            $this->ifExists($id);

            Css::update(['id' => $id], [

                'removed'  => 0
            ]);
        }

        Session::set('success', 'You have successfully recovered the css file(s)!');
        redirect("/admin/css");
    }

    /**
     * To remove css file(s) permanently or move to thrashcan
     * 
     * @param array $request _POST deleteIds (css deleteIds)
     */
    public function delete($request) {

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