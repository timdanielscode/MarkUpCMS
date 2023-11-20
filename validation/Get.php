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
     * @param string $value get value
     * @return string get value
     */ 
    public static function validate($value) {

        if(!empty($value) && $value !== null) {

            $regex = '/[#$%^&*()+=\\[\]\';,{}|":<>?~\\\\]/';

            if(preg_match($regex, $value)) {
        
                exit();
            }
        
            return $value;
        }
    }
}