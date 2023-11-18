<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use app\models\Post;
use app\models\WebsiteSlug;
use validation\Rules;
use core\Session;
use core\Csrf;
                    
class SettingsController extends Controller {

    private function redirect($inputName, $path) {

        if(submitted($inputName) === false || Csrf::validate(Csrf::token('get'), post('token')) === false ) { 
            
            redirect($path) . exit(); 
        } 
    }
                
    public function index() {    

        $data['currentLoginSlug'] = WebsiteSlug::getData(['slug']);
        $data['rules'] = [];

        return $this->view('/admin/settings/index', $data);
    }

    public function updateSlug($request) {

        $this->redirect("submit", '/admin/settings');

        $rules = new Rules();

        if($rules->update_website_slug(Post::whereColumns(['slug'], ['slug' => "/" . $request['slug']]))->validated()) {

            $this->update($request);

        } else {

            $data['rules'] = $rules->errors;
            return $this->view('/admin/settings/index', $data);
        }

        Session::delete("logged_in");
        Session::delete("username");
        Session::delete('user_role');

        redirect('/' . $request['slug']);
    }

    private function update($request) {

        if(!empty(WebsiteSlug::getData(['id'])) && WebsiteSlug::getData(['id']) !== null) {

            WebsiteSlug::update(['id' => WebsiteSlug::getData(['id'])['id']], [

                'slug'     => "/" . $request['slug'],
                'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
            ]);

        } else {

            WebsiteSlug::insert([

                'slug' => "/" . $request['slug'],
                'created_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
                'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
            ]);
        }
    }
}  