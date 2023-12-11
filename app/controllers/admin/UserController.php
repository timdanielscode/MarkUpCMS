<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use app\models\User;
use app\models\UserRole;
use core\Session;
use validation\Rules;
use core\http\Response;
use extensions\Pagination;
use validation\Get;

class UserController extends Controller {

    private $_data;

    /**
     * To show 404 page with 404 status code (on not existing user)
     * 
     * @param string $id _POST user id
     * @return object UserController
     */ 
    private function ifExists($id) {

        if(empty(User::ifRowExists($id)) ) {

            return Response::statusCode(404)->view("/404/404")->data . exit();
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
            
        if($rules->user($request['f_username'], $request['email'], $request['password'], $request['password_confirm'], $request['role'], User::where(['username' => $request['f_username']]), User::where(['email' => $request['email']]))->validated()) {
                    
            User::insert([

                'username' => $request['f_username'],
                'email' => $request['email'],
                'password' => password_hash($request['password'], PASSWORD_DEFAULT),
                'removed'   => 0,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]);

            if($request['role'] === 'Normal') { $roleId = 1; } else { $roleId = 2; }

            UserRole::insert([

                'user_id' => User::getLastRegisteredUserId()['id'],
                'role_id' => $roleId
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
     * @param array $request username
     * @return object UserController, Controller
     */
    public function edit($request) {

        $this->ifExists($request['username']);

        $this->_data['user'] = User::userAndRole('username', $request['username']);
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

        if($rules->user_edit($request['f_username'], $request['email'], User::checkUniqueUsername($request["f_username"], $id), User::checkUniqueEmail($request['email'], $id))->validated()) {

            User::update(['id' => $id], [

                'username'  =>  $request['f_username'],
                'email' => $request['email']
            ]);

            redirect("/admin/users/" . $request['f_username'] . "/edit"); 
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

        UserRole::update(['user_id' => $id], [

            'role_id'  =>  2,
            'user_id' => $id
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