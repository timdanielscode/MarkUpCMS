<?php
                
  /**                
   * File: /middleware/LoginMiddleware.php
   *                
   */  

  namespace middleware;
                
  use core\Session; 

  class LoginMiddleware {
                
    public function __construct($run) {    
                 
      if(Session::exists("logged_in") === true ) {

        /**                
         * Runs code inside closure function from run method in routes               
         */ 
         return $run();
      }    
    }          
  }  