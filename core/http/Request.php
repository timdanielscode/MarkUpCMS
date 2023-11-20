<?php
/**
 * Request
 * 
 * @author Tim Daniëls
 */
namespace core\http;

use validation\request\Rules;

class Request {

    private $_postData = [], $_getData = [], $_getValues = [];

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
     * Setting type of post superglobal values
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
     * Setting type of get superglobal values
     * 
     * @param array $get superglobal
     */
    private function setGetData($get) {

        if(!empty($get) && $get !== null) {

            foreach($get as $name => $value) {

                if(isset($get[$name]) === true) {

                    $this->checkType($value, $name);
                }
            }
        }
    }

    /**
     * Checking type of get value
     * 
     * @param mixed $value array|string get value
     * @param string $name name get 
     */
    private function checkType($value, $name) {

        if(gettype($value) === 'array') {

            return $this->setGetValues($value, $name);
        }

        $this->setGetValue($value, $name);
    }

    /**
     * Setting type of get superglobal values after checking type of value
     * 
     * @param string $value get value
     * @param string $name name get 
     */
    private function setGetValue($value, $name) {

        $name = htmlspecialchars($name);
        $value = htmlspecialchars($value);

        $this->_getData[$name] = $value;
    }

    /**
     * Setting type of get superglobal values after checking type of value
     * 
     * @param array $values get values
     * @param string $name name get 
     */
    private function setGetValues($values, $name) {

        $name = htmlspecialchars($name);

        foreach($values as $value) {

            $value = htmlspecialchars($value);
            $this->_getValues[] = $value;
        }

        $this->_getData[$name] = $this->_getValues;
    }
}