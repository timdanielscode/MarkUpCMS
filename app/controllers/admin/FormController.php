<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use app\models\Forms;


class FormController extends Controller {

    public function create() {

        return $this->view('admin/forms/create');
    }

    public function store($request) {

        Forms::insert([

            'title' => $request['title'],
            'content' => $request['content'],
            'created_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
            'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
        ]);

        redirect('/admin/forms/create');
    }

}