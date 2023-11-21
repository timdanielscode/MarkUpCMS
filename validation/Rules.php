<?php
/**
 * Rules
 * 
 * @author Tim Daniëls
 */
namespace validation;

use core\validation\Validate;


class Rules {

    public $errors;

    /**
     * You can add the validation rule methods right here
     * 
     * https://indy-php.com/docs/validation
     * 
     * https://indy-php.com/docs/
     * Chain input method on instance variable and add html input name as argument
     * Chain as method to input and add alias as argument
     * Chain rules method to as method and add array of validation rules as argument
     * 
     * Set property $this->errors to instance errors property ($this->errors = $validation->errors)
     * Where $validation = instance Validate
     * return $this
     */

    public function installationDatabase() {    
                
        $validation = new Validate();
                    
        $validation->input("host")->as("Host")->rules(["required" => true, "min" => "1", "max" => "99"]);         
        $validation->input("database")->as("Database name")->rules(["required" => true, "min" => "1", "max" => "99"]);         
        $validation->input("username")->as("Database username")->rules(["required" => true, "min" => "1", "max" => "99", "special-ini" => true]); 
        $validation->input("password")->as("Password")->rules(["required" => true, "min" => "10", "max" => "200", "special-ini" => true]);          
        $validation->input("retypePassword")->as("Password")->rules(["required" => true, "match" => "password"]);
                    
        $this->errors = $validation->errors;
        return $this;
    }

    public function installationStoreUser() {    
                
        $validation = new Validate();
                    
        $validation->input("username")->as("Username")->rules(["required" => true, "min" => "6", "max" => "30", "special" => true]);         
        $validation->input("email")->as("Email")->rules(["required" => true, "min" => "6", "max" => "30", "special" => true]);         
        $validation->input("password")->as("Password")->rules(["required" => true, "min" => "16", "max" => "200"]);          
        $validation->input("retypePassword")->as("Password")->rules(["required" => true, "match" => "password"]);
                    
        $this->errors = $validation->errors;
        return $this;
    }

    public function registerRules() {    
                
        $validation = new Validate();
                  
        $validation->input("username")->as("Username")->rules(["required" => true, "min" => "6", "max" => "30", "special" => true]);         
        $validation->input("email")->as("Email")->rules(["required" => true, "min" => "6", "max" => "30", "special" => true]);        
        $validation->input("password")->as("Password")->rules(["required" => true, "min" => "16", "max" => "200"]);   
        $validation->input("retypePassword")->as("Password")->rules(["required" => true, "match" => "password"]);
                   
        $this->errors = $validation->errors;
        return $this;
      }

    public function loginRules() {    
                    
        $validation = new Validate();
                  
        $validation->input("username")->as("Username")->rules(["required" => true, "min" => "6", "max" => "30", "special" => true]);       
        $validation->input("password")->as("Password")->rules(["required" => true, "min" => "2", "max" => "200"]);
                   
        $this->errors = $validation->errors;
        return $this;
    }  

    public function profile_edit_role($adminIds) {

        $validation = new Validate();
  
        $validation->input('role')->as('Role')->rules(['required' => true, 'min-one-admin' => $adminIds]);
  
        $this->errors = $validation->errors;
        return $this;
    }

    public function change_password() {

        $validation = new Validate();
  
        $validation->input("password")->as("Password")->rules(["required" => true, "min" => "16", "max" => "60"]);   
        $validation->input("retypePassword")->as("Password")->rules(["required" => true, "match" => "password"]);
  
        $this->errors = $validation->errors;
        return $this;

    }

    public function create_post($title, $uniqueTitle) {
        
        $validation = new Validate();
        
        $validation->input(['title' => $title])->as('Title')->rules(['required' => true, 'max' => 49, 'special' => true, 'unique' => $uniqueTitle]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function update_post($title, $uniqueTitle) {
        
        $validation = new Validate();
        
        $validation->input(['title' => $title])->as('Title')->rules(['required' => true, 'max' => 49, 'special' => true, 'unique' => $uniqueTitle]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function update_metadata($title, $description, $keywords) {

        $validation = new Validate();

        $validation->input(['metaTitle' => $title])->as('Title')->rules(['max' => 60, 'special' => true]);
        $validation->input(['metaDescription' => $description])->as('Description')->rules(['max' => 160, 'special' => true]);
        $validation->input(['metaKeywords' => $keywords])->as('Keywords')->rules(['max' => 500]);

        $this->errors = $validation->errors;
        return $this;
    }

    public function update_post_slug($slug, $unique) {

      $validation = new Validate();

      $validation->input(['postSlug' => $slug])->as('Slug')->rules(['max' => 49, 'special' => true, 'unique' => $unique]);

      $this->errors = $validation->errors;
      return $this;
    }

    public function update_post_category($categories, $unique) {

        $validation = new Validate();

        $validation->input(['categories' => $categories])->as('With category assigned slug')->rules(['required' => true, 'unique' => $unique]);
  
        $this->errors = $validation->errors;
        return $this;
    }

    public function remove_post_category($submit, $unique) {

        $validation = new Validate();

        $validation->input(['submit' => $submit])->as('Without category assigned slug')->rules(['required' => true, 'unique' => $unique]);
  
        $this->errors = $validation->errors;
        return $this;
    }

    public function user_create($username, $email) {
        
        $validation = new Validate();
        
        $validation->input('f_username')->as('Username')->rules(['required' => true, 'max' => 49, 'special' => true, 'unique' => $username]);
        $validation->input('email')->as('Email')->rules(['required' => true, 'min' => 5, 'max' => 49, 'special' => true, 'unique' => $email]);
        $validation->input('password')->as('Password')->rules(['required' => true, 'min' => 16, 'max' => 249]);
        $validation->input('password_confirm')->as('Password')->rules(['required' => true, 'match' => 'password']);
        $validation->input('role')->as('User role')->rules(['required' => true]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function user_edit($username, $email) {

      $validation = new Validate();

      $validation->input('f_username')->as('Username')->rules(['required' => true, 'max' => 49, 'special' => true, 'unique' => $username]);
      $validation->input('email')->as('Email')->rules(['required' => true, 'min' => 5, 'max' => 49, 'special' => true, 'unique' => $email]);

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

    public function create_menu($title, $unique) {
        
        $validation = new Validate();
        
        $validation->input(['title' => $title])->as('Title')->rules(['required' => true, 'max' => 49, 'special' => true, 'unique' => $unique]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function menu_update($title, $unique) {

        $validation = new Validate();
        
        $validation->input(['title' => $title])->as('Title')->rules(['required' => true, 'max' => 49, 'special' => true, 'unique' => $unique]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function media($uniqueFilename) {

        $validation = new Validate();
        
        $validation->input('media_description')->as('Description')->rules(['max' => 99, 'special' => true]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function insert_media_folder($folder) {

        $validation = new Validate();
        
        $validation->input(['P_folder' => $folder])->as('Folder')->rules(['required' => true, 'max' => 49, 'special' => true]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function media_update_title_description() {

        $validation = new Validate();
        
        $validation->input('title')->as('Title')->rules(['required' => true, 'max' => 49, 'special' => true]);
        $validation->input('description')->as('Description')->rules(['max' => 99, 'special' => true]);

        $this->errors = $validation->errors;
        return $this;
    }

    public function update_media_filename($filename, $unique) {

        $validation = new Validate();
        
        $validation->input(['filename' => $filename])->as('Filename')->rules(['required' => true, 'max' => 49, 'special' => true, 'unique' => $unique]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function update_media_description($description) {

        $validation = new Validate();
        
        $validation->input(['description' => $description])->as('Description')->rules(['required' => true, 'special' => true, 'max' => 99]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function create_category($title, $description, $unique) {
        
        $validation = new Validate();
        
        $validation->input(['title' => $title])->as('Title')->rules(['required' => true, 'max' => 49, 'special' => true, 'unique' => $unique]);
        $validation->input(['description' => $description])->as('Description')->rules(['max' => 99, 'special' => true]);
        
        $this->errors = $validation->errors;
        return $this;
    }     

    public function edit_category($title, $description, $unique) {
        
        $validation = new Validate();
        
        $validation->input(['title' => $title])->as('Title')->rules(['required' => true, 'max' => 49, 'special' => true, 'unique' => $unique]);
        $validation->input(['description' => $description])->as('Description')->rules(['max' => 99, 'special' => true]);
        
        $this->errors = $validation->errors;
        return $this;
    }     

    public function slug_category($slug) {
        
        $validation = new Validate();
        
        $validation->input(['slug' => $slug])->as('Slug')->rules(['required' => true, 'max' => 49, 'special' => true]);
        
        $this->errors = $validation->errors;
        return $this;
    } 

    public function create_widget($title, $unique) {
        
        $validation = new Validate();
        
        $validation->input(['title' => $title])->as('Title')->rules(['required' => true, 'max' => 49, 'special' => true, 'unique' => $unique]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function edit_widget($title, $unique) {

        $validation = new Validate();
        
        $validation->input(['title' => $title])->as('Title')->rules(['required' => true, 'max' => 49, 'special' => true, 'unique' => $unique]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function update_website_slug($unique) {

        $validation = new Validate();
        
        $validation->input('slug')->as('Slug')->rules(['required' => true, 'max' => 49, 'special' => true, 'unique' => $unique]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function create_cdn($unique) {

        $validation = new Validate();
        
        $validation->input('title')->as('Title')->rules(['required' => true, 'max' => 49, 'special' => true, 'unique' => $unique]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function edit_cdn($unique) {

        $validation = new Validate();
        
        $validation->input('title')->as('Title')->rules(['required' => true, 'max' => 49, 'special' => true, 'unique' => $unique]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function updatePassword() {

        $validation = new Validate();

        $validation->input('password')->as('Password')->rules(['required' => true, 'min' => 5]);
        $validation->input('newPassword')->as('New passoword')->rules(['required' => true, 'min' => 5]);
        $validation->input('retypePassword')->as('Retype password and new password')->rules(['match' => 'newPassword']);

        $this->errors = $validation->errors;

        return $this;
    }

    /**
     * Checking if empty errors
     * 
     * @return mixed bool | void
     */
    public function validated($request = null) {

      if(empty($this->errors) ) {

          return true;
      }
  }
}

