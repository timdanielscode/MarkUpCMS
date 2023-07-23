<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use database\DB;
                    
class DashboardController extends Controller {
                
    public function index() {    

        $data['pages'] = DB::try()->select('id')->from('pages')->fetch();
        $data['menus'] = DB::try()->select('id')->from('menus')->fetch();
        $data['categories'] = DB::try()->select('id')->from('categories')->fetch();
        $data['css'] = DB::try()->select('id')->from('css')->fetch();
        $data['js'] = DB::try()->select('id')->from('js')->fetch();
        $data['media'] = DB::try()->select('id')->from('media')->fetch();
        $data['users'] = DB::try()->select('id')->from('users')->fetch();

        return $this->view("admin/dashboard/index", $data);     
    }
}  