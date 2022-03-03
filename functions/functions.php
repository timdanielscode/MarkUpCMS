<?php
/**
 * @author Tim Daniëls
 * @version 1.0
 */

function redirect($path) {
    
    if($path) {
        header('location: '.$path);
    } else {
        echo "Path not found!";
    }
}

function submitted($name) {

    if(!empty($name) && isset($_POST[$name])) {
        return true;
    } else if(!empty($name) && isset($_GET[$name])) {
        return true;
    } else {
        return false;
    }
}

function post($name = null) {

    if(!empty($name) && isset($_POST[$name])) {
        $post = htmlspecialchars($_POST[$name]);
        return $post;
    } 
}

function get($name) {

    if(!empty($name) && isset($_GET[$name])) {
        $get = htmlentities($_GET[$name], ENT_QUOTES, 'UTF-8');
        return $get;
    } 
}

function involve($path) {

    require_once $path;
}

function loadImage($path) {
    echo $path;
}

