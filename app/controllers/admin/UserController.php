<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use app\models\User;
use app\models\UserRole;
use app\models\Roles;
use core\Session;
use database\DB;
use core\Csrf;
use validation\Rules;
use core\http\Response;
use extensions\Pagination;


class UserController extends Controller {

    public function index() {

        $role = new Roles();
        $user = new User();
        $userRole = new UserRole();

        $allUsers = DB::try()->select($user->t.'.*', $role->t.'.'.$role->name)->from($user->t)->join($userRole->t)->on($user->t.'.'.$user->id, '=', $userRole->t.'.'.$userRole->user_id)->join($role->t)->on($userRole->t.'.'.$userRole->role_id, '=', $role->t.'.'.$role->id)->order($user->t.'.'.$user->id)->fetch();
        
        $allUsers = Pagination::get($allUsers, 5);
        $numberOfPages = Pagination::getPageNumbers();
        
        if(submitted('search')) {

            $search = get('search');
            $allUsers = DB::try()->select($user->t.'.*', $role->t.'.'.$role->name)->from($user->t)->join($userRole->t)->on($user->t.'.'.$user->id, '=', $userRole->t.'.'.$userRole->user_id)->join($role->t)->on($userRole->t.'.'.$userRole->role_id, '=', $role->t.'.'.$role->id)->where($user->username, '=', $search)->or($user->email, '=', $search)->or($role->name, '=', $search)->fetch();
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

            $rules = new Rules();
            $user = new User();
            $userRole = new UserRole();
                
            $uniqueUsername = DB::try()->select($user->username)->from($user->t)->where($user->username, '=', post('f_username'))->first();
            $uniqueEmail = DB::try()->select($user->email)->from($user->t)->where($user->email, '=', post('email'))->fetch();

            if($rules->register_rules_admin($uniqueUsername, $uniqueEmail)->validated()) {
                    
                User::insert([

                    $user->username => $request['f_username'],
                    $user->email => $request['email'],
                    $user->password => password_hash($request['password'], PASSWORD_DEFAULT),
                    $user->retypePassword => password_hash($request['password_confirm'], PASSWORD_DEFAULT),
                    $user->created_at => date("Y-m-d H:i:s"),
                    $user->updated_at => date("Y-m-d H:i:s")
                ]);

                $lastRegisteredUser = DB::try()->select($user->id)->from($user->t)->order($user->id)->desc(1)->first();              

                if($request['role'] === 'Normal') { $roleId = 1; } else { $roleId = 2; }

                UserRole::insert([

                    $userRole->user_id => $lastRegisteredUser['id'],
                    $userRole->role_id => $roleId
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

        $current = DB::try()->select('users.*', 'roles.name')->from('users')->join('user_role')->on('user_id', '=', 'users.id')->join('roles')->on('role_id', '=', 'roles.id')->where('users.username', '=', $request['username'])->fetch();

        if(empty($current)) {
            return Response::statusCode(404)->view("/404/404");
        } else {
            $data['current'] = $current;
            return $this->view('admin/users/show', $data);
        }

    }

    public function edit($request) {
       
        $user = new User();
        $user_role = new UserRole();
        $role = new Roles();

        $user = DB::try()->select($user->t.'.*', $role->t.'.'.$role->name)->from($user->t)->join($user_role->t)->on($user->t.'.'.$user->id, '=', $user_role->t.'.'.$user_role->user_id)->join($role->t)->on($user_role->t.'.'.$user_role->role_id, '=', $role->t.'.'.$role->id)->where($user->t.'.'.$user->username, '=', $request["username"])->first();
       
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

        $user = new User();
        $rules = new Rules();

        $username = $request['f_username'];
        $uniqueUsername = DB::try()->select($user->username)->from($user->t)->where($user->username, '=', $username)->first();

        if($rules->user_edit_username($uniqueUsername)->validated()) {

            DB::try()->update($user->t)->set([

                $user->username => $username

            ])->where($user->username, '=', $request['username'])->run(); 


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

        $uniqueEmail = DB::try()->select('email')->from('users')->where('email', '=', $email)->first();

        $rules = new Rules();

        if($rules->user_edit_email($uniqueEmail)->validated()) {

            DB::try()->update('users')->set([

                'email' => $email

            ])->where('username', '=', $request['username'])->run(); 

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

            DB::try()->update('user_role')->set([

                'role_id' => $role

            ])->where('user_id', '=', $request['id'])->run(); 

            redirect('/admin/users'); 

        } else {

            $data['rules'] = $rules->errors;
            return $this->view('admin/users/edit', $data);
        }
    }

    public function delete($request) {
        
        $username = $request['username'];

        $user = new User();
        $user = DB::try()->delete($user->t)->where($user->username, "=", $username)->run();

        redirect("/admin/users");
    }
}