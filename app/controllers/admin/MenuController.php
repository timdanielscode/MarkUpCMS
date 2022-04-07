<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use core\Csrf;
use validation\Rules;
use app\models\Menu;
use parts\Session;
use database\DB;
use parts\Pagination;


class MenuController extends Controller {

    public function index() {

        $menu = new Menu();
        $menus = DB::try()->all($menu->t)->order('date_created_at')->fetch();

        $count = count($menus);
        $search = get('search');

        if(!empty($search) ) {
            $menus = DB::try()->all($menu->t)->where($menu->title, 'LIKE', '%'.$search.'%')->or($menu->author, 'LIKE', '%'.$search.'%')->or($menu->date_created_at, 'LIKE', '%'.$search.'%')->or($menu->time_created_at, 'LIKE', '%'.$search.'%')->or($menu->date_updated_at, 'LIKE', '%'.$search.'%')->or($menu->time_updated_at, 'LIKE', '%'.$search.'%')->fetch();
        }
        if(empty($menus) ) {
            $menus = array(["id" => "?","title" => "not found", "date_created_at" => "-", "time_created_at" => "", "date_updated_at" => "-", "time_updated_at" => ""]);
        }
        
        $menus = Pagination::set($menus, 20);
        $numberOfPages = Pagination::getPages();

        $data["menus"] = $menus;
        $data['numberOfPages'] = $numberOfPages;
        $data['count'] = $count;

        return $this->view('admin/menus/index', $data);
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
                        $menu->author => Session::get('username'),
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

    public function edit($request) {

        $menus = new Menu();
        $menu = DB::try()->select('*')->from($menus->t)->where($menus->id, '=', $request['id'])->first();
        $data['menu'] = $menu;
        $data['rules'] = [];

        return $this->view('admin/menus/edit', $data);
    }

    public function update($request) {

        if(submitted('submit')) {

            if(CSRF::validate(CSRF::token('get'), post('token'))) {
                
                $menu = new Menu();
                $rules = new Rules();
                $id = $request['id'];
                $title = $request["title"];
                $content = $request["content"];

                if($rules->menu_update()->validated()) {

                    DB::try()->update($menu->t)->set([
                        $menu->title => $title,
                        $menu->content => $content,
                        $menu->date_updated_at => date("d/m/Y"),
                        $menu->time_updated_at => date("H:i")
                    ])->where($menu->id, '=', $id)->run();              

                    Session::set('updated', 'User updated successfully!');
                    redirect("/admin/menus/$id/edit");
                    
                } else {
                    $data['rules'] = $rules->errors;
                    $data['menu'] = DB::try()->select('*')->from($menu->t)->where($menu->id, '=', $id)->first();
                    return $this->view("/admin/menus/edit", $data);
                }

            } else {
                Session::set('csrf', 'Cross site request forgery!');
                redirect("/admin/menus/$id");
            }
        }
    }


}