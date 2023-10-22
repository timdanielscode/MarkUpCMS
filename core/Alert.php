<?php 
/**
 * Create success/failed messages based on setted session names
 * 
 * @author Tim Daniëls
 */
namespace core;

class Alert {

    private static $_type;

    /**
     * Checking if message type is not null
     * 
     * @param string $type success | failed
     */
    public static function message($type) {

        if(!empty($type) && $type !== null) {

            return self::check($type);
        }
    }

    /**
     * Checking message type 
     * 
     * @param string $type success | failed
     */    
    private static function check($type) {

        if($type === 'success') {

            self::$_type = $type;
            return self::createSuccess();

        } else if($type === 'failed') {

            self::$_type = $type;
            return self::createFailed();
        } 
    }

    /**
     * Creating success message 
     */      
    private static function createSuccess() {

        if(Session::exists(self::$_type)) {

            echo '<div class="message-container success"><span class="success-message">' . Session::get(self::$_type) . '</span></div>';
            Session::delete(self::$_type);
        }
    }

    /**
     * Creating failed message 
     */       
    private static function createFailed() {

        if(Session::exists(self::$_type)) {

            echo '<div class="message-container failed"><span class="failed-message">' . Session::get(self::$_type) . '</span></div>';
            Session::delete(self::$_type);
        }
    }
}