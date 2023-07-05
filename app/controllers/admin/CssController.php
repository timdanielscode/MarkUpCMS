<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use app\models\Css;
use database\DB;
use core\Csrf;
use validation\Rules;
use core\Session;
use extensions\Pagination;

class CssController extends Controller {

    private $_fileExtension = ".css";
    private $_folderLocation = "website/assets/css/";

    public function index() {

        $css = new Css();
        $cssFiles = $css->allCssButOrderedOnDate();

        if(empty($cssFiles) ) {

            $cssFiles = array(["id" => "?","file_name" => "no css file created yet", "extension" => "","date_created_at" => "-", "time_created_at" => "", "date_updated_at" => "-", "time_updated_at" => ""]);
        }
        $count = count($cssFiles);
        $search = get('search');

        if(!empty($search) ) {

            $cssFiles = $css->cssFilesOnSearch($search);

            if(empty($cssFiles) ) {
                $cssFiles = array(["id" => "?","file_name" => "not found", "date_created_at" => "-", "time_created_at" => "", "date_updated_at" => "-", "time_updated_at" => ""]);
            }
        }

        $cssFiles = Pagination::get($cssFiles, 20);
        $numberOfPages = Pagination::getPageNumbers();

        $data['cssFiles'] = $cssFiles;
        $data['numberOfPages'] = $numberOfPages;
        $data['count'] = $count;

        return $this->view('admin/css/index', $data);
    }

    public function create() {

        $data['rules'] = [];
        return $this->view('admin/css/create', $data);
    }

    public function store($request) {

        if(submitted('submit')) {

            if(Csrf::validate(Csrf::token('get'), post('token') ) === true) {

                $rules = new Rules();

                if($rules->css()->validated()) {
                    
                    $filename = "/".$request['filename'];
                    $filename = str_replace(" ", "-", $filename);

                    $code = $request['code'];

                    $file = fopen("website/assets/css" . $filename . ".css", "w");

                    fwrite($file, $code);
                    fclose($file);
                    
                    Css::insert([

                        'file_name' => $request['filename'],
                        'extension' => '.css',
                        'date_created_at'   => date('d/m/Y'),
                        'time_created_at'   => date('H:i'),
                        'date_updated_at'   => date('d/m/Y'),
                        'time_updated_at'   => date('H:i')
                    ]);

                    Session::set('create', 'You have successfully created a new post!');            
                    redirect('/admin/css');

                } else {

                    $data['rules'] = $rules->errors;
                    return $this->view('admin/css/create', $data);
                }
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

    public function read($request) {

        $file = Css::get($request['id']);
        $code = $this->getFileContent($file['file_name']);

        $data['file'] = $file;
        $data['code'] = $code;

        return $this->view('admin/css/read', $data);
    }

    public function edit($request) {

        $cssFile = Css::where('id', '=', $request['id']);
        $code = $this->getFileContent($cssFile['file_name']);

        $data['cssFile'] = $cssFile;
        $data['code'] = $code;
        
        $data['rules'] = [];

        return $this->view('admin/css/edit', $data);
    }

    public function update($request) {

        if(submitted('submit') && Csrf::validate(Csrf::token('get'), post('token'))) {
                
            $id = $request['id'];
            $filename = str_replace(" ", "-", $request["filename"]);
            $currentCssFileName = Css::where('id', '=', $id)['file_name'];

            $rules = new Rules();

            if($rules->css()->validated()) {

                rename($this->_folderLocation . $currentCssFileName . $this->_fileExtension, $this->_folderLocation . $filename . $this->_fileExtension);

                Css::update(['id' => $id], [

                    'file_name'     => $filename,
                    'date_updated_at'   => date("d/m/Y"),
                    'time_updated_at'   => date("H:i")
                ]);
	
                $file = fopen("website/assets/css/" . $filename . $this->_fileExtension, "w");
                fwrite($file, $request["code"]);
                fclose($file);

                Session::set('updated', 'User updated successfully!');
                redirect("/admin/css/$id/edit");
                    
            } else {
                
                $filePath = $this->_folderLocation . $currentCssFileName . $this->_fileExtension; 
                $code = file_get_contents($filePath);

                $data['code'] = $code;
                $data['rules'] = $rules->errors;
                $data['cssFile'] = Css::where('id', '=', $id);
                
                return $this->view("/admin/css/edit", $data);
            }
        }
    }

    public function delete($request) {

        $filename = Css::where('id', '=', $request['id'])['file_name'];
        $path = "website/assets/css/" . $filename . ".css";
        unlink($path);

        Css::delete('id', $request['id']);

        redirect("/admin/css");
    }

}