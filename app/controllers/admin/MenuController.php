<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use core\Csrf;
use validation\Rules;
use app\models\Menu;
use core\Session;
use database\DB;
use extensions\Pagination;

class MenuController extends Controller {

    public function index() {

        $menu = new Menu();
        $menus = $menu->allMenusButOrderedOnDate();

        $count = count($menus);
        $search = get('search');

        if(!empty($search) ) {

            $menus = $menu->menusOnSearch($search);
        }
        if(empty($menus) ) {

            $menus = array(["id" => "?","title" => "not found", "author" => "-", "position" => "-", "date_created_at" => "-", "time_created_at" => "", "date_updated_at" => "-", "time_updated_at" => ""]);
        }
        
        $menus = Pagination::get($menus, 20);
        $numberOfPages = Pagination::getPageNumbers();

        $data["menus"] = $menus;
        $data['numberOfPages'] = $numberOfPages;
        $data['count'] = $count;

        return $this->view('admin/menus/index', $data);
    }

    public function create() {

        $data['rules'] = [];
        return $this->view('admin/menus/create', $data);
    }

    public function store($request) {

        if(submitted('submit') && Csrf::validate(Csrf::token('get'), post('token') ) === true) {

            $rules = new Rules();

            if($rules->create_menu()->validated()) {
                    
                Menu::insert([

                    'title' => $request['title'],
                    'content'   => $request['content'],
                    'position'  => 'unset',
                    'author'    =>  Session::get('username'),
                    'date_created_at'   =>     date("d/m/Y"),
                    'time_created_at'   =>     date("H:i"),
                    'date_updated_at'   =>     date("d/m/Y"),
                    'time_updated_at'   =>     date("H:i")   
                ]);

                Session::set('create', 'You have successfully created a new menu!');            
                redirect('/admin/menus');

            } else {

                $data['rules'] = $rules->errors;
                return $this->view('admin/menus/create', $data);
            } 
        }
    }

    public function read($request) {

        $menu = Menu::where('id', '=', $request['id'])[0];
        $data['menu'] = $menu;

        return $this->view('/admin/menus/read', $data);
    }

    public function edit($request) {

        $menu = Menu::where('id', '=', $request['id'])[0];
        $data['menu'] = $menu;
        
        $data['rules'] = [];

        return $this->view('admin/menus/edit', $data);
    }

    public function update($request) {

        //if(Csrf::validate(Csrf::token('get'), post('token'))) {
        
            $id = $request['id'];

            if(submitted('submit') ) {

                $this->updateTitleAndContent($id, $request);

            } else if (submitted('submitPosition') ) {

                $this->updatePosition($id, $request);

            } else if (submitted('submitOrdering')) {

                $this->updateOrdering($id, $request);
            }
        //}
    }

    private function updateTitleAndContent($id, $request) {

        $rules = new Rules();

        if($rules->menu_update()->validated()) {
                
            Menu::update(['id' => $id], [

                'title'     => $request['title'],
                'content'   => $request['content'],
                'date_updated_at'   => date("d/m/Y"),
                'time_updated_at'   => date("H:i")
            ]);

            Session::set('updated', 'User updated successfully!');
            redirect("/admin/menus/$id/edit");
                
        } else {

            $data['rules'] = $rules->errors;
            $data['menu'] = Menu::where('id', '=', $id);
            return $this->view("/admin/menus/edit", $data);
        }
    }

    private function updatePosition($id, $request) {

        $id = $request['id'];
        Menu::update(['id' => $id], [
            'position' => $request['position']
        ]); 

        redirect("/admin/menus/$id/edit");
    }

    private function updateOrdering($id, $request) {

        $id = $request['id'];
        Menu::update(['id' => $id], [
            'ordering'  => $request['ordering']
        ]);
        
        redirect("/admin/menus/$id/edit");
    }

    public function delete($request) {

        Menu::delete('id', $request['id']);
        redirect("/admin/menus");
    }
}