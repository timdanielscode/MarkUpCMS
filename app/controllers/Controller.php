<?php 
/**
 * Use to handle views
 * 
 * @author Tim Daniëls
 * @version 1.0
 */

namespace app\controllers;

class Controller {

    /**
     * @param string $path optional
     * @param array $args arguments which can be passed
     * @return mixed object|bool 
     */
    public function view($path = null, $args = []) {

        if(!empty($args)) {
            extract($args);
        }
        if($path) {
            require_once "../app/views/" . $path . ".php";
        } else {
            return false;
        }
        return $this;
    }

    /**
     * @param string $file to include view parts on a view
     * @return void
     */
    public function include($file) {
        return involve("../app/views/includes/" . $file . ".php");
    }

    /**
     * @param string $title for adding meta title
     * @return void
     */
    public function title($title) {
        echo "<title>$title</title>";
    }

    /**
     * @param string $title for adding meta description
     * @return void
     */
    public function description($content) {
        echo '<meta name="description" content="'.$content.'">';
    }

    /**
     * @param string $name meta name
     * @param string $content meta content
     * @return void
     */
    public function meta($name, $content) {
        echo '<meta name="'.$name.'" content="'.$content.'">';
    } 
}