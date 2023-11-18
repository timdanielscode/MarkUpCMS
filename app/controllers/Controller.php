<?php
/**
 * Controller
 * 
 * @author Tim Daniëls
 */

namespace app\controllers;

use core\Session;

class Controller {

    private $_path;

    /**
     * Setting view path
     * 
     * @param string $path name view
     * @return object Controller
     */
    public function view($path) {

        if(!empty($path) && $path !== null) {

            $this->_path = $path;
        } 

        return $this;
    }    

    /**
     * Extracting data on view
     * 
     * @param array $args optional view variables
     */
    public function data($args = null) {

        if(!empty($args) && $args !== null) {

            extract($args);
        }

        include("../app/views/" . $this->_path . ".php");
    }

    /**
     * Require view from includes folder
     * 
     * @param string $file name view
     * @return void
     */
    public function include($file) {

        involve("../app/views/includes/" . $file . ".php");
    }

    /**
     * Adding html title tag in view
     * 
     * @param string $title meta
     * @return void
     */
    public function title($title) {

        echo "<title>$title</title>";
    }

    /**
     * Adding html meta tag in view
     * 
     * @param string $content meta 
     * @return void
     */
    public function description($content) {

        echo '<meta name="description" content="'.$content.'">';
    }

    /**
     * Adding html meta tag in view
     * 
     * @param string $name meta
     * @param string $content meta
     * @return void
     */
    public function meta($name, $content) {

        echo '<meta name="'.$name.'" content="'.$content.'">';
    } 

    /**
     * Adding html script tag in view
     * 
     * @param string $src source
     * @param string $defer optional
     * @return void
     */
    public function script($src, $defer = null) {

        if($defer === true) {

            echo '<script type="text/javascript" src='.'"'.$src.'"'.' defer></script>';
        } else {

            echo '<script type="text/javascript" src='.'"'.$src.'"'.'></script>';
        }

       
    }

    /**
     * Adding html link tag in view
     * 
     * @param string $href hypertext reference
     * @return void
     */
    public function stylesheet($href) {

        echo '<link rel='.'"'.'stylesheet'.'" '. 'type='.'"'.'text/css'.'" '. 'href='.'"'.$href.'"'.'>';
    }



}