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

        $req = new Request();

        $post = Post::where('slug', '=', $req->getUri());

        if(!empty($post) ) {

            $cssFiles = DB::try()->select('file_name', 'extension')->from('css')->fetch();
            $jsFiles = DB::try()->select('file_name', 'extension')->from('js')->fetch();
            $menusTop = DB::try()->all('menus')->where('position', '=', 'top')->order('ordering')->fetch();
            $menusBottom = DB::try()->all('menus')->where('position', '=', 'bottom')->order('ordering')->fetch();
    
            $data['post'] = $post;
            $data['cssFiles'] = $cssFiles;
            $data['jsFiles'] = $jsFiles;
            $data['menusTop'] = $menusTop;
            $data['menusBottom'] = $menusBottom;

            return $this->view('page', $data);
        }
        
    }
}