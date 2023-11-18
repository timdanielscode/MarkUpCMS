<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use app\models\User;
use app\models\UserRole;
use core\Session;
use database\DB;
use core\Csrf;
use validation\Rules;
use core\http\Response;
use extensions\Pagination;
use validation\Get;

class UserController extends Controller {

    private $_count;

    private function ifExists($id) {

        if(empty(User::ifRowExists($id)) ) {

            return Response::statusCode(404)->view("/404/404") . exit();
        }
    }

    private function redirect($inputName, $path) {

        if(submitted($inputName) === false || Csrf::validate(Csrf::token('get'), post('token')) === false ) { 
            
            redirect($path) . exit(); 
        } 
    }

    public function index() {

        $data['allUsers'] = $this->getUsers(Get::validate([get('search')]));
        $data['count'] = $this->_count;
        $data['numberOfPages'] = Pagination::getPageNumbers();

        return $this->view('admin/users/index', $data);
    }

    private function getUsers($search) {

        $users = User::allUsersWithRoles();
    
        if(!empty($search)) {
    
            $users = User::allUsersWithRolesOnSearch($search);
        }
    
        $this->_count = count($users);
        return Pagination::get($users, 10);
    }

    public function create() {

        $data["rules"] = [];
        return $this->view('admin/users/create', $data);
    }

    public function store($request) {

        $this->redirect("submit", '/admin/users');

        $rules = new Rules();
            
        if($rules->user_create(User::where(['username' => $request['f_username']]), User::where(['email' => $request['email']]))->validated()) {
                    
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

            $data['rules'] = $rules->errors;
            return $this->view('admin/users/create', $data);
        }
    }

    public function read($request) {

        $this->ifExists($request['username']);

        $data['current'] = User::userAndRole($request['username']);
        return $this->view('admin/users/read', $data);
    }

    public function edit($request) {

        $this->ifExists($request['username']);

        $data['user'] = User::userAndRole($request['username']);
        $data['rules'] = [];

        return $this->view('admin/users/edit', $data);
    }

    public function update($request) {

        $id = $request['id'];
        $this->ifExists($id);
        $this->redirect("submit", "/admin/users");

        $rules = new Rules();

        if($rules->user_edit(User::checkUniqueUsername($request["f_username"], $id), User::checkUniqueEmail($request['email'], $id))->validated()) {

            User::update(['id' => $id], [

                'username'  =>  $request['f_username'],
                'email' => $request['email']
            ]);

            redirect("/admin/users/" . $request['f_username'] . "/edit"); 
            Session::set('success', 'You have successfully updated the user details!');

        } else {

            $data['user'] = User::userAndRole($request['f_username']);
            $data['rules'] = $rules->errors;

            return $this->view('admin/users/edit', $data);
        }
    }

    public function updateRole($request) {

        $id = $request['id'];
        $this->ifExists($id);
        $this->redirect("submit", "/admin/users");

        UserRole::update(['user_id' => $id], [

            'role_id'  =>  2,
            'user_id' => $id
        ]);
            
        Session::set('success', 'You have successfully updated the user role!');
        redirect('/admin/users'); 
    }

    public function recover($request) {

        $this->redirect("recoverIds", "/admin/users");

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

    public function delete($request) {

        $this->redirect("deleteIds", "/admin/users");

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