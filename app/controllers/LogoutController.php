<?php
             
  /**        
   * Controller: app/controllers/LogoutController.php
   */ 

  namespace app\controllers;

  use core\Session;
                
  class LogoutController extends Controller {
                
    public function logout() {    
                     
      Session::delete("logged_in");
      Session::delete("username");
      
      redirect("/");
    }
  }  