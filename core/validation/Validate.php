<?php

namespace core\validation;

use core\Session;

class Validate {

    private $_name, $_value, $_alias;
    public $errors;

    /**
     * To set a html input name and value 
     * 
     * @param string $values html input name and value
     * @return object $this Validate
     */    
    public function input($values) {

        if(!empty($values) && $values !== null) {

            $this->_name = key($values);
            $this->_value = $values[key($values)];
        }

        return $this;
    }

    /**
     * To set html input name aliases
     * 
     * @param string $alias html input value
     * @return mixed object Validate
     */ 
    public function as($alias) {

        if(!empty($alias) && $alias !== null) {

            $this->_alias = $alias;
            return $this;
        }
    }

    /**
     * To set rules
     * 
     * @param string $rules type of rules
     */     
    public function rules($rules) {

        if(!empty($rules) && $rules !== null) {

            foreach($rules as $rule => $value) {

                switch($rule) {
    
                    case 'required':

                        if(empty($this->_value) && $value === true) {

                            $this->message($this->_name, "$this->_alias is required.");
                        } 
                    break;
                    case 'min':

                        if(strlen($this->_value) < $value) {

                            $this->message($this->_name, "$this->_alias must be at least $value characters.");
                        }
                    break;
                    case 'max':

                        if(strlen($this->_value) > $value) {

                            $this->message($this->_name, "$this->_alias can not be more than $value characters.");
                        }
                    break;
                    case 'csrf':

                        if($this->_value !== $value) {

                            redirect('/') . exit();
                        }

                        Session::delete('csrf');
                    break;
                    case 'match':

                        if($this->_value !== $value) {

                            $this->message($this->_name, "$this->_alias does not match.");
                        }
                    break;
                    case 'unique':

                        if(!empty($value)) {
                                
                            $this->message($this->_name, "$this->_alias already exists.");
                        }
                    break;
                    case 'special':

                        $regex = '/[#$%^&*()+=\\[\]\';,\/{}|":<>?~\\\\]/';

                        if(preg_match($regex, $this->_value)) {

                            $this->message($this->_name, "$this->_alias contains special characters.");  
                        }
                    break;
                    case 'special-ini':

                        $regex = '/[?{}|&~![()^"]/';

                        if(preg_match($regex, $this->_value)) {

                            $this->message($this->_name, "$this->_alias contains one of the following special characters: ?{}|&~![()^" . '"');  
                        }
                    break;
                    case 'first':

                        if($this->_value[0] !== $value) {

                            $this->message($this->_name, "$this->_alias does not start with a $value.");
                        }
                    break;
                    case 'min-one-admin':
                        
                        if(count($value) < 2) {

                            $this->message($this->_name,"There should be at least one admin.");
                        }
                    break;
                }
            }
        } 
    }

    /**
     * To set the failded validation error messages to show the failed validation error messages
     * 
     * @param string $inputName optional html input name
     * @param string $message optional rule message
     * @return array validation errors
     */     
    private function message($inputName = null, $message = null) {

        $this->errors[] = [$inputName => $message];
        return $this->errors;
    }
}