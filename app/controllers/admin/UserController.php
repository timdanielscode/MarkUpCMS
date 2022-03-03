<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use app\models\User;
use app\models\UserRole;
use app\models\Roles;
use parts\Session;
use database\DB;
use core\CSRF;
use validation\Rules;
use core\Response;
use parts\Pagination;


class UserController extends Controller {

    public function index() {

        $role = new Roles();
        $user = new User();
        $userRole = new UserRole();

        $allUsers = DB::try()->select($user->t.'.*', $role->t.'.'.$role->name)->from($user->t)->join($userRole->t)->on($user->t.'.'.$user->id, '=', $userRole->t.'.'.$userRole->user_id)->join($role->t)->on($userRole->t.'.'.$userRole->role_id, '=', $role->t.'.'.$role->id)->order($user->t.'.'.$user->id)->fetch();
        
        $allUsers = Pagination::set($allUsers, 5);
        $numberOfPages = Pagination::getPages();
        
        if(submitted('search')) {

            $search = get('search');
            $allUsers = DB::try()->select($user->t.'.*', $role->t.'.'.$role->name)->from($user->t)->join($userRole->t)->on($user->t.'.'.$user->id, '=', $userRole->t.'.'.$userRole->user_id)->join($role->t)->on($userRole->t.'.'.$userRole->role_id, '=', $role->t.'.'.$role->id)->where($user->username, '=', $search)->or($user->email, '=', $search)->or($role->name, '=', $search)->fetch();
        }

        $data['allUsers'] = $allUsers;
        $data['numberOfPages'] = $numberOfPages;

        return $this->view('admin/users/index', $data);
    }

    public function create() {

        return $this->view('admin/users/create');
    }

    public function store() {

        if(submitted('submit')) {

            if(Csrf::validate(Csrf::token('get'), post('token') ) === true) {

                $rules = new Rules();
                $user = new User();
                $userRole = new UserRole();
                
                $uniqueUsername = DB::try()->select($user->username)->from($user->t)->where($user->username, '=', post('username'))->first();
                $uniqueEmail = DB::try()->select($user->email)->from($user->t)->where($user->email, '=', post('email'))->fetch();

                if($rules->register_rules_admin($uniqueUsername, $uniqueEmail)->validated()) {
                    
                    DB::try()->insert($user->t, [

                        $user->username => post('username'),
                        $user->email => post('email'),
                        $user->password => password_hash(post('password'), PASSWORD_DEFAULT),
                        $user->retype_password => password_hash(post('password_confirm '), PASSWORD_DEFAULT),
                        $user->created_at => date("Y-m-d H:i:s"),
                        $user->updated_at => date("Y-m-d H:i:s")
                    ]);

                    $lastRegisteredUser = DB::try()->select($user->id)->from($user->t)->order($user->id)->desc(1)->first();              

                    if(post('role') == 'Normal') {
                        $roleId = 1;
                    } else {
                        $roleId = 2;
                    }

                    DB::try()->insert($userRole->t, [
                        $userRole->user_id => $lastRegisteredUser['id'],
                        $userRole->role_id => $roleId 
                    ]); 

                    Session::set('registered', 'You have been successfully registered!');            
                    redirect('/admin/users');

                } else {

                    $data['rules'] = $rules->errors;
                    return $this->view('admin/users/create', $data);
                }
            } else {
                Session::set('csrf', 'Cross site request forgery!');
                redirect('/admin/users/create');
            }
        }
    }

    public function read($request) {

        $role = new Roles();
        $user = new User();
        $userRole = new UserRole();

        $current = DB::try()->select('u'.'.*', $role->t.'.'.$role->name)->from('users')->as('u')->join($userRole->t)->on('u'.'.id', '=', $userRole->t.'.'.$userRole->user_id)->join($role->t)->on($userRole->t.'.'.$userRole->role_id, '=', $role->t.'.'.$role->id)->where('u.id', '=', $request['id'])->and('u.username', '=', $request["username"])->fetch();

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

        $user = DB::try()->select($user->t.'.*', $role->t.'.'.$role->name)->from($user->t)->join($user_role->t)->on($user->t.'.'.$user->id, '=', $user_role->t.'.'.$user_role->user_id)->join($role->t)->on($user_role->t.'.'.$user_role->role_id, '=', $role->t.'.'.$role->id)->where($user->t.'.'.$user->id, '=', $request["id"])->and($user->t.'.'.$user->username, '=', $request["username"])->first();
       
        $data['user'] = $user;
        $data['rules'] = [];

        if(empty($user)) {
            return Response::statusCode(404)->view("/404/404");
        } else {
            return $this->view('admin/users/edit', $data);
        }
    }

    public function update($request) {

        $id = $request["id"];
        
        if(submitted('submit')) {

            if(CSRF::validate(CSRF::token('get'), post('token'))) {
                
                $rules = new Rules();
                $user = new User();
                $userRole = new UserRole();
                $role = new Roles();

                $username = $request["username"];
                $email = $request["email"];

                if($rules->user_edit()->validated()) {
                
                    DB::try()->update($user->t)->set([
                        $user->username => $username,
                        $user->email => $email,
                    ])->where($user->id, '=', $id)->run();              

                    if(post('role') == 'Normal') { $roleId = 1;} else {$roleId = 2;}

                    DB::try()->update($userRole->t)->set([
                        $userRole->role_id => $roleId,
                    ])->where($userRole->user_id, '=', $id)->run(); 

                    Session::set('updated', 'User updated successfully!');
                    redirect("/admin/users");
                    
                } else {
                    $data['rules'] = $rules->errors;
                }

            } else {
                Session::set('csrf', 'Cross site request forgery!');
                redirect("/admin/users/$id");
            }
        }

        $user = DB::try()->select($user->t.'.*', $role->t.'.'.$role->name)->from($user->t)->join($userRole->t)->on($user->t.'.'.$user->id, '=', $userRole->t.'.'.$userRole->user_id)->join($role->t)->on($userRole->t.'.'.$userRole->role_id, '=', $role->t.'.'.$role->id)->where($user->t.'.'.$user->id, '=', $id)->first();
        $data['user'] = $user;

        return $this->view('admin/users/edit', $data);
    }

    public function delete($id) {
        
        $id = $id['id'];

        $user = new User();
        $user = DB::try()->delete($user->t)->where($user->id, "=", $id)->run();

        redirect("/admin/users");
    }


}