<?php
/**
 * Use to run application envoirement
 * 
 * @author Tim Daniëls
 * @version 1.1
 */

namespace core;

class App {

    protected $middleware;
    public $route;
    public $request;
    public $response;

    public function __construct(Middleware $middleware) {

        $this->middleware = $middleware;
        $this->request = new Request();
        $this->response = new Response();
        $this->route = new Route($this->request, $this->response);
    }

    /**
     * @param object $middleware
     * @return void
     */
    public function add($middleware) {

        $this->middleware->add($middleware);
    }

    /**
     * @return void
     */    
    public function run() {

        $this->middleware->handle();
        require_once '../routes/routes.php';
        $this->route->uriNotFound(404);
    }

}