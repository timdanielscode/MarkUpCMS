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

        echo Session::get('username');

        $user = new User();
        
        $allUsers = Pagination::get($user->allUsersWithRoles(), 11);

        $count = count($allUsers);

        $numberOfPages = Pagination::getPageNumbers();
        
        if(submitted('search')) {

            $search = get('search');
            $allUsers = $user->allUsersWithRoles($search);
        }
       
        $data['allUsers'] = $allUsers;
        $data['count'] = $count;
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

        $userRole = DB::try()->select('roles.name')->from('roles')->join('user_role')->on('user_role.role_id', '=', 'roles.id')->join('users')->on('user_role.user_id', '=', 'users.id')->where('users.username', '=', $request['username'])->first();

        $user = new User();
        $user = $user->userAndRole($request['username']);
       
        $data['user'] = $user;
        $data['rules'] = [];

        if(empty($user) || $userRole['name'] === 'admin') {

            return Response::statusCode(404)->view("/404/404");
        } else {
            return $this->view('admin/users/edit', $data);
        }
    }

    public function update($request) {

        $username = $request['f_username'];
        $uniqueUsername = DB::try()->select('username')->from('users')->where('username', '=', $request['f_username'])->and('username', '!=', $request['username'])->fetch();
        $uniqueEmail = DB::try()->select('email')->from('users')->where('email', '=', $request['email'])->and('username', '!=', $request['username'])->fetch();

        $rules = new Rules();

        if($rules->user_edit($uniqueUsername, $uniqueEmail)->validated()) {

            User::update(['username' => $request['f_username']],[

                'username'  =>  $username,
                'email' => $request['email']
            ]);

            redirect('/admin/users/' . $request['f_username'] . '/edit'); 

        } else {

            $data['user']['id'] = User::where('username', '=', $request['username'])[0]['id'];
            $data['user']['username'] = $request['username'];
            $data['user']['email'] = User::where('username', '=', $request['username'])[0]['email'];
            $data['user']['name'] = DB::try()->select('users.username', 'roles.name')->from('users')->join('user_role')->on('user_role.user_id', '=', 'users.id')->join('roles')->on('user_role.role_id', '=', 'roles.id')->where('users.username', '=', $request['username'])->fetch()[0]['name'];

            $data['rules'] = $rules->errors;
            return $this->view('admin/users/edit', $data);
        }
    }

    public function delete($request) {
        
        $userRole = DB::try()->select('roles.name')->from('roles')->join('user_role')->on('user_role.role_id', '=', 'roles.id')->join('users')->on('user_role.user_id', '=', 'users.id')->where('users.username', '=', $request['username'])->first();

        if($userRole['name'] === 'admin') {

            return Response::statusCode(404)->view("/404/404");
        } else {

            User::delete('username', $request['username']);
            redirect("/admin/users");
        }
    }
}