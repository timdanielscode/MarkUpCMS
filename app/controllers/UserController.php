<?php

namespace app\controllers;

use app\models\User;
use database\DB;
use core\Response;
use parts\Session;
use core\Csrf;
use validation\Rules;

class UserController extends Controller {

    public function read($request) {

        $user = new User();
        $user = DB::try()->all($user->t)->where($user->username, 'LIKE BINARY', Session::get('username'))->first();
 
        if(empty($user) || $request['username'] !== Session::get('username')) {
            
            return Response::statusCode(404)->view("/404/404");
        } else {
            $data['user'] = $user;
            
            return $this->view('profile/read', $data);
        }
    }

    public function edit($request) {

        $user = new User();
        $user = DB::try()->all($user->t)->where($user->username, 'LIKE BINARY', $request['username'])->first();

        if(empty($user) || $request['username'] !== Session::get('username')) {
            return Response::statusCode(404)->view("/404/404");
        } else {
            $data['user'] = $user;
            $data['rules'] = [];
            return $this->view('profile/edit', $data);
        }

    }

    public function update($request) {
        
        if(submitted('submit')) {

            $rules = new Rules();
            $user = new User();
            $username = $request["username"];
            //print_r($request);
            
            //echo $username;
            $email = $request["email"];
            
            if(Csrf::validate(Csrf::token('get'), post('token') ) === true) {
                
                $uniqueUsername = DB::try()->select($user->username)->from($user->t)->where($user->username, '=', post('username'))->first();
                $uniqueEmail = DB::try()->select($user->email)->from($user->t)->where($user->email, '=', post('email'))->fetch();

                if($rules->profile_edit($uniqueUsername, $uniqueEmail)->validated()) {
                
                    DB::try()->update($user->t)->set([
                        $user->username => $username,
                        $user->email => $email,
                    ])->where($user->username, '=', Session::get('username'))->run();

                    Session::delete('username');
                    Session::set('username', $username);
                    Session::set('updated', 'User updated successfully!');
                   
                    redirect("/profile/$username");
                } else {

                    $data['rules'] = $rules->errors;
                    $data['user']['username'] = $username;
                    $data['user']['email'] = $email;

                    return $this->view('profile/edit', $data);
                }
            } else {

                Session::set('Csrf_token_message', 'Cross site request forgery!');
                $data['user']['username'] = '';
                $data['user']['email'] = '';

                redirect("/profile/".Session::get('username')."/edit");
            }
        }
    }    
}