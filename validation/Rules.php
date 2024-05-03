<?php

namespace validation;

use core\validation\Validate;

class Rules {

    public $errors;

    public function installation_user($request, $token) {    
                
        $validation = new Validate();
                    
        $validation->input(['username' => $request['username']])->as("Username")->rules(["required" => true, "min" => 5, "max" => 200, "special" => true]);         
        $validation->input(['email' => $request['email']])->as("Email")->rules(["required" => true, "min" => 5, "max" => 200, "special" => true]);         
        $validation->input(['password' => $request['password']])->as("Password")->rules(["required" => true, "min" => 16, "max" => 200, "special" => true]);          
        $validation->input(['retypePassword' => $request['retypePassword']])->as("Password")->rules(["match" => $request['password']]);
        $validation->input(['token' => $request['token']])->as('Token')->rules(['csrf' => $token]);
                    
        $this->errors = $validation->errors;
        return $this;
    }

    public function login($request, $token) {    
                    
        $validation = new Validate();
                  
        $validation->input(['username' => $request['username']])->as("Username")->rules(["required" => true, "min" => 5, "max" => 200, "special" => true]);       
        $validation->input(['password' => $request['password']])->as("Password")->rules(["required" => true, "min" => 16, "max" => 200, "special" => true]);
        $validation->input(['token' => $request['token']])->as('Token')->rules(['csrf' => $token]);
                   
        $this->errors = $validation->errors;
        return $this;
    }  

    public function profile_edit($request, $username, $email) {

        $validation = new Validate();
  
        $validation->input(['f_username' => $request['f_username']])->as('Username')->rules(['required' => true, 'min' => 5, 'max' => 200, 'special' => true, 'unique' => $username]);
        $validation->input(['email' => $request['email']])->as('Email')->rules(['required' => true, 'min' => 5, 'max' => 200, 'special' => true, 'unique' => $email]);
  
        $this->errors = $validation->errors;
        return $this;
    }

    public function profile_edit_role($role, $adminIds) {

        $validation = new Validate();
  
        $validation->input(['role' => $role])->as('Role')->rules(['required' => true, 'min-one-admin' => $adminIds]);
  
        $this->errors = $validation->errors;
        return $this;
    }

    public function profile_password($request) {

        $validation = new Validate();

        $validation->input(['password' => $request['password']])->as('Password')->rules(['required' => true, 'min' => 16, 'max' => 200, "special" => true]);
        $validation->input(['newPassword' => $request['newPassword']])->as('New passoword')->rules(['required' => true, 'min' => 16, 'max' => 200, "special" => true]);
        $validation->input(['retypePassword' => $request['retypePassword']])->as('Retype password and new password')->rules(['match' => $request['newPassword']]);

        $this->errors = $validation->errors;

        return $this;
    }

    public function user($request, $username, $email) {
        
        $validation = new Validate();
        
        $validation->input(['f_username' => $request['f_username']])->as('Username')->rules(['required' => true, 'min' => 5, 'max' => 200, 'special' => true, 'unique' => $username]);
        $validation->input(['email' => $request['email']])->as('Email')->rules(['required' => true, 'min' => 5, 'max' => 49, 'special' => true, 'unique' => $email]);
        $validation->input(['password' => $request['password']])->as('Password')->rules(['required' => true, 'min' => 16, 'max' => 200, "special" => true]);
        $validation->input(['retypePassword' => $request['retypePassword']])->as('Password')->rules(['required' => true, 'match' => $request['password']]);
        $validation->input(['role' => $request['role']])->as('User role')->rules(['required' => true]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function user_edit($request, $username, $email) {

                
        $validation = new Validate();
        
        $validation->input(['f_username' => $request['f_username']])->as('Username')->rules(['required' => true, 'min' => 5, 'max' => 200, 'special' => true, 'unique' => $username]);
        $validation->input(['email' => $request['email']])->as('Email')->rules(['required' => true, 'min' => 5, 'max' => 49, 'special' => true, 'unique' => $email]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function page($request, $title) {
        
        $validation = new Validate();
        
        $validation->input(['title' => $request['title']])->as('Title')->rules(['required' => true, 'max' => 49, 'alphanumeric' => true, 'unique' => $title]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function page_slug($request, $slug) {

        $validation = new Validate();
  
        $validation->input(['pageSlug' => $request['pageSlug']])->as('Slug')->rules(['max' => 200, 'unique' => $slug]);
  
        $this->errors = $validation->errors;
        return $this;
    }

    public function seo($request) {

        $validation = new Validate();

        $validation->input(['metaTitle' => $request['metaTitle']])->as('Title')->rules(['max' => 200, 'alphanumeric' => true]);
        $validation->input(['metaDescription' => $request['metaDescription']])->as('Description')->rules(['max' => 200]);
        $validation->input(['metaKeywords' => $request['metaKeywords']])->as('Keywords')->rules(['max' => 200]);

        $this->errors = $validation->errors;
        return $this;
    }

    public function page_update_category($categories, $slug) {

        $validation = new Validate();

        $validation->input(['categories' => $categories['categories']])->as('With category assigned slug')->rules(['required' => true, 'unique' => $slug]);
  
        $this->errors = $validation->errors;
        return $this;
    }

    public function page_remove_category($request, $slug) {

        $validation = new Validate();

        $validation->input(['submit' => $request['submit']])->as('Without category assigned slug')->rules(['required' => true, 'unique' => $slug]);
  
        $this->errors = $validation->errors;
        return $this;
    }

    public function category($request, $title) {
        
        $validation = new Validate();
        
        $validation->input(['title' => $request['title']])->as('Title')->rules(['required' => true, 'max' => 49, 'alphanumeric' => true, 'unique' => $title]);
        
        $this->errors = $validation->errors;
        return $this;
    }     

    public function category_slug($slug) {
        
        $validation = new Validate();
        
        $validation->input(['slug' => $slug])->as('Slug')->rules(['required' => true, 'max' => 49, 'alphanumeric' => true]);
        
        $this->errors = $validation->errors;
        return $this;
    } 

    public function menu($request, $title) {
        
        $validation = new Validate();
        
        $validation->input(['title' => $request['title']])->as('Title')->rules(['required' => true, 'max' => 49, 'alphanumeric' => true, 'unique' => $title]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function widget($request, $title) {
        
        $validation = new Validate();
        
        $validation->input(['title' => $request['title']])->as('Title')->rules(['required' => true, 'max' => 49, 'alphanumeric' => true, 'unique' => $title]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function css($request, $filename) {
        
        $validation = new Validate();
        
        $validation->input(['filename' => $request['filename']])->as('Filename')->rules(['required' => true, 'max' => 49, 'alphanumeric' => true, 'unique' => $filename]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function js($request, $filename) {
        
        $validation = new Validate();
        
        $validation->input(['filename' => $request['filename']])->as('Filename')->rules(['required' => true, 'max' => 49, 'alphanumeric' => true, 'unique' => $filename]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function meta($request, $title) {

        $validation = new Validate();
        
        $validation->input(['title' => $request['title']])->as('Title')->rules(['required' => true, 'max' => 49, 'alphanumeric' => true, 'unique' => $title]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function media_filename($request, $filename) {

        $validation = new Validate();
        
        $validation->input(['filename' => $request['filename']])->as('Filename')->rules(['required' => true, 'max' => 49, 'alphanumeric' => true, 'unique' => $filename]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function media_description($description) {

        $validation = new Validate();
        
        $validation->input(['description' => $description])->as('Description')->rules(['required' => true, 'alphanumeric' => true, 'max' => 200]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function media_folder($folder) {

        $validation = new Validate();
        
        $validation->input(['P_folder' => $folder])->as('Folder')->rules(['required' => true, 'max' => 49, 'alphanumeric' => true]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function settings_slug($request, $slug) {

        $validation = new Validate();
        
        $validation->input(['slug' => $request['slug']])->as('Slug')->rules(['required' => true, 'max' => 200, 'alphanumeric' => true, 'unique' => $slug]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    /**
     * To check for failed validation errors
     * 
     * @return mixed bool
     */
    public function validated() {

        if(empty($this->errors) ) {

            return true;
        }
    }
}

