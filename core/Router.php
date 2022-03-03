<?php
/**
 * Use for handling routes
 * 
 * @author Tim Daniëls
 * @version 1.2
 */
namespace core;

use app\controllers\http\ResponseController;

use core\RouteBinder;

class Router extends RouteBinder {

    private $_uri, $_path, $request, $request_vals, $_pathRouteKeyKeys, $_error;
    private $_partsPath = [];
    private $_routeBinder;

    /**
     * @param object $request Request
     * @param object $response Response
     */
    public function __construct($request, $response) {
    
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * 
     * handles get request based routes
     * 
     * @param string $path
     * @param array $routeKeys optional
     */
    public function getRequest($path, $routeKeys = null) {

        if($routeKeys) {
            
            $checkKeys = implode('|', $routeKeys);
            if(preg_match("($checkKeys)", $path) === 1) { 

                $this->setRouteKeyKeys($path, $routeKeys);
                $this->_routeBinder = new RouteBinder();
                $this->_routeBinder->setPath($this->_partsPath, $this->_pathRouteKeyKeys, $this->request->getUri());
                $path = $this->_routeBinder->getPath();  
            } 
        }

        if(strtok($this->request->getUri(), '?') == $path || strtok($this->request->getUri(), '?') . "/" == $path) {
            if($this->request->getMethod() === 'GET') {
                $this->_path = $path;
            } 
        } 
        return $this;
    }

    /**
     * 
     * handles post request based routes
     * 
     * @param string $path
     * @param array $routeKeys optional
     */
    public function postRequest($path, $routeKeys = null) {

        if($routeKeys) {
           
            $checkKeys = implode('|', $routeKeys);
            if(preg_match("($checkKeys)", $path) === 1) { 
                
                $this->setRouteKeyKeys($path, $routeKeys);
                $this->_routeBinder = new RouteBinder();
                $this->_routeBinder->setPath($this->_partsPath, $this->_pathRouteKeyKeys, $this->request->getUri()); 
                $path = $this->_routeBinder->getPath();      
            } 
        }
        
        if($this->uri() == $path || $this->uri() . "/" == $path) {

            if($this->request->getMethod() === 'POST') {
                if($this->_routeBinder) {
                    $this->_routeBinder->postRequestVariables();
                }
                $this->_path = $path;  
            } 
        } 
        return $this;
    }

    /**
     * 
     * pushes routekey keys of path
     * 
     * @param string $path
     * @param array $routeKeys
     */
    public function setRouteKeyKeys($path, $routeKeys) {

        $this->_partsPath = explode("/", $path);

        foreach($routeKeys as $routeKey) {
            $this->_pathRouteKeyKeys[] = array_search('['.$routeKey.']', $this->_partsPath);
        }
    }

    /**
     * 
     * adds controller and method 
     * 
     * @param string $class like controller
     * @param string $method optional 'action' 
     */    
    public function add($class, $method = null) {  

        if($this->uri() == $this->_path || $this->uri() . "/" == $this->_path) {
            
            $namespaceClass = $this->namespace($class);
            if(class_exists($namespaceClass)) { 

                $instance = new $namespaceClass;
                if($method) {
                    if(method_exists($namespaceClass, $method)) {
                        $this->request_vals = $this->request->get();
                       if($this->_routeBinder) {
                           if($this->_routeBinder->getRequestVariables() !== null) {
                                $this->request_vals = array_merge($this->request_vals, $this->_routeBinder->getRequestVariables());
                           }    
                        }
                        return $instance->$method($this->request_vals) . exit();
                    } 
                } else {
                    return $instance . exit(); 
                }
            } 
        } 
    }

    /**
     * 
     * handles view based on path
     * 
     * @param string $path 
     */ 
    public function handleView($path = null, $view) {
     
        if(strtok($this->request->getUri(), '?') == $path || strtok($this->request->getUri(), '?') . "/" == $path) {
            if($this->request->getMethod() === 'GET') {
                $this->_path = $path;
                $controller = $this->namespace("Controller");
                $instance = new $controller;
                
                return $instance->view($view) . exit();
            } 
        } 
    }

    /**
     * get uri
     * @return property uri 
     */   
    public function uri() {
        
        $this->_uri = $this->request->getUri();
        $this->_uri = strtok($this->_uri, '?');
            
        return $this->_uri; 
    }
    
    /**
     * add namespace to classes
     * @param string $class like controller 
     * @return string default namespace src\controllers\
     */      
    private function namespace($class) {

        $namespace = 'app\controllers\\' . $class;       
        return $namespace;
    }    

    /**
     * add status code
     * @param string $code 
     * @return void
     */        
    public function code($code) {

        if(empty($this->_path)) {
            $this->response->set($code);
            $controller = new ResponseController();
            $controller->pageNotFound();
        } 
    }

    private function error($error) {

        $this->_error = $error;
        return $this->_error;
    }
}
