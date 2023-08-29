<?php
/**
 * Get 
 * 
 * @author Tim Daniëls
 */
namespace validation;

class Get {
 
    /**
     * Validating get values on special characters
     * 
     * @param array $values get values
     * @return string get value | empty string
     */ 
    public static function validate($values) {

        foreach($values as $value) {

            $regex = '/[#$%^&*()+=\\[\]\';,{}|":<>?~\\\\]/';
            if(preg_match($regex, $value)) {

                return '';
            }

            return $value;
        }
    }
}