<?php
                
  /**        
   * Model: app/models/User.php
   */ 

  namespace app\models;
                
  class User {
                
    public $t = "users"; 

    public $id = "id", 
      
      $username = "username",          
      $email = "email", 
      $password = "password",    
      $retypePassword  = "retypePassword",   
      $created_at = "created_at", 
      $updated_at = "updated_at";     
  }   