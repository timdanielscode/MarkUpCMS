<?php
/**
 * Rules
 * 
 * @author Tim Daniëls
 */
namespace validation;

use core\validation\Validate;

use app\controllers\Controller;
use core\http\Request;
use core\Session;

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

     public function installationRules() {    
                
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

      public function profile_edit_details($username, $email) {

        $validation = new Validate();
  
        $validation->input('f_username')->as('Username')->rules(['required' => true, 'max' => 49, 'special' => true, 'unique' => $username]);
        $validation->input('email')->as('Email')->rules(['required' => true, 'min' => 5, 'max' => 49, 'special' => true, 'unique' => $email]);
  
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

    public function create_post($uniqueTitle) {
        
        $validation = new Validate();
        
        $validation->input('title')->as('Title')->rules(['required' => true, 'max' => 49, 'special' => true, 'unique' => $uniqueTitle]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function update_post($uniqueTitle) {
        
        $validation = new Validate();
        
        $validation->input('title')->as('Title')->rules(['required' => true, 'max' => 49, 'special' => true, 'unique' => $uniqueTitle]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function update_metadata() {

        $validation = new Validate();

        $validation->input('metaTitle')->as('Title')->rules(['max' => 60, 'special' => true]);
        $validation->input('metaDescription')->as('Description')->rules(['max' => 160, 'special' => true]);
        $validation->input('metaKeywords')->as('Keywords')->rules(['max' => 500]);

        $this->errors = $validation->errors;
        return $this;
    }

    public function update_post_slug($unique) {

      $validation = new Validate();

      $validation->input('postSlug')->as('Slug')->rules(['max' => 49, 'special' => true, 'unique' => $unique]);

      $this->errors = $validation->errors;
      return $this;
    }

    public function update_post_category($unique) {

        $validation = new Validate();

        $validation->input('categories')->as('With category assigned slug')->rules(['required' => true, 'unique' => $unique]);
  
        $this->errors = $validation->errors;
        return $this;
    }

    public function remove_post_category($unique) {

        $validation = new Validate();

        $validation->input('submit')->as('Without category assigned slug')->rules(['required' => true, 'unique' => $unique]);
  
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

    public function css($unique) {
        
        $validation = new Validate();
        
        $validation->input('filename')->as('Filename')->rules(['required' => true, 'max' => 29, 'special' => true, 'unique' => $unique]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function js($unique) {
        
        $validation = new Validate();
        
        $validation->input('filename')->as('Filename')->rules(['required' => true, 'max' => 29, 'special' => true, 'unique' => $unique]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function create_menu($unique) {
        
        $validation = new Validate();
        
        $validation->input('title')->as('Title')->rules(['required' => true, 'max' => 49, 'special' => true, 'unique' => $unique]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function menu_update($unique) {

        $validation = new Validate();
        
        $validation->input('title')->as('Title')->rules(['required' => true, 'max' => 49, 'special' => true, 'unique' => $unique]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function media($uniqueFilename) {

        $validation = new Validate();
        
        $validation->input('media_description')->as('Description')->rules(['max' => 99, 'special' => true]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function insert_media_folder() {

        $validation = new Validate();
        
        $validation->input('P_folder')->as('Folder')->rules(['required' => true, 'max' => 49, 'special' => true]);
        
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

    public function update_media_filename($unique) {

        $validation = new Validate();
        
        $validation->input('filename')->as('Filename')->rules(['required' => true, 'max' => 49, 'special' => true, 'unique' => $unique]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function update_media_description() {

        $validation = new Validate();
        
        $validation->input('description')->as('Description')->rules(['required' => true, 'special' => true, 'max' => 99]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function create_category($unique) {
        
        $validation = new Validate();
        
        $validation->input('title')->as('Title')->rules(['required' => true, 'max' => 49, 'special' => true, 'unique' => $unique]);
        $validation->input('description')->as('Description')->rules(['max' => 99, 'special' => true]);
        
        $this->errors = $validation->errors;
        return $this;
    }     

    public function edit_category($unique) {
        
        $validation = new Validate();
        
        $validation->input('title')->as('Title')->rules(['required' => true, 'max' => 49, 'special' => true, 'unique' => $unique]);
        $validation->input('description')->as('Description')->rules(['max' => 99, 'special' => true]);
        
        $this->errors = $validation->errors;
        return $this;
    }     

    public function slug_category() {
        
        $validation = new Validate();
        
        $validation->input('slug')->as('Slug')->rules(['required' => true, 'max' => 49, 'special' => true]);
        
        $this->errors = $validation->errors;
        return $this;
    } 

    public function create_widget($unique) {
        
        $validation = new Validate();
        
        $validation->input('title')->as('Title')->rules(['required' => true, 'max' => 49, 'special' => true, 'unique' => $unique]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function edit_widget($unique) {

        $validation = new Validate();
        
        $validation->input('title')->as('Title')->rules(['required' => true, 'max' => 49, 'special' => true, 'unique' => $unique]);
        
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

    /**
     * Validating validation rules
     * On fail, returning view with extracted validation error rules and if exists request data
     * 
     * @return mixed bool | void
     */
    public function validated($request = null) {

      if(empty($this->errors) ) {

          return true;
      }
  }
}

