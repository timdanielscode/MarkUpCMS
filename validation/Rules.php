<?php
/**
 * Use to set error validation messages
 * Here are some examples to use
 * 
 * @author Tim Daniëls
 * @version 1.0
 */

namespace validation;

use src\models\User; 
use core\validation\Validate;

class Rules {

    public $errors;

    public function register_rules($username, $email) {
        
        $validation = new Validate();
        
        $validation->input('username')->as('Username')->rules(['required' => true, 'min' => 5, 'max' => 40, 'special' => true, 'unique' => $username]);
        $validation->input('email')->as('Email')->rules(['required' => true, 'min' => 5, 'max' => 40, 'special' => true, 'unique' => $email]);
        $validation->input('password')->as('Password')->rules(['required' => true, 'min' => 5, 'max' => 50]);
        $validation->input('password_confirm')->as('Password')->rules(['required' => true, 'match' => 'password']);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function login_rules() {
        
        $validation = new Validate();

        $validation->input('username')->as('Username')->rules(['required' => true, 'min' => 5, 'max' => 20, 'special' => true]);
        $validation->input('password')->as('Password')->rules(['required' => true,'min' => 5, 'max' => 50]);

        $this->errors = $validation->errors;
        return $this->errors;
    }

    public function profile_edit($username, $email) {

        $validation = new Validate();

        $validation->input('username')->as('Username')->rules(['required' => true, 'min' => 5, 'max' => 40, 'special' => true, 'unique' => $username]);
        $validation->input('email')->as('Email')->rules(['required' => true, 'min' => 5, 'max' => 40, 'special' => true, 'unique' => $email]);

        $this->errors = $validation->errors;
        return $this;
    }

    public function user_edit() {

        $validation = new Validate();

        $validation->input('username')->as('Username')->rules(['required' => true, 'min' => 5, 'max' => 50]);
        $validation->input('email')->as('Email')->rules(['required' => true, 'min' => 5, 'max' => 50]);

        $this->errors = $validation->errors;
        return $this;
    }

    public function register_rules_admin($username, $email) {
        
        $validation = new Validate();
        
        $validation->input('username')->as('Username')->rules(['required' => true, 'min' => 5, 'max' => 40, 'special' => true, 'unique' => $username]);
        $validation->input('email')->as('Email')->rules(['required' => true, 'min' => 5, 'max' => 40, 'special' => true, 'unique' => $email]);
        $validation->input('password')->as('Password')->rules(['required' => true, 'min' => 5, 'max' => 50]);
        $validation->input('password_confirm')->as('Password')->rules(['required' => true, 'match' => 'password']);
        $validation->input('role')->as('User role')->rules(['required' => true]);
        
        $this->errors = $validation->errors;
        return $this;
    }


    /**
     * @return bool
    */     
    public function validated() {

        if(empty($this->errors) ) {
            return true;
        }
    }


}