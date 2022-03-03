<?php
/**
 * Use for handling requests
 * 
 * @author Tim Daniëls
 * @version 1.0
 */
namespace core;

class Request {

    /** 
     * @return global request method
     */    
    public function getMethod() {
        return $_SERVER['REQUEST_METHOD'];
    }

    /** 
     * @return global request uri
     */    
    public function getUri() {

        return $_SERVER['REQUEST_URI'];
    }
 
    /** 
     * @param array $param
     * @return array post|get data
     */       
    public function get($param = null) {

        $data = [];

        if($this->getMethod() === 'POST') {
      
            foreach($_POST as $key => $value) {
                $value = htmlspecialchars($value);
                $data[$key] = $value;
                if($param) {
                    if($key == $param) {
                        $param = $value;
                        return $param;
                    } else { return $param . ' not found!'; }
                }
            }
        }

        if($this->getMethod() === 'GET') {
            foreach($_GET as $key => $value) {
                $key = htmlspecialchars($key);
                $data[$key] = $value;
                if($param) {
                    if($key == $param) {
                        $param = $value;
                        return $param;
                    } else { return $param . ' not found!'; }
                }
                
            }
        }
        return $data;
    }
}