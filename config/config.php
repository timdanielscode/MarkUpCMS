<?php
/**
 * config file
 * 
 * @author Tim Daniëls
 */

session_start();

session_regenerate_id();

ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);