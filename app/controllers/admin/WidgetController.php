<?php

namespace app\controllers\admin;

use app\models\Widget;
use app\models\PageWidget;
use app\models\Css;
use app\models\Js;
use app\models\Meta;
use core\Session;
use extensions\Pagination;
use validation\Rules;
use core\http\Response;
use validation\Get;

class WidgetController extends \app\controllers\Controller {

    private $_data;

    /**
     * To show 404 page with 404 status code (on not existing widget)
     * 
     * @param string $id _POST widget id
     * @return object WidgetController
     */ 
    private function ifExists($id) {

        if(empty(Widget::ifRowExists($id)) ) {

            return Response::statusCode(404)->view("/404/404")->data() . exit();
        }
    }

    /**
     * To show the widget index view
     * 
     * @param array $request _GET search, page
     * @return object WidgetController, Controller
     */
    public function index($request) {

        $widgets = Widget::allWidgetsButOrderedOnDate();

        $this->_data['search'] = '';

        if(!empty($request['search'] ) ) {

            $this->_data['search'] = Get::validate($request['search']);
            $widgets = Widget::widgetsOnSearch($this->_data['search']);
        }

        $this->_data["widgets"] = Pagination::get($request, $widgets, 10);
        $this->_data["count"] = count($widgets);
        $this->_data['numberOfPages'] = Pagination::getPageNumbers();

        return $this->view('admin/widgets/index')->data($this->_data);
    }

    /**
     * To show the widget create view
     * 
     * @return object WidgetController, Controller
     */
    public function create() {

        $this->_data['rules'] = [];
        return $this->view('admin/widgets/create')->data($this->_data);
    }

    /**
     * To store a new widget (on successful validation)
     * 
     * @param array $request _POST title, content
     * @return object WidgetController, Controller (on failed validation)
     */
    public function store($request) {

        if(!empty($request['content']) ) { $hasContent = 1; } else { $hasContent = 0; }

        $rules = new Rules();

        if($rules->widget($request, Widget::whereColumns(['title'], ['title' => $request['title']]))->validated()) {

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

            $this->_data['title'] = $request['title'];
            $this->_data['content'] = $request['content'];
            $this->_data['rules'] = $rules->errors;

            return $this->view('admin/widgets/create')->data($this->_data);
        }
    }

    /**
     * To show the widget read view
     * 
     * @param array $request id (widget id)
     * @return object WidgetController, Controller
     */
    public function read($request) {

        $this->ifExists($request['id']);

        $this->_data['cssFiles'] = Css::getAll(['file_name', 'extension']);
        $this->_data['jsFiles'] = Js::getAll(['file_name', 'extension']);
        $this->_data['metas'] = Meta::allMetaButOrderedByDate();
        $this->_data['widget'] = Widget::get($request['id']);

        return $this->view('admin/widgets/read')->data($this->_data);
    }

    /**
     * To show the widget edit view
     * 
     * @param array $request id (widget id)
     * @return object WidgetController, Controller
     */
    public function edit($request) {

        $this->ifExists($request['id']);

        $this->_data['widget'] = Widget::get($request['id']);
        $this->_data['rules'] = [];

        return $this->view('admin/widgets/edit')->data($this->_data);
    }

    /**
     * To update widget data (on successful validation)
     * 
     * @param array $request id (widget id), _POST title, content
     * @return object WidgetController, Controller (on failed validation)
     */
    public function update($request) {

        $id = $request['id'];
        $this->ifExists($request['id']);
        
        $rules = new Rules();
        
        if($rules->widget($request, Widget::checkUniqueTitleId($request['title'], $id))->validated()) {

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

    /**
     * To remove widget(s) from thrashcan
     * 
     * @param array $request _POST recoverIds (widget recoverIds)
     */
    public function recover($request) {

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

    /**
     * To remove widget(s) permanently or move to thrashcan
     * 
     * @param array $request _POST deleteIds (widget deleteIds)
     */
    public function delete($request) {

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