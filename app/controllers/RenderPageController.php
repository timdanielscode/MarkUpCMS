<?php

namespace app\controllers;

use app\controllers\Controller;
use app\models\Post;
use app\models\Css;
use app\models\Js;
use app\models\Menu;
use app\models\Widget;
use app\models\Cdn;
use core\http\Request;

class RenderPageController extends Controller {

    private $_data, $_postId;

    public function render() {

        $request = new Request();
        $this->_data['post'] = Post::where(['slug' => $request->getUri()]);

        if(!empty(Widget::getPostWidgets($this->_data['post'][0]['id'])) ) {

            foreach(Widget::getPostWidgets($this->_data['post'][0]['id']) as $widget) {

                $widgetId = $widget['widget_id'];
                $regex = '/@widget\[' . $widgetId . '\];/';
                $content = Widget::whereColumns(['content'], ['id' => $widgetId]);
                $this->_data['post'][0]['body'] = preg_replace($regex, $content[0]['content'], $this->_data['post'][0]['body']);
            }
        }

        $this->_data['cdns'] = Cdn::getContent($this->_data['post'][0]['id']);
        $this->_data['cssFiles'] = Css::getFilenameExtension($this->_data['post'][0]['id']);
        $this->_data['jsFiles'] = Js::getFilenameExtension($this->_data['post'][0]['id']);
        $this->_data['menusTop'] = Menu::getTopMenus();
        $this->_data['menusBottom'] = Menu::getBottomMenus();

        return $this->view('page')->data($this->_data);
    }
}