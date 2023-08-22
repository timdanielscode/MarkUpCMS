<?php

namespace app\controllers\admin;

use app\controllers\Controller;


class FormController extends Controller {

    public function create() {

        return $this->view('admin/forms/create');
    }

}