<?php

namespace app\controllers\admin;

use app\models\Page;
use app\models\WebsiteSlug;
use validation\Rules;
use core\Session;
                    
class SettingsController extends \app\controllers\Controller {

    private $_data;
         
    /**
     * To show the settings index view
     * 
     * @param array $request _GET search (pages)
     * @return object SettingsController, Controller
     */
    public function index($request) {    

        $this->_data['currentLoginSlug'] = WebsiteSlug::getData(['slug']);
        $this->_data['rules'] = [];

        return $this->view('/admin/settings/index')->data($this->_data);
    }

    /**
     * To update login website data (slug) (on successful validation)
     * 
     * @param array $request _POST slug
     * @return object SettingsController, Controller (on failed validation)
     */
    public function updateSlug($request) {

        $rules = new Rules();

        if($rules->settings_slug($request, Page::whereColumns(['slug'], ['slug' => "/" . $request['slug']]))->validated()) {

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