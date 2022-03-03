<?php
/**
 * Use to display bootstrap css alerts on views
 * 
 * @author Tim Daniëls
 * @version 1.0
 */

namespace parts;

class Alert {
    
    /**
     * 
     * @param string $type optional for example success|info|danger default is primary
     * @param string $sessionName expects existing session name
     * @example parts\Alert::display("danger", "Csrf"); Where Csrf is an existing session name
     * @return class Session::get()
     * 
     */
    public static function display($type = null, $sessionName) {
        
        if(!empty($sessionName) && $sessionName !== null) {
            switch($type)  {
                case 'primary': 
                    echo '<div class="alert alert-primary" role="alert">';
                break;
                case 'secondary':
                    echo '<div class="alert alert-secondary" role="alert">';
                break;
                case 'success':
                    echo '<div class="alert alert-success" role="alert">';
                break;
                case 'danger':
                    echo '<div class="alert alert-danger" role="alert">';
                break;
                case 'warning':
                    echo '<div class="alert alert-warning" role="alert">';
                break;
                case 'info':
                    echo '<div class="alert alert-info" role="alert">';
                break;
                case 'light':
                    echo '<div class="alert alert-light" role="alert">';
                break;
                case 'dark':
                    echo '<div class="alert alert-dark" role="alert">';
                break;       
                default: 
                    echo '<div class="alert alert-primary" role="alert">';
                break;
            }
            return Session::get($sessionName) . '</div>';
        } 
    }  
}

