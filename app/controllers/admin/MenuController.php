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
use validation\Get;

class MenuController extends Controller {

    private function ifExists($id) {

        $menu = new Menu();

        if(empty($menu->ifRowExists($id)) ) {

            return Response::statusCode(404)->view("/404/404") . exit();
        }
    }

    private function redirect($inputName, $path) {

        if(submitted($inputName) === false || Csrf::validate(Csrf::token('get'), post('token')) === false ) { 
            
            redirect($path) . exit(); 
        } 
    }

    public function index() {

        $menu = new Menu();
        $menus = $menu->allMenusButOrderedOnDate();
        
        $search = Get::validate([get('search')]);

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

        $this->redirect("submit", '/admin/menus');

        $rules = new Rules();
        $menu = new Menu();

        if($rules->create_menu($menu->checkUniqueTitle($request['title']))->validated()) {
                    
            if(!empty($request['content']) ) { $hasContent = 1; } else { $hasContent = 0; }

            Menu::insert([

                'title' => $request['title'],
                'content'   => $request['content'],
                'has_content' => $hasContent,
                'position'  => 'unset',
                'ordering'  => 0,
                'author'    =>  Session::get('username'),
                'removed' => 0,
                'created_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
                'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
            ]);
          
            Session::set('success', 'You have successfully created a new menu!');
            redirect('/admin/menus');

        } else {

            $data['rules'] = $rules->errors;
            return $this->view('admin/menus/create', $data);
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

        $id = $request['id'];
        $this->ifExists($id);
        $this->redirect("submit", "/admin/menus/$id/edit");

        $rules = new Rules();
        $menu = new Menu();
        
        if($rules->menu_update($menu->checkUniqueTitleId($request['title'], $request['id']))->validated()) {
                    
            if(!empty($request['content']) ) { $hasContent = 1; } else { $hasContent = 0; }

            Menu::update(['id' => $id], [

                'title'     => $request['title'],
                'content'   => $request['content'],
                'has_content' => $hasContent, 
                'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
            ]);

            Session::set('success', 'You have successfully updated the menu!');
            redirect("/admin/menus/$id/edit");
                    
        } else {

            $data['rules'] = $rules->errors;
            $data['menu'] = Menu::where('id', '=', $request['id'])[0];

            return $this->view("/admin/menus/edit", $data);
        }
    }

    public function updatePosition($request) {

        $id = $request['id'];
        $this->ifExists($id);
        $this->redirect("submit", "/admin/menus/$id/edit");

        Menu::update(['id' => $id], [

            'position' => $request['position'],
            'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']) 
        ]); 

        Session::set('success', 'You have successfully updated the menu position!');
        redirect("/admin/menus/$id/edit");
    }

    public function updateOrdering($request) {

        $id = $request['id'];
        $this->ifExists($id);
        $this->redirect("submit", "/admin/menus/$id/edit");

        Menu::update(['id' => $id], [

            'ordering'  => $request['ordering'],
            'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
        ]);
            
        Session::set('success', 'You have successfully updated the menu ordering!');
        redirect("/admin/menus/$id/edit");
    }

    public function recover($request) {

        $id = $request['id'];
        $this->redirect("recoverIds", "/admin/menus");

        $recoverIds = explode(',', $request['recoverIds']);
            
        foreach($recoverIds as $request['id'] ) {

            $this->ifExists($request['id']);

            Menu::update(['id' => $request['id']], [

                'removed'  => 0
            ]);
        }

        Session::set('success', 'You have successfully recovered the menu(s)!');
        redirect("/admin/menus");
    }

    public function delete($request) {

        $this->redirect("deleteIds", "/admin/menus");
        $deleteIds = explode(',', $request['deleteIds']);

        if(!empty($deleteIds) && !empty($deleteIds[0])) {

            foreach($deleteIds as $id) {

                $this->ifExists($id);
                $menu = new Menu();

                if($menu->getData($id, ['removed'])['removed'] !== 1) {

                    Menu::update(['id' => $id], [

                        'removed'  => 1,
                        'position' => 'unset',
                        'ordering' => 0
                    ]);

                    Session::set('success', 'You have successfully moved the menu(s) to the trashcan!');

                } else if($menu->getData($id, ['removed'])['removed'] === 1) {

                    Menu::delete("id", $id);
                    Session::set('success', 'You have successfully removed the menu(s)!');
                }
            }
        }
        redirect("/admin/menus");
    }
}