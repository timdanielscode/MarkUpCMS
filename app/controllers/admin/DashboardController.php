<?php

  namespace app\controllers\admin;

  use app\controllers\Controller;
                
  class DashboardController extends Controller {
                
    public function index() {    

        return $this->view("admin/dashboard/index");     
    }
}  