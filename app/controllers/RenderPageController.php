<?php

namespace app\controllers;

use app\controllers\Controller;
use app\models\Post;
use app\models\Css;
use app\models\Js;
use app\models\Menu;
use database\DB;
use core\http\Request;
use core\http\Response;
use ResponseController;

class RenderPageController extends Controller {

    public function render() {

        $posts = new Post();
        $req = new Request();

        $post = DB::try()->select('*')->from($posts->t)->where($posts->slug, '=', $req->getUri())->first();

        if(!empty($post) ) {

            $css = new Css();
            $js = new Js();
            $menu = new Menu();

            $cssFiles = DB::try()->select('file_name', 'extension')->from($css->t)->fetch();
            $jsFiles = DB::try()->select('file_name', 'extension')->from($js->t)->fetch();
            $menusTop = DB::try()->all($menu->t)->where($menu->position, '=', 'top')->order('ordering')->fetch();
            $menusBottom = DB::try()->all($menu->t)->where($menu->position, '=', 'bottom')->order('ordering')->fetch();
    
            $data['post'] = $post;
            $data['cssFiles'] = $cssFiles;
            $data['jsFiles'] = $jsFiles;
            $data['menusTop'] = $menusTop;
            $data['menusBottom'] = $menusBottom;

            return $this->view('page', $data);
        }
        
    }
}