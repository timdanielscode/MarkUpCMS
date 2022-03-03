<?php
/**
 * Autload classes
 * 
 * @author Tim Daniëls
 * @version 1.0
 */

spl_autoload_register(function($class) {
    if(file_exists("../" . str_replace("\\", "/", $class) . '.php')) {
        include_once "../" . str_replace("\\", "/", $class) . '.php';
    } 
});