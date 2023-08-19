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

            $postId = $post[0]['id'];

            $cssFiles = DB::try()->select('file_name', 'extension')->from('css')->join('css_page')->on('css_page.css_id', '=', 'css.id')->where('css_page.page_id', '=', $postId)->fetch();
            $jsFiles = DB::try()->select('file_name', 'extension')->from('js')->join('js_page')->on('js_page.js_id', '=', 'js.id')->where('js_page.page_id', '=', $postId)->fetch();

            $menusTop = DB::try()->all('menus')->where('position', '=', 'top')->order('ordering')->fetch();
            $menusBottom = DB::try()->all('menus')->where('position', '=', 'bottom')->order('ordering')->fetch();

            $postWidgets = DB::try()->select('widget_id')->from('page_widget')->where('page_id', '=', $postId)->fetch();

            if(!empty($postWidgets) && $postWidgets !== null) {

                foreach($postWidgets as $postWidget) {

                    $widgetId = $postWidget['widget_id'];
                    $regex = '/@widget\[' . $widgetId . '\];/';

                    $widgetContent = DB::try()->select('content')->from('widgets')->where('id', '=', $widgetId)->first();
                    $post[0]['body'] = preg_replace($regex, $widgetContent[0], $post[0]['body']);
                }
            }

            $data['post'] = $post;
            $data['cssFiles'] = $cssFiles;
            $data['jsFiles'] = $jsFiles;
            $data['menusTop'] = $menusTop;
            $data['menusBottom'] = $menusBottom;

            return $this->view('page', $data);
        }
    }
}