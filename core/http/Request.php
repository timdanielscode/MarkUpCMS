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
    
                    $this->checkTypeOfGlobal($value, $name, 'POST');
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

                    $this->checkTypeOfGlobal($value, $name, 'GET');
                }
            }
        }
    }

    /**
     * Checking type of global
     * 
     * @param mixed $value array|string get value
     * @param string $name name get 
     * @param string $type type of global
     */
    private function checkTypeOfGlobal($value, $name, $type) {

        if($type === 'GET') {

            return $this->checkTypeGet($value, $name);
        } 
            
        return $this->checkTypePost($value, $name);
    }


    /**
     * Checking type of get
     * 
     * @param mixed $value array|string get value
     * @param string $name name get 
     */
    private function checkTypeGet($value, $name) {

        if(gettype($value) === 'array') {

            return $this->setGetValues($value, $name);
        }

        $this->setGetValue($value, $name);
    }

    /**
     * Checking type of post
     * 
     * @param mixed $value array|string post value
     * @param string $name name post 
     */
    private function checkTypePost($value, $name) {

        if(gettype($value) === 'array') {

            return $this->setPostValues($value, $name);
        }

        $this->setPostValue($value, $name);
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
     * Setting type of post superglobal values after checking type of value
     * 
     * @param string $value post value
     * @param string $name name post 
     */
    private function setPostValue($value, $name) {

        $name = htmlspecialchars($name);
        $value = htmlspecialchars($value);

        $this->_postData[$name] = $value;
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

    /**
     * Setting type of post superglobal values after checking type of value
     * 
     * @param array $values post values
     * @param string $name name post 
     */
    private function setPostValues($values, $name) {

        $name = htmlspecialchars($name);

        foreach($values as $value) {

            $value = htmlspecialchars($value);
            $this->_postValues[] = $value;
        }

        $this->_postData[$name] = $this->_postValues;
    }
}