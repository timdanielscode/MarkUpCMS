<?php

namespace app\controllers\admin;

use app\controllers\Controller;

class PostController extends Controller {

    public function create() {
        
        return $this->view('admin/posts/index');
    }

}