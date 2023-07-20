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
        
        $search = get('search');

        if(!empty($search) ) {

            $menus = $menu->menusOnSearch($search);
        }
        $count = count($menus);
        
        $menus = Pagination::get($menus, 3);
        $numberOfPages = Pagination::getPageNumbers();

        $data["menus"] = $menus;
        $data["count"] = $count;
        $data['numberOfPages'] = $numberOfPages;

        return $this->view('admin/menus/index', $data);
    }

    public function create() {

        $data['rules'] = [];
        return $this->view('admin/menus/create', $data);
    }

    public function store($request) {

        if(submitted('submit') && Csrf::validate(Csrf::token('get'), post('token') ) === true) {

            $rules = new Rules();

            $unique = DB::try()->select('title')->from('menus')->where('title', '=', $request['title'])->and('id', '!=', $request['id'])->fetch();

            if($rules->create_menu($unique)->validated()) {
                    
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

        if(submitted('submit') ) {

            return $this->updateTitleAndContent($request);
        } else if (submitted('submitPosition') ) {

            return $this->updatePosition($request);
        } else if (submitted('submitOrdering')) {

            return $this->updateOrdering($request);
        }
    }

    private function updateTitleAndContent($request) {

        $rules = new Rules();

        $unique = DB::try()->select('title')->from('menus')->where('title', '=', $request['title'])->and('id', '!=', $request['id'])->fetch();

        if($rules->menu_update($unique)->validated()) {
                
            $id = $request['id'];

            Menu::update(['id' => $id], [

                'title'     => $request['title'],
                'content'   => $request['content'],
                'date_updated_at'   => date("d/m/Y"),
                'time_updated_at'   => date("H:i")
            ]);

            redirect("/admin/menus/$id/edit");
                
        } else {

            $data['rules'] = $rules->errors;
            $data['menu'] = Menu::where('id', '=', $request['id'])[0];

            return $this->view("/admin/menus/edit", $data);
        }
    }

    private function updatePosition($request) {

        $id = $request['id'];

        Menu::update(['id' => $id], [
            'position' => $request['position']
        ]); 

        redirect("/admin/menus/$id/edit");
    }

    private function updateOrdering($request) {

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