<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'address_book');
//define('BASE_PATH', dirname(__DIR__));
define('BASE_PATH', realpath(dirname(__FILE__) . '/..'));