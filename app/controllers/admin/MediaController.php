<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use database\DB;
use app\models\Media;
use parts\Session;
use core\Csrf;
use validation\Rules;

class MediaController extends Controller {

    public function index() {
        
        return $this->view('admin/media/index');
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

                //if($rules->create_post()->validated()) {
                    
                    DB::try()->insert($media->t, [

                        $media->media_title => post('media_title'),
                        $media->media_description => post('media_description'),
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
                Session::set('csrf', 'Cross site request forgery!');
                redirect('/admin/media/create');
            }
        }
    }

}