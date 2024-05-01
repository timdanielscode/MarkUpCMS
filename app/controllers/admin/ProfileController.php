<?php
                
namespace app\controllers\admin;

use core\Session; 
use core\http\Response;
use validation\Rules;
use app\models\User;
use app\models\UserRole;
use extensions\Auth;
use app\models\WebsiteSlug;
                
class ProfileController extends \app\controllers\Controller {

    private $_data;

    /**
     * To show 404 page with 404 status code (on not existing user)
     * 
     * @param string $id _POST user id
     * @return object ProfileController
     */ 
    private function ifExists($id) {

        if(empty(User::ifRowExists($id)) ) {

            return Response::statusCode(404)->view("/404/404")->data() . exit();
        }
    }

    /**
     * To show the profile index view
     * 
     * @return object ProfileController, Controller
     */
    public function index($request) { 

        $this->ifExists($request['id']);
    
        $this->_data['user'] = User::getLoggedInUserAndRole(Session::get('username'));
        $this->_data['id'] = $request['id'];
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

        if($rules->profile_edit($request, User::checkUniqueUsername($request["f_username"], $id), User::checkUniqueEmail($request['email'], $id))->validated()) {

            User::update(['id' => $id], [

                'username'  =>  $request['f_username'],
                'email' => $request['email']
            ]);

            Session::set('username', $request['f_username']);
            Session::set('success', 'You have successfully updated your profile details!');

            redirect('/admin/profile/' . $request['id']);

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

        $id = $request['id'];

        $rules = new Rules();

        if($rules->profile_edit_role($request['role'], User::where(['role_id' => 1]))->validated()) {

            User::update(['id' => $id], [

                'role_id'  =>  1,
            ]);
            
            Session::set('user_role', null);
            Session::set('success', 'You have successfully updated your user role!');

            redirect("/admin/profile/$id");

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
    public function editPassword($request) {

        $this->ifExists($request['id']);

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

        if($rules->profile_password($request)->validated() === true && Auth::success(["username" => $request]) === true) {

            User::update(['username' => Session::get('username')], [

                'password' => password_hash($request['newPassword'], PASSWORD_DEFAULT)
            ]);

            Session::delete('logged_in');
            Session::delete('username');
            Session::delete('user_role');

            $websiteSlug = WebsiteSlug::getColumns(['slug'], 1);
            redirect($websiteSlug['slug']) . exit();

        } else {

            $this->_data['username'] = Session::get('username');
            $this->_data['user'] = User::getLoggedInUserAndRole(Session::get('username'));
            $this->_data['rules'] = $rules->errors;

            return $this->view('admin/profile/changePassword')->data($this->_data);
        }
    }

    /**
     * To remove a user 
     * 
     * @param array $request _POST id (user id)
     */
    public function delete() {

        if(count(User::where(['role_id' => '1'])) > 1) {

            User::delete('username', Session::get('username'));

            Session::delete('username');
            Session::delete('user_role');
            Session::delete("logged_in");
    
            redirect('/');
        } else {

            $rules = new Rules();
            $rules->errors[] = ['role' => 'There should be at least one admin.'];
            $this->_data['user'] = User::getLoggedInUserAndRole(Session::get('username'));
            $this->_data['rules'] = $rules->errors;

            return $this->view('/admin/profile/index')->data($this->_data);
        }
    }
}  