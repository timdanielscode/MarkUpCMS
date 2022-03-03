<?php

namespace app\controllers\admin;

use app\controllers\Controller;

class AdminController extends Controller {

    public function index() {

        return $this->view('admin/dashboard/index');
    }

    public function create() {

        return $this->view('admin/users/index');
    }

}