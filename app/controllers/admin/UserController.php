<?php

namespace app\controllers\admin;

use app\models\User;
use core\Session;
use validation\Rules;
use core\http\Response;
use extensions\Pagination;
use validation\Get;

class UserController extends \app\controllers\Controller {

    private $_data;

    /**
     * To show 404 page with 404 status code (on not existing user)
     * 
     * @param string $id _POST user id
     * @return object UserController
     */ 
    private function ifExists($id) {

        if(empty(User::ifRowExists($id)) ) {

            return Response::statusCode(404)->view("/404/404")->data() . exit();
        }
    }

    /**
     * To show the user index view
     * 
     * @param array $request _GET search, page
     * @return object UserController, Controller
     */
    public function index($request) {

        $users = User::allUsersWithRoles(Session::get('username'));

        $this->_data['search'] = '';

        if(!empty($request['search'] ) ) {

            $this->_data['search'] = Get::validate($request['search']);
            $users = User::allUsersWithRolesOnSearch($this->_data['search'], Session::get('username'));
        }

        $this->_data['allUsers'] = Pagination::get($request, $users, 10);
        $this->_data['count'] = count($users);
        $this->_data['numberOfPages'] = Pagination::getPageNumbers();

        return $this->view('admin/users/index')->data($this->_data);
    }

    /**
     * To show the user create view
     * 
     * @return object UserController, Controller
     */
    public function create() {

        $this->_data["rules"] = [];
        return $this->view('admin/users/create')->data($this->_data);
    }

    /**
     * To store a new user (on successful validation)
     * 
     * @param array $request _POST f_username, email, password, password_confirm, role
     * @return object UserController, Controller (on failed validation)
     */
    public function store($request) {

        $rules = new Rules();

        if($rules->user($request, User::where(['username' => $request['f_username']]), User::where(['email' => $request['email']]))->validated()) {
                    
            if($request['role'] === 'null') { $request['role'] = NULL; }  

            User::insert([

                'username' => $request['f_username'],
                'email' => $request['email'],
                'password' => password_hash($request['password'], PASSWORD_DEFAULT),
                'removed'   => 0,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
                'role_id' => $request['role']
            ]);

            Session::set('success', 'You have successfully created a new user!');           
            redirect('/admin/users');

        } else {

            $this->_data['username'] = $request['f_username'];
            $this->_data['email'] = $request['email'];
            $this->_data['rules'] = $rules->errors;

            return $this->view('admin/users/create')->data($this->_data);
        }
    }

    /**
     * To show the user edit view
     * 
     * @param array $request id
     * @return object UserController, Controller
     */
    public function edit($request) {

        $this->ifExists($request['id']);

        $this->_data['user'] = User::normalUser('id', $request['id']);
        $this->_data['rules'] = [];

        return $this->view('admin/users/edit')->data($this->_data);
    }

    /**
     * To update user data (details) (on successful validation)
     * 
     * @param array $request username, _POST id (user id), f_username, email
     * @return object UserController, Controller (on failed validation)
     */
    public function update($request) {

        $id = $request['id'];
        $this->ifExists($id);

        $rules = new Rules();

        if($rules->user_edit($request, User::checkUniqueUsername($request["f_username"], $id), User::checkUniqueEmail($request['email'], $id))->validated()) {

            User::update(['id' => $id], [

                'username'  =>  $request['f_username'],
                'email' => $request['email']
            ]);

            redirect("/admin/users/$id/edit"); 
            Session::set('success', 'You have successfully updated the user details!');

        } else {

            $this->_data['user'] = User::userAndRole('id', $request['id']);
            $this->_data['rules'] = $rules->errors;

            return $this->view('admin/users/edit')->data($this->_data);
        }
    }

    /**
     * To update user data (role) (on successful validation)
     * 
     * @param array username, $request _POST id (user id)
     * @return object UserController, Controller (on failed validation)
     */
    public function updateRole($request) {

        $id = $request['id'];
        $this->ifExists($id);

        User::update(['id' => $id], [

            'role_id'  =>  1
        ]);
            
        Session::set('success', 'You have successfully updated the user role!');
        redirect('/admin/users'); 
    }

    /**
     * To remove user(s) from thrashcan
     * 
     * @param array $request _POST recoverIds (user recoverIds)
     */
    public function recover($request) {

        $recoverIds = explode(',', $request['recoverIds']);
            
        foreach($recoverIds as $id ) {

            $this->ifExists($id);

            User::update(['id' => $id], [

                'removed'  => 0
            ]);
        }
        
        Session::set('success', 'You have successfully recovered the user(s)!');
        redirect("/admin/users");
    }

    /**
     * To remove user(s) permanently or move to thrashcan
     * 
     * @param array $request _POST deleteIds (user deleteIds)
     */
    public function delete($request) {

        $deleteIds = explode(',', $request['deleteIds']);

        if(!empty($deleteIds) && !empty($deleteIds[0])) {

            foreach($deleteIds as $id) {

                $this->ifExists($id);

                if(User::getColumns(['removed'], $id)['removed'] !== 1) {

                    User::update(['id' => $id], [

                        'removed'  => 1,
                    ]);

                    Session::set('success', 'You have successfully moved the user(s) to the trashcan!');

                } else if(User::getColumns(['removed'], $id)['removed'] === 1) {

                    User::delete("id", $id);
                    Session::set('success', 'You have successfully removed the user(s)!');
                }
            }
        }
        redirect("/admin/users");
    }
}