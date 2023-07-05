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

class UserController extends Controller {

    public function index() {

        $user = new User();
        
        $allUsers = Pagination::get($user->allUsersWithRoles(), 5);
        $numberOfPages = Pagination::getPageNumbers();
        
        if(submitted('search')) {

            $search = get('search');
            $allUsers = $user->allUsersWithRoles($search);
        }

        $data['allUsers'] = $allUsers;
        $data['numberOfPages'] = $numberOfPages;

        return $this->view('admin/users/index', $data);
    }

    public function create() {

        $data["rules"] = [];
        return $this->view('admin/users/create', $data);
    }

    public function store($request) {

        if(submitted("submit") === true && Csrf::validate(Csrf::token('get'), post('token')) === true ) {

            $uniqueUsername = User::where('username', '=', $request['f_username']);
            $uniqueEmail = User::where('email', '=', $request['email']);

            $rules = new Rules();
            
            if($rules->register_rules_admin($uniqueUsername, $uniqueEmail)->validated()) {
                    
                User::insert([

                    'username' => $request['f_username'],
                    'email' => $request['email'],
                    'password' => password_hash($request['password'], PASSWORD_DEFAULT),
                    'retypePassword' => password_hash($request['password_confirm'], PASSWORD_DEFAULT),
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ]);

                $user = new User();
                $lastRegisteredUser = $user->getLastRegisteredUserId();

                if($request['role'] === 'Normal') { $roleId = 1; } else { $roleId = 2; }

                UserRole::insert([

                    'user_id' => $lastRegisteredUser['id'],
                    'role_id' => $roleId
                ]);

                Session::set('registered', 'You have been successfully registered!');            
                redirect('/admin/users');

            } else {

                $data['rules'] = $rules->errors;
                return $this->view('admin/users/create', $data);
            }
        }
    }

    public function read($request) {

        $user = new User();
        $user = $user->userAndRole($request['username']);

        if(empty($user)) {
            return Response::statusCode(404)->view("/404/404");
        } else {
            $data['current'] = $user;
            return $this->view('admin/users/read', $data);
        }
    }

    public function edit($request) {

        $user = new User();
        $user = $user->userAndRole($request['username']);
       
        $data['user'] = $user;
        $data['rules'] = [];

        if(empty($user)) {
            return Response::statusCode(404)->view("/404/404");
        } else {
            return $this->view('admin/users/edit', $data);
        }
    }

    public function update($request) {

        //if(submitted("submit") === true && Csrf::validate(Csrf::token('get'), post('token')) === true ) {
        if(submitted("submit") === true) {

            if(array_key_exists('f_username', $request)) {

                $this->updateUsername($request);
            } else if (array_key_exists('email', $request) ) {

                $this->updateEmail($request);
            } else if (array_key_exists('role', $request)) {

                $this->updateRole($request);
            }
        }
    }

    private function updateUsername($request) {

        $username = $request['f_username'];
        $uniqueUsername = User::where('username', '=', $username);

        $rules = new Rules();

        if($rules->user_edit_username($uniqueUsername)->validated()) {

            User::update(['username' => $request['username']],[

                'username'  =>  $username
            ]);

            if(Session::get('username') === $request['f_username']) {

                Session::set('username', $request['f_username']); 
            }

            redirect('/admin/users'); 

        } else {

            $data['user']['username'] = $request['username'];
            $data['user']['email'] = User::where('username', '=', $request['username'])['email'];
            $data['user']['name'] = DB::try()->select('users.username', 'roles.name')->from('users')->join('user_role')->on('user_role.user_id', '=', 'users.id')->join('roles')->on('user_role.role_id', '=', 'roles.id')->where('users.username', '=', $request['username'])->fetch()[0]['name'];

            $data['rules'] = $rules->errors;
            return $this->view('admin/users/edit', $data);
        }
    }

    private function updateEmail($request) {

        $email = $request['email'];
        $uniqueEmail = User::where('email', '=', $email);

        $rules = new Rules();

        if($rules->user_edit_email($uniqueEmail)->validated()) {

            User::update(['username' => $request['username']],[

                'email'  =>  $email
            ]);

            redirect('/admin/users'); 

        } else {

            $data['user']['name'] = DB::try()->select('users.username', 'roles.name')->from('users')->join('user_role')->on('user_role.user_id', '=', 'users.id')->join('roles')->on('user_role.role_id', '=', 'roles.id')->where('users.username', '=', $request['username'])->fetch()[0]['name'];
            $data['user']['username'] = User::where('username', '=', $request['username'])['username'];
            $data['user']['email'] = $request['email'];

            $data['rules'] = $rules->errors;
            return $this->view('admin/users/edit', $data);
        }
    }

    private function updateRole($request) {

        $role = $request['role'];

        $rules = new Rules();

        if($rules->user_edit_role()->validated()) {

            UserRole::update(['user_id' => $request['id']],[

                'role_id'  =>  $role
            ]);

            redirect('/admin/users'); 

        } else {

            $data['rules'] = $rules->errors;
            return $this->view('admin/users/edit', $data);
        }
    }

    public function delete($request) {
        
        User::delete('username', $request['username']);
        redirect("/admin/users");
    }
}