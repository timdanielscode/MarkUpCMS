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

    public function fetchData() {

        $media = new Media();
        $allMedia = DB::try()->all($media->t)->order('date_created_at')->fetch();

        $data['allMedia'] = $allMedia;

        return $this->view('admin/media/table', $data);
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
                
                $filename = $_FILES['file']['name'];
                $tmp = $_FILES['file']['tmp_name'];
                $size = $_FILES['file']['size'];
                $type = $_FILES['file']['type'];

                if($rules->media()->validated()) {
                    
                    if($type == 'image/png' || $type  == 'image/webp' || $type  == 'image/gif' || $type  == 'image/jpeg' || $type  == 'image/svg+xml') {
                        $fileDestination = "website/assets/img/".$filename;
                    } else if($type == 'video/mp4' || $type == 'video/quicktime') {
                        $fileDestination = "website/assets/video/".$filename;
                    } else if($type == 'application/pdf') {
                        $fileDestination = "website/assets/application/".$filename;
                    } else {
                        $fileDestination = '';
                    }
                    
                    move_uploaded_file($tmp, $fileDestination);

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

                } else {
                    $data['rules'] = $rules->errors;
                    return $this->view('admin/media/create', $data);
                }
            } else {
                Session::set('csrf', 'Cross site request forgery!');
                redirect('/admin/media/create');
            }
        }
    }


    public function edit($request) {
        
        $media = new Media();
        $media = DB::try()->all($media->t)->where($media->id, '=', $request['id'])->first();

        $data['media'] = $media;
        $data['rules'] = [];
        return $this->view('/admin/media/edit', $data);
    }

    public function updateFilename($request) { 

        $data['id'] = $_POST['id'];
        $data['filename'] = $_POST['filename'];

        $rules = new Rules();

        if($rules->update_media_filename()->validated()) {

            $media = new Media();
    
            $currentFile = DB::try()->select($media->media_filename, $media->media_filetype)->from($media->t)->where($media->id, '=', $data['id'])->first();
            $currentFileName = $currentFile[0];
            $type = $currentFile[1];
         
            if($type == 'image/png' || $type  == 'image/webp' || $type  == 'image/gif' || $type  == 'image/jpeg' || $type  == 'image/svg+xml') {
                $fileDestination = "website/assets/img/";
            } else if($type == 'video/mp4' || $type == 'video/quicktime') {
                $fileDestination = "website/assets/video/";
            } else if($type == 'application/pdf') {
                $fileDestination = "website/assets/application/";
            } else {
                $fileDestination = '';
            }
    
            rename($fileDestination.$currentFileName, $fileDestination.$data['filename']);
    
            DB::try()->update($media->t)->set([
                $media->media_filename => $data['filename']
            ])->where($media->id, '=', $data['id'])->run(); 

            echo json_encode($data);
        }
    }
    
}