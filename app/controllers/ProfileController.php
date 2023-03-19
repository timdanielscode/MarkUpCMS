<?php
                
  namespace app\controllers;

  use core\Session; 
  use core\http\Response;
                
  class ProfileController extends Controller {
    
    public function index($request) { 
      
      if(Session::get("username") === $request["username"]) { 

        return $this->view("profile/index");    
      } else {
        
        return Response::statusCode(404)->view("/404/404");
      }  
    }      
  }  