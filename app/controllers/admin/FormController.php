<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use app\models\Forms;
use database\DB;

class FormController extends Controller {

    public function index() {

        $data['forms'] = DB::try()->select('*')->from('forms')->fetch();

        return $this->view('admin/forms/index', $data);
    }

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

    public function edit($request) {

        $data['form'] = Forms::get($request['id']);

        return $this->view('admin/forms/edit', $data);
    }

    public function update($request) {

        $id = $request['id'];

        Forms::update(['id' => $id], [

            'title'     => $request['title'],
            'content'   => $request['content'],
            'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
        ]);

        redirect("/admin/forms/$id/edit");

    }

}