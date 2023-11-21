<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use core\Csrf;
use app\models\Widget;
use app\models\PageWidget;
use core\Session;
use extensions\Pagination;
use validation\Rules;
use core\http\Response;
use validation\Get;

class WidgetController extends Controller {

    private $_data;

    private function ifExists($id) {

        if(empty(Widget::ifRowExists($id)) ) {

            return Response::statusCode(404)->view("/404/404") . exit();
        }
    }

    private function redirect($inputName, $path) {

        if(submitted($inputName) === false || Csrf::validate(Csrf::token('get'), post('token')) === false ) { 
            
            redirect($path) . exit(); 
        } 
    }

    public function index($request) {

        $widgets = Widget::allWidgetsButOrderedOnDate();

        $this->_data['search'] = '';

        if(!empty($request['search'] ) ) {

            $this->_data['search'] = Get::validate($request['search']);
            $widgets = Widget::widgetsOnSearch($this->_data['search']);
        }

        $this->_data["widgets"] = Pagination::get($widgets, 10);
        $this->_data["count"] = count($widgets);
        $this->_data['numberOfPages'] = Pagination::getPageNumbers();

        return $this->view('admin/widgets/index')->data($this->_data);
    }

    public function create() {

        $this->_data['rules'] = [];
        return $this->view('admin/widgets/create')->data($this->_data);
    }

    public function store($request) {

        //$this->redirect("submit", '/admin/widgets');

        if(!empty($request['content']) ) { $hasContent = 1; } else { $hasContent = 0; }

        $rules = new Rules();

        if($rules->create_widget($request['title'], Widget::whereColumns(['title'], ['title' => $request['title']]))->validated()) {

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

            $this->_data['rules'] = $rules->errors;
            $this->_data['title'] = $request['title'];

            return $this->view('admin/widgets/create')->data($this->_data);
        }
    }

    public function read($request) {

        $this->ifExists($request['id']);

        $this->_data['widget'] = Widget::get($request['id']);

        return $this->view('admin/widgets/read')->data($this->_data);
    }

    public function edit($request) {

        $this->ifExists($request['id']);

        $this->_data['widget'] = Widget::get($request['id']);
        $this->_data['rules'] = [];

        return $this->view('admin/widgets/edit')->data($this->_data);
    }

    public function update($request) {

        $id = $request['id'];
        $this->ifExists($request['id']);
        //$this->redirect("submit", "/admin/widgets/$id/edit");
        
        $rules = new Rules();
        
        if($rules->edit_widget($request['title'], Widget::checkUniqueTitleId($request['title'], $id))->validated()) {

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

            $this->_data['widget'] = Widget::get($id);
            $this->_data['rules'] = $rules->errors;

            return $this->view('/admin/widgets/edit')->data($this->_data);
        }
    }

    public function recover($request) {
        
       // $this->redirect("recoverIds", "/admin/widgets");

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

       // $this->redirect("deleteIds", "/admin/widgets");

        $deleteIds = explode(',', $request['deleteIds']);

        if(!empty($deleteIds) && !empty($deleteIds[0])) {

            foreach($deleteIds as $id) {

                $this->ifExists($id);

                if(Widget::getColumns(['removed'], $id)['removed'] !== 1) {

                    Widget::update(['id' => $id], [

                        'removed'  => 1
                    ]);

                    PageWidget::delete('widget_id', $id);
                    Session::set('success', 'You have successfully moved the widget(s) to the trashcan!');

                } else if(Widget::getColumns(['removed'], $id)['removed'] === 1) {

                    Widget::delete("id", $id);
                    PageWidget::delete('widget_id', $id);
                    Session::set('success', 'You have successfully removed the widget(s)!');
                }
            }
        }
        redirect("/admin/widgets");
    }
}