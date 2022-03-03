<?php
/**
 * Use to get error validation messages
 * Set errors on Rules.php
 * Return errors through controller
 * 
 * @author Tim Daniëls
 * @version 1.0
 */
namespace parts\validation;

class Errors {

    /**
     * 
     * @param array $errors array of validation rules which are set in Rules.php
     * @param string $name correspondents with form input name
     * @example val\Errors::get($rules, 'username');
     * @return array errors validation messages
     * 
     */    
    public static function get($errors, $name) {

        if(!empty($errors && $errors !== null && !empty($name) && $name !== null)) {
            foreach($errors as $error) {
                if(array_key_exists($name, $error)) {
                    return $error[$name];
                } 
            }     
        } 
    }

    /**
     * 
     * Based on existing errors and input name 
     * Bootstrap class is-valid|is-valid will be generating
     * 
     * @param array $errors array of validation rules which are set in Rules.php
     * @param string $name 
     * @example val\Errors::addValidClass($rules, 'username');
     * @return void
     * 
     */     
    public static function addValidClass($errors, $name) {
        
        if(!empty($errors && $errors !== null && !empty($name) && $name !== null)) {
            $col = [];
            foreach($errors as $error) {
                if(array_key_exists($name, $error)) {
                    array_push($col, $name);
                } 
            }
            # but I overwrite is-invalid in style.css
            if(in_array($name, $col)) {
                 echo "is-invalid";
            } else {
                # you can use bootstraps is-valid class
                 echo "is-valid";
            }
        } else {
            echo "";
        }
    }
}