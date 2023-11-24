<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use app\models\Post;
use app\models\WebsiteSlug;
use validation\Rules;
use core\Session;
use core\Csrf;
                    
class SettingsController extends Controller {

    private $_data;
         
    public function index() {    

        $this->_data['currentLoginSlug'] = WebsiteSlug::getData(['slug']);
        $this->_data['rules'] = [];

        return $this->view('/admin/settings/index')->data($this->_data);
    }

    public function updateSlug($request) {

        $rules = new Rules();

        if($rules->update_website_slug($request['slug'], Post::whereColumns(['slug'], ['slug' => "/" . $request['slug']]))->validated()) {

            WebsiteSlug::update(['id' => WebsiteSlug::getData(['id'])['id']], [

                'slug'     => "/" . $request['slug'],
                'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
            ]);

            Session::delete("logged_in");
            Session::delete("username");
            Session::delete('user_role');
    
            redirect('/' . $request['slug']);

        } else {

            $this->_data['rules'] = $rules->errors;
            return $this->view('/admin/settings/index')->data($this->_data);
        }
    }
}  