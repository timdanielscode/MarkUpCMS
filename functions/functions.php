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