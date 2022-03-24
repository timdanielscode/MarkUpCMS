<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use app\models\Asset;
use database\DB;

class AssetsController extends Controller {

    public function index() {

        $asset = new Asset();
        $assets = DB::try()->all($asset->t)->order('date_created_at')->fetch();

        //$count = count($posts);

        $data['assets'] = $assets;

        return $this->view('admin/assets/index', $data);
    }

}