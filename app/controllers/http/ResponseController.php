<?php 
/**
 * Use to handle 404 repsonse from within controller
 * Using this aproach you can make use of includes on view 
 * 
 * @author Tim Daniëls
 * @version 1.0
 */

namespace app\controllers\http;

use app\controllers\Controller;
use core\Session;
use app\models\Post;
use app\models\Css;
use app\models\Js;
use database\DB;

class ResponseController extends Controller {

    /**
     * @return object Controller view method
     */
    public function pageNotFound() {
        
        if(Session::exists('logged_in') ) {

            return $this->view('/404/404');
        } else {

            $post404 = Post::where('title', '=', 404);
            
            if(!empty($post404) ) {
    
                $cssFiles = Css::all();
                $jsFiles = Js::all();

                $data['post'] = $post404;
                $data['cssFiles'] = $cssFiles;
                $data['jsFiles'] = $jsFiles;
    
                return $this->view('page', $data);
            }
        }
    }
}