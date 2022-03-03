<?php

namespace app\controllers\admin;

use app\controllers\Controller;

class PostController extends Controller {

    public function create() {
        
        $data['rules'] = [];

        return $this->view('admin/posts/create', $data);
    }

}