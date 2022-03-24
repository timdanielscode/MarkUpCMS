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

                    $content = post('content');
            
                    $extension = ".css";
                    $file = fopen("website/assets/css/" . $filename . $extension, "w");
                    fwrite($file, $content);
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

}