<?php
                
namespace middleware;
                
use core\Session; 

class NoLoginMiddleware {
                
  public function __construct($run) {    
    
      if(Session::exists("logged_in") === false) {

         return $run();
      }    
    }          
  }  