<?php
                
  namespace app\controllers;

  use core\Session; 
  use core\http\Response;
  use database\DB;
  use validation\Rules;
  use app\models\User;
  use app\models\UserRole;
  use core\Csrf;
                
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

    public function updateDetails($request) {

        $username = $request['f_username'];
        $uniqueUsername = DB::try()->select('username')->from('users')->where('username', '=', $request['f_username'])->and('username', '!=', $request['username'])->fetch();
        $uniqueEmail = DB::try()->select('email')->from('users')->where('email', '=', $request['email'])->and('username', '!=', $request['username'])->fetch();

        $rules = new Rules();

        if($rules->profile_edit_details($uniqueUsername, $uniqueEmail)->validated()) {

            User::update(['username' => $request['username']],[

                'username'  =>  $username,
                'email' => $request['email']
            ]);

            Session::set('username', $username);
            redirect('/profile/' . $username);

        } else {

            $data['user'] = DB::try()->select('users.id, users.username, users.email, roles.name')->from('users')->join('user_role')->on('users.id', '=', 'user_role.user_id')->join('roles')->on('user_role.role_id', '=', 'roles.id')->where('users.username', '=', $request['username'])->first();
            $data['rules'] = $rules->errors;
            return $this->view('/profile/index', $data);
        }
    }

    public function updateRole($request) {

        $rules = new Rules();

        $adminIds = UserRole::where('role_id', '=', 2);

        if($rules->profile_edit_role($adminIds)->validated()) {

            UserRole::update(['user_id' => $request['id']], [

                'role_id'  =>  1,
                'user_id' => $request['id']
            ]);
        
            Session::set('user_role', 'normal');
            redirect('/profile/' . Session::get('username'));

        } else {

            $data['user'] = DB::try()->select('users.id, users.username, users.email, roles.name')->from('users')->join('user_role')->on('users.id', '=', 'user_role.user_id')->join('roles')->on('user_role.role_id', '=', 'roles.id')->where('users.username', '=', $request['username'])->first();
            $data['rules'] = $rules->errors;

            return $this->view('/profile/index', $data);
        }
    }

    public function editPassword() {

        $user = DB::try()->select('users.id, users.username, users.email, roles.name')->from('users')->join('user_role')->on('user_role.user_id', '=', 'users.id')->join('roles')->on('roles.id', '=', 'user_role.role_id')->where('users.username', '=', Session::get('username'))->first();

        $data['user'] = $user;
        $data['rules'] = [];

        return $this->view('/profile/changePassword', $data);
    }

    public function updatePassword($request) {

        $rules = new Rules();

        if($rules->change_password()->validated()) {

            User::update(['id' => $request['id']], [

                'password' => password_hash($request['password'], PASSWORD_DEFAULT),
                'retypePassword' => password_hash($request['password_confirm'], PASSWORD_DEFAULT),
            ]);
        
            redirect('login');

        } else {

            $data['user'] = DB::try()->select('users.id, users.username, users.email, roles.name')->from('users')->join('user_role')->on('users.id', '=', 'user_role.user_id')->join('roles')->on('user_role.role_id', '=', 'roles.id')->where('users.username', '=', $request['username'])->first();
            $data['rules'] = $rules->errors;

            return $this->view('/profile/changePassword', $data);
        }
    }

    public function delete($request) {

        User::delete('username', Session::get('username'));
        UserRole::delete('user_id', $request['id']);

        Session::delete('username');
        Session::delete('user_role');
        Session::delete("logged_in");

        redirect('/');
    }
}  