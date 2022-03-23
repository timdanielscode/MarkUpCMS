<?php 
/**
 * Handles route binded routes
 * 
 * @author Tim Daniëls
 * @version 1.1
 */
namespace core;

use core\Router;

class RouteBinder {

    private $_path, $_partsUri, $_uriRouteKeyValues, $_pathRouteKeyValues, $_requestVariables;

    /**
     * 
     * path with routekeys on uri 
     * replaces uri values with path values on same key
     * 
     * @param array $path exploded
     * @param array $routeKeyKeys
     * @param array $string request uri 
     */
    public function setPath($path, $routeKeyKeys, $uri) {

        $uri = strtok($uri, '?');
        $this->_partsUri = explode("/", $uri);

        foreach($routeKeyKeys as $routeKeyKey) {
            if(!empty($this->_partsUri[$routeKeyKey])) {
                $this->_uriRouteKeyValues = $this->_partsUri[$routeKeyKey];
                $this->_pathRouteKeyValues = $path[$routeKeyKey];
                $path[$routeKeyKey] = $this->_uriRouteKeyValues;
                $this->_pathRouteKeyValues = trim($this->_pathRouteKeyValues, "[]");
                $this->_requestVariables[$this->_pathRouteKeyValues] =  $this->_uriRouteKeyValues;
            }
        }

        return $this->_path = implode("/", $path);
    }

    /**
     * @return property path
     */
    public function getPath() {

        return $this->_path;
    }

    /**
     * 
     * replaces post variables with existing where 
     * request keys match $_POST keys
     *  
     */ 
    public function postRequestVariables() {
        
        if(!empty($_POST[$this->_pathRouteKeyValues]) ) {
            return $this->_requestVariables[$this->_pathRouteKeyValues] = $_POST[$this->_pathRouteKeyValues];
        }
    }

    /**
     * @return property requestVariables
     */   
    public function getRequestVariables() {

        if($this->_requestVariables !== null) {
            
            return $this->_requestVariables;
        } else {
            return false;
        }
    }

}
