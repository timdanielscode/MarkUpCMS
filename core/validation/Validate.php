<?php
/**
 * Use to validate post requests
 * 
 * @author Tim Daniëls
 * @version 1.1
 */

namespace core\validation;

class Validate {

    private $_inputName, $_inputNames, $_alias, $_inputValue, $_error;
    public $errors;

    /**
     * @param string $inputName
     * @return mixed object|void
     */    
    public function input($inputName) {

        if(!empty($inputName)) {

            $this->_inputNames[] = $inputName;
            $doubles = [];

            foreach(array_count_values($this->_inputNames) as $values => $count) {
                if($count > 1) {
                    $doubles[] = $values;
                } 
            }
            if(!empty($doubles)) {
                echo $this->error("Input names cannot be the same!");
                exit();
            } else {
                if(post($inputName) !== null) {
                    $this->_inputName = $inputName;
                    $this->_inputValue = post($this->_inputName);
                }
                else if ($_FILES[$inputName] !== null) {
                    $this->_inputName = $inputName;
                    $this->_inputValue = $_FILES[$this->_inputName];
                } else {
                    echo $this->error("Input name:$inputName is equal to null!");
                    exit();
                }
            } return $this;
        } else {
            echo $this->error("Input names cannot be null or empty!");
            exit();
        }
    }

    /**
     * 
     * use to create an alias 
     * 
     * @param string $alias
     * @return mixed object|void
     */ 
    public function as($alias) {

        if(!empty($alias)) {
            $this->_alias = $alias;
            return $this;
        } else {
            echo $this->error("Aliasses cannot be null or empty!");
            exit();
        }
    }

    /**
     * 
     * use to handle validation rules
     * 
     * @param string $rules optional
     * @return mixed object|void
     */     
    public function rules($rules = null) {

        if(!empty($rules) && $rules !== null) {
            foreach($rules as $rule => $value) {

                switch($rule) {
    
                    case 'required':
                        if(empty($this->_inputValue) && $value === true) {
                            $this->message($this->_inputName, "$this->_alias is required.");
                        }
                    break;
                    case 'min':
                        $count_str = strlen($this->_inputValue);
                        if($count_str < $value) {
                            $this->message($this->_inputName, "$this->_alias must be at least $value characters.");
                        }
                    break;
                    case 'max':
                        $count_str = strlen($this->_inputValue);
                        if($count_str > $value) {
                            $this->message($this->_inputName, "$this->_alias can not be more than $value characters.");
                        }
                    break;
                    case 'match':
                        $compare_value = post($value);
                        if($compare_value !== $this->_inputValue) {
                            $this->message($this->_inputName, "$this->_alias does not match.");
                        }
                    break;
                    case 'unique':
                        if(!empty($value)) {
                            $this->message($this->_inputName, "$this->_alias $this->_inputValue already exists.");
                        }
                    break;
                    case 'special':
                        $regex = '/[#$%^&*()+=\\[\]\';,\/{}|":<>?~\\\\]/';
                        if(preg_match($regex, $this->_inputValue)) {
                            $this->message($this->_inputName, "$this->_alias contains special characters.");  
                            $_POST[$this->_inputName] = "";
                        }
                    break;
                    case 'first':
                        if($this->_inputValue[0] !== $value) {
                            $this->message($this->_inputName, "$this->_alias does not start with a $value.");
                        }
                    break;
                    case 'selected':
                        if(empty($_FILES[$this->_inputName]['name']) && $value === true) {
                            $this->message($this->_inputName, "No file is selected.");
                        }
                    break;
                    case 'mimes':
                        if(!in_array($_FILES[$this->_inputName]['type'], $value) ) {
                            $value = implode(', ', $value);
                            $this->message($this->_inputName, "Type of file must be one of the following: $value.");
                        }
                    break;
                    case 'error':
                        if($_FILES[$this->_inputName]['error'] === 1 && $value === true) {
                            $this->message($this->_inputName, "Error found in file.");
                        }
                    break;
                    case 'size':
                        if($_FILES[$this->_inputName]['size'] > $value) {
                            $mbs = $value / 1000000;
                            $mbs = number_format((float)$mbs, 1, '.', '');
                            $filesizeInMbs = $_FILES[$this->_inputName]['size'] / 1000000;
                            $filesizeInMbs = number_format((float)$filesizeInMbs, 1, '.', '');
                            $this->message($this->_inputName, "$filesizeInMbs mb is to big to upload, filesize can't be bigger than $mbs mb.");
                        }
                    break;
                    default:
                        echo $this->error("Validation rule: '$rule' does not exist!");
                        exit();
                    break;
                }
            }
        } else {
            $rules = [];
        }

    }

    /**
     * @param string $inputName
     * @param string $message
     * @return mixed object|void
     */     
    private function message($inputName = null, $message = null) {

        $this->errors[] = [$inputName => $message];
        return $this->errors;
    }

    private function error($error) {

        $this->_error = $error;
        return $this->_error;
    }
}