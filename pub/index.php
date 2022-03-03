<?php 
/**
 * Use for initialize application envoirement
 * 
 * @author Tim Daniëls
 * @version 1.0
 */

require_once '../core/Autoload.php';
require_once '../functions/functions.php';
require_once '../config/config.php';

use core\App;

$app = new App(new core\Middleware());

$app->run();


