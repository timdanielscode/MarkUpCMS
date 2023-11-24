<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use validation\Rules;
use app\models\Menu;
use core\Session;
use extensions\Pagination;
use core\http\Response;
use validation\Get;

class MenuController extends Controller {

    private $_data;

    private function ifExists($id) {

        if(empty(Menu::ifRowExists($id)) ) {

            return Response::statusCode(404)->view("/404/404")->data() . exit();
        }
    }

    public function index($request) {

        $menus = Menu::allMenusButOrderedOnDate();

        $this->_data['search'] = '';

        if(!empty($request['search'] ) ) {

            $this->_data['search'] = Get::validate($request['search']);
            $menus = Menu::menusOnSearch($this->_data['search']);
        }

        $this->_data["menus"] = Pagination::get($request, $menus, 10);
        $this->_data["count"] = count($menus);
        $this->_data['numberOfPages'] = Pagination::getPageNumbers();

        return $this->view('admin/menus/index')->data($this->_data);
    }

    public function create() {

        $this->_data['rules'] = [];
        return $this->view('admin/menus/create')->data($this->_data);
    }

    public function store($request) {

        $rules = new Rules(); 

        if($rules->menu($request['title'], Menu::whereColumns(['title'], ['title' => $request['title']]))->validated()) {
                    
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

            $this->_data['content'] = $request['content'];
            $this->_data['title'] = $request['title'];
            $this->_data['rules'] = $rules->errors;

            return $this->view('admin/menus/create')->data($this->_data);
        } 
    }

    public function read($request) {

        $this->ifExists($request['id']);

        $this->_data['menu'] = Menu::get($request['id']);

        return $this->view('/admin/menus/read')->data($this->_data);
    }

    public function edit($request) {

        $this->ifExists($request['id']);

        $this->_data['menu'] = Menu::get($request['id']);
        $this->_data['rules'] = [];

        return $this->view('admin/menus/edit')->data($this->_data);
    }

    public function update($request) {

        $id = $request['id'];
        $this->ifExists($id);

        $rules = new Rules();
        
        if($rules->menu($request['title'], Menu::checkUniqueTitleId($request['title'], $id))->validated()) {
                    
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

            $this->_data['rules'] = $rules->errors;
            $this->_data['menu'] = Menu::get($request['id']);

            return $this->view("/admin/menus/edit")->data($this->_data);
        }
    }

    public function updatePosition($request) {

        $id = $request['id'];
        $this->ifExists($id);

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

        Menu::update(['id' => $id], [

            'ordering'  => $request['ordering'],
            'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
        ]);
            
        Session::set('success', 'You have successfully updated the menu ordering!');
        redirect("/admin/menus/$id/edit");
    }

    public function recover($request) {

        $recoverIds = explode(',', $request['recoverIds']);
            
        foreach($recoverIds as $id) {

            $this->ifExists($id);

            Menu::update(['id' => $id], [

                'removed'  => 0
            ]);
        }

        Session::set('success', 'You have successfully recovered the menu(s)!');
        redirect("/admin/menus");
    }

    public function delete($request) {

        $deleteIds = explode(',', $request['deleteIds']);

        if(!empty($deleteIds) && !empty($deleteIds[0])) {

            foreach($deleteIds as $id) {

                $this->ifExists($id);

                if(Menu::getColumns(['removed'], $id)['removed'] !== 1) {

                    Menu::update(['id' => $id], [

                        'removed'  => 1,
                        'position' => 'unset',
                        'ordering' => 0
                    ]);

                    Session::set('success', 'You have successfully moved the menu(s) to the trashcan!');

                } else if(Menu::getColumns(['removed'], $id)['removed'] === 1) {

                    Menu::delete("id", $id);
                    Session::set('success', 'You have successfully removed the menu(s)!');
                }
            }
        }
        redirect("/admin/menus");
    }
}