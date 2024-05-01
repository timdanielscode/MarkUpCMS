<?php

namespace app\controllers;

use app\controllers\Controller;
use app\models\Page;
use app\models\Css;
use app\models\Js;
use app\models\Menu;
use app\models\Widget;
use app\models\Meta;
use core\http\Request;

class RenderPageController extends Controller {

    private $_data;

    /**
     * To show the page view (show the actual pages and all contents)
     * 
     * @return object RenderPageController, Controller
     */
    public function render($path) {

        $this->_data['page'] = $this->getPage($path);

        if(!empty(Widget::getPageWidgets($this->_data['page'][0]['id'])) ) {

            foreach(Widget::getPageWidgets($this->_data['page'][0]['id']) as $widget) {

                $widgetId = $widget['widget_id'];
                $regex = '/@widget\[' . $widgetId . '\];/';
                $content = Widget::whereColumns(['content'], ['id' => $widgetId]);
                $this->_data['page'][0]['body'] = preg_replace($regex, $content[0]['content'], $this->_data['page'][0]['body']);
            }
        }

        $this->_data['metas'] = Meta::getContent($this->_data['page'][0]['id']);
        $this->_data['cssFiles'] = Css::getFilenameExtension($this->_data['page'][0]['id']);
        $this->_data['jsFiles'] = Js::getFilenameExtension($this->_data['page'][0]['id']);
        $this->_data['menusTop'] = Menu::getTopMenus();
        $this->_data['menusBottom'] = Menu::getBottomMenus();

        return $this->view('page')->data($this->_data);
    }

    /**
     * To get the page
     * 
     * @return array page data
     */
    private function getPage($path) {

        if(!empty($path) && $path !== null) {

            return Page::where(['slug' => '/' . $path['path']]);
        } else {

            $request = new Request();
            return Page::where(['slug' => $request->getUri()]);
        }
    }
}