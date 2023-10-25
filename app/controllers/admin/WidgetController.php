<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use core\Csrf;
use app\models\Widget;
use app\models\PageWidget;
use core\Session;
use extensions\Pagination;
use database\DB;
use validation\Rules;
use core\http\Response;
use validation\Get;

class WidgetController extends Controller {

    private function ifExists($id) {

        $widget = new Widget();

        if(empty($widget->ifRowExists($id)) ) {

            return Response::statusCode(404)->view("/404/404") . exit();
        }
    }

    private function redirect($inputName, $path) {

        if(submitted($inputName) === false || Csrf::validate(Csrf::token('get'), post('token')) === false ) { 
            
            redirect($path) . exit(); 
        } 
    }

    public function index() {

        $widget = new Widget();
        $widgets = $widget->allWidgetsButOrderedOnDate();
        $search = Get::validate([get('search')]);

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

        $id = $request['id'];
        $this->redirect("submit", '/admin/widgets');

        if(!empty($request['content']) ) { $hasContent = 1; } else { $hasContent = 0; }

        $rules = new Rules();
        $widget = new Widget();

        if($rules->create_widget($widget->checkUniqueTitle($request['title']))->validated()) {

            Widget::insert([

                'title' => $request['title'],
                'content'   => $request['content'],
                'has_content' => $hasContent,
                'author'    =>  Session::get('username'),
                'removed' => 0,
                'created_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
                'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
            ]);

            Session::set('success', 'You have successfully created a new widget!');
            redirect('/admin/widgets');
        } else {

            $data['rules'] = $rules->errors;
            return $this->view('admin/widgets/create', $data);
        }
    }

    public function read($request) {

        $this->ifExists($request['id']);

        $widget = Widget::get($request['id']);
        $data['widget'] = $widget;

        return $this->view('admin/widgets/read', $data);
    }

    public function edit($request) {

        $this->ifExists($request['id']);

        $widget = Widget::get($request['id']);

        $data['widget'] = $widget;
        $data['rules'] = [];

        return $this->view('admin/widgets/edit', $data);
    }

    public function update($request) {

        $id = $request['id'];
        $this->ifExists($request['id']);
        $this->redirect("submit", "/admin/widgets/$id/edit");
        
        $rules = new Rules();
        $widget = new Widget();
        
        if($rules->edit_widget($widget->checkUniqueTitleId($request['title'], $id))->validated()) {

            if(!empty($request['content']) ) { $hasContent = 1; } else { $hasContent = 0; }

            Widget::update(['id' => $id], [

                'title'     => $request['title'],
                'content'   => $request['content'],
                'has_content' => $hasContent, 
                'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
            ]);

            Session::set('success', 'You have successfully updated the widget!');
            redirect("/admin/widgets/$id/edit");
        } else {

            $data['widget'] = Widget::get($id);
            $data['rules'] = $rules->errors;
            return $this->view('/admin/widgets/edit', $data);
        }
    }

    public function recover($request) {
        
        $this->redirect("recoverIds", "/admin/widgets");

        $recoverIds = explode(',', $request['recoverIds']);
            
        foreach($recoverIds as $id) {

            $this->ifExists($id);

            Widget::update(['id' => $id], [

                'removed'  => 0
            ]);
        }

        Session::set('success', 'You have successfully recovered the widget(s)!');
        redirect("/admin/widgets");
    }

    public function delete($request) {

        $this->redirect("deleteIds", "/admin/widgets");

        $deleteIds = explode(',', $request['deleteIds']);

        if(!empty($deleteIds) && !empty($deleteIds[0])) {

            foreach($deleteIds as $id) {

                $this->ifExists($id);
                $widget = new Widget();

                if($widget->getData($id, ['removed'])['removed'] !== 1) {

                    Widget::update(['id' => $id], [

                        'removed'  => 1
                    ]);

                    PageWidget::delete('widget_id', $id);
                    Session::set('success', 'You have successfully moved the widget(s) to the trashcan!');

                } else if($widget->getData($id, ['removed'])['removed'] === 1) {

                    Widget::delete("id", $id);
                    PageWidget::delete('widget_id', $id);
                    Session::set('success', 'You have successfully removed the widget(s)!');
                }
            }
        }
        redirect("/admin/widgets");
    }
}