<?php

namespace app\controllers;

use core\Csrf;
use validation\Rules;
use app\models\User;
use app\models\UserRole;
use app\models\Roles;
use app\models\Table;
use app\models\WebsiteSlug;

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
     * To store the first type of admin user (on successful validation)
     * 
     * @param array $request _POST username, email, password, retypePassword, token
     * @return object InstallationController, Controller (on failed validation)
     */
    public function storeUser($request) {

        $rules = new Rules();  
                    
        if($rules->installation_user($request['username'], $request['email'], $request['password'], $request['retypePassword'], $request['token'], Csrf::get())->validated() ) {

            Table::create();

            User::insert([
                    
                'username' => $request["username"], 
                'email' => $request["email"], 
                'password' => password_hash($request["password"], PASSWORD_DEFAULT),
                'removed'   => 0,
                'created_at' => date("Y-m-d H:i:s"), 
                'updated_at' => date("Y-m-d H:i:s")
            ]); 

            UserRole::insert([
    
                'user_id' => User::getLastUserId()['id'],
                'role_id' => 2
            ]);

            Roles::insert(['name'  => 'normal']);
            Roles::insert(['name'  => 'admin']);

            if(empty(WebsiteSlug::getData(['id']) ) || WebsiteSlug::getData(['id']) === null) { 

                WebsiteSlug::insert([

                    'slug' => '/login',
                    'created_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
                    'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
                ]);
            }

            redirect('/login');

        } else {
                         
            $this->_data["rules"] = $rules->errors;
            return $this->view("admin/installation/user")->data($this->_data);
        }
    }
}