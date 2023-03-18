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
            $post = new Post();
            $post404 = DB::try()->select("*")->from($post->t)->where($post->title, '=', 404)->first();
            
            if(!empty($post404) ) {
                $css = new Css();
                $js = new Js();
    
                $cssFiles = DB::try()->select('file_name', 'extension')->from($css->t)->fetch();
                $jsFiles = DB::try()->select('file_name', 'extension')->from($js->t)->fetch();
    
                $data['post'] = $post404;
                $data['cssFiles'] = $cssFiles;
                $data['jsFiles'] = $jsFiles;
    
                return $this->view('page', $data);
            }
        }
    }
}