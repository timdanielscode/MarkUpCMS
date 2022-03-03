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

class ResponseController extends Controller {

    /**
     * @return object Controller view method
     */
    public function pageNotFound() {
        
        return $this->view('/404/404');
    }
}