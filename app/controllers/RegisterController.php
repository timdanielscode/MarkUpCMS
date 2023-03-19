<?php
             
  namespace app\controllers;
                
  use core\Csrf; 
  use validation\Rules;    
  use app\models\User;      
  use database\DB;
            
  class RegisterController extends Controller {
                
    public function create() {    
                    
        $data["rules"] = [];
                   
        return $this->view("register", $data);        
      } 
         
    public function store($request) {    
                 
      if(submitted("submit") && Csrf::validate(Csrf::token("get"), post("token")) ) {
                
        $rules = new Rules();  
                
        if($rules->registerRules()->validated() ) {
                 
          $user = new User();    
            
          DB::try()->insert($user->t, [
                
            $user->username => $request["username"], 
            $user->email => $request["email"], 
            $user->password => password_hash($request["password"], PASSWORD_DEFAULT),
            $user->retypePassword => password_hash($request["retypePassword"], PASSWORD_DEFAULT),
            $user->created_at => date("Y-m-d H:i:s"), 
            $user->updated_at => date("Y-m-d H:i:s")
                         
          ]);   

          redirect("/login");    

        } else {
                     
          $data["rules"] = $rules->errors;
          return $this->view("register", $data);
        }
      }
    }
  }      