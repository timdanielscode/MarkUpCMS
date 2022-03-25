<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use app\models\Js;
use database\DB;
use core\Csrf;
use validation\Rules;
use parts\Session;
use parts\Pagination;

class JsController extends Controller {

    public function index() {

        $js = new Js();
        $jsFiles = DB::try()->all($js->t)->order('date_created_at')->fetch();
        if(empty($jsFiles) ) {
            $jsFiles = array(["id" => "?","file_name" => "no js file created yet", "extension" => "","date_created_at" => "-", "time_created_at" => "", "date_updated_at" => "-", "time_updated_at" => ""]);
        }
        $count = count($jsFiles);
        $search = get('search');

        if(!empty($search) ) {
            $jsFiles = DB::try()->all($js->t)->where($js->file_name, 'LIKE', '%'.$search.'%')->or($js->date_created_at, 'LIKE', '%'.$search.'%')->or($js->time_created_at, 'LIKE', '%'.$search.'%')->or($js->date_updated_at, 'LIKE', '%'.$search.'%')->or($js->time_updated_at, 'LIKE', '%'.$search.'%')->fetch();
            if(empty($jsFiles) ) {
                $jsFiles = array(["id" => "?","file_name" => "not found", "date_created_at" => "-", "time_created_at" => "", "date_updated_at" => "-", "time_updated_at" => ""]);
            }
        }

        $jsFiles = Pagination::set($jsFiles, 20);
        $numberOfPages = Pagination::getPages();

        $data['jsFiles'] = $jsFiles;
        $data['numberOfPages'] = $numberOfPages;
        $data['count'] = $count;

        return $this->view('admin/js/index', $data);
    }

    public function create() {

        $data['rules'] = [];
        return $this->view('admin/js/create', $data);
    }

    public function store() {

        if(submitted('submit')) {

            if(Csrf::validate(Csrf::token('get'), post('token') ) === true) {

                $rules = new Rules();
                $js = new Js();

                if($rules->js()->validated()) {
                    
                    $filename = "/".post('filename');
                    $filename = str_replace(" ", "-", $filename);

                    $code = post('code');
            
                    $file = fopen("website/assets/js/" . $filename . ".js", "w");
                    fwrite($file, $code);
                    fclose($file);
                    
                    DB::try()->insert($js->t, [

                        $js->file_name => post('filename'),
                        $js->extension => '.js',
                        $js->date_created_at => date("d/m/Y"),
                        $js->time_created_at => date("H:i"),
                        $js->date_updated_at => date("d/m/Y"),
                        $js->time_updated_at => date("H:i")
                    ]);

                    Session::set('create', 'You have successfully created a new post!');            
                    redirect('/admin/js');

                } else {

                    $data['rules'] = $rules->errors;
                    return $this->view('admin/js/create', $data);
                }
            } else {
                Session::set('csrf', 'Cross site request forgery!');
                redirect('/admin/users/create');
            }
        }
    }

    public function edit($request) {

        $js = new Js();
        $jsFile = DB::try()->select('*')->from($js->t)->where($js->id, '=', $request['id'])->first();
        
        $filePath = "website/assets/js/" . $jsFile["file_name"] . $jsFile["extension"]; 		
        
        if(file_exists($filePath) ) {
            $code = file_get_contents($filePath);
        } else {
            $code = "";
        }

        $data['jsFile'] = $jsFile;
        $data['code'] = $code;
        $data['rules'] = [];

        return $this->view('admin/js/edit', $data);
    }

    public function update($request) {

        if(submitted('submit')) {

            if(CSRF::validate(CSRF::token('get'), post('token'))) {
                
                $js = new Js();
                $rules = new Rules();

                $id = $request['id'];
                $filename = $request["filename"];
                $code = $request["code"];

                $currenJsFileName = DB::try()->select('file_name')->from($js->t)->where($js->id, '=', $request['id'])->first();

                if($rules->js()->validated()) {

                    $filename = str_replace(" ", "-", $filename);

                    rename("website/assets/js/" . $currenJsFileName[0] . ".js", "website/assets/js/" . $filename . ".js");

                    DB::try()->update($js->t)->set([
                        $js->file_name => $filename,
                        $js->date_updated_at => date("d/m/Y"),
                        $js->time_updated_at => date("H:i")
                    ])->where($js->id, '=', $id)->run();    
                    		
                    $file = fopen("website/assets/js/" . $filename . ".js", "w");
                    fwrite($file, $code);
                    fclose($file);

                    Session::set('updated', 'User updated successfully!');
                    redirect("/admin/js/$id/edit");
                    
                } else {
                    $data['rules'] = $rules->errors;

                    $filePath = "website/assets/js/" . $currenJsFileName[0] . ".js"; 
                    $code = file_get_contents($filePath);

                    $data['code'] = $code;
                    $data['jsFile'] = DB::try()->select('*')->from($js->t)->where($js->id, '=', $id)->first();
                    return $this->view("/admin/js/edit", $data);
                }

            } else {
                Session::set('csrf', 'Cross site request forgery!');
                redirect("/admin/posts/$id");
            }
        }
    }

    public function delete($request) {

        $id = $request['id'];

        $js = new Js();
    
        $filename = DB::try()->select('file_name')->from($js->t)->where($js->id, '=', $request['id'])->first();
        $path = "website/assets/js/" . $filename[0] . ".js";
        unlink($path);

        $js = DB::try()->delete($js->t)->where($js->id, "=", $id)->run();

        redirect("/admin/js");
    }

}