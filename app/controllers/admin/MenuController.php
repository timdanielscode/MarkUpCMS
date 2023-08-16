<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use core\Csrf;
use validation\Rules;
use app\models\Menu;
use core\Session;
use database\DB;
use extensions\Pagination;
use core\http\Response;

class MenuController extends Controller {

    private function ifExists($id) {

        $menu = new Menu();

        if(empty($menu->ifRowExists($id)) ) {

            return Response::statusCode(404)->view("/404/404") . exit();
        }
    }

    public function index() {

        $menu = new Menu();
        $menus = $menu->allMenusButOrderedOnDate();
        
        $search = get('search');

        if(!empty($search) ) {

            $menus = $menu->menusOnSearch($search);
        }
        $count = count($menus);
        
        $menus = Pagination::get($menus, 10);
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
                    
                if(!empty($request['content']) ) { $hasContent = 1; } else { $hasContent = 0; }

                Menu::insert([

                    'title' => $request['title'],
                    'content'   => $request['content'],
                    'has_content' => $hasContent,
                    'position'  => 'unset',
                    'ordering'  => 0,
                    'author'    =>  Session::get('username'),
                    'removed' => 0,
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

        $this->ifExists($request['id']);

        $menu = Menu::where('id', '=', $request['id'])[0];
        $data['menu'] = $menu;

        return $this->view('/admin/menus/read', $data);
    }

    public function edit($request) {

        $this->ifExists($request['id']);

        $menu = Menu::where('id', '=', $request['id'])[0];

        if($menu['removed'] === 1) { return Response::statusCode(404)->view("/404/404") . exit(); }

        $data['menu'] = $menu;
        
        $data['rules'] = [];

        return $this->view('admin/menus/edit', $data);
    }

    public function update($request) {

        $this->ifExists($request['id']);

        if(submitted("submit") === true && Csrf::validate(Csrf::token('get'), post('token')) === true ) {

            $rules = new Rules();

            $unique = DB::try()->select('title')->from('menus')->where('title', '=', $request['title'])->and('id', '!=', $request['id'])->fetch();

            if($rules->menu_update($unique)->validated()) {
                    
                $id = $request['id'];

                if(!empty($request['content']) ) { $hasContent = 1; } else { $hasContent = 0; }

                Menu::update(['id' => $id], [

                    'title'     => $request['title'],
                    'content'   => $request['content'],
                    'has_content' => $hasContent, 
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
    }

    public function updatePosition($request) {

        $this->ifExists($request['id']);

        if(submitted("submit") === true && Csrf::validate(Csrf::token('get'), post('token')) === true ) {

            $id = $request['id'];

            Menu::update(['id' => $id], [
                'position' => $request['position']
            ]); 

            redirect("/admin/menus/$id/edit");
        }
    }

    public function updateOrdering($request) {

        $this->ifExists($request['id']);

        if(submitted("submit") === true && Csrf::validate(Csrf::token('get'), post('token')) === true ) {

            $id = $request['id'];

            Menu::update(['id' => $id], [
                'ordering'  => $request['ordering']
            ]);
            
            redirect("/admin/menus/$id/edit");
        }
    }

    public function recover($request) {

        $this->ifExists($request['id']);

        $menu = DB::try()->select('removed')->from('menus')->where('id', '=', $request['id'])->first();

        Menu::update(['id' => $request['id']], [

            'removed'  => 0
        ]);

        redirect("/admin/menus");
    }

    public function delete($request) {

        $deleteIds = explode(',', $request['deleteIds']);

        if(!empty($deleteIds) && !empty($deleteIds[0])) {

            foreach($deleteIds as $request['id']) {

                $this->ifExists($request['id']);

                $menu = DB::try()->select('title, removed')->from('menus')->where('id', '=', $request['id'])->first();

                if($menu['removed'] !== 1) {

                    Menu::update(['id' => $request['id']], [

                        'removed'  => 1,
                        'position' => 'unset',
                        'ordering' => 0
                    ]);

                } else if($menu['removed'] === 1) {

                    Menu::delete("id", $request['id']);
                }
            }
        }

        redirect("/admin/menus");
    }
}