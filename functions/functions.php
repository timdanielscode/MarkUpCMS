<?php
/**
 * Functions
 * 
 * @author Tim Daniëls
 */

/** 
 * @param string $path header
 * @return void
 */     
function redirect($path) {
    
    if($path) {
        header('location: '.$path);
    } 
}

/** 
 * @param string $name _POST|_GET
 * @return bool
 */     
function submitted($name) {

    if(!empty($name) && isset($_POST[$name]) || !empty($name) && isset($_GET[$name])) {
        
        return true;
    } 
}

/** 
 * @param string $name
 * @return global _GET value
 */  
function get($name) {

    if(!empty($name) && isset($_GET[$name])) {

        $get = htmlspecialchars($_GET[$name]);
        return $get;
    } 
}

