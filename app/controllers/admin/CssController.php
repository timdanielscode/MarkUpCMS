<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use app\models\Css;
use database\DB;
use core\Csrf;
use validation\Rules;
use parts\Session;
use parts\Pagination;

class CssController extends Controller {

    public function index() {

        $css = new Css();
        $cssFiles = DB::try()->all($css->t)->order('date_created_at')->fetch();
        if(empty($cssFiles) ) {
            $cssFiles = array(["id" => "?","file_name" => "no css file created yet", "extension" => "","date_created_at" => "-", "time_created_at" => "", "date_updated_at" => "-", "time_updated_at" => ""]);
        }
        $count = count($cssFiles);
        $search = get('search');

        if(!empty($search) ) {
            $cssFiles = DB::try()->all($css->t)->where($css->file_name, 'LIKE', '%'.$search.'%')->or($css->date_created_at, 'LIKE', '%'.$search.'%')->or($css->time_created_at, 'LIKE', '%'.$search.'%')->or($css->date_updated_at, 'LIKE', '%'.$search.'%')->or($css->time_updated_at, 'LIKE', '%'.$search.'%')->fetch();
            if(empty($cssFiles) ) {
                $cssFiles = array(["id" => "?","file_name" => "not found", "date_created_at" => "-", "time_created_at" => "", "date_updated_at" => "-", "time_updated_at" => ""]);
            }
        }

        $cssFiles = Pagination::set($cssFiles, 20);
        $numberOfPages = Pagination::getPages();

        $data['cssFiles'] = $cssFiles;
        $data['numberOfPages'] = $numberOfPages;
        $data['count'] = $count;

        return $this->view('admin/css/index', $data);
    }

    public function create() {

        $data['rules'] = [];
        return $this->view('admin/css/create', $data);
    }

    public function store() {

        if(submitted('submit')) {

            if(Csrf::validate(Csrf::token('get'), post('token') ) === true) {

                $rules = new Rules();
                $css = new Css();

                if($rules->css()->validated()) {
                    
                    $filename = "/".post('filename');
                    $filename = str_replace(" ", "-", $filename);

                    $code = post('code');
            
                    $file = fopen("website/assets/css/" . $filename . ".css", "w");
                    fwrite($file, $code);
                    fclose($file);
                    
                    DB::try()->insert($css->t, [

                        $css->file_name => post('filename'),
                        $css->extension => '.css',
                        $css->date_created_at => date("d/m/Y"),
                        $css->time_created_at => date("H:i"),
                        $css->date_updated_at => date("d/m/Y"),
                        $css->time_updated_at => date("H:i")
                    ]);

                    Session::set('create', 'You have successfully created a new post!');            
                    redirect('/admin/css');

                } else {

                    $data['rules'] = $rules->errors;
                    return $this->view('admin/css/create', $data);
                }
            } else {
                Session::set('csrf', 'Cross site request forgery!');
                redirect('/admin/users/create');
            }
        }
    }

    public function edit($request) {

        $css = new Css();
        $cssFile = DB::try()->select('*')->from($css->t)->where($css->id, '=', $request['id'])->first();
        
        $filePath = "website/assets/css/" . $cssFile["file_name"] . $cssFile["extension"]; 		
        
        if(file_exists($filePath) ) {
            $code = file_get_contents($filePath);
        } else {
            $code = "";
        }

        $data['cssFile'] = $cssFile;
        $data['code'] = $code;
        $data['rules'] = [];

        return $this->view('admin/css/edit', $data);
    }

    public function update($request) {

        if(submitted('submit')) {

            if(CSRF::validate(CSRF::token('get'), post('token'))) {
                
                $css = new Css();
                $rules = new Rules();

                $id = $request['id'];
                $filename = $request["filename"];
                $code = $request["code"];

                $currenCssFileName = DB::try()->select('file_name')->from($css->t)->where($css->id, '=', $request['id'])->first();

                if($rules->css()->validated()) {

                    $filename = str_replace(" ", "-", $filename);

                    rename("website/assets/css/" . $currenCssFileName[0] . ".css", "website/assets/css/" . $filename . ".css");

                    DB::try()->update($css->t)->set([
                        $css->file_name => $filename,
                        $css->date_updated_at => date("d/m/Y"),
                        $css->time_updated_at => date("H:i")
                    ])->where($css->id, '=', $id)->run();    
                    		
                    $file = fopen("website/assets/css/" . $filename . ".css", "w");
                    fwrite($file, $code);
                    fclose($file);

                    Session::set('updated', 'User updated successfully!');
                    redirect("/admin/css/$id/edit");
                    
                } else {
                    $data['rules'] = $rules->errors;

                    $filePath = "website/assets/css/" . $currenCssFileName[0] . ".css"; 
                    $code = file_get_contents($filePath);

                    $data['code'] = $code;
                    $data['cssFile'] = DB::try()->select('*')->from($css->t)->where($css->id, '=', $id)->first();
                    return $this->view("/admin/css/edit", $data);
                }

            } else {
                Session::set('csrf', 'Cross site request forgery!');
                redirect("/admin/posts/$id");
            }
        }
    }

    public function delete($request) {

        $id = $request['id'];

        $css = new Css();
    
        $filename = DB::try()->select('file_name')->from($css->t)->where($css->id, '=', $request['id'])->first();
        $path = "website/assets/css/" . $filename[0] . ".css";
        unlink($path);

        $css = DB::try()->delete($css->t)->where($css->id, "=", $id)->run();

        redirect("/admin/css");
    }

}