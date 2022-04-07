<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use core\Csrf;
use validation\Rules;
use app\models\Menu;
use parts\Session;
use database\DB;


class MenuController extends Controller {

    public function index() {

        return $this->view('admin/menus/index');
    }

    public function create() {

        $data['rules'] = [];
        return $this->view('admin/menus/create', $data);
    }

    public function store() {

        if(submitted('submit')) {

            if(Csrf::validate(Csrf::token('get'), post('token') ) === true) {

                $rules = new Rules();
                $menu = new Menu();

                if($rules->create_menu()->validated()) {
                    
                    DB::try()->insert($menu->t, [

                        $menu->title => post('title'),
                        $menu->content => post('content'),
                        $menu->date_created_at => date("d/m/Y"),
                        $menu->time_created_at => date("H:i"),
                        $menu->date_updated_at => date("d/m/Y"),
                        $menu->time_updated_at => date("H:i")
                    ]);

                    Session::set('create', 'You have successfully created a new menu!');            
                    redirect('/admin/menus');

                } else {

                    $data['rules'] = $rules->errors;
                    return $this->view('admin/menus/create', $data);
                }
            } else {
                Session::set('csrf', 'Cross site request forgery!');
                return $this->view('admin/menus/create', $data);
            }
        }
    }


}