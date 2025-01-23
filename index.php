<?php
require_once 'config/config.php';

$request = $_SERVER['REQUEST_URI'];
$base_path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$request = substr($request, strlen($base_path));

switch ($request) {
    case '/' :
    case '' :
        require __DIR__ . '/views/index.view.php';
        break;
    case '/controllers/ajax_handler.php' :
        require __DIR__ . '/controllers/ajax_handler.php';
        break;
    case '/controllers/export.php' :
        require __DIR__ . '/controllers/export.php';
        break;
    default:
        http_response_code(404);
        require __DIR__ . '/views/404.view.php';
        break;
}