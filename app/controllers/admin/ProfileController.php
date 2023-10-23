<?php
                
  namespace app\controllers\admin;

  use app\controllers\Controller;
  use core\Session; 
  use core\http\Response;
  use database\DB;
  use validation\Rules;
  use app\models\User;
  use app\models\UserRole;
  use core\Csrf;
  use extensions\Auth;
                
  class ProfileController extends Controller {
    
    public function index() { 
      
        $user = DB::try()->select('users.id, users.username, users.email, roles.name')->from('users')->join('user_role')->on('user_role.user_id', '=', 'users.id')->join('roles')->on('roles.id', '=', 'user_role.role_id')->where('users.username', '=', Session::get('username'))->first();

        $data['rules'] = [];
        $data['user'] = $user;

        return $this->view("/admin/profile/index", $data);    
    }      

    public function updateDetails($request) {

        if(submitted("submit") === true && Csrf::validate(Csrf::token('get'), post('token')) === true ) {

            $uniqueUsername = DB::try()->select('username')->from('users')->where('username', '=', $request['f_username'])->and('username', '!=', Session::get('username'))->fetch();
            $uniqueEmail = DB::try()->select('email')->from('users')->where('email', '=', $request['email'])->and('username', '!=', Session::get('username'))->fetch();

            $rules = new Rules();

            if($rules->profile_edit_details($uniqueUsername, $uniqueEmail)->validated()) {

                User::update(['username' => Session::get('username')],[

                    'username'  =>  $request['f_username'],
                    'email' => $request['email']
                ]);

                Session::set('username', $request['f_username']);
                Session::set('success', 'You have successfully updated the your details!');
                redirect('/admin/profile/' . $request['f_username']);

            } else {

                $data['user'] = DB::try()->select('users.id, users.username, users.email, roles.name')->from('users')->join('user_role')->on('users.id', '=', 'user_role.user_id')->join('roles')->on('user_role.role_id', '=', 'roles.id')->where('users.username', '=', Session::get('username'))->first();
                $data['rules'] = $rules->errors;
                
                return $this->view("/admin/profile/index", $data); 
            }
        }
    }

    public function updateRole($request) {

        if(submitted("submit") === true && Csrf::validate(Csrf::token('get'), post('token')) === true ) {

            $rules = new Rules();
            $adminIds = UserRole::where('role_id', '=', 2);

            if($rules->profile_edit_role($adminIds)->validated()) {

                UserRole::update(['user_id' => $request['id']], [

                    'role_id'  =>  1,
                    'user_id' => $request['id']
                ]);
            
                Session::set('user_role', 'normal');
                Session::set('success', 'You have successfully updated your user role!');
                redirect('/admin/profile/' . Session::get('username'));

            } else {

                $data['user'] = DB::try()->select('users.id, users.username, users.email, roles.name')->from('users')->join('user_role')->on('users.id', '=', 'user_role.user_id')->join('roles')->on('user_role.role_id', '=', 'roles.id')->where('users.username', '=', Session::get('username'))->first();
                $data['rules'] = $rules->errors;

                return $this->view('/admin/profile/index', $data);
            }
        }
    }

    public function editPassword() {

        $user = DB::try()->select('users.id, users.username, users.email, roles.name')->from('users')->join('user_role')->on('user_role.user_id', '=', 'users.id')->join('roles')->on('roles.id', '=', 'user_role.role_id')->where('users.username', '=', Session::get('username'))->first();

        $data['user'] = $user;
        $data['rules'] = [];

        return $this->view('/admin/profile/changePassword', $data);
    }

    public function updatePassword($request) {

        $rules = new Rules();

        if($rules->updatePassword()->validated() === true) {

            $this->authenticate($rules, $request['id'], $request['newPassword']);
        } else {

            $data['user'] = DB::try()->select('users.id, users.username, users.email, roles.name')->from('users')->join('user_role')->on('users.id', '=', 'user_role.user_id')->join('roles')->on('user_role.role_id', '=', 'roles.id')->where('users.username', '=', Session::get('username'))->first();
            $data['rules'] = $rules->errors;

            return $this->view('admin/profile/changePassword', $data);
        }
    }

    private function authenticate($rules, $id, $password) {

        if(Auth::authenticate() ) {

            $this->updateCurrentPassword($id, $password);
        } else {

            $data['user'] = DB::try()->select('users.id, users.username, users.email, roles.name')->from('users')->join('user_role')->on('users.id', '=', 'user_role.user_id')->join('roles')->on('user_role.role_id', '=', 'roles.id')->where('users.username', '=', Session::get('username'))->first();
            $rules->errors[] = ['retypePassword' => $this->getFailedLoginAttemptMessages()];
            $data['rules'] = $rules->errors;

            return $this->view('/admin/profile/changePassword', $data);
        }
    }

    private function updateCurrentPassword($id, $password) {

        if(submitted("submit") === true && Csrf::validate(Csrf::token('get'), post('token')) === true ) {

            if(!empty($id) && $id !== null && !empty($password) && $password !== null) {

                User::update(['id' => $id], [

                    'password' => password_hash($password, PASSWORD_DEFAULT)
                ]);
            }
        
            Session::delete('logged_in');
            Session::delete('username');
            Session::delete('user_role');
            
            $this->redirectLoginPage();
        }
    }

    private function getFailedLoginAttemptMessages() {

        if(Session::exists('failed_login_attempt') === true && Session::exists('failed_login_attempts_timestamp') === false) {

            $message = "Incorrect credentials.";
        } else if(Session::exists('failed_login_attempt') === true && Session::exists('failed_login_attempts_timestamp') === true) {
            $message = "Too many failed attempts.";
        } else {
            $message = "";
        }

        return $message;
    }

    private function redirectLoginPage() {

        $websiteSlug = DB::try()->select('slug')->from('websiteSlug')->first();

        if(!empty($websiteSlug) && $websiteSlug !== null) {

            redirect($websiteSlug['slug']);
        } else {
            redirect('/login');
        }
    }

    public function delete($request) {

        if(submitted("delete") === true && Csrf::validate(Csrf::token('get'), post('token')) === true ) {

            User::delete('username', Session::get('username'));
            UserRole::delete('user_id', $request['id']);

            Session::delete('username');
            Session::delete('user_role');
            Session::delete("logged_in");

            redirect('/');
        }
    }
}  