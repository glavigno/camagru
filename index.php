<?php
define('URL', str_replace("index.php", "", (isset($_SERVER['HTTPS']) ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]"));
define('UPLOAD_DIR', 'public/images/');

require('controllers/Router.php');

$router = new Router();
$router->routeReq();