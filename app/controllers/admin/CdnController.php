<?php

namespace app\controllers\admin;

use app\controllers\Controller;

class CdnController extends Controller {

    public function create() {

        return $this->view('admin/cdn/create');
    }


}