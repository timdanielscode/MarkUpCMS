<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use core\Csrf;
use app\models\Widget;
use core\Session;
use extensions\Pagination;
use database\DB;
use validation\Rules;

class WidgetController extends Controller {

    public function index() {

        $widget = new Widget();

        $widgets = $widget->allWidgetsButOrderedOnDate();
        
        $search = get('search');

        if(!empty($search) ) {

            $widgets = $widget->widgetsOnSearch($search);
        }

        $count = count($widgets);
        
        $widgets = Pagination::get($widgets, 10);
        $numberOfPages = Pagination::getPageNumbers();

        $data["widgets"] = $widgets;
        $data["count"] = $count;
        $data['numberOfPages'] = $numberOfPages;

        return $this->view('admin/widgets/index', $data);
    }

    public function create() {

        $data['rules'] = [];

        return $this->view('admin/widgets/create', $data);
    }

    public function store($request) {

        if(submitted('submit') && Csrf::validate(Csrf::token('get'), post('token') ) === true) {

            if(!empty($request['content']) ) { $hasContent = 1; } else { $hasContent = 0; }

            $rules = new Rules();

            $uniqueTitle = DB::try()->select('title')->from('widgets')->where('title', '=', $request['title'])->fetch();

            if($rules->create_widget($uniqueTitle)->validated()) {

                Widget::insert([

                    'title' => $request['title'],
                    'content'   => $request['content'],
                    'has_content' => $hasContent,
                    'author'    =>  Session::get('username'),
                    'removed' => 0,
                    'created_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
                    'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
                ]);

                redirect('/admin/widgets');
            } else {

                $data['rules'] = $rules->errors;
                return $this->view('admin/widgets/create', $data);
            }
        }
    }

    public function edit($request) {

        $widget = Widget::get($request['id']);

        $data['widget'] = $widget;
        $data['rules'] = [];

        return $this->view('admin/widgets/edit', $data);
    }

    public function update($request) {

        if(submitted("submit") === true && Csrf::validate(Csrf::token('get'), post('token')) === true ) {

            $id = $request['id'];

            $rules = new Rules();

            $uniqueTitle = DB::try()->select('title')->from('widgets')->where('title', '=', $request['title'])->and('id', '!=', $id)->fetch();

            if($rules->edit_widget($uniqueTitle)->validated()) {

                if(!empty($request['content']) ) { $hasContent = 1; } else { $hasContent = 0; }

                Widget::update(['id' => $id], [

                    'title'     => $request['title'],
                    'content'   => $request['content'],
                    'has_content' => $hasContent, 
                    'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
                ]);

                redirect("/admin/widgets/$id/edit");
            } else {

                $data['widget'] = Widget::get($id);
                $data['rules'] = $rules->errors;
                return $this->view('/admin/widgets/edit', $data);
            }
        }
    }

    public function delete($request) {

        $deleteIds = explode(',', $request['deleteIds']);

        if(!empty($deleteIds) && !empty($deleteIds[0])) {

            foreach($deleteIds as $request['id']) {

                $widget = DB::try()->select('title, removed')->from('widgets')->where('id', '=', $request['id'])->first();

                if($widget['removed'] !== 1) {

                    Widget::update(['id' => $request['id']], [

                        'removed'  => 1
                    ]);

                } else if($widget['removed'] === 1) {

                    Widget::delete("id", $request['id']);
                }
            }
        }

        redirect("/admin/widgets");
    }
}