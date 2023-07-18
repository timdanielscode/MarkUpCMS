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
                
      $validation->input("username")->as("Username")->rules([
                
        "required" => true, 
        "min" => "6", 
        "max" => "30", 
        "special" => true
                
      ]);
                
      $validation->input("email")->as("Email")->rules([
                
        "required" => true, 
        "min" => "6", 
        "max" => "30", 
        "special" => true
                
      ]);
                
      $validation->input("password")->as("Password")->rules([
                
        "required" => true, 
        "min" => "6", 
        "max" => "60"
                
      ]);
                 
      $validation->input("retypePassword")->as("Password")->rules([
                
        "required" => true, 
        "match" => "password"
                
      ]);
                 
      $this->errors = $validation->errors;
      return $this;
    }

     public function registerRules() {    
                
        $validation = new Validate();
                  
        $validation->input("username")->as("Username")->rules([
                  
          "required" => true, 
          "min" => "6", 
          "max" => "30", 
          "special" => true
                  
        ]);
                  
        $validation->input("email")->as("Email")->rules([
                  
          "required" => true, 
          "min" => "6", 
          "max" => "30", 
          "special" => true
                  
        ]);
                  
        $validation->input("password")->as("Password")->rules([
                  
          "required" => true, 
          "min" => "6", 
          "max" => "60"
                  
        ]);
                   
        $validation->input("retypePassword")->as("Password")->rules([
                  
          "required" => true, 
          "match" => "password"
                  
        ]);
                   
        $this->errors = $validation->errors;
        return $this;
      }

      public function loginRules() {    
                    
        $validation = new Validate();
                  
        $validation->input("username")->as("Username")->rules([
                  
          "required" => true,             
          "min" => "6",          
          "max" => "30",          
          "special" => true            
  
        ]);
                  
        $validation->input("password")->as("Password")->rules([
                  
          "required" => true,              
          "min" => "6",              
          "max" => "60"
                  
        ]);
                   
        $this->errors = $validation->errors;
        return $this;
      }  

    public function profile_edit($username, $email) {

        $validation = new Validate();

        $validation->input('username')->as('Username')->rules(['required' => true, 'min' => 5, 'max' => 40, 'special' => true, 'unique' => $username]);
        $validation->input('email')->as('Email')->rules(['required' => true, 'min' => 5, 'max' => 40, 'special' => true, 'unique' => $email]);

        $this->errors = $validation->errors;
        return $this;
    }

    /*public function user_edit($username, $email) {

        $validation = new Validate();

        $validation->input('username')->as('Username')->rules(['required' => true, 'min' => 5, 'max' => 50, 'unique' => $username]);
        $validation->input('email')->as('Email')->rules(['required' => true, 'min' => 5, 'max' => 50, 'unique', $email]);

        $this->errors = $validation->errors;
        return $this;
    }*/


    public function user_edit($username, $email) {

      $validation = new Validate();

      $validation->input('f_username')->as('Username')->rules(['required' => true, 'min' => 5, 'max' => 50, 'unique' => $username]);
      $validation->input('email')->as('Email')->rules(['required' => true, 'min' => 5, 'max' => 50, 'unique' => $email]);
      $validation->input('role')->as('Role')->rules(['required' => true]);

      $this->errors = $validation->errors;
      return $this;
    }

    public function register_rules_admin($username, $email) {
        
        $validation = new Validate();
        
        $validation->input('f_username')->as('Username')->rules(['required' => true, 'min' => 5, 'max' => 40, 'special' => true, 'unique' => $username]);
        $validation->input('email')->as('Email')->rules(['required' => true, 'min' => 5, 'max' => 40, 'special' => true, 'unique' => $email]);
        $validation->input('password')->as('Password')->rules(['required' => true, 'min' => 5, 'max' => 50]);
        $validation->input('password_confirm')->as('Password')->rules(['required' => true, 'match' => 'password']);
        $validation->input('role')->as('User role')->rules(['required' => true]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function create_post() {
        
        $validation = new Validate();
        
        $validation->input('title')->as('Title')->rules(['required' => true, 'min' => 5, 'max' => 50, 'special' => true]);
        $validation->input('body')->as('Body')->rules(['required' => true, 'min' => 5]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function update_post() {
        
        $validation = new Validate();
        
        $validation->input('title')->as('Title')->rules(['required' => true, 'min' => 5, 'max' => 50, 'special' => true]);
        $validation->input('body')->as('Body')->rules(['required' => true, 'min' => 5]);
        $validation->input('slug')->as('Slug')->rules(['required' => true, 'first' => '/']);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function css() {
        
        $validation = new Validate();
        
        $validation->input('filename')->as('Filename')->rules(['required' => true, 'min' => 5, 'max' => 40, 'special' => true]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function js() {
        
        $validation = new Validate();
        
        $validation->input('filename')->as('Filename')->rules(['required' => true, 'min' => 5, 'max' => 10, 'special' => true]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function media($uniqueFilename) {

        $validation = new Validate();
        
        $validation->input('media_title')->as('Title')->rules(['required' => true, 'min' => 2, 'max' => 40, 'special' => true]);
        $validation->input('media_description')->as('Description')->rules(['required' => true, 'min' => 1, 'max' => 100, 'special' => true]);
        $validation->input('file')->as('File')->rules(['unique' => $uniqueFilename, 'selected' => true, 'size' => 5000000, 'error' => true, 'mimes' => array('image/png', 'image/jpeg', 'image/gif', 'image/webp', 'image/svg+xml', 'application/pdf', 'video/mp4', 'video/quicktime')]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function update_media_filename() {

        $validation = new Validate();
        
        $validation->input('filename')->as('Filename')->rules(['min' => 2, 'max' => 40, 'special' => true]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function create_menu() {
        
        $validation = new Validate();
        
        $validation->input('title')->as('Title')->rules(['required' => true, 'min' => 1, 'max' => 50, 'special' => true]);
        $validation->input('content')->as('Content')->rules(['required' => true, 'min' => 5]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function menu_update() {

        $validation = new Validate();
        
        $validation->input('title')->as('Title')->rules(['required' => true, 'min' => 1, 'max' => 50, 'special' => true]);
        $validation->input('content')->as('Content')->rules(['required' => true, 'min' => 5]);
        
        $this->errors = $validation->errors;
        return $this;
    }

    public function create_category() {
        
        $validation = new Validate();
        
        $validation->input('title')->as('Title')->rules(['required' => true, 'min' => 1, 'max' => 50, 'special' => true]);
        $validation->input('description')->as('Description')->rules(['max' => 100, 'special' => true]);
        
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

