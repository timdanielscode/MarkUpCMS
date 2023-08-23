<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use app\models\Cdn;

class CdnController extends Controller {

    public function create() {

        return $this->view('admin/cdn/create');
    }

    public function store($request) {

        Cdn::insert([

            'title' => $request['title'],
            'content' => $request['code'],
            'created_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
            'updated_at' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
        ]);

        redirect('/admin/cdn/create');
    }

}