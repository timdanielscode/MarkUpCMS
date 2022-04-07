<?php

namespace app\controllers;

use app\controllers\Controller;
use app\models\Post;
use app\models\Css;
use app\models\Js;
use database\DB;
use core\Request;
use core\Response;
use ResponseController;

class RenderPageController extends Controller {

    public function render() {

        $posts = new Post();
        $req = new Request();

        $post = DB::try()->select('*')->from($posts->t)->where($posts->slug, '=', $req->getUri())->first();

        if(!empty($post) ) {

            $css = new Css();
            $js = new Js();

            $cssFiles = DB::try()->select('file_name', 'extension')->from($css->t)->fetch();
            $jsFiles = DB::try()->select('file_name', 'extension')->from($js->t)->fetch();
    
            $data['post'] = $post;
            $data['cssFiles'] = $cssFiles;
            $data['jsFiles'] = $jsFiles;

            return $this->view('page', $data);
        }
        
    }
}