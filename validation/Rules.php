<?php

namespace validation;

use core\validation\Validate;

class Rules {

    public $errors;

    public function installation_database($host, $database, $username, $password, $retypePassword, $addedToken, $csrf) {    
                
        $validation = new Validate();
                    
        $validation->input(['host' => $host])->as("Host")->rules(["required" => true, "min" => 5, "max" => 99]);         
        $validation->input(['database' => $database])->as("Database name")->rules(["required" => true, "min" => 5, "max" => 99]);         
        $validation->input(['username' => $username])->as("Database username")->rules(["required" => true, "min" => 5, "max" => 99, "special-ini" => true]); 
        $validation->input(['password' => $password])->as("Password")->rules(["required" => true, "min" => 16, "max" => 200, "special-ini" => true]);          
        $validation->input(['retypePassword' => $retypePassword])->as("Password")->rules(["required" => true, "match" => $password]);
        $validation->input(['token' => $addedToken])->as('Token')->rules(['csrf' => $csrf]);
                    
        $this->errors = $validation->errors;
        return $this;
    }

    public function installation_user($username, $email, $password, $retypePassword, $addedToken, $csrf) {    
                
        $validation = new Validate();
                    
        $validation->input(['username' => $username])->as("Username")->rules(["required" => true, "min" => 5, "max" => 30, "special" => true]);         
        $validation->input(['email' => $email])->as("Email")->rules(["required" => true, "min" => 5, "max" => 30, "special" => true]);         
        $validation->input(['password' => $password])->as("Password")->rules(["required" => true, "min" => 16, "max" => 200]);          
        $validation->input(['retypePassword' => $retypePassword])->as("Password")->rules(["required" => true, "match" => $password]);
        $validation->input(['token' => $addedToken])->as('Token')->rules(['csrf' => $csrf]);
                    
        $this->errors = $validation->errors;
        return $this;
    }

    public function login($username, $password, $addedToken, $csrf) {    
                    
        $validation = new Validate();
                  
        $validation->input(['username' => $username])->as("Username")->rules(["required" => true, "min" => 5, "max" => 30, "special" => true]);       
        $validation->input(['password' => $password])->as("Password")->rules(["required" => true, "min" => 16, "max" => 200]);
        $validation->input(['token' => $addedToken])->as('Token')->rules(['csrf' => $csrf]);
                   
        $this->errors = $validation->errors;
        return $this;
    }  

    public function profile_edit($username, $email, $uniqueUsername, $uniqueEmail) {

        $validation = new Validate();
  
        $validation->input(['f_username' => $username])->as('Username')->rules(['required' => true, 'min' => 5, 'max' => 49, 'special' => true, 'unique' => $uniqueUsername]);
        $validation->input(['email' => $email])->as('Email')->rules(['required' => true, 'min' => 5, 'max' => 49, 'special' => true, 'unique' => $uniqueEmail]);
  
        $this->errors = $validation->errors;
        return $this;
    }

    public function profile_edit_role($role, $adminIds) {

        $validation = new Validate();
  
        $validation->input(['role' => $role])->as('Role')->rules(['required' => true, 'min-one-admin' => $adminIds]);
  
        $this->errors = $validation->errors;
        return $this;
    }

    public function profile_password($password, $newPassword, $retypePassword) {

        $validation = new Validate();

        $validation->input(['password' => $password])->as('Password')->rules(['required' => true, 'min' => 16]);
        $validation->input(['newPassword' => $newPassword])->as('New passoword')->rules(['required' => true, 'min' => 16]);
        $validation->input(['retypePassword' => $retypePassword])->as('Retype password and new password')->rules(['match' => $newPassword]);

        $this->errors = $validation->errors;

        return $this;
    }

    public function user($username, $email, $password, $passwordConfirm, $role, $uniqueUsername, $uniqueEmail) {
        
        $validation = new Validate();
        
        $validation->input(['f_username' => $username])->as('Username')->rules(['required' => true, 'min' => 5, 'max' => 49, 'special' => true, 'unique' => $uniqueUsername]);
        $validation->input(['email' => $email])->as('Email')->rules(['required' => true, 'min' => 5, 'max' => 49, 'special' => true, 'unique' => $uniqueEmail]);
        $validation->input(['password' => $password])->as('Password')->rules(['required' => true, 'min' => 16, 'max' => 200]);
        $validation->input(['password_confirm' => $passwordConfirm])->as('Password')->rules(['required' => true, 'match' => $password]);
        $validation->input(['role' => $role])->as('User role')->rules(['required' => true]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function post($title, $uniqueTitle) {
        
        $validation = new Validate();
        
        $validation->input(['title' => $title])->as('Title')->rules(['required' => true, 'max' => 49, 'special' => true, 'unique' => $uniqueTitle]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function post_slug($slug, $unique) {

        $validation = new Validate();
  
        $validation->input(['postSlug' => $slug])->as('Slug')->rules(['max' => 49, 'special' => true, 'unique' => $unique]);
  
        $this->errors = $validation->errors;
        return $this;
    }

    public function meta($title, $description, $keywords) {

        $validation = new Validate();

        $validation->input(['metaTitle' => $title])->as('Title')->rules(['max' => 60, 'special' => true]);
        $validation->input(['metaDescription' => $description])->as('Description')->rules(['max' => 160, 'special' => true]);
        $validation->input(['metaKeywords' => $keywords])->as('Keywords')->rules(['max' => 500]);

        $this->errors = $validation->errors;
        return $this;
    }

    public function post_update_category($categories, $unique) {

        $validation = new Validate();

        $validation->input(['categories' => $categories])->as('With category assigned slug')->rules(['required' => true, 'unique' => $unique]);
  
        $this->errors = $validation->errors;
        return $this;
    }

    public function post_remove_category($submit, $unique) {

        $validation = new Validate();

        $validation->input(['submit' => $submit])->as('Without category assigned slug')->rules(['required' => true, 'unique' => $unique]);
  
        $this->errors = $validation->errors;
        return $this;
    }

    public function category($title, $description, $unique) {
        
        $validation = new Validate();
        
        $validation->input(['title' => $title])->as('Title')->rules(['required' => true, 'max' => 49, 'special' => true, 'unique' => $unique]);
        $validation->input(['description' => $description])->as('Description')->rules(['max' => 99, 'special' => true]);
        
        $this->errors = $validation->errors;
        return $this;
    }     

    public function category_slug($slug) {
        
        $validation = new Validate();
        
        $validation->input(['slug' => $slug])->as('Slug')->rules(['required' => true, 'max' => 49, 'special' => true]);
        
        $this->errors = $validation->errors;
        return $this;
    } 

    public function menu($title, $unique) {
        
        $validation = new Validate();
        
        $validation->input(['title' => $title])->as('Title')->rules(['required' => true, 'max' => 49, 'special' => true, 'unique' => $unique]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function widget($title, $unique) {
        
        $validation = new Validate();
        
        $validation->input(['title' => $title])->as('Title')->rules(['required' => true, 'max' => 49, 'special' => true, 'unique' => $unique]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function css($filename, $unique) {
        
        $validation = new Validate();
        
        $validation->input(['filename' => $filename])->as('Filename')->rules(['required' => true, 'max' => 29, 'special' => true, 'unique' => $unique]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function js($filename, $unique) {
        
        $validation = new Validate();
        
        $validation->input(['filename' => $filename])->as('Filename')->rules(['required' => true, 'max' => 29, 'special' => true, 'unique' => $unique]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function cdn($title, $unique) {

        $validation = new Validate();
        
        $validation->input(['title' => $title])->as('Title')->rules(['required' => true, 'max' => 49, 'special' => true, 'unique' => $unique]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function media_filename($filename, $unique) {

        $validation = new Validate();
        
        $validation->input(['filename' => $filename])->as('Filename')->rules(['required' => true, 'max' => 49, 'special' => true, 'unique' => $unique]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function media_description($description) {

        $validation = new Validate();
        
        $validation->input(['description' => $description])->as('Description')->rules(['required' => true, 'special' => true, 'max' => 99]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function media_folder($folder) {

        $validation = new Validate();
        
        $validation->input(['P_folder' => $folder])->as('Folder')->rules(['required' => true, 'max' => 49, 'special' => true]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function settings_slug($slug, $unique) {

        $validation = new Validate();
        
        $validation->input(['slug' => $slug])->as('Slug')->rules(['required' => true, 'max' => 49, 'special' => true, 'unique' => $unique]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    /**
     * To check for failed validation errors
     * 
     * @return mixed bool | void
     */
    public function validated($request = null) {

        if(empty($this->errors) ) {

            return true;
        }
    }
}

