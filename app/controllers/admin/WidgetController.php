<?php

namespace app\controllers\admin;

use app\controllers\Controller;

class WidgetController extends Controller {

    public function create() {

        $data['rules'] = [];

        return $this->view('admin/widgets/create', $data);
    }
}