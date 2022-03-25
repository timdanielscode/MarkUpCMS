<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use database\DB;
use core\Csrf;
use validation\Rules;

class MediaController extends Controller {

    public function index() {
        
        return $this->view('admin/media/index');
    }

}