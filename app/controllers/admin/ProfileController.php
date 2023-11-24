<?php
                
  namespace app\controllers\admin;

  use app\controllers\Controller;
  use core\Session; 
  use core\http\Response;
  use validation\Rules;
  use app\models\User;
  use app\models\UserRole;
  use core\Csrf;
  use extensions\Auth;
  use app\models\WebsiteSlug;
                
  class ProfileController extends Controller {

    private $_data;

    private function ifExists($id) {

        if(empty(Post::ifRowExists($id)) ) {

            return Response::statusCode(404)->view("/404/404")->data() . exit();
        }
    }

    public function index() { 
    
        $this->_data['user'] = User::getLoggedInUserAndRole(Session::get('username'));
        $this->_data['rules'] = [];

        return $this->view("/admin/profile/index")->data($this->_data);    
    }      

    public function updateDetails($request) {

        $id = $request['id'];

        $rules = new Rules();

        if($rules->user_edit(User::checkUniqueUsername($request["f_username"], $id), User::checkUniqueEmail($request['email'], $id))->validated()) {

            User::update(['id' => $id], [

                'username'  =>  $request['f_username'],
                'email' => $request['email']
            ]);

            Session::set('username', $request['f_username']);
            Session::set('success', 'You have successfully updated your profile details!');
            redirect('/admin/profile/' . $request['f_username']);

        } else {

            $this->_data['user'] = User::getLoggedInUserAndRole(Session::get('username'));
            $this->_data['rules'] = $rules->errors;
                
            return $this->view("/admin/profile/index")->data($this->_data); 
        }
    }

    public function updateRole($request) {

        $rules = new Rules();

        if($rules->profile_edit_role($request['role'], UserRole::where(['role_id' => 2]))->validated()) {

            UserRole::update(['user_id' => $request['id']], [

                'role_id'  =>  1,
                'user_id' => $request['id']
            ]);
            
            Session::set('user_role', 'normal');
            Session::set('success', 'You have successfully updated your user role!');
            redirect('/admin/profile/' . Session::get('username'));

        } else {

            $this->_data['user'] = User::getLoggedInUserAndRole(Session::get('username'));
            $this->_data['rules'] = $rules->errors;

            return $this->view('/admin/profile/index')->data($this->_data);
        }
    }

    public function editPassword() {

        $this->_data['user'] = User::getLoggedInUserAndRole(Session::get('username'));
        $this->_data['rules'] = [];

        return $this->view('/admin/profile/changePassword')->data($this->_data);
    }

    public function updatePassword($request) {

        $rules = new Rules();

        if($rules->profile_password($request['password'], $request['newPassword'], $request['retypePassword'])->validated() === true) {

            $this->authenticate($rules, $request['id'], $request['newPassword']);
        } else {

            $this->_data['user'] = User::getLoggedInUserAndRole(Session::get('username'));
            $this->_data['rules'] = $rules->errors;

            return $this->view('admin/profile/changePassword')->data($this->_data);
        }
    }

    private function authenticate($rules, $id, $password) {

        if(Auth::authenticate() ) {

            $this->updateCurrentPassword($id, $password);
        } else {

            $this->_data['user'] = User::getLoggedInUserAndRole(Session::get('username'));
            $rules->errors[] = ['retypePassword' => $this->getFailedLoginAttemptMessages()];
            $this->_data['rules'] = $rules->errors;

            return $this->view('/admin/profile/changePassword')->data($this->_data);
        }
    }

    private function updateCurrentPassword($id, $password) {

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

        $websiteSlug = WebsiteSlug::getColumns(['slug'], 1);

        if(!empty($websiteSlug) && $websiteSlug !== null) {

            redirect($websiteSlug['slug']);
        } else {
            redirect('/login');
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