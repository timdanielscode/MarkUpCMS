<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use database\DB;
use app\models\Media;
use parts\Session;
use core\Csrf;
use parts\Pagination;
use validation\Rules;

class MediaController extends Controller {

    public function index() {
        
        $media = new Media();
        $allMedia = DB::try()->all($media->t)->order('date_created_at')->fetch();

        $count = count($allMedia);
        $search = get('search');

        if(!empty($search) ) {
            $allMedia = DB::try()->all($media->t)->where($media->media_title, 'LIKE', '%'.$search.'%')->or($media->media_description, 'LIKE', '%'.$search.'%')->or($media->date_created_at, 'LIKE', '%'.$search.'%')->or($media->time_created_at, 'LIKE', '%'.$search.'%')->or($media->date_updated_at, 'LIKE', '%'.$search.'%')->or($media->time_updated_at, 'LIKE', '%'.$search.'%')->fetch();
            if(empty($allMedia) ) {
                $allMedia = array(["id" => "?","title" => "not found", "author" => "not found", "date_created_at" => "-", "time_created_at" => "", "date_updated_at" => "-", "time_updated_at" => ""]);
            }
        }
        
        $allMedia = Pagination::set($allMedia, 20);
        $numberOfPages = Pagination::getPages();

        $data["allMedia"] = $allMedia;
        $data['numberOfPages'] = $numberOfPages;
        $data['count'] = $count;

        return $this->view('admin/media/index', $data);
    }

    public function create() {

        $data["rules"] = [];

        return $this->view('admin/media/create', $data);
    }

    public function store() {

        if(submitted('submit')) {

            if(Csrf::validate(Csrf::token('get'), post('token') ) === true) {

                $rules = new Rules();
                $media = new Media();
                
                $file = $_FILES['file'];
                $filename = $_FILES['file']['name'];
                $tmp = $_FILES['file']['tmp_name'];
                $error = $_FILES['file']['error'];
                $size = $_FILES['file']['size'];
                $type = $_FILES['file']['type'];

                $ext = explode(".", $filename);
                $ext = strtolower(end($ext));

                $validated = array('jpg', 'jpeg', 'png');

                if(in_array($ext, $validated)) {
                    if($error === 0) {
                        if($size < 500000) {
                            $fileDestination = "website/assets/img/".$filename;
                            move_uploaded_file($tmp, $fileDestination);
                            echo 'ok';
                        } else {
                            echo 'file is to big';
                        }
                    } else {
                        echo 'error';
                    }
                } else {
                    echo 'not validated';
                }


                //if($rules->create_post()->validated()) {
                    
                    DB::try()->insert($media->t, [

                        $media->media_title => post('media_title'),
                        $media->media_description => post('media_description'),
                        $media->media_filename => $filename,
                        $media->media_filetype => $type,
                        $media->media_filesize => $size,
                        $media->date_created_at => date("d/m/Y"),
                        $media->time_created_at => date("H:i"),
                        $media->date_updated_at => date("d/m/Y"),
                        $media->time_updated_at => date("H:i")
                    ]);

                    Session::set('create', 'You have successfully created a new post!');            
                    redirect('/admin/media');

                //} else {

                    //$data['rules'] = $rules->errors;
                    //return $this->view('admin/users/create', $data);
                //}
            } else {
                //Session::set('csrf', 'Cross site request forgery!');
                //redirect('/admin/media/create');
            }
        }
    }

}