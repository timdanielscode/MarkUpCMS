<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use core\Csrf;
use app\models\Widget;
use core\Session;

class WidgetController extends Controller {

    public function create() {

        $data['rules'] = [];

        return $this->view('admin/widgets/create', $data);
    }

    public function store($request) {

        if(submitted('submit') && Csrf::validate(Csrf::token('get'), post('token') ) === true) {

            if(!empty($request['content']) ) { $hasContent = 1; } else { $hasContent = 0; }

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
        }
    }
}