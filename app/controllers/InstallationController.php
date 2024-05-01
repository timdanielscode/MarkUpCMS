<?php

namespace app\controllers;

use core\Csrf;
use validation\Rules;
use app\models\User;
use app\models\UserRole;
use app\models\Roles;
use app\models\Table;
use app\models\WebsiteSlug;
use app\models\Page;

class InstallationController extends Controller {

    private $_data;

    /**
     * To show the installation (create) user view
     * 
     * @return object InstallationController, Controller
     */
    public function createUser() {

        $this->_data['rules'] = [];

        return $this->view('admin/installation/user')->data($this->_data);
    }

    /**
     * To store the first type of admin user and page (on successful validation)
     * 
     * @param array $request _POST username, email, password, retypePassword, token
     * @return object InstallationController, Controller (on failed validation)
     */
    public function storeUser($request) {

        $rules = new Rules();  
                    
        if($rules->installation_user($request, Csrf::get())->validated() ) {

            Table::create();

            User::insert([
                    
                'username' => $request["username"], 
                'email' => $request["email"], 
                'password' => password_hash($request["password"], PASSWORD_DEFAULT),
                'removed'   => 0,
                'created_at' => date("Y-m-d H:i:s"), 
                'updated_at' => date("Y-m-d H:i:s"),
                'role_id'   => 1
            ]); 

            Roles::insert(['type'  => 'admin']);

            if(empty(WebsiteSlug::getData(['id']) ) || WebsiteSlug::getData(['id']) === null) { 

                WebsiteSlug::insert([

                    'slug' => '/login',
                    'created_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
                    'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
                ]);
            }

            Page::insert([
                    
                'title' => "homepage", 
                'slug' => "/",
                'body' => "<h1>Homepage!</h1>",
                'has_content' => 1,
                'author' => $request['username'],
                'removed' => 0,
                'created_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
                'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
            ]);

            redirect('/login');

        } else {
                         
            $this->_data["rules"] = $rules->errors;
            return $this->view("admin/installation/user")->data($this->_data);
        }
    }
}