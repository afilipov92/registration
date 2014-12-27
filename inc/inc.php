<?php
error_reporting(E_ALL);
session_start();

function __autoload($className) {
    require_once __DIR__ . DIRECTORY_SEPARATOR . $className . '.php';
}

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', 'Blackpearl99');
define('DB_NAME', 'study3');