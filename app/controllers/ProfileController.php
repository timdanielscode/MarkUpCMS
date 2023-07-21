<?php
                
  namespace app\controllers;

  use core\Session; 
  use core\http\Response;
  use database\DB;
                
  class ProfileController extends Controller {
    
    public function index($request) { 
      
      if(Session::get("username") === $request["username"]) { 

        $user = DB::try()->select('users.id, users.username, users.email, roles.name')->from('users')->join('user_role')->on('user_role.user_id', '=', 'users.id')->join('roles')->on('roles.id', '=', 'user_role.role_id')->where('users.username', '=', Session::get('username'))->first();
       
        $data['rules'] = [];
        $data['user'] = $user;

        return $this->view("profile/index", $data);    
      } else {
        
        return Response::statusCode(404)->view("/404/404");
      }  
    }      
  }  