<?php
/**
 * Request
 * 
 * @author Tim Daniëls
 */
namespace core\http;

use validation\request\Rules;

class Request {

    private $_postData = [], $_getData = [];

    /** 
     * Getting REQUEST_METHOD
     * 
     * @return global REQUEST_METHOD
     */    
    public function getMethod() {

        return $_SERVER['REQUEST_METHOD'];
    }

    /** 
     * Getting REQUEST_URI
     * 
     * @return global REQUEST_URI
     */    
    public function getUri() {

        return $_SERVER['REQUEST_URI'];
    }
 
    /** 
     * Getting POST & GET superglobals
     * 
     * @return array POST/GET variables
     */       
    public function get() {

        $this->setPostData($_POST);
        $this->setGetData($_GET);

        return array_merge($this->_getData, $this->_postData);
    }

    /**
     * Setting type of post superglobal
     * 
     * @param array $post superglobal
     */
    private function setPostData($post) {

        if(!empty($post) && $post !== null) {

            foreach($post as $name => $value) {

                if(isset($post[$name]) === true) {
    
                    $name = htmlspecialchars($name);
                    $value = htmlspecialchars($value);
    
                    $this->_postData[$name] = $value;
                }
            }
        }
    }

    /**
     * Setting type of get superglobal
     * 
     * @param array $get superglobal
     */
    private function setGetData($get) {

        if(!empty($get) && $get !== null) {

            foreach($get as $name => $value) {

                if(isset($get[$name]) === true) {
    
                    $name = htmlspecialchars($name);
    
                    if(gettype($value) === 'array') {

                        $value = htmlspecialchars($value[0]);
                    } else {
                        $value = htmlspecialchars($value);
                    }
    
                    $this->_getData[$name] = $value;
                }
            }
        }
    }
}