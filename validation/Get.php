<?php

namespace validation;

class Get {
 
    /**
     * To validate type of request get values on special characters
     * 
     * @param string $value _GET value
     * @return string $value _GET value
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