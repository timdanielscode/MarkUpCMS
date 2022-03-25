<?php

namespace app\controllers;

use app\controllers\Controller;
use app\models\Post;
use app\models\Css;
use app\models\Js;
use database\DB;
use core\Request;
use core\Response;

class RenderPageController extends Controller {

    public function render() {

        $posts = new Post();
        $css = new Css();
        $js = new Js();
        $req = new Request();

        $post = DB::try()->select('*')->from($posts->t)->where($posts->slug, '=', $req->getUri())->first();
        $cssFiles = DB::try()->select('file_name', 'extension')->from($css->t)->fetch();
        $jsFiles = DB::try()->select('file_name', 'extension')->from($js->t)->fetch();
   
        if(empty($post) ) {
            //return Response::statusCode(404)->view("/404/404");
            // return 404 of posts..
            // and or could create a default 404 page..
        } else {
            $data['post'] = $post;
            $data['cssFiles'] = $cssFiles;
            $data['jsFiles'] = $jsFiles;

            return $this->view('page', $data);
        }
    }
}