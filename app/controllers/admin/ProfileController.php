<?php
                
namespace app\controllers\admin;

use app\controllers\Controller;
use core\Session; 
use core\http\Response;
use validation\Rules;
use app\models\User;
use app\models\UserRole;
use extensions\Auth;
use app\models\WebsiteSlug;
                
class ProfileController extends Controller {

    private $_data;

    /**
     * To show 404 page with 404 status code (on not existing user)
     * 
     * @param string $id _POST user id
     * @return object ProfileController
     */ 
    private function ifExists($id) {

        if(empty(Post::ifRowExists($id)) ) {

            return Response::statusCode(404)->view("/404/404")->data() . exit();
        }
    }

    /**
     * To show the profile index view
     * 
     * @return object ProfileController, Controller
     */
    public function index() { 
    
        $this->_data['user'] = User::getLoggedInUserAndRole(Session::get('username'));
        $this->_data['rules'] = [];

        return $this->view("/admin/profile/index")->data($this->_data);    
    }      

    /**
     * To update user data (details) (on successful validation)
     * 
     * @param array $request _POST id (user id), f_username, email
     * @return object ProfileController, Controller (on failed validation)
     */
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

    /**
     * To update user data (role) (on successful validation)
     * 
     * @param array $request _POST id (user id), role
     * @return object ProfileController, Controller (on failed validation)
     */
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

    /**
     * To show the profile change password view
     * 
     * @return object ProfileController, Controller
     */
    public function editPassword() {

        $this->_data['user'] = User::getLoggedInUserAndRole(Session::get('username'));
        $this->_data['rules'] = [];

        return $this->view('/admin/profile/changePassword')->data($this->_data);
    }

    /**
     * To update user data (password) (on successful validation)
     * 
     * @param array $request _POST id (user id), password, newPassword, retypePassword
     * @return object ProfileController, Controller (on failed validation)
     */
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

    /**
     * To authenticate user (before update user password)
     * 
     * @param object $rules validation rules
     * @param string id _POST user id
     * @param string $password _POST newPassword
     * @return object ProfileController, Controller (on failed authentication)
     */
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

    /**
     * To update user data (password)
     * 
     * @param string id _POST user id
     * @param string $password _POST newPassword
     */
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

    /**
     * To show failed login validation error messages
     * 
     * @return string $message validation error message
     */
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

    /**
     * To redirect to 'login' view (after successfully updated password)
     */
    private function redirectLoginPage() {

        $websiteSlug = WebsiteSlug::getColumns(['slug'], 1);

        if(!empty($websiteSlug) && $websiteSlug !== null) {

            redirect($websiteSlug['slug']);
        } 
    }

    /**
     * To remove a user 
     * 
     * @param array $request _POST id (user id)
     */
    public function delete($request) {

        User::delete('username', Session::get('username'));
        UserRole::delete('user_id', $request['id']);

        Session::delete('username');
        Session::delete('user_role');
        Session::delete("logged_in");

        redirect('/');
    }
}  