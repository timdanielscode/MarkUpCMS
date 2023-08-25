<?php
/**
 * config file
 * 
 * @author Tim Daniëls
 */

header("strict-transport-security: max-age=7776000");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: no-referrer;");
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");

session_set_cookie_params(['secure' => true, 'httponly' => true, 'samesite' => 'lax']);
session_start();
session_regenerate_id();

ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);