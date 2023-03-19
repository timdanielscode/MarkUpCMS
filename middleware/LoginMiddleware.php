<?php
                
namespace middleware;
                
use core\Session; 

class LoginMiddleware {
                
  public function __construct($run) {    
    
      if(Session::exists("logged_in") === true ) {

         return $run();
      }    
    }          
  }  