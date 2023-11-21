<?php
/**
 * Validate
 * 
 * @author Tim Daniëls
 */

namespace core\validation;

class Validate {

    private $_name, $_value, $_alias;
    public $errors;

    /**
     * Setting input values
     * 
     * @param string $values html input name and value
     * @return Object $this Validate
     */    
    public function input($values) {

        if(!empty($values) && $values !== null) {

            $this->_name = key($values);
            $this->_value = $values[key($values)];
        }

        return $this;
    }

    /**
     * Setting html input name aliases
     * so aliases can be printed out on view instead of the actual input name
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
     * Creating the validation rules and setting error messages
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
                        $count_str = strlen($this->_value);
                        if($count_str < $value) {

                            $this->message($this->_name, "$this->_alias must be at least $value characters.");
                        }
                    break;
                    case 'max':
                        $count_str = strlen($this->_value);
                        if($count_str > $value) {

                            $this->message($this->_name, "$this->_alias can not be more than $value characters.");
                        }
                    break;
                    case 'match':
                        $compare_value = post($value);
                        if($compare_value !== $this->_value) {

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
                            $_POST[$this->_name] = "";
                        }
                    break;
                    case 'special-ini':
                        $regex = '/[?{}|&~![()^"]/';
                        if(preg_match($regex, $this->_value)) {
                            $this->message($this->_name, "$this->_alias contains one of the following special characters: ?{}|&~![()^" . '"');  
                            $_POST[$this->_name] = "";
                        }
                    break;
                    case 'first':
                        if($this->_value[0] !== $value) {

                            $this->message($this->_name, "$this->_alias does not start with a $value.");
                        }
                    break;
                    case 'selected':

                        if(empty($_FILES[$this->_name]['name']) && $value === true) {

                            $this->message($this->_name, "No file is selected.");
                        }
                    break;
                    case 'mimes':

                        foreach($_FILES[$this->_name]['type'] as $type) {

                            if(gettype($value) !== 'array') {
                                
                                $value = explode(',', $value);
                            }

                            if(!in_array($type, $value) ) {

                                $value = implode(', ', $value);
                                $this->message($this->_name, "Type of file must be one of the following: $value.");
                            }
                        }
                    break;
                    case 'error':
                        foreach($_FILES[$this->_name]['error'] as $error) {

                            if($error === 1 && $value === true) {

                                $maxUploadSize = ini_get('upload_max_filesize');
                                $this->message($this->_name, "File size cannot be bigger than " . $maxUploadSize . '.');
                            }
                        }
                    break;
                    case 'size':

                        foreach($_FILES[$this->_name]['size'] as $size) {

                            if($size > $value) {

                                $mbs = $value / 1000000;
                                $mbs = number_format((float)$mbs, 1, '.', '');
                                $filesizeInMbs = $size / 1000000;
                                $filesizeInMbs = number_format((float)$filesizeInMbs, 1, '.', '');
    
                                $this->message($this->_name, "$filesizeInMbs mb is to big to upload, filesize can't be bigger than $mbs mb.");
                            }
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
     * @param string $inputName optional html input name
     * @param string $message optional rule message
     * @return array validation errors
     */     
    private function message($inputName = null, $message = null) {

        $this->errors[] = [$inputName => $message];
        return $this->errors;
    }
}